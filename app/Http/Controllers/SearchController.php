<?php

namespace App\Http\Controllers;

use App;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

use App\DBAnimes;

class SearchController extends Controller
{
    /**
     * @param Request $request
     * @return bool|string
     */
    public function Find(Request $request)
    {
        try
        {
            $searchString = Input::get('searchString');
            $animes = DBAnimes::where('name', 'LIKE', '%' . $searchString . '%')
                ->get();

            if(count($animes))
                return View::make('templates.SearchItem', array('items' => $animes))->render();
            else
                return false;
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
    public function AdvancedSearch(Request $request)
    {

        try
        {
            $films = array();

            if(Request::ajax())  {
                $searchString = Input::get('searchString');
                $categorySearch = Input::get('categorySearch');

                $films = App\DBAnimes::where('name', 'LIKE', '%'.$searchString.'%')
                    ->get();

                foreach ($categorySearch as $i)
                {
                    $films->where('category', 'LIKE', '%'.$i.'%')->get();
                }
            }

            return View::make('templates.NewUpdated', array('films' => $films))->render();
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function SearchFilmByCategory(Request $request)
    {
        try
        {
            // search codes
            $id = Input::get('id');
            $page = Input::get('page');

            $seachFilms = App\DBAnimes::where('category', 'LIKE', '%'.$id.'%')
                ->orderBy('updated_at', 'desc')
                ->paginate(env('PAGE_SPLIT_SMALL'), ['*'], 'page', $page);
            $seachFilms->setPath('search/category/'.$id);

            return View::make('templates.SearchAnime', array('seachFilms' => $seachFilms))->render();
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function SearchFilmByCountry(Request $request)
    {
        try
        {
            // search codes
            $id = Input::get('id');
            $page = Input::get('page');

            $seachFilms = App\DBAnimes::where('country', '=', $id)
                ->orderBy('updated_at', 'desc')
                ->paginate(env('PAGE_SPLIT_SMALL'), ['*'], 'page', $page);
            $seachFilms->setPath('search/country/'.$id);

            return View::make('templates.SearchAnime', array('seachFilms' => $seachFilms))->render();
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }
}
