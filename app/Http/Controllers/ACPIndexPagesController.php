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
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\PagesController;

class ACPIndexPagesController extends Controller
{
    public function index(Request $request)
    {
        $adminHash = hash('sha256', 'Anime4A.com Admin Signed');
        $path = '/admincp/anime';
        if(Input::has('path'))
            $path = Input::get('path');

        if(Session::has('AdminSigned')&&Session::get('AdminSigned')==$adminHash)
        {
            return View::make('admincp.index', array('path' => $path));
        }
        else
        {
            $pageController = new PagesController();
            $userSigned = $pageController->CheckUserLogin();

            if($userSigned->signed) {
                $user = App\DBUsers::where('username', $userSigned->username)
                    ->get()->first();

                if(!is_null($user))
                {
                    if($user->type<0)
                    {
                        Session::put('AdminSigned', $adminHash);

                        return View::make('admincp.index', array('path' => $path));
                    }
                }
            }
        }
        return Redirect::route('Index');
    }
}