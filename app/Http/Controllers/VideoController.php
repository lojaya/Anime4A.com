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
