<?php

namespace App\Http\Controllers;

use App;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

use App\DBUsers;

class UsersController extends Controller
{
    public function Register(Request $request)
    {
        try
        {
            $user = new DBUsers();

            $result = (object) array('completed' => true, 'error' => '');

            $username = Input::get('username');
            $password = Input::get('password');
            $password2 = Input::get('password2');

            // Validate inputs data
            if (strlen($username)>100) {
                $result->completed = false;
                $result->error .= "<p>Email quá dài(6-100)!</p>";
            }
            if (strlen($username)<6) {
                $result->completed = false;
                $result->error .= "<p>Email quá ngắn(6-100)!</p>";
            }
            if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
                $result->completed = false;
                $result->error .= "<p>Không phải định dạng email!</p>";
            }
            if (strlen($password)>30) {
                $result->completed = false;
                $result->error .= "<p>Password quá dài(6-30)!</p>";
            }
            if (strlen($password)<6) {
                $result->completed = false;
                $result->error .= "<p>Password quá ngắn(6-30)!</p>";
            }

            if($password==$password2)
            {
                if($result->completed)
                {
                    // Register success codes
                    $hashPassword = hash('sha256', $password);
                    $user->username = $username;
                    $user->password = $hashPassword;

                    // save to database default: user->type = 0
                    $user->save();

                    // add session
                    $loginHash = hash('sha256', 'Anime4A Login Successful');
                    Session::put('loginHash', $loginHash);
                    Session::put('username', $username);

                    return json_encode($result);
                }
            }else
            {
                $result->completed = false;
                $result->error .= '<p>Mật khẩu xác nhận không chính xác!</p>';
            }

            return json_encode($result);

        }
        catch(\Exception $e)
        {
            $result = (object) array('completed' => false, 'error' => '');
            $result->error = $e->getMessage();
            return json_encode($result);
        }
    }

    public function LogIn(Request $request)
    {
        try
        {
            $result = (object) array('completed' => false, 'error' => '');
            $username = Input::get('username');
            $password = Input::get('password');

            $hashPassword = hash('sha256', $password);

            $user = DBUsers::where('username', $username)
                ->get()->first();

            if(!is_null($user))
            {
                if($user->password==$hashPassword)
                {
                    // Login success codes
                    $result->completed = true;

                    // add session
                    $loginHash = hash('sha256', 'Anime4A Login Successful');
                    Session::put('loginHash', $loginHash);
                    Session::put('username', $username);
                }
                else
                    $result->error .= '<p>1. Email hoặc mật khẩu không chính xác!</p>';
            }else
                $result->error .= '<p>2. Email hoặc mật khẩu không chính xác!</p>';

            return json_encode($result);
        }
        catch(\Exception $e)
        {
            $result = (object) array('completed' => false, 'error' => '');
            $result->error = $e->getMessage();
            return json_encode($result);
        }
    }

    public function LogOut(Request $request)
    {
        try
        {
            $result = (object) array('completed' => true, 'error' => '');
            Session::forget('loginHash');
            Session::forget('username');
            Session::forget('AdminSigned');
            return json_encode($result);

        }
        catch(\Exception $e)
        {
            $result = (object) array('completed' => false, 'error' => '');
            $result->error = $e->getMessage();
            return json_encode($result);
        }
    }
}
