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

use App\DBFansub;

class ACPFansubPagesController extends Controller
{
    public function _List(Request $request)
    {
        try {
            $items = DBFansub::all();
            $url = Request::root().'/admincp/fansub';
            return View::make('admincp.ACPListView', [
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
            $url = Request::root().'/admincp/fansub';
            if(Input::has('id')){
                $id = Input::get('id');
                $data = DBFansub::find($id);
                Session::put('edit_id', $id);

                return View::make('admincp.ACPEditor',[
                    'data' => $data,
                    'url' => $url
                ])->render();
            }
            else{
                Session::forget('edit_id');
                return View::make('admincp.ACPEditor',[
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
                $data = DBFansub::find($id);
            } else {
                $data = new DBFansub();
            }
            $name = Input::get('name');
            if(strlen($name))
                $data->name = $name;
            $data->description = Input::get('description');
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
                    DBFansub::destroy($i);
                }

                $items = DBFansub::all();
                $url = Request::root().'/admincp/fansub';
                return View::make('admincp.ACPListView', [
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