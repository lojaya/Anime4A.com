<?php

namespace App\Http\Controllers;

use App;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    /**
     * @param Request $request
     * @return string
     */
    public function Test(Request $request)
    {
        try
        {
            $url = 'https://photos.google.com/share/AF1QipP_VE9kOHCeLa-M2ERShiEmyw51CzbRgcwoisXYdwTst_KzOETupg_FYPw_mz71-A/photo/AF1QipOpj0iNAEPx3vBIPNrTL3w6AWeXKhGcnb12C01G?key=bjRkQjU3U25qVTdiMlpkTS02cHp4bW4xZjNuRzJR';
            $j2t = new \J2T();
            $j2t->setLink = $url;
            $j2t->setFormat = isset($_GET['format']) ? $_GET['format'] : false;
            $data = $j2t->run();

            $data = $j2t->getSource();

            $_pattern = array(
                'valid_link' => array(
                    '/[0-9]{2}\/[0-9]{3,4}x[0-9]{3,4}\",\"url.*\]/',
                    '/\"url.*\"/'
                ),
                'quality' => array(
                    '/https.*720/',
                    '/https.*medium/',
                    '/https.*small/'
                ),
                'json' => array(
                    '/(.*?)&itag=[0-9]{2}&type=(.*?);\+codecs.*&quality=(.*)/'
                )
            );
            $cP = preg_match($_pattern['valid_link'][0], $data, $matches);
            $pattern = $matches[0];
            preg_match($_pattern['valid_link'][1], $pattern, $matches);
            $mediaArr = explode(',url', $matches[0]);

            $data = array();
            foreach($mediaArr as $i =>$value) {
                $value = str_replace('\u003d', '=', $value);
                $value = str_replace('\u0026', '&', $value);
                $value = str_replace('%3A', ':', $value);
                $value = str_replace('%3B', ';', $value);
                $value = str_replace('%3D', '=', $value);
                $value = str_replace('%2F', '/', $value);
                $value = str_replace('%2C', ',', $value);
                $value = str_replace('%22', '"', $value);
                if(preg_match($_pattern['quality'][0], $value, $m))
                {
                    preg_match($_pattern['json'][0],$m[0], $s);
                    $data['content'][] = array(
                        'url' => $s[1],
                        'quality' => $s[3],
                        'type' => $s[2]
                    );
                    unset($mediaArr[$i],$s);
                }
                if(preg_match($_pattern['quality'][1], $value, $m))
                {
                    preg_match($_pattern['json'][0],$m[0], $s);
                    $data['content'][] = array(
                        'url' => $s[1],
                        'quality' => $s[3],
                        'type' => $s[2]
                    );
                    unset($mediaArr[$i],$s);
                }
                if(preg_match($_pattern['quality'][2], $value, $m))
                {
                    preg_match($_pattern['json'][0],$m[0], $s);
                    $data['content'][] = array(
                        'url' => $s[1],
                        'quality' => $s[3],
                        'type' => $s[2]
                    );
                    unset($mediaArr[$i],$s);
                }
            }
            return var_dump($data);
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }


    /**
     * @param Request $request
     * @return string
     */
    public function Test2(Request $request)
    {
        try
        {
            $url = 'https://photos.google.com/share/AF1QipP_VE9kOHCeLa-M2ERShiEmyw51CzbRgcwoisXYdwTst_KzOETupg_FYPw_mz71-A/photo/AF1QipOpj0iNAEPx3vBIPNrTL3w6AWeXKhGcnb12C01G?key=bjRkQjU3U25qVTdiMlpkTS02cHp4bW4xZjNuRzJR';

            return VideoController::getGoogle($url);
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

}