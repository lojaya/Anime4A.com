<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/13/2016
 * Time: 5:32 PM
 */

namespace App\Library;

use App\DBCategory;

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

    /**
     * @param $url
     * @return string
     */
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

    /**
     * @param $cat
     * @return mixed
     */
    public static function GetCategoryNameString($cat)
    {
        $catArr = explode(',', $cat);
        $result = $cat;
        foreach($catArr as $i)
        {
            $name = DBCategory::GetName($i);
            $result = str_replace($i, $name, $result);
        }
        return $result;
    }

    /**
     * @param $cat
     * @return array
     */
    public static function GetCategoryNameArray($cat)
    {
        $result = explode(',', $cat);
        for($i = 0; $i<count($result); $i++){

        }
        foreach($result as $i => $value)
        {
            $result[$i] = DBCategory::GetName($value);
        }
        return $result;
    }
}
