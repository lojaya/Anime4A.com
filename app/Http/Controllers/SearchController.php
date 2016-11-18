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
}
