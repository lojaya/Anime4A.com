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
use Illuminate\Support\Facades\Input;

use App\DBLog;

class ACPLogPagesController extends Controller
{
    public function _List(Request $request)
    {
        try {
            $items = DBLog::all();
            $url = Request::root().'/admincp/log';
            return View::make('admincp.ACPLogListView', [
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
            $url = Request::root().'/admincp/log';
            if(Input::has('id')){
                $id = Input::get('id');
                $data = DBLog::find($id);

                return View::make('admincp.ACPLogEditor',[
                    'data' => $data,
                    'url' => $url
                ])->render();
            }
        } catch (\Exception $e) {
            return '<div class="report">Lỗi!' . $e->getMessage() . '</div>';
        } finally {
        }
    }
}