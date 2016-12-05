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
    // Hiện trang chủ
    /**
     * @param Request $request
     * @return string
     */
    public function showHomePage(Request $request, $type = null, $id = null)
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
            $userSigned = PagesController::CheckUserLogin();

            // Data for header
            $category_list = App\DBCategory::select('id', 'name')->get();
            $country_list = App\DBCountry::select('id', 'name')->get();
            $years = App\DBAnimes::distinct()->select(DB::raw('YEAR(release_date) year'))->get();

            // Films new updated
            $films = App\DBAnimes::orderBy('updated_at', 'desc')
                ->paginate(env('PAGE_SPLIT_BIG'), ['*'], 'page', 0);

            Session::put('type', 'A');
            $films->setPath('get-list-newUpdated');

            // Seach for homepage
            $breadcrumb = (object) array(
                'key' => '...',
                'value' => '...',
            );

            $hotFilms = App\DBAnimes::orderBy('updated_at', 'desc')
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
                    $seachFilms = App\DBAnimes::where('category', 'LIKE', '%'.$id.'%')
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
                    $seachFilms = App\DBAnimes::where('country', '=', $id)
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
                    $seachFilms = App\DBAnimes::whereYear('release_date', $id)
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
                $filmsRandom = App\DBAnimes::inRandomOrder()->take(25)->get();

                return View::make('index')->with([
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
                    'headerItems' => $filmsRandom,
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

    // Hiện trang xem phim
    /**
     * @param Request $request
     * @param $name
     * @param $anime_id
     * @param bool $episode_id
     * @param bool $fansub_id
     * @param bool $server_id
     * @return string
     */
    public function showVideoViewPage(Request $request, $name, $anime_id, $episode_id = false, $fansub_id = false, $server_id = false)
    {
        try
        {
            // Check user login
            $userSigned = PagesController::CheckUserLogin();

            // Remove video session
            Session::forget('video_id');

            // Data for header
            $category_list = App\DBCategory::select('id', 'name')->get();
            $country_list = App\DBCountry::select('id', 'name')->get();
            $years = App\DBAnimes::distinct()->select(DB::raw('YEAR(release_date) year'))->get();

            // Khởi tạo dữ liệu của trang
            $episode_list = array();
            $fansub_list = array();
            $server_list = array();

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
                            $episode_id = $f->id;
                    }
                }

                if($episode_id){
                    // GET FANSUB LIST
                    $fansub_list = App\DBVideos::select('fansub_id')
                        ->distinct('fansub_id')
                        ->where('episode_id',$episode_id)->get();

                }

                // Có chọn fansub  để xem
                if($fansub_id&&strlen($fansub_id))
                {
                    // GET SERVER LIST
                    $server_list = App\DBVideos::select('server_id')
                        ->distinct('server_id')
                        ->where([
                            ['episode_id', '=', $episode_id],
                            ['fansub_id', '=', $fansub_id],
                        ])->get();
                }
                // Chưa chọn fansub để xem -> mặc định xem fansub đầu
                else{
                    if(count($fansub_list))
                    {
                        $f = $fansub_list->first();
                        if(!is_null($f))
                        {
                            $fansub_id = $f->fansub_id;
                            // GET SERVER LIST
                            $server_list = App\DBVideos::select('server_id')
                                ->distinct('server_id')
                                ->where([
                                    ['episode_id', '=', $episode_id],
                                    ['fansub_id', '=', $fansub_id],
                                ])->get();
                        }
                    }
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
                ->where('fansub_id', '=', $fansub_id)
                ->where('server_id', '=', $server_id)
                ->get();
            $data = array();
            $video_type = '';
            if(!is_null($video->first()))
            {
                $video_id = $video->first()->id;
                Session::put('video_id', $video_id);
                $video = $video->first();

                if(strrpos($video->url_source, 'google')){
                    $j2t = new \J2T();
                    $j2t->setLink = $video->url_source;
                    $j2t->setFormat = isset($_GET['format']) ? $_GET['format'] : false;
                    $data = $j2t->run();
                    $data = str_replace('\\','', $data);
                    if(!count($data))
                    {
                        $data[] = array(
                            'type'      => 'mp4',
                            'label'     => 'HD',
                            'file'      => 'http://thenewcode.com/assets/videos/polina.mp4',
                            'default'   => true
                        );
                        $data = json_encode($data);
                    }
                    $video_type = 'google';
                }else
                {
                    $video_type = 'onecloud';
                }
            }else
                $video = null;

            // update view count
            $anime = App\DBAnimes::find($anime_id);
            $anime->view_count += 1;
            $anime->save();

            $bookmarks = $this::GetBookmarks($userSigned->username);

            $years = App\DBAnimes::distinct()->select(DB::raw('YEAR(release_date) year'))->get();


            // Check user device
            $mDetector = new App\Mobile_Detect();
            if($mDetector->isMobile()||$mDetector->isTablet())
            {
                return '<p style="font-size: 120pt;">Mobile</p>';
            }
            else
            {
                return view('VideoViewPage')->with([
                    'userSigned' => $userSigned,
                    'episode_list' => $episode_list,
                    'fansub_list' => $fansub_list,
                    'server_list' => $server_list,
                    'bookmarks' => $bookmarks,
                    'anime' => $anime,
                    'video' => $video,
                    'video_type' => $video_type,
                    'data' => $data,
                    'anime_id' => $anime_id,
                    'episode_id' => $episode_id,
                    'fansub_id' => $fansub_id,
                    'server_id' => $server_id,
                    'category_list' => $category_list,
                    'country_list' => $country_list,
                    'years' => $years,
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
            $userSigned = PagesController::CheckUserLogin();

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
            $userSigned = PagesController::CheckUserLogin();

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
     * @return object
     */
    public function CheckUserLogin()
    {
        $user = (object) array('signed' => false, 'loginHash' => '', 'username' => '');
        $loginHash = hash('sha256', 'Anime4A Login Successful');
        if(Session::has('loginHash')&&Session::has('username'))
        {
            if($loginHash==Session::get('loginHash')){
                $user->signed = true;
                $user->loginHash = $loginHash;
                $user->username = Session::get('username');
            }
        }
        return $user;
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
