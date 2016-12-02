<?php

namespace App\Http\Controllers;

require_once "kGoogle.class.php";

use App;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Session;
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
                $j2t = new \J2T();
                $j2t->setLink = $video->url_source;
                $j2t->setFormat = isset($_GET['format']) ? $_GET['format'] : false;
                $data = $j2t->run();
                $data = str_replace('\\','', $data);

                return $data;//redirect($video->url_source);
            }
            else
                return '<span style="color: white; font-size: 18pt">Video không tồn tại hoặc xảy ra sự cố ngoài ý muốn!!!</span>';
        }
        catch(\Exception $e)
        {
            return '<span style="color: white; font-size: 18pt">Video không tồn tại hoặc xảy ra sự cố ngoài ý muốn!!!</span>';
        }
    }

    // HOST: Google
    public static function getGoogle($url)
    {
        try
        {
            $source = VideoController::GetSource($url);

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
            $cP = preg_match($_pattern['valid_link'][0], $source, $matches);
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
            return $data;
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    public static function GetSource($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'GET');
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    // HOST: OpenLoad
    public static function GetOpenLoad($url)
    {
        try
        {
            $client = Client::getInstance();
            $client->getEngine()->setPath('D:\Project\www\Anime4A\bin\phantomjs.exe');

            $client->isLazy();

            $request = $client->getMessageFactory()->createRequest($url, 'GET');
            $request->addSetting('userAgent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7)');
            $response = $client->getMessageFactory()->createResponse();

            // Send the request
            $client->send($request, $response);

            if ($response->getStatus() === 200) {
                // Dump the requested page content
                $data = $response->getContent();
                $jsonURL = explode('video class="jw-video jw-reset"', $data);
                $jsonURL = explode('src="', $jsonURL[1]);
                $jsonURL = explode('"', $jsonURL[1]);
                $source = $jsonURL[0];
                return VideoController::getDirectLink($source);
            }

            return false;
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }
    // Lấy link file video để xem
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
