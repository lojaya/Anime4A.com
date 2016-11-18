<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/14/2016
 * Time: 10:59 PM
 */

use Illuminate\Support\Facades\DB;


$e = get('https://openload.co/stream/vK9eWUhZo_4~1479446303~2405:4800::~S91QGRW_');

var_dump($e);


// Get Direct Link
function getDirectLink($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);

    return $url;
}
function getDirectLink2($url)
{
    $headers = json_encode(get_headers($url));
    $url = explode('Location: ', $headers);
    $url = explode('","', $url[1]);

    return $url[0];
}
function get($url)
{
    $urlInfo = parse_url($url);
    $out  = "GET  {$url} HTTP/1.1\r\n";
    $out .= "Host: {$urlInfo['host']}\r\n";
    $out .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13\r\n";
    $out .= "Connection: Close\r\n\r\n";
    if (!$con = @fsockopen($urlInfo['host'], 80, $errno, $errstr, 10))
        return $errstr." ".$errno;
    fwrite($con, $out);
    $data = '';
    while (!feof($con)) {
        $data .= fgets($con, 512);
    }
    fclose($con);
    preg_match("!\r\n(?:Location|URI): *(.*?) *\r\n!", $data, $matches);
    $url = $matches[1];
    return trim($url);
}
?>
