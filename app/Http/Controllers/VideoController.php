<?php

namespace App\Http\Controllers;

use App;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Session;
use JonnyW\PhantomJs\Client;

class VideoController extends Controller
{
    // Get File Url Temp
    // HOST: Google
    public function getGoogle()
    {
        try
        {
            return "Ab";
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }
    // HOST: OpenLoad
    public function GetOpenLoad($url)
    {
        try
        {
            $client = Client::getInstance();
            $client->getEngine()->setPath('D:\Project\www\Anime4A\bin\phantomjs.exe');

            $request = $client->getMessageFactory()->createRequest($url, 'GET');

            $response = $client->getMessageFactory()->createResponse();

            // Send the request
            $client->send($request, $response);

            if ($response->getStatus() === 200) {
                // Dump the requested page content
                $data = $response->getContent();
                $jsonURL = explode('<span id="streamurl">', $data);
                $jsonURL = explode('</span>', $jsonURL[1]);
                $source = "https://openload.co/stream/" . $jsonURL[0];

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
