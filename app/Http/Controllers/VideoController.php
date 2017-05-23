<?php

namespace App\Http\Controllers;

require_once "kGoogle.class.php";
include_once('simple_html_dom.php');

use App;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

use JonnyW\PhantomJs\Client;
use JonnyW\PhantomJs\DependencyInjection\ServiceContainer;

class VideoController extends Controller
{
    // Get Video
    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function GetVideo(Request $request, $id)
    {
        try
        {
            $video = App\DBVideos::find($id);
            if(!is_null($video)&&strlen($video->url_source))
            {
                $source = $video->url_source;
                return view('EmbedVideo')->with([
                    'video' => $video,
                    'data' => encrypt($source)
                ]);
            }
            else
                return '<span style="color: white; font-size: 18pt">Video không tồn tại hoặc xảy ra sự cố ngoài ý muốn!!!</span>';
        }
        catch(\Exception $e)
        {
            return '<span style="color: white; font-size: 18pt">Video không tồn tại hoặc xảy ra sự cố ngoài ý muốn!!!</span>';
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function GetGGVideo(Request $request, $id)
    {
        try
        {
            $video = App\DBVideos::find($id);
            if(!is_null($video)&&strlen($video->url_source))
            {
                $source = '';
                if(!is_null($video)&&strlen($video->stream_google_url))
                {
                    //$source = $video->stream_google_url;
                }else{
                }
                    $j2t = new \J2T();
                    $j2t->setLink = $video->url_source;
                    $j2t->setFormat = isset($_GET['format']) ? $_GET['format'] : false;
                    $source = $j2t->run();

                    $video->stream_google_url = $source;
                    $video->save();

                if(!strlen($source))
                {
                    $source[] = array(
                        'type'      => 'mp4',
                        'label'     => 'HD',
                        'file'      => 'http://thenewcode.com/assets/videos/polina.mp4',
                        'default'   => true
                    );
                    $source = json_encode($source);
                }
                $source = str_replace('\\','', $source);
                return view('EmbedVideo')->with([
                    'video' => $video,
                    'data' => $source,
                ]);
            }
            else
                return '<span style="color: white; font-size: 18pt">Video không tồn tại hoặc xảy ra sự cố ngoài ý muốn!!!</span>';
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    private $itags = array(
        '37',
        '22',
        '59',
        '18'
    );

    public function GetVideoData(Request $request)
    {
        try
        {
            $url = decrypt(Input::get('url'));
            $ip = Input::get('ip');
            $id = $this->getDriveId($url);
            if(!is_null($id)&&strlen($id))
            {
                if(filter_var($ip, FILTER_VALIDATE_IP,FILTER_FLAG_IPV4))
                    return $this->getVideoIPv6_2($id);

                if(filter_var($ip, FILTER_VALIDATE_IP,FILTER_FLAG_IPV6))
                    return $this->getVideoIPv4($id);
            }
            else
                return '<span style="color: white; font-size: 18pt">Video không tồn tại hoặc xảy ra sự cố ngoài ý muốn!!!</span>';
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    private function getDriveId($url)
    {
        preg_match('/(?:https?:\/\/)?(?:[\w\-]+\.)*(?:drive|docs)\.google\.com\/(?:(?:folderview|open|uc)\?(?:[\w\-\%]+=[\w\-\%]*&)*id=|(?:folder|file|document|presentation)\/d\/|spreadsheet\/ccc\?(?:[\w\-\%]+=[\w\-\%]*&)*key=)([\w\-]{28,})/i', $url , $match);

        if(isset($match[1])){
            $id = $match[1];
            return $id;
        }

        return false;
    }

    private function getSourceIPv6($url){
        if (strpos($url,'drive.google') == true) {
            if (preg_match('@https?://(?:[\w\-]+\.)*(?:drive|docs)\.google\.com/(?:(?:folderview|open|uc)\?(?:[\w\-\%]+=[\w\-\%]*&)*id=|(?:folder|file|document|presentation)/d/|spreadsheet/ccc\?(?:[\w\-\%]+=[\w\-\%]*&)*key=)([\w\-]{28,})@i', $url, $match)) {
                $id = $match[1];
                $u = 'https://drive.google.com/file/d/'.$id.'/view?pli=1';
            }
        }else{
            $u = $url;
        }

        $curl = new GGDrive;
        $curl->get('https://www.proxfree.com/','',2);
        $curl->httpheader = array(
            'Referer:https://de.proxfree.com/permalink.php?url=eKcKvRAsZMJp3EkmD1K78%2Bqx%2FrqnRtIHySNzmMxUbxvJ%2FxfYKDbfQTtfxlzFz63ZA2PxrVLbAzRji7PR98co4KUo8OToTy25nhXHdedVcXsUt3WZdBKH09owwj58mvXq&bit=1',
            'Upgrade-Insecure-Requests:1',
            'Content-Type:application/x-www-form-urlencoded',
            'Cache-Control:max-age=0',
            'Connection:keep-alive',
            'Accept-Language:en-US,en;q=0.8,vi;q=0.6,und;q=0.4',
        );

        $y=( $curl->post('https://de.proxfree.com/request.php?do=go&bit=1',"pfipDropdown=default&get=$u",4) );

        return ($curl->get($y[0]["Location"],'',2));
    }

    private function getSourceIPv4($url)
    {
        return file_get_html($url);
    }

    private function getVideoIPv6($id)
    {
        try {
            $id = urldecode($id);
            $url = 'https://drive.google.com/file/d/' . $id . '/view?pli=1';
            $body = $this->getSourceIPv6($url);
            if(strpos($body,'status=fail') !== false ) return false;

            $body = $this->decodeText($body);

            $data = explode(',["fmt_stream_map","', $body);
            $data = explode('"]', $data[1]);
            $data = str_replace(array('\u003d', '\u0026'), array('=', '&'), $data[0]);
            $data = explode(',', $data);
            asort($data);
            $source = array();
            foreach ($data as $url) {
                list($itag,$link) = explode('|', $url);
                if(in_array($itag, $this->itags)){
                    if($itag == 37) {
                        $source[] = array(
                            'type'      => 'mp4',
                            'label'     => 'HD/1080p',
                            'file'      => preg_replace("/\/[^\/]+\.google\.com/","/redirector.googlevideo.com",$link),
                            'default'   => false
                        );
                    }
                    if($itag == 22) {
                        $source[] = array(
                            'type'      => 'mp4',
                            'label'     => 'HD/720p',
                            'file'      => preg_replace("/\/[^\/]+\.google\.com/","/redirector.googlevideo.com",$link),
                            'default'   => true
                        );
                    }
                    if($itag == 59) {
                        $source[] = array(
                            'type'      => 'mp4',
                            'label'     => 'SD/480p',
                            'file'      => preg_replace("/\/[^\/]+\.google\.com/","/redirector.googlevideo.com",$link),
                            'default'   => false
                        );
                    }
                    if($itag == 18){
                        $source[] = array(
                            'type'      => 'mp4',
                            'label'     => 'SD/360p',
                            'file'      => preg_replace("/\/[^\/]+\.google\.com/","/redirector.googlevideo.com",$link),
                            'default'   => false
                        );
                    }

                }
            }
            $source = json_encode($source);
            return $source;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function getVideoIPv6_2($id) // Xai ke host nguoi ta
    {
        $url = 'https://api.anivn.com/?url=https://drive.google.com/file/d/' . $id . '/view';
        $url = 'https://api.blogit.vn/getlink.php?link='.$url.'&json=jwplayer';

        $ch = @curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $head[] = "Connection: keep-alive";
        $head[] = "Keep-Alive: 300";
        $head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $head[] = "Accept-Language: en-us,en;q=0.5";
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $page = curl_exec($ch);
        curl_close($ch);
        return $page;
    }

    private function getVideoIPv4($id)
    {
        try {
            $id = urldecode($id);
            $url = 'https://mail.google.com/e/get_video_info?docid=' . $id;
            $body = $this->getSourceIPv4($url);
            $body = $this->decodeText($body);

            if(strpos($body,'status=fail') !== false ) return false;

            $fmt = $this->fetchValueIPv4(urldecode($body), 'fmt_stream_map=', '&fmt_list=');

            $urls = explode(',', $fmt);
            $source = array();
            foreach ($urls as $url) {
                list($itag,$link) = explode('|', $url);
                if(in_array($itag, $this->itags)){
                    if($itag == 37) {
                        $source[] = array(
                            'type'      => 'mp4',
                            'label'     => 'HD/1080p',
                            'file'      => preg_replace("/\/[^\/]+\.google\.com/","/redirector.googlevideo.com",$link),
                            'default'   => false
                        );
                    } elseif ($itag == 22) {
                        $source[] = array(
                            'type'      => 'mp4',
                            'label'     => 'HD/720p',
                            'file'      => preg_replace("/\/[^\/]+\.google\.com/","/redirector.googlevideo.com",$link),
                            'default'   => true
                        );
                    } elseif ($itag == 59) {
                        $source[] = array(
                            'type'      => 'mp4',
                            'label'     => 'SD/480p',
                            'file'      => preg_replace("/\/[^\/]+\.google\.com/","/redirector.googlevideo.com",$link),
                            'default'   => false
                        );
                    } elseif ($itag == 18){
                        $source[] = array(
                            'type'      => 'mp4',
                            'label'     => 'SD/360p',
                            'file'      => preg_replace("/\/[^\/]+\.google\.com/","/redirector.googlevideo.com",$link),
                            'default'   => false
                        );
                    }

                }
            }
            $source = json_encode($source);
            return $source;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private function fetchValueIPv4($str, $find_start, $find_end)
    {
        $start = stripos($str, $find_start);

        if($start==false) return '';

        $length = strlen($find_start);
        $temp = substr($str, $start+$length);
        $end = stripos($temp, $find_end);
        return substr($temp, 0, $end);
    }

    private function decodeText($str)
    {
        return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $str);
    }



    public function VideoStreaming(Request $request, $id, $label)
    {
        $video = App\DBVideos::find($id);
        $file = '';
        if(!is_null($video)&&strlen($video->stream_google_url))
        {
            $data = json_decode($video->stream_google_url);
            foreach ($data as $i)
            {
                if($label===$i->label)
                    $file = $i->file;
            }

        }
        $value = \App\Library\MyFunction::getDirectLink($file);
        /*$file = fopen($value, 'rb');
        while (($content = fread($file, 2048)) !== false) { // Read in 2048-byte chunks
            echo $content; // or output it somehow else.
            flush(); // force output so far
        }
        fclose($file);*/
        $file = $value;
        $fp = @fopen($file, 'rb');
        $size   = filesize($file); // File size
        $length = $size;           // Content length
        $start  = 0;               // Start byte
        $end    = $size - 1;       // End byte
        header('Content-type: video/mp4');
//header("Accept-Ranges: 0-$length");
        header("Accept-Ranges: bytes");
        if (isset($_SERVER['HTTP_RANGE'])) {
            $c_start = $start;
            $c_end   = $end;
            list(, $range) = explode('=', $_SERVER['HTTP_RANGE'], 2);
            if (strpos($range, ',') !== false) {
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                header("Content-Range: bytes $start-$end/$size");
                exit;
            }
            if ($range == '-') {
                $c_start = $size - substr($range, 1);
            }else{
                $range  = explode('-', $range);
                $c_start = $range[0];
                $c_end   = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $size;
            }
            $c_end = ($c_end > $end) ? $end : $c_end;
            if ($c_start > $c_end || $c_start > $size - 1 || $c_end >= $size) {
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                header("Content-Range: bytes $start-$end/$size");
                exit;
            }
            $start  = $c_start;
            $end    = $c_end;
            $length = $end - $start + 1;
            fseek($fp, $start);
            header('HTTP/1.1 206 Partial Content');
        }
        header("Content-Range: bytes $start-$end/$size");
        header("Content-Length: ".$length);
        $buffer = 1024 * 8;
        while(!feof($fp) && ($p = ftell($fp)) <= $end) {
            if ($p + $buffer > $end) {
                $buffer = $end - $p + 1;
            }
            set_time_limit(0);
            echo fread($fp, $buffer);
            flush();
        }
        fclose($fp);
    }
    // Lấy link file video để xem
    /**
     * @return bool|string
     */
    public function getVideoFileUrlTemp()
    {
        try
        {
            if(Session::has('video_id'))
            {
                // Get Variables
                $video_id = Session::get('video_id');

                // GET VIDEO
                $video = App\DBVideos::find($video_id);

                $video_url = $video->url_source;
                $video_url_temp = $video->url_temp;

                // kiểm tra url temp
                if (strlen($video_url_temp)){
                    $video_url_temp = VideoController::GetOpenLoad($video_url);
                    if(VideoController::ValidVideoFileUrl($video_url_temp))
                    {

                        // Đã có và còn hoạt động thì sử dụng
                        return $video_url_temp;
                    }
                    else{
                        // Hết hoạt động thì tạo mới
                        $video_url_temp = VideoController::GetOpenLoad($video_url);
                    }
                }
                else
                {
                    // Tạo mới
                    $video_url_temp = VideoController::GetOpenLoad($video_url);
                }
                // Cập nhật csdl
                if ($video_url_temp){
                    $video->url_temp = $video_url_temp;
                    $video->save();
                }

                return $video_url_temp;
            }
            return false;
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    // Get Direct Link
    /**
     * @param $url
     * @return string
     */
    public function getDirectLink($url)
    {
        try
        {
            $headers = json_encode(get_headers($url));
            $url = explode('Location: ', $headers);
            $url = explode('","', $url[1]);

            return $url[0];
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    // Check Link
    /**
     * @param $url
     * @return bool|string
     */
    public function ValidVideoFileUrl($url)
    {
        try
        {
            $url = str_replace('\\','', $url);
            $url = str_replace('https://','',$url);
            $curl = curl_init($url);

            curl_setopt($curl, CURLOPT_NOBODY, true);
            $result = curl_exec($curl);

            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if($httpCode == '404') {
                return false;
            }
            if($httpCode == '200') {
                return true;
            }
            return false;
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }
}


class GGDrive
{
    var $contents;
    var $_header;
    var $headers = array();
    var $body;
    var $url = "";
    var $realm;
    var $ua = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1) Gecko/20061010 Firefox/2.0";
    var $proxy;
    var $prtype;
    var $tout = 10;
    var $opts = false;
    var $cookiefile = "lib/cookie.txt";
    var $httpheader = array();
    var $follow = false;
    var $referer = "";
    var $ch;

    function __construct(){
        $this->cookiefile = dirname(__FILE__)."/cookie.txt";
    }
    function exec($method, $url, $vars = "", $h = 1)
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_HEADER, ($h == 2) ? 0 : 1);

        if (is_array($this->realm)) {
            curl_setopt($this->ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($this->ch, CURLOPT_USERPWD, $this->realm[0] . ':' . $this->realm[1]);
        }

        if ($this->proxy != "") {
            if (strstr($this->proxy, "@")) {
                $t = explode("@", $this->proxy);
                $up = $t[0];
                $ip = $t[1];
            }
            curl_setopt($this->ch, CURLOPT_HTTPPROXYTUNNEL, 1) ;
            curl_setopt($this->ch, CURLOPT_PROXY, isset($ip) && $ip ? $ip : $this->proxy);
            curl_setopt($this->ch, CURLOPT_PROXYTYPE, $this->prtype);
            if (isset($up) && $up) {
                curl_setopt($this->ch, CURLOPT_PROXYAUTH, CURLAUTH_NTLM);
                curl_setopt($this->ch, CURLOPT_PROXYUSERPWD, $up);
            }
        }

        if ($this->ua)
            curl_setopt($this->ch, CURLOPT_USERAGENT, $this->ua);
        if ($this->referer || $this->url)
            curl_setopt($this->ch, CURLOPT_REFERER, $this->referer ? $this->referer : $this->
            url);

        if ($this->follow)
            curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);

        if (strncmp($url, "https", 6)) {
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
        }
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->cookiefile);
        curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->cookiefile);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->tout);

        if (count($this->httpheader)) {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->httpheader);
        }

        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $this->tout);
        if ($method == 'POST') {
            curl_setopt($this->ch, CURLOPT_POST, 1);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $vars);
        }

        if (is_array($this->opts) && $this->opts != false) {
            foreach ($this->opts as $k => $v) {
                curl_setopt($this->ch, $k, $v);
            }
        }

        $data = curl_exec($this->ch);
        $this->url = $url;

        if ($data) {
            if (preg_match("/^HTTP\/1\.1 302/", $data) && $h != 2 && strstr($data, "\r\n\r\nHTTP/1.1 200")) {
                $pos = strpos($data, "\r\n\r\n");
                $data = substr($data, $pos + 4);
            }

            if ($h == 1 || $h == 2)
                return $data;
            else {
                $pos = strpos($data, "\r\n\r\n");
                $this->body = substr($data, $pos + 4);
                $this->_header = substr($data, 0, $pos);
                $this->_header = explode("\r\n", trim($this->_header));
                foreach ($this->_header as $v) {
                    $v = explode(":", $v, 2);
                    $this->headers[$v[0]] = isset($v[1]) ? trim($v[1]) : '';
                }
                return $h == 3 ? $this->headers : array($this->headers, $this->body);
            }

        } else {
            return curl_error($this->ch);
        }
    }

    function proxy($proxy, $prtype = CURLPROXY_HTTP)
    { //CURLPROXY_SOCKS5
        $this->proxy = $proxy;
        $this->prtype = $prtype;
    }

    function settimeout($timeout)
    {
        $this->tout = $timeout;
    }

    function get($url,$vars, $h = 1)
    {
        $ret = $this->exec('GET', $url, $vars, $h);
        //$this->close();
        return $ret;
    }

    function post($url, $vars, $h = 1)
    {
        $ret = $this->exec('POST', $url, $vars, $h);
        //$this->close();
        return $ret;
    }

    function setopt($opt, $value = true)
    {
        $this->opts[$opt] = $value;
    }

    function seturl($url)
    {
        $this->url = $url;
    }

    function close()
    {
        curl_close($this->ch);
    }
}