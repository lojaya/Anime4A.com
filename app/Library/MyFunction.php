<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/13/2016
 * Time: 5:32 PM
 */

namespace App\Library;

use App\DBLog;
use Illuminate\Support\Facades\Cookie;
use App\DBCategory;
use App\DBType;

class MyFunction {
    // Format video name
    /**
     * @param $name
     * @return mixed
     */
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
            $result = str_replace($i, ' '.$name, $result);
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

    /**
     * @param $type
     * @param $ep_new
     * @param $ep_total
     * @return string
     */
    public static function ShowType($type, $ep_new, $ep_total)
    {
        try
        {
            if($type==3||$type==7){
                if($ep_total==0)
                    return $ep_new.'/??';
                else
                    return $ep_new.'/'.$ep_total;

            }
            return DBType::GetName($type);
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public static function CreateEpisodeArray($e1, $e2)
    {
        try
        {
            if($e1>$e2)
                return false;
            $length = strlen((string) $e2);
            $result = array();
            for($i = $e1; $i<=$e2; $i++){
                $value = sprintf('%0'.$length.'s', $i);
                $result[] = $value;
            }
            return $result;
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public static function log()
    {
        try
        {
            if(!Cookie::has('connectingLog'))
            {
                Cookie::queue(Cookie::make('connectingLog', 'connectingLog', 30));

                // Function to get the client IP address
                $ipaddress = '';
                if (isset($_SERVER['HTTP_CLIENT_IP']))
                    $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
                else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                    $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
                else if(isset($_SERVER['HTTP_X_FORWARDED']))
                    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
                else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
                    $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
                else if(isset($_SERVER['HTTP_FORWARDED']))
                    $ipaddress = $_SERVER['HTTP_FORWARDED'];
                else if(isset($_SERVER['REMOTE_ADDR']))
                    $ipaddress = $_SERVER['REMOTE_ADDR'];
                else
                    $ipaddress = 'UNKNOWN';

                $log = new DBLog();
                $log->ip = $ipaddress;
                $log->referer = $_SERVER['HTTP_REFERER'];
                $log->user_agent = $_SERVER['HTTP_USER_AGENT'];
                $log->request_uri = $_SERVER['REQUEST_URI'];
                $log->save();
            }
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

}
