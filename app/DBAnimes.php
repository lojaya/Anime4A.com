<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 11/12/2016
 * Time: 8:08 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;

class DBAnimes extends Model
{
    //
    protected $connection = 'mysql';
    protected $table = 'anime4a_animes';
    protected $dates = [
        'created_at',
        'updated_at',
        'release_date'
    ];

    /**
     * @param $id
     * @return string
     */
    public static function GetName($id)
    {
        try
        {
            $obj = DBAnimes::find($id);
            if(!is_null($obj))
                return $obj->name;
            return '';
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }
}