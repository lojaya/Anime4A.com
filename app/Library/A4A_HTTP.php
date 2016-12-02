<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 12/3/2016
 * Time: 1:22 AM
 */

namespace App;
/**
 * Library cURL
 * Source: https://github.com/php-curl-class/php-curl-class
 *
 * @package HTTP
 * @author AzureCloud <azurecloud.22@gmail.com>
 * @version 1.0.0
 *
 */
class A4A_HTTP {
    const DEFAULT_TIMEOUT = 30;
    /**
     *
     * @var object
     */
    protected static $_instance;
    /**
     *
     * @var string
     */
    public $base_url = '';
    protected $url;
    /**
     *
     * @var resource
     */
    private $ch;
    /**
     *
     * @var array
     */
    protected $_options = array();
    /**
     *
     * @var array
     */
    protected $_headers = array();
    /**
     *
     * @var array
     */
    protected $_cookies = array();
    public $raw_response;
    /**
     *
     * @var string
     */
    public $raw_response_headers = '';
    /**
     *
     * @var array
     */
    public $raw_response_cookies = array();
    /**
     *
     * @var array
     */
    public $errors = array(
        'status',
        'code',
        'msg',
        'curl' => array(
            'status',
            'code',
            'msg',
        ),
        'http' => array(
            'status',
            'code',
            'msg'
        )
    );
    public $response;
    public $request_headers;
    public $response_headers;

    /**
    private $successFunction = null;
    private $errorFunction = null;
    private $completeFunction = null;
     **/
    /**
     *
     * @var type
     */
    private $_is_json = null;
    /**
     *
     * @var array
     */
    private $_pattern = array(
        'json' => '/^(?:application|text)\/(?:[a-z]+(?:[\.-][0-9a-z]+){0,}[\+\.]|x-)?json(?:-[a-z]+)?/i',
        'xml' => '~^(?:text/|application/(?:atom\+|rss\+)?)xml~i'
    );

    /**
     * A4A_HTTP constructor.
     * @param null $base_url
     * @throws \Exception
     */
    public function __construct($base_url = null){
        if(!extension_loaded('curl')){
            throw new \Exception('cURL library is not loaded!');
        }
        $this->setup($base_url);
    }

    /**
     *
     * @return object
     */
    public static function instance(){
        if(self::$_instance == NULL){
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    /**
     * @param null $url
     * @return $this
     */
    public function setup($url = null){
        $this->ch = curl_init();
        $this->_defaultUserAgent();
        $this->_defaultTimeout();

        //.....
        $this->setOption(CURLINFO_HEADER_OUT, true);
        $this->setOption(CURLOPT_HEADERFUNCTION, array($this, '_rawResponseHeaders'));
        $this->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->_headers = new CaseInsensitiveArray();
        $this->setURL($url);
        return $this;
    }

    /**
     *
     * @param string $url
     * @param array $data
     * @return string
     */
    public function get($url, $data = array()){
        if(is_array($url)){
            $data = $url;
            $url = $this->base_url;
        }
        $this->setURL($url, $data);
        $this->setOption(CURLOPT_CUSTOMREQUEST, 'GET');
        $this->setOption(CURLOPT_HTTPGET, true);
        return $this->execute();
    }

    /**
     *
     * @param string $url
     * @param array $data
     * @return string
     */
    public function post($url, $data = array()){
        if (is_array($url)) {
            $data = $url;
            $url = $this->base_url;
        }
        $this->setURL($url);
        $this->setOption(CURLOPT_CUSTOMREQUEST, 'POST');
        $this->setOption(CURLOPT_POST, true);
        $this->setOption(CURLOPT_POSTFIELDS, $this->_buildPostParams($data));
        return $this->execute();
    }

    /**
     *
     * @param string $url
     * @param array $data
     * @return string
     */
    public function options($url, $data = array()){
        if (is_array($url)) {
            $data = $url;
            $url = $this->base_url;
        }
        $this->setURL($url, $data);
        $this->unsetHeader('Content-Length');
        $this->setOption(CURLOPT_CUSTOMREQUEST, 'OPTIONS');
        $this->setOption(CURLOPT_ENCODING , 'gzip');
        $this->setOption(CURLOPT_SSL_VERIFYPEER, false);

        return $this->execute();
    }

    /**
     *
     * @return string
     */
    public function execute(){
        //self::_callfunc($this->beforeSendFunction);
        $this->raw_response = $this->_rawResponse();
        $this->errors['curl']['code'] = $this->_raw_curl_errno();

        $this->errors['curl']['msg'] = $this->_raw_curl_error_msg();
        $this->errors['curl']['status'] = !($this->errors['curl']['code'] === 0);

        $this->errors['http']['code'] = $this->_getinfo_http_code();
        $this->errros['http']['status'] = in_array(floor($this->errors['http']['code'] / 100), array(4, 5));

        $this->errors['status'] = $this->errors['curl']['status'] || $this->errros['http']['status'];
        $this->errors['code'] = $this->errors['status'] ? ($this->errors['curl']['status'] ? $this->errors['curl']['code'] : $this->errors['http']['code']) : 0;

        if($this->getOption(CURLINFO_HEADER_OUT) === true) {
            $this->request_headers = $this->_parseRequestHeaders($this->_getinfo_header_out());
        }
        $this->response_headers = $this->_parseResponseHeaders($this->raw_response_headers);
        list($this->response, $this->raw_response) = $this->_parseResponse($this->response_headers, $this->raw_response);

        $this->errors['http']['msg'] = '';

        if($this->errors['status']){
            if (isset($this->response_headers['Status-Line'])) {
                $this->errors['http']['msg'] = $this->response_headers['Status-Line'];
            }
            $this->errors['msg'] = $this->errors['curl']['status'] ? $this->errors['curl']['msg'] : $this->errors['http']['msg'];
            //self::_callfunc($this->errorFunction);
        }else{
            //self::_callfunc($this->successFunction);
        }
        //self::_callfunc($this->completeFunction);
        return $this->response;
    }

    /**
     *
     * @return string
     */
    protected function _getinfo_header_out(){
        return $this->getInfo(CURLINFO_HEADER_OUT);
    }

    /**
     *
     * @return string
     */
    protected function _getinfo_http_code(){
        return $this->getInfo(CURLINFO_HTTP_CODE);
    }

    /**
     *
     * @return type
     */
    protected function _raw_curl_error_msg(){
        return curl_error($this->ch);
    }

    /**
     *
     * @return type
     */
    protected function _raw_curl_errno(){
        return curl_errno($this->ch);
    }

    /**
     *
     * @return string
     */
    protected function _rawResponse(){
        return curl_exec($this->ch);
    }

    /**
     *
     * @param resource $ch
     * @param string $headers
     * @return int
     */
    protected function _rawResponseHeaders($ch, $headers){
        if (preg_match('/^Set-Cookie:\s*([^=]+)=([^;]+)/mi', $headers, $cookies) == 1) {
            $this->raw_response_cookies[$cookies[1]] = $cookies[2];
        }
        $this->raw_response_headers .= $headers;
        return strlen($headers);
    }

    /**
     *
     * @param array $params
     * @return \CURLFile
     */
    protected function _buildPostParams($params){
        if(is_array($params)){
            if(A4A_Array::is_array_multidim($params)){
                if(isset($this->_headers['Content-Type'])){
                    $json_enc = json_encode($params);
                    if(!($json_enc === false)){
                        $params = $json_enc;
                    }
                }else{
                    $params = self::http_build_multi_query($params);
                }
            }else{
                $binary_data = false;
                foreach($params as $key => $value){
                    if (is_array($value) && empty($value)) {
                        $params[$key] = '';
                    }elseif(is_string($value) && strpos($value, '@') === 0){
                        $binary_data = true;
                        if (class_exists('CURLFile')) {
                            $params[$key] = new \CURLFile(substr($value, 1));
                        }
                    }elseif($value instanceof \CURLFile) {
                        $binary_data = true;
                    }
                }
                if(!$binary_data){
                    if(isset($this->headers['Content-Type']) &&
                        preg_match($this->_pattern['json'], $this->headers['Content-Type'])) {
                        $json_str = json_encode($data);
                        if (!($json_str === false)) {
                            $data = $json_str;
                        }
                    } else {
                        $data = http_build_query($data, '', '&');
                    }
                }
            }
        }
        return $params;
    }

    /**
     *
     * @param string $opt
     * @return string|array
     */
    public function getOption($opt){
        return $this->_options[$opt];
    }

    /**
     *
     */
    private function _defaultTimeout(){
        $this->setTimeout(self::DEFAULT_TIMEOUT);
    }

    /**
     *
     */
    private function _defaultUserAgent(){
        $this->setUserAgent("aaa");
    }

    /**
     * @param $username
     * @param string $password
     * @return $this
     */
    public function setBasicAuthentication($username, $password = ''){
        $this->setOption(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $this->setOption(CURLOPT_USERPWD, $username . ':' . $password);
        return $this;
    }

    /**
     * @param $username
     * @param string $password
     * @return $this
     */
    public function setDigestAuthentication($username, $password = ''){
        $this->setOption(CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        $this->setOption(CURLOPT_USERPWD, $username . ':' . $password);
        return $this;
    }

    /**
     *
     * @param string $key
     * @param string $value
     * @return type
     */
    public function setCookie($key, $value){
        $this->_cookies[$key] = $value;
        return $this->setOption(CURLOPT_COOKIE, str_replace(' ', '%20', urldecode(http_build_query($this->_cookies, '', '; '))));
    }

    /**
     *
     * @param string $key
     * @return string
     */
    public function getCookie($key){
        return isset($this->raw_response_cookies[$key]) ? $this->raw_response_cookies[$key] : null;
    }

    /**
     *
     * @param int $port_num
     * @return type
     */
    public function setPort($port_num){
        return $this->setOption(CURLOPT_PORT, intval($port_num));
    }

    /**
     *
     * @param int $sec
     * @return type
     */
    public function setConnectTimeout($sec){
        return $this->setOption(CURLOPT_CONNECTTIMEOUT, $sec);
    }

    /**
     *
     * @param string $cookiefile
     * @return type
     */
    public function setCookieFile($cookiefile){
        return $this->setOption(CURLOPT_COOKIEFILE, $cookiefile);
    }

    /**
     *
     * @param string $cookiejar
     * @return type
     */
    public function setCookieJar($cookiejar){
        return $this->setOption(CURLOPT_COOKIEJAR,$cookiejar);
    }

    /**
     *
     * @param string $option
     * @return string
     */
    public function getInfo($option){
        return $this->_getInfo($option);
    }

    /**
     * @param $option
     * @param $value
     * @return type
     * @throws \Exception
     */
    public function setOption($option, $value){
        $required_options = array(
            CURLOPT_RETURNTRANSFER => 'CURLOPT_RETURNTRANSFER',
        );
        if (in_array($option, array_keys($required_options), true) && !($value === true)) {
            throw new \Exception($required_options[$option] . ' is a required option');
        }
        $this->_options[$option] = $value;
        return $this->_setOpt($option,$value);
    }

    /**
     *
     * @param string $opt
     * @return string
     */
    protected function _getInfo($opt){
        return curl_getinfo($this->ch, $opt);
    }

    /**
     *
     * @param string $opt
     * @param string $val
     * @return type
     */
    protected function _setOpt($opt, $val){
        return curl_setopt($this->ch,$opt,$val);
    }

    /**
     *
     * @param string $k
     * @param string $val
     * @return type
     */
    public function setHeader($k,$val){
        $this->_headers[$k] = $val;
        $headers = array();
        foreach($this->_headers as $key => $value){
            $headers[] = $key . ': ' . $value;
        }
        return $this->setOption(CURLOPT_HTTPHEADER, $headers);
    }

    /**
     *
     * @param string $ref
     * @return type
     */
    public function setReferrer($ref){
        return $this->setOption(CURLOPT_REFERER, $ref);
    }

    /**
     *
     * @param int $sec
     * @return type
     */
    public function setTimeout($sec){
        return $this->setOption(CURLOPT_TIMEOUT, $sec);
    }

    /**
     *
     * @param string $url
     * @param array $data
     * @return type
     */
    public function setURL($url,$data = array()){
        $this->base_url = $url;
        $this->url = $this->_buildURL($url, $data);
        return $this->setOption(CURLOPT_URL, $this->url);
    }

    /**
     * @param $useragent
     * @return type
     */
    public function setUserAgent($useragent){
        return $this->setOption(CURLOPT_USERAGENT,$useragent);
    }

    /**
     *
     * @param array $data
     * @param string $key
     * @return string
     */
    public static function http_build_multi_query($data, $key = null){
        $query = array();
        if(empty($data)){
            return $key . '=';
        }
        $is_array_assoc = J2T_Array::is_array_assoc($data);
        foreach($data as $k => $value) {
            if(is_string($value) || is_numeric($value)){
                $brackets = $is_array_assoc ? '[' . $k . ']' : '[]';
                $query[] = urlencode($key === null ? $k : $key . $brackets) . '=' . rawurlencode($value);
            }elseif (is_array($value)){
                $nested = $key === null ? $k : $key . '[' . $k . ']';
                $query[] = self::http_build_multi_query($value, $nested);
            }
        }
        return implode('&', $query);
    }

    /**
     *
     * @param string $url
     * @param array $data
     * @return string
     */
    private function _buildURL($url, $data = array()){
        return $url . (empty($data) ? '' : '?' . http_build_query($data));
    }

    /**
     *
     * @param array $raw_headers
     * @return array
     */
    private function _parseHeaders($raw_headers){
        $raw_headers = preg_split('/\r\n/', $raw_headers, null, PREG_SPLIT_NO_EMPTY);
        $http_headers = new CaseInsensitiveArray();
        $raw_headers_count = count($raw_headers);
        for($i = 1; $i < $raw_headers_count; $i++){
            list($key, $value) = explode(':', $raw_headers[$i], 2);
            $key = trim($key);
            $value = trim($value);
            // Use isset() as array_key_exists() and ArrayAccess are not compatible.
            if(isset($http_headers[$key])) {
                $http_headers[$key] .= ',' . $value;
            }else{
                $http_headers[$key] = $value;
            }
        }
        return array(isset($raw_headers['0']) ? $raw_headers['0'] : '', $http_headers);
    }

    /**
     * @param $raw_headers
     * @return CaseInsensitiveArray
     */
    private function _parseRequestHeaders($raw_headers){
        $request_headers = new CaseInsensitiveArray();
        list($first_line, $headers) = $this->_parseHeaders($raw_headers);
        $request_headers['Request-Line'] = $first_line;
        foreach($headers as $key => $value){
            $request_headers[$key] = $value;
        }
        return $request_headers;
    }

    /**
     * @param $raw_response_headers
     * @return CaseInsensitiveArray
     */
    private function _parseResponseHeaders($raw_response_headers){
        $response_header_array = explode("\r\n\r\n", $raw_response_headers);
        $response_header  = '';
        for($i = count($response_header_array) - 1; $i >= 0; $i--){
            if(stripos($response_header_array[$i], 'HTTP/') === 0){
                $response_header = $response_header_array[$i];
                break;
            }
        }
        $response_headers = new CaseInsensitiveArray();
        list($first_line, $headers) = $this->_parseHeaders($response_header);
        $response_headers['Status-Line'] = $first_line;
        foreach ($headers as $key => $value) {
            $response_headers[$key] = $value;
        }
        return $response_headers;
    }

    /**
     * @param $response_headers
     * @param $raw_response
     * @return array
     */
    private function _parseResponse($response_headers, $raw_response){
        $response = $raw_response;
        if(isset($response_headers['Content-Type'])) {
            if (preg_match($this->_pattern['json'], $response_headers['Content-Type'])) {
                $json_decoder = $this->_is_json;
                if (is_callable($json_decoder)) {
                    $response = $json_decoder($response);
                }
            }elseif(preg_match($this->_pattern['xml'], $response_headers['Content-Type'])){
                $xml_obj = @simplexml_load_string($response);
                if (!($xml_obj === false)) {
                    $response = $xml_obj;
                }
            }
        }
        return array($response, $raw_response);
    }

    /**
     *
     */
    protected static function _callfunc(){
        $args = func_get_args();
        $function = array_shift($args);
        if(is_callable($function)){
            array_unshift($args, self::instance());
            call_user_func_array($function, $args);
        }
    }

    /**
     * @param $key
     */
    public function unsetHeader($key){
        $this->setHeader($key, '');
        unset($this->_headers[$key]);
    }

    /**
     * @param bool $on
     * @param resource $output
     * @return $this
     */
    public function verbose($on = true, $output=STDERR){
        // Turn off CURLINFO_HEADER_OUT for verbose to work. This has the side
        // effect of causing Curl::requestHeaders to be empty.
        if ($on){
            $this->setOption(CURLINFO_HEADER_OUT, false);
        }
        $this->setOption(CURLOPT_VERBOSE, $on);
        $this->setOption(CURLOPT_STDERR, $output);
        return $this;
    }

    public function __destruct(){
        if(is_resource($this->ch)){
            curl_close($this->ch);
        }
        unset($this->options);
        $this->_is_json = null;
    }
}

class A4A_Array {
    /**
     *
     * @return boolean
     */
    public static function is_array_multidim($array){
        if (!is_array($array)){
            return false;
        }
        return (bool)count(array_filter($array, 'is_array'));
    }

    /**
     *
     * @param array $array
     * @return boolean
     */
    public static function is_array_assoc($array){
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }
}

class CaseInsensitiveArray implements \ArrayAccess, \Countable, \Iterator {

    private $container = array();
    public function offsetSet($offset, $value) {
        if($offset === null) {
            $this->container[] = $value;
        }else{
            $index = array_search(strtolower($offset), array_keys(array_change_key_case($this->container, CASE_LOWER)));
            if (!($index === false)) {
                $keys = array_keys($this->container);
                unset($this->container[$keys[$index]]);
            }
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset){
        return array_key_exists(strtolower($offset), array_change_key_case($this->container, CASE_LOWER));
    }

    public function offsetUnset($offset){
        unset($this->container[$offset]);
    }

    public function offsetGet($offset){
        $index = array_search(strtolower($offset), array_keys(array_change_key_case($this->container, CASE_LOWER)));
        if($index === false){
            return null;
        }
        $values = array_values($this->container);
        return $values[$index];
    }

    public function count(){
        return count($this->container);
    }

    public function current(){
        return current($this->container);
    }

    public function next(){
        return next($this->container);
    }

    public function key(){
        return key($this->container);
    }

    public function valid(){
        return !($this->current() === false);
    }

    public function rewind(){
        reset($this->container);
    }
}
