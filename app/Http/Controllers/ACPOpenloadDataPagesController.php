<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 11/9/2016
 * Time: 4:52 PM
 */

namespace App\Http\Controllers;

use App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class ACPOpenloadDataPagesController extends Controller
{
    /**
     * @param Request $request
     * @return string
     */
    public function _List(Request $request)
    {
        try {
            $items = App\DBAnimes::all();

            $url = Request::root().'/admincp/openload-data';
            return View::make('admincp.ACPListView', [
                'items' => $items,
                'url' => $url
            ])->render();

        } catch (\Exception $e) {
            return '<div class="report">Lỗi!' . $e->getMessage() . '</div>';
        } finally {
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    public function _Edit(Request $request)
    {
        try
        {
            $url = Request::root().'/admincp/openload-data';
            $fansubList = App\DBFansub::all();
            if(Input::has('id')){
                $id = Input::get('id');
                $data = App\DBAnimes::find($id);

                return View::make('admincp.ACPOpenloadDataEditor',[
                    'data' => $data,
                    'fansubList' => $fansubList,
                    'url' => $url
                ])->render();
            }
            else{
                return '<div class="report">Lỗi!</div>';
            }
        } catch (\Exception $e) {
            return '<div class="report">Lỗi!' . $e->getMessage() . '</div>';
        } finally {
        }
    }

    public function _Save(Request $request)
    {
        try {
            if (Input::has('id')) {
                $anime_id = Input::get('id');
                $fansub_id = Input::get('fansub_id');
                $server_id = 3;
                $urls = Input::get('urls');

                $items = explode("\n", $urls);

                foreach ($items as $item) {
                    if(strlen($item)&&strpos($item, 'openload.co')) {
                        $start = strrpos($item, '/') + 1;
                        $end = strrpos($item, '.mp4');
                        $length = $end - $start;
                        $ep_name = substr($item, $start, $length);
						
						$ep_name = str_replace('%2C', ',', $ep_name);
						$ep_name = str_replace('%2B', '+', $ep_name);

                        $ep_id = null;
                        $episode = App\DBEpisodes::where([
                            ['anime_id', '=', $anime_id],
                            ['episode', '=', $ep_name]
                        ])->get()->first();
                        if (!is_null($episode)) {
                            $ep_id = $episode->id;
                        } else {
                            $episode = new App\DBEpisodes();
                            $episode->anime_id = $anime_id;
                            $episode->episode = $ep_name;
                            $episode->save();
                            $ep_id = $episode->id;
                        }

                        if (!is_null($ep_id)) {
                            $video = new App\DBVideos();
                            $video->episode_id = $ep_id;
                            $video->fansub_id = $fansub_id;
                            $video->server_id = $server_id;
                            $video->url_source = $item;
                            $video->save();

                            $url_download = str_replace('embed', 'f', $item);
                            $episode->url_download = $url_download;
                            $episode->save();
                        }
                    }
                }
                return '<div class="report">Lưu thành công!</div>';
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
}