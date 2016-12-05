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
}
