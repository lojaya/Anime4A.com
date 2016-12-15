<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 11/9/2016
 * Time: 4:52 PM
 */

namespace App\Http\Controllers;

use App;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

use App\Library\MyFunction;
use App\DBVideos;

class ACPVideoController extends Controller
{
    /**
     * @param Request $request
     * @return string
     */
    public function VideoList(Request $request)
    {
        try {
            $anime_id = Session::get('anime_id');
            $ep = Input::get('ep');
            Session::put('ep', $ep);

            $episode = App\DBEpisodes::where([
                ['anime_id', '=', $anime_id],
                ['episode', '=', $ep],
            ])->get()->first();

            if(!is_null($episode)){
                $episode_id = $episode->id;
                $episode_name = $episode->episode;
                $items = DBVideos::where('episode_id', $episode_id)->get();

                Session::forget('video_id');
                return View::make('admincp.templates.VideoList', array('items' => $items, 'episode_id' => $episode_id, 'episode_name' => $episode_name))->render();
            }

        } catch (\Exception $e) {
            return '<div class="report">Lỗi!' . $e->getMessage() . '</div>';
        } finally {
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    public function VideoEdit(Request $request)
    {
        try
        {
            $fansubList = App\DBFansub::all();
            $serverList = App\DBServer::all();

            if(Input::has('id')){
                $id = Input::get('id');
                $video = DBVideos::find($id);
                Session::put('video_id', $id);

                $episode_id = $video->episode_id;
                $anime_id = App\DBEpisodes::find($episode_id)->anime_id;

                return View::make('admincp.ACPVideoEditor',[
                    'video' => $video,
                    'fansubList' => $fansubList,
                    'serverList' => $serverList,
                    'anime_id' => $anime_id,
                    'episode_id' => $episode_id,
                    'url_source' => $video->url_source
                ])->render();
            }
        } catch (\Exception $e) {
            return '<div class="report">Lỗi!' . $e->getMessage() . '</div>';
        } finally {
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    public function VideoSave(Request $request)
    {
        try {
            $episode_id = Input::get('episode_id');
            if(strlen($episode_id))
            {
                if (Session::has('video_id')) {
                    $id = Session::get('video_id');
                    $video = DBVideos::find($id);
                } else {
                    $video = new DBVideos();
                }

                if(strlen($episode_id))
                    $video->episode_id = $episode_id;

                $fansub_id = Input::get('fansub_id');
                if(strlen($fansub_id))
                    $video->fansub_id = $fansub_id;

                $server_id = Input::get('server_id');
                if(strlen($server_id))
                    $video->server_id = $server_id;

                $url_source = Input::get('url_source');
                $video->url_source = $url_source;
                $url_download = Input::get('url_download');
                $video->url_download = $url_download;

                $video->save();

                // remove edit id session
                Session::forget('video_id');

                return '<div class="report">Lưu thành công!</div>';
            }else
            {
                return '<div class="report">Lỗi chọn Anime và Episode!</div>';
            }
        } catch (\Exception $e) {
            $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                return '<div class="report">Lỗi trùng dữ liệu!</div>';
            }
            return '<div class="report">Lỗi!</div>';
        } finally {
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    public function VideoAdd(Request $request)
    {
        try {
            $fansubList = App\DBFansub::all();
            $serverList = App\DBServer::all();

            $anime_id = Session::get('anime_id');
            $ep = Session::get('ep');
            $episode = App\DBEpisodes::where([
                ['anime_id', '=', $anime_id],
                ['episode', '=', $ep],
            ])->get()->first();

            if(!is_null($episode)) {
                $episode_id = $episode->id;
                Session::forget('video_id');
                return View::make('admincp.ACPVideoEditor', [
                    'episode_id' => $episode_id,
                    'fansubList' => $fansubList,
                    'serverList' => $serverList
                ])->render();
            }
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
    public function VideoDelete(Request $request)
    {
        try {
            // Delete code
            if (Input::has('id')) {
                $id = Input::get('id');
                DBVideos::destroy($id);
                return '<div class="report">Xóa thành công!</div>';
            }
        } catch (\Exception $e) {
            return '<div class="report">Lỗi!' . $e->getMessage() . '</div>';
        } finally {
        }
    }
}