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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

use App\DBUsers;

class ACPUserPagesController extends Controller
{
    public function _List(Request $request)
    {
        try {
            $items = DBUsers::all();
            $url = Request::root().'/admincp/user';
            return View::make('admincp.ACPUserListView', [
                'items' => $items,
                'url' => $url
            ])->render();

        } catch (\Exception $e) {
            return '<div class="report">Lỗi!' . $e->getMessage() . '</div>';
        } finally {
        }
    }

    public function _Edit(Request $request)
    {
        try
        {
            $url = Request::root().'/admincp/user';
            if(Input::has('id')){
                $id = Input::get('id');
                $data = DBUsers::find($id);
                Session::put('edit_id', $id);

                return View::make('admincp.ACPUserEditor',[
                    'data' => $data,
                    'url' => $url
                ])->render();
            }
            else{
                Session::forget('edit_id');
                return View::make('admincp.ACPUserEditor',[
                    'url' => $url
                ])->render();
            }
        } catch (\Exception $e) {
            return '<div class="report">Lỗi!' . $e->getMessage() . '</div>';
        } finally {
        }
    }

    public function _Save(Request $request)
    {
        try {
            if (Session::has('edit_id')) {
                $id = Session::get('edit_id');
                $data = DBUsers::find($id);
            } else {
                $data = new DBUsers();
            }
            $username = Input::get('username');
            if(strlen($username))
                $data->username = $username;
            $data->type = Input::get('type');
            // update database
            $data->save();

            // remove edit id session
            Session::forget('edit_id');

            return '<div class="report">Lưu thành công!</div>';
        } catch (\Exception $e) {
            $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                return '<div class="report">Lỗi trùng dữ liệu!</div>';
            }
            return '<div class="report">Lỗi!</div>';
        } finally {
        }
    }

    public function _Delete(Request $request)
    {
        try {
            // Delete code
            if (Input::has('id')) {
                $ids = Input::get('id');
                foreach ($ids as $i) {
                    DBUsers::destroy($i);
                }

                $items = DBUsers::all();
                $url = Request::root().'/admincp/user';
                return View::make('admincp.ACPUserListView', [
                    'items' => $items,
                    'url' => $url
                ])->render();
            }
        } catch (\Exception $e) {
            return '<div class="report">Lỗi!' . $e->getMessage() . '</div>';
        } finally {
        }
    }
}