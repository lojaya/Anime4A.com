<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/13/2016
 * Time: 5:32 PM
 */

namespace App\Library;

class MyFunction {
    // Format video name
    public static function GetFormatedName($name)
    {
        $string = strtolower($name);
        $pattern = '([^a-zA-Z0-9]) ';
        $replacement = '${1}-';
        $string =  preg_replace($pattern, $replacement, $string);
        $pattern = '[--+]';
        $replacement = '-';
        return preg_replace($pattern, $replacement, $string);
    }

    function nameFormat($name)
    {
        $string = strtolower($name);
        $pattern = '([^a-zA-Z0-9]) ';
        $replacement = '${1}-';
        $string =  preg_replace($pattern, $replacement, $string);
        $pattern = '[--+]';
        $replacement = '-';
        return preg_replace($pattern, $replacement, $string);
    }

    /**
     * @param $value
     * @param $category
     * @return bool
     */
    public static function CheckCategory($value, $category)
    {
        $f = true;
        foreach ($category as $i)
        {
            if($value==$i)
            {
                $f = false;
            }
        }
        return $f;
    }

    public static function getDirectLink($url) {
        $urlInfo = parse_url($url);
        $out  = "GET  {$url} HTTP/1.1\r\n";
        $out .= "Host: {$urlInfo['host']}\r\n";
        $out .= "User-Agent: {$_SERVER['HTTP_USER_AGENT']}\r\n";
        $out .= "Connection: Close\r\n\r\n";
        $con = @fsockopen('ssl://'. $urlInfo['host'], 443, $errno, $errstr, 10);

        if (!$con){
            return $errstr." ".$errno;
        }
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
}
