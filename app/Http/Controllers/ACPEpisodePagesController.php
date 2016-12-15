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
    /**
     * @param Request $request
     * @return string
     */
    public function AnimeList(Request $request)
    {
        try {
            $items = DBEpisodes::distinct()->select('anime_id')->get();

            return View::make('admincp.ACPEpisodeListView', array('items' => $items))->render();
        } catch (\Exception $e) {
            return '<div class="report">Lỗi!' . $e->getMessage() . '</div>';
        } finally {
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    public function EpisodeList(Request $request)
    {
        try {
            $id = Session::get('anime_id');

            $items = DBEpisodes::where('anime_id', $id)
                ->orderBy(\DB::raw('episode + 0'))
                ->get();

            return View::make('admincp.templates.EpisodeList', array('items' => $items))->render();
        } catch (\Exception $e) {
            return '<div class="report">Lỗi!' . $e->getMessage() . '</div>';
        } finally {
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    public function EpisodeEditor(Request $request)
    {
        try {
            $animeList = App\DBAnimes::all();
            $fansubDefaultList = App\DBFansub::all();

            if (Input::has('id')) {
                $id = Input::get('id');

                Session::put('anime_id', $id);

                return View::make('admincp.ACPEpisodeEditor', [
                    'anime_id' => $id,
                    'animeList' => $animeList,
                    'fansubDefaultList' => $fansubDefaultList
                ])->render();
            } else {
                Session::forget('anime_id');
                return View::make('admincp.ACPEpisodeEditor', [
                    'animeList' => $animeList,
                    'fansubDefaultList' => $fansubDefaultList
                ])->render();
            }
        } catch (\Exception $e) {
            return '<div class="report">Lỗi!' . $e->getMessage() . '</div>';
        } finally {
        }
    }

    /**
     * @param Request $request
     */
    public function EpisodeAdd(Request $request)
    {
        try {
            if (Input::has('episode')) {
                $episode = Input::get('episode');

                $ep = new DBEpisodes();
                $ep->episode = $episode;

                if (Session::has('anime_id')) {
                    $anime_id = Session::get('anime_id');
                    $ep->anime_id = $anime_id;
                    $ep->save();

                    // update newest episode
                    ACPEpisodePagesController::UpdateNewestEpisode($anime_id, $episode);

                    return 'Thành Công';
                }else
                {
                    $anime_id = Input::get('anime_id');
                    Session::put('anime_id', $anime_id);
                    $ep->anime_id = $anime_id;
                    $ep->save();

                    // update newest episode
                    ACPEpisodePagesController::UpdateNewestEpisode($anime_id, $episode);

                    return 'Thành Công';
                }
            } else {
                return 'Lỗi!';
            }
        } catch (\Exception $e) {
            return '<div class="report">Lỗi!'.$e->getMessage().'</div>';
        }
    }

    /**
     * @param Request $request
     */
    public function EpisodeAddList(Request $request)
    {
        try {
            if (Input::has('e1')&&Input::has('e2')) {
                $e1 = Input::get('e1');
                $e2 = Input::get('e2');
                $ep_arr = \App\Library\MyFunction::CreateEpisodeArray($e1, $e2);


                foreach($ep_arr as $i){
                    $ep = new DBEpisodes();
                    $ep->episode = $i;
                    if (Session::has('anime_id')) {

                        $anime_id = Session::get('anime_id');
                        $ep->anime_id = $anime_id;
                        $ep->save();

                        // update newest episode
                        ACPEpisodePagesController::UpdateNewestEpisode($anime_id, $i);
                    }else
                    {
                        $anime_id = Input::get('anime_id');
                        Session::put('anime_id', $anime_id);
                        $ep->anime_id = $anime_id;
                        $ep->save();

                        // update newest episode
                        ACPEpisodePagesController::UpdateNewestEpisode($anime_id, $i);
                    }
                }
                return 'Thành Công';
            } else {
                return 'Lỗi!';
            }
        } catch (\Exception $e) {
            return '<div class="report">Lỗi!'.$e->getMessage().'</div>';
        }
    }

    /**
     * @param Request $request
     */
    public function EpisodeSave(Request $request)
    {
        try {
            if (Input::has('episode_id')&&Input::has('episode')) {
                $episode_id = Input::get('episode_id');
                $episode = Input::get('episode');

                $ep = DBEpisodes::find($episode_id);
                $ep->episode = $episode;
                $ep->save();

                return 'Thành Công';
            } else {
                return 'Lỗi!';
            }
        } catch (\Exception $e) {
            return '<div class="report">Lỗi!'.$e->getMessage().'</div>';
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    public function EpisodeDelete(Request $request)
    {
        try {
            // Delete code
            if (Input::has('episode_id')) {
                $id = Input::get('episode_id');
                DBEpisodes::destroy($id);
            }
            return 'Thành Công';
        }
        catch (\Exception $e){
            return '<div class="report">Lỗi!'.$e->getMessage().'</div>';
        }
    }

    /**
     * @param $anime_id
     * @param $episode
     * @return string
     */
    public static function UpdateNewestEpisode($anime_id, $episode)
    {
        try {
            $anime = App\DBAnimes::find($anime_id);
            $anime->episode_new = $episode;
            $anime->save();
        }
        catch (\Exception $e){
            return '<div class="report">Lỗi!'.$e->getMessage().'</div>';
        }
    }
}