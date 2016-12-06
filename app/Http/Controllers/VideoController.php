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
                return redirect($video->url_source);
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
                $j2t = new \J2T();
                $j2t->setLink = $video->url_source;
                $j2t->setFormat = isset($_GET['format']) ? $_GET['format'] : false;
                $source = json_decode($j2t->run());

                $data = array();
                foreach ($source as $key => $value){
                    $value->file = \App\Library\MyFunction::getDirectLink($value->file);

                    $data[] = array(
                        'type'      => $value->type,
                        'label'     => $value->label,
                        'file'      => $value->file,
                        'default'   => $value->default
                    );
                }

                if(!count($data))
                {
                    $data[] = array(
                        'type'      => 'mp4',
                        'label'     => 'HD',
                        'file'      => 'http://thenewcode.com/assets/videos/polina.mp4',
                        'default'   => true
                    );
                }
                $data = json_encode($data);
                $data = str_replace('\\','', $data);

                return view('EmbedVideo')->with([
                    'video' => $video,
                    'data' => $data,
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
