<?php

namespace App\Http\Controllers;

use App;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;


class AnimesController extends Controller
{
    // Lấy thông tin animes mới cập nhật
    /**
     * @param Request $request
     * @return string
     */
    public function newUpdated(Request $request)
    {
        try
        {
            $films = array();
            $pageSplit = env('PAGE_SPLIT_BIG');
            if(Request::ajax())  {
                $type = '';
                if(Input::has('type')){
                    $type = Input::get('type');
                }else{
                    $type = Session::get('type');
                }

                $page = Input::get('page');

                switch ($type) {
                    case 'D':
                        Session::put('type', 'D');
                        $films = App\DBAnimes::where('enabled', 1)
                            ->whereDate('updated_at', '=', date('Y-m-d'))
                            ->orderBy('updated_at', 'desc')
                            ->paginate($pageSplit, ['*'], 'page', $page);
                        break;
                    case 'W':
                        Session::put('type', 'W');
                        $films = App\DBAnimes::where('enabled', 1)
                            ->where('updated_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 7 DAY)'))
                            ->orderBy('updated_at', 'desc')
                            ->paginate($pageSplit, ['*'], 'page', $page);
                        break;
                    case 'M':
                        Session::put('type', 'M');
                        $films = App\DBAnimes::where('enabled', 1)
                            ->where('updated_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 30 DAY)'))
                            ->orderBy('updated_at', 'desc')
                            ->paginate($pageSplit, ['*'], 'page', $page);
                        break;
                    case 'S':
                        Session::put('type', 'S');
                        $films = App\DBAnimes::where('enabled', 1)
                            ->where('updated_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 120 DAY)'))
                            ->orderBy('updated_at', 'desc')
                            ->paginate($pageSplit, ['*'], 'page', $page);
                        break;
                    case 'Y':
                        Session::put('type', 'Y');
                        $films = App\DBAnimes::where('enabled', 1)
                            ->whereYear('updated_at', '=', date('Y'))
                            ->orderBy('updated_at', 'desc')
                            ->paginate($pageSplit, ['*'], 'page', $page);
                        break;
                    case 'A':
                        Session::put('type', 'A');
                        $films = App\DBAnimes::where('enabled', 1)
                            ->orderBy('updated_at', 'desc')
                            ->paginate($pageSplit, ['*'], 'page', $page);
                        break;
                    default:
                        return '';
                }

            }

            $films->setPath('get-list-newUpdated');
            return View::make('templates.NewUpdated', array('films' => $films))->render();
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    // Lấy thông tin animes mới ra mắt
    /**
     * @param Request $request
     * @return string
     */
    public function newestAnime(Request $request)
    {
        try
        {
            $films = array();
            $myFunc = new MyFunction();
            $htmlCode = '';
            if(Request::ajax())  {
                $type = Input::get('type');
                switch ($type) {
                    case 'D':
                        $films = App\DBAnimes::whereDate('release_date', '=', date('Y-m-d'))
                            ->orderBy('release_date', 'desc')
                            ->take(5)->get();
                        break;
                    case 'W':
                        $films = App\DBAnimes::where('release_date', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 7 DAY)'))
                            ->orderBy('release_date', 'desc')
                            ->take(5)->get();
                        break;
                    case 'M':
                        $films = App\DBAnimes::where('release_date', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 30 DAY)'))
                            ->orderBy('release_date', 'desc')
                            ->take(5)->get();
                        break;
                    case 'S':
                        $films = App\DBAnimes::where('release_date', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 120 DAY)'))
                            ->orderBy('release_date', 'desc')
                            ->take(5)->get();
                        break;
                    case 'Y':
                        $films = App\DBAnimes::whereYear('release_date', '=', date('Y'))
                            ->orderBy('release_date', 'desc')
                            ->take(5)->get();
                        break;
                    default:
                        return '';
                }
            }
            return View::make('templates.SideBar', array('films' => $films))->render();
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    // Lấy thông tin animes xem nhiều nhất
    /**
     * @param Request $request
     * @return string
     */
    public function mostView(Request $request)
    {
        try
        {
            $films = array();

            $nItem = 10;
            if(Request::ajax()) {
                $type = '';
                if(Input::has('type')){
                    $type = Input::get('type');
                }else{
                    $type = Session::get('type');
                }

                switch ($type) {
                    case 'D':
                        $films = App\DBAnimes::where('enabled', 1)
                            ->whereDate('updated_at', '=', date('Y-m-d'))
                            ->orderBy('view_count', 'desc')
                            ->take($nItem)->get();
                        break;
                    case 'W':
                        $films = App\DBAnimes::where('enabled', 1)
                            ->where('updated_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 7 DAY)'))
                            ->orderBy('view_count', 'desc')
                            ->take($nItem)->get();
                        break;
                    case 'M':
                        $films = App\DBAnimes::where('enabled', 1)
                            ->where('updated_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 30 DAY)'))
                            ->orderBy('view_count', 'desc')
                            ->take($nItem)->get();
                        break;
                    case 'S':
                        $films = App\DBAnimes::where('enabled', 1)
                            ->where('updated_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 120 DAY)'))
                            ->orderBy('view_count', 'desc')
                            ->take($nItem)->get();
                        break;
                    case 'Y':
                        $films = App\DBAnimes::where('enabled', 1)
                            ->orderBy('view_count', 'desc')
                            ->take($nItem)->get();
                        break;
                    default:
                        return '';
                }
            }
            return View::make('templates.SideBar', array('films' => $films))->render();
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
    public function Bookmark(Request $request)
    {
        try
        {
            if(Input::has('id')){
                $id = Input::get('id');
                $bookmark = new App\DBBookmarks();
                $bookmark->user_id = UsersController::CheckUserLogin()->id;
                $bookmark->anime_id = $id;
                $bookmark->save();
                return $id;
            }

            return false;
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function BookmarkDelete(Request $request)
    {
        try
        {
            if(Input::has('id')){
                $id = Input::get('id');
                App\DBBookmarks::destroy($id);
                $userSigned = UsersController::CheckUserLogin();
                $bookmarks = PagesController::GetBookmarks($userSigned->username);
                return View::make('templates.BookmarkItem', array('bookmarks' => $bookmarks))->render();
            }
            return false;
        }
        catch(\Exception $e)
        {
            return false;
        }
    }
}
