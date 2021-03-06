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

class ACPVideoPagesController extends Controller
{
    /**
     * @param Request $request
     * @return string
     */
    public function VideoList(Request $request)
    {
        try {
            $items = DBVideos::all();

            return View::make('admincp.ACPVideoListView', array('items' => $items))->render();
        } catch (\Exception $e) {
            return '<div class="report">Lỗi!' . $e->getMessage() . '</div>';
        } finally {
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    public function VideoEditor(Request $request)
    {
        try
        {
            $fansubList = App\DBFansub::all();
            $serverList = App\DBServer::all();

            $anime_id = Session::get('edit_id');

            $epValue = Input::get('ep');
            $epObj = App\DBEpisodes::where('anime_id', $anime_id)
                ->where('episode', $epValue)
                ->get()->first();
            $epId = $epObj->id;

            $videos = DBVideos::where('episode_id', $epId)->get();


            if(Input::has('id')){
                $id = Input::get('id');
                $video = DBVideos::find($id);
                Session::put('edit_id', $id);

                $episode_id = $video->episode_id;
                $anime_id = App\DBEpisodes::find($episode_id)->anime_id;

                $episodeList = App\DBEpisodes::select('id', 'episode')
                    ->where('anime_id', $anime_id)
                    ->orderByRaw(\DB::raw('episode + 0'))->get(); // natural order

                return View::make('admincp.ACPVideoEditor',[
                    'video' => $video,
                    'fansubList' => $fansubList,
                    'serverList' => $serverList,
                    'anime_id' => $anime_id,
                    'url_source' => $video->url_source,
                    'episode_id' => $episode_id,
                    'episodeList' => $episodeList
                ])->render();
            }
            else{
                Session::forget('edit_id');
                return View::make('admincp.ACPVideoEditor',[
                    'fansubList' => $fansubList,
                    'serverList' => $serverList
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
                if (Session::has('edit_id')) {
                    $id = Session::get('edit_id');
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
                Session::forget('edit_id');

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
    public function VideoDelete(Request $request)
    {
        try {
            // Delete code
            if (Input::has('id')) {
                $ids = Input::get('id');
                foreach ($ids as $i) {
                    DBVideos::destroy($i);
                }

                $items = DBVideos::all();
                return View::make('admincp.ACPVideoListView', array('items' => $items))->render();
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
    public function GetEpisode(Request $request)
    {
        if(Input::has('id'))
        {
            $anime_id = Input::get('id');
            $ep = App\DBEpisodes::select('id', 'episode')
                ->where('anime_id', $anime_id)
                ->orderBy(\DB::raw('episode + 0'))
                ->get();
            return json_encode($ep);
        }
    }
}