<?php

namespace App\Http\Controllers;

use App;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

use App\Library\MyFunction;

class PagesController extends Controller
{
    /**
     * @param Request $request
     * @return string
     */
    public function showHomePage(Request $request, $type = null, $id = null) // Hiện trang chủ
    {
        try
        {
            // Set Localization
            // Checking & create session data
            $lang = session('lang');
            if(strlen($lang) <= 0){
                // Checking IP Location
                $ip = $_SERVER['REMOTE_ADDR'];
                //$details = json_decode(file_get_contents("http://freegeoip.net/json/{$ip}"));
                $country = 'vn';//$details->country_code;
                switch(strtolower($country)){
                    case 'en':
                        session(['lang' => 'en']);
                        $lang = 'en';
                        break;
                    case 'vn':
                        session(['lang' => 'vn']);
                        $lang = 'vn';
                        break;
                    default:
                        session(['lang' => 'en']);
                        $lang = 'en';
                        break;
                }
            }

            switch ($lang){
                case 'en':
                    App::setLocale('en');
                    break;
                case 'vn':
                    App::setLocale('vn');
                    break;
                default:
                    App::setLocale('en');
            }

            // Check user login
            $userSigned = UsersController::CheckUserLogin();

            // Data for header
            $category_list = App\DBCategory::select('id', 'name')->get();
            $country_list = App\DBCountry::select('id', 'name')->get();
            $years = App\DBAnimes::distinct()->select(DB::raw('YEAR(release_date) year'))
                ->orderBy('year', 'desc')->get();

            // Films new updated
            $films = App\DBAnimes::where('enabled', 1)->orderBy('updated_at', 'desc')
                ->paginate(env('PAGE_SPLIT_BIG'), ['*'], 'page', 0);

            Session::put('type', 'A');
            $films->setPath('get-list-newUpdated');

            // Seach for homepage
            $breadcrumb = (object) array(
                'key' => '...',
                'value' => '...',
            );

            $hotFilms = App\DBAnimes::where('enabled', 1)->orderBy('hot', 'desc')
                ->take(env('PAGE_SPLIT_SMALL'))->get();

            $seaching = false;
            $seachFilms = null;
            switch ($type)
            {
                case 'the-loai':
                    $seaching = true;
                    $breadcrumb->key = 'Thể Loại';

                    $category = App\DBCategory::find($id);
                    $breadcrumb->value = $category->name;

                    // search codes
                    $seachFilms = App\DBAnimes::where('enabled', 1)->where('category', 'LIKE', '%'.$id.'%')
                        ->orderBy('updated_at', 'desc')
                        ->paginate(env('PAGE_SPLIT_SMALL'), ['*'], 'page', 1);
                    $seachFilms->setPath('search/category/'.$id);
                    break;
                case 'quoc-gia':
                    $seaching = true;
                    $breadcrumb->key = 'Quốc Gia';

                    $country = App\DBCountry::find($id);
                    $breadcrumb->value = $country->name;

                    // search codes
                    $seachFilms = App\DBAnimes::where('enabled', 1)->where('country', '=', $id)
                        ->orderBy('updated_at', 'desc')
                        ->paginate(env('PAGE_SPLIT_SMALL'), ['*'], 'page', 1);
                    $seachFilms->setPath('search/country/'.$id);
                    break;
                case 'nam-san-xuat':
                    $seaching = true;
                    $breadcrumb->key = 'Năm';

                    $breadcrumb->value = $id;

                    // paging
                    $page = Input::get('page');

                    // search codes
                    $seachFilms = App\DBAnimes::where('enabled', 1)->whereYear('release_date', $id)
                        ->orderBy('updated_at', 'desc')
                        ->paginate(env('PAGE_SPLIT_SMALL'), ['*'], 'page', 1);
                    $seachFilms->setPath('search/year/'.$id);
                    break;
            }

            // Get Bookmarks
            $bookmarks = $this::GetBookmarks($userSigned->username);

            // Check user device
            $mDetector = new App\Mobile_Detect();
            if($mDetector->isMobile()||$mDetector->isTablet())
            {
                return View::make('mobile.index')->with([
                    'userSigned' => $userSigned,
                    'breadcrumb' => $breadcrumb,
                    'seaching' => $seaching,
                    'bookmarks' => $bookmarks,
                    'films' => $films,
                    'hotFilms' => $hotFilms,
                    'seachFilms' => $seachFilms,
                    'category_list' => $category_list,
                    'country_list' => $country_list,
                    'years' => $years,
                    'homepageSelected' => 'M',
                    'newestFilmSelected' => 'S',
                    'mostViewSelected' => 'W'
                ]);
            }
            else
            {
                // Random films for slider
                //$filmsRandom = App\DBAnimes::where('enabled', 1)->orderBy('hot', 'desc')->take(env('PAGE_SPLIT_SMALL'))->get();

                $sidebarFilms = App\DBAnimes::where('enabled', 1)
                    ->orderBy('view_count', 'desc')
                    ->take(10)->get();

                return View::make('index')->with([
                    'userSigned' => $userSigned,
                    'breadcrumb' => $breadcrumb,
                    'seaching' => $seaching,
                    'bookmarks' => $bookmarks,
                    'films' => $films,
                    'hotFilms' => $hotFilms,
                    'seachFilms' => $seachFilms,
                    'sidebarFilms' => $sidebarFilms,
                    'category_list' => $category_list,
                    'country_list' => $country_list,
                    'years' => $years,
                    'headerItems' => $hotFilms,
                    'homepageSelected' => 'M',
                    'newestFilmSelected' => 'S',
                    'mostViewSelected' => 'W'
                ]);
            }
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    /**
     * @param Request $request
     * @param $name
     * @param $anime_id
     * @param bool $episode_id
     * @param bool $fansub_id
     * @param bool $server_id
     * @return string
     */
    public function showVideoViewPage(Request $request, $name, $anime_id, $episode_id = false, $server_id = false) // Hiện trang xem phim
    {
        try
        {
            // Check user login
            $userSigned = UsersController::CheckUserLogin();

            // Remove video session
            Session::forget('video_id');

            // Data for header
            $category_list = App\DBCategory::select('id', 'name')->get();
            $country_list = App\DBCountry::select('id', 'name')->get();
            $years = App\DBAnimes::distinct()->select(DB::raw('YEAR(release_date) year'))
                ->orderBy('year', 'desc')->get();

            // Khởi tạo dữ liệu của trang
            $episode_list = array();
            $server_list = array();
            $n = '';
            if(strlen($anime_id))
            {
                // GET EPISODE LIST
                $episode_list = App\DBEpisodes::select('id', 'episode')
                    ->where('anime_id', $anime_id)
                    ->orderBy(DB::raw('episode + 0'))->get(); // natural order

                // Có chọn tập phim để xem
                if($episode_id&&strlen($episode_id))
                {
                }
                // Chưa chọn tập phim để xem -> mặc định xem tập đầu
                else{
                    if(count($episode_list))
                    {
                        $f = $episode_list->first();
                        if(!is_null($f))
                        {
                            $episode_id = $f->id;
                        }
                    }
                }

                if($episode_id){
                    // GET SERVER LIST
                    $server_list = App\DBVideos::select('server_id')
                        ->distinct('server_id')
                        ->where('episode_id', $episode_id)->get();
                    $n=$episode_id;
                }

                // Có chọn server  để xem
                if($server_id&&strlen($server_id))
                {
                }
                // Chưa chọn server để xem -> mặc định xem server đầu
                else{
                    if(count($server_list))
                    {
                        $f = $server_list->first();
                        if(!is_null($f))
                            $server_id = $f->server_id;
                    }
                }
            }
            else{
                return "Incorrect Anime!";
            }


            $video = App\DBVideos::where('episode_id', '=', $episode_id)
                ->where('server_id', '=', $server_id)
                ->get()->first();

            $video_type = '';
            if(!is_null($video))
            {
                $video_id = $video->id;
                Session::put('video_id', $video_id);

                if(strrpos($video->url_source, 'drive.google.com')){
                    $video_type = 'google';
                }else{
                    $video_type = 'photos';
                }
            }

            // update view count
            $anime = App\DBAnimes::find($anime_id);
            $anime->view_count += 1;
            $anime->timestamps = false; // no update time stamps
            $anime->save();

            $bookmarks = $this::GetBookmarks($userSigned->username);

            // Check user device
            $mDetector = new App\Mobile_Detect();
            if($mDetector->isMobile()||$mDetector->isTablet())
            {
                return view('mobile.VideoViewPage')->with([
                    'userSigned' => $userSigned,
                    'episode_list' => $episode_list,
                    'server_list' => $server_list,
                    'bookmarks' => $bookmarks,
                    'anime' => $anime,
                    'video' => $video,
                    'video_type' => $video_type,
                    'anime_id' => $anime_id,
                    'episode_id' => $episode_id,
                    'server_id' => $server_id,
                    'category_list' => $category_list,
                    'country_list' => $country_list,
                    'years' => $years,
                    'homepageSelected' => 'M',
                    'newestFilmSelected' => 'S',
                    'mostViewSelected' => 'W'
                ]);
            }
            else
            {

                // Sidebar Films List
                $sidebarFilms = App\DBAnimes::where('enabled', 1)
                    ->orderBy('view_count', 'desc')
                    ->take(10)->get();

                return view('VideoViewPage')->with([
                    'userSigned' => $userSigned,
                    'episode_list' => $episode_list,
                    'server_list' => $server_list,
                    'bookmarks' => $bookmarks,
                    'anime' => $anime,
                    'video' => $video,
                    'video_type' => $video_type,
                    'anime_id' => $anime_id,
                    'episode_id' => $n,
                    'server_id' => $server_id,
                    'category_list' => $category_list,
                    'country_list' => $country_list,
                    'years' => $years,
                    'sidebarFilms' => $sidebarFilms,
                    'homepageSelected' => 'M',
                    'newestFilmSelected' => 'S',
                    'mostViewSelected' => 'W'
                ]);
            }
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    // Trang danh sách
    /**
     * @param Request $request
     * @return string
     */
    public function listPage(Request $request)
    {
        try
        {
            // Check user login
            $userSigned = UsersController::CheckUserLogin();

            // Data for header
            $category_list = App\DBCategory::select('name')->get();
            $country_list = App\DBCountry::select('name')->get();

            $animes = App\DBAnimes::all();
            return View::make('ListPage')->with([
                'userSigned' => $userSigned,
                'category_list' => $category_list,
                'country_list' => $country_list,
                'animes' => $animes
            ]);
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    // Trang tìm kiếm
    /**
     * @param Request $request
     * @return string
     */
    public function searchPage(Request $request)
    {
        try
        {
            // Check user login
            $userSigned = UsersController::CheckUserLogin();

            // Data for header
            $category_list = App\DBCategory::all();
            $country_list = App\DBCountry::select('name')->get();

            return View::make('SearchPage')->with([
                'userSigned' => $userSigned,
                'category_list' => $category_list,
                'country_list' => $country_list,
                'homepageSelected' => 'M',
                'newestFilmSelected' => 'S',
                'mostViewSelected' => 'W'
            ]);
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
    public function GetSources(Request $request)
    {
        try
        {
            // Check user login
            $userSigned = UsersController::CheckUserLogin();

            return View::make('GetSources')->with([
                'userSigned' => $userSigned
            ]);
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }
    /**
     * @param $user_name
     * @return null
     */
    public static function GetBookmarks($user_name)
    {
        $user = App\DBUsers::where('username', $user_name)->get();
        $bookmarks = null;
        if(!is_null($user->first()))
        {
            $user = $user->first();
            $bookmarks = App\DBBookmarks::where('user_id',$user->id)->get();
        }
        return $bookmarks;
    }
}
