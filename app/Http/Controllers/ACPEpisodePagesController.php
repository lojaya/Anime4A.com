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
use App\DBEpisodes;
use DateTime;

class ACPEpisodePagesController extends Controller
{
    public function EpisodeList(Request $request)
    {
        try {
            $items = DBEpisodes::all();

            return View::make('admincp.ACPEpisodeListView', array('items' => $items))->render();
        } catch (\Exception $e) {
            return '<div class="report">Lỗi!' . $e->getMessage() . '</div>';
        } finally {
        }
    }

    public function EpisodeEditor(Request $request)
    {
        try {
            $animeList = App\DBAnimes::all();

            if (Input::has('id')) {
                $id = Input::get('id');
                $episode = DBEpisodes::find($id);

                Session::put('edit_id', $id);

                return View::make('admincp.ACPEpisodeEditor', [
                    'episode' => $episode,
                    'animeList' => $animeList
                ])->render();
            } else {
                Session::forget('edit_id');
                return View::make('admincp.ACPEpisodeEditor', [
                    'animeList' => $animeList
                ])->render();
            }
        } catch (\Exception $e) {
            return '<div class="report">Lỗi!' . $e->getMessage() . '</div>';
        } finally {
        }
    }

    public function EpisodeSave(Request $request)
    {
        try{
            if(Session::has('edit_id'))
            {
                $id = Session::get('edit_id');
                $episode = DBEpisodes::find($id);
            }else{
                $episode = new DBEpisodes();
            }
            $anime_id = Input::get('anime_id');
            if(strlen($anime_id))
                $episode->anime_id = $anime_id;
            $episode->episode = Input::get('episode');

            $episode->save();

            // remove edit id session
            Session::forget('edit_id');

            return '<div class="report">Lưu thành công!</div>';
        }
        catch (\Exception $e){
            $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                return '<div class="report">Lỗi trùng dữ liệu!</div>';
            }
            return '<div class="report">Lỗi!'.$e->getMessage().'</div>';
        }
        finally {
        }
    }

    public function EpisodeDelete(Request $request)
    {
        try {
            // Delete code
            if (Input::has('id')) {
                $ids = Input::get('id');
                foreach ($ids as $i) {
                    DBEpisodes::destroy($i);
                }

                $items = DBEpisodes::all();
                return View::make('admincp.ACPEpisodeListView', array('items' => $items))->render();
            }
        }
        catch (\Exception $e){
            return '<div class="report">Lỗi!'.$e->getMessage().'</div>';
        }
        finally {
        }
    }
}