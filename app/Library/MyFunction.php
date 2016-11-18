<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/13/2016
 * Time: 5:32 PM
 */

namespace App\Library;

use Illuminate\Support\Facades\DB;

class MyFunction {
    // Format video name
    function nameFormat($name)
    {
        $string = strtolower($name);
        $pattern = '([^a-zA-Z0-9]) ';
        $replacement = '${1}-';
        $string =  preg_replace($pattern, $replacement, $string);
        $pattern = '[--+]';
        $replacement = '-';
        return preg_replace($pattern, $replacement, $string);
    }

    // Get Direct Link
    public function getDirectLink($url)
    {
        $result = get_headers($url)[7];
        preg_match("/Location: (.*)/", $result, $matches);
        return $matches[1];
    }

    function uploadFile($f,$p){
        $target_dir = $p;
        $target_file = $target_dir . basename($f["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($f["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($f["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($f["tmp_name"], $target_file)) {
                echo "The file ". basename( $f["name"]). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    public function checkCategory($value, $category)
    {
        $f = true;
        foreach ($category as $i)
        {
            if($value==$i)
            {
                $f = false;
            }
        }
        return $f;
    }

    function getAnimeName($id) {
        if(!is_null($id)&&strlen($id)>0) {
            $result = DB::table('anime4a_animes')->where('id',$id)->get();
            if (!is_null($result)) {
                if (count($result) > 0) {
                    return $result[0]->name;
                }
            }
        }
    }

    function getFansubName($id) {
        if(!is_null($id)&&strlen($id)>0) {
            $result = DB::table('anime4a_fansub')->where('id',$id)->get();
            if (!is_null($result)) {
                if (count($result) > 0) {
                    return $result[0]->name;
                }
            }
        }
    }

    function getServerName($id) {
        if(!is_null($id)&&strlen($id)>0) {
            $result = DB::table('anime4a_server')->where('id',$id)->get();
            if (!is_null($result)) {
                if (count($result) > 0) {
                    return $result[0]->name;
                }
            }
        }
    }

    function getCategoryList() {
        return DB::table('anime4a_category')->get();
    }

    function getCategoryName($id) {
        if(!is_null($id)&&strlen($id)>0) {
            $result = DB::table('anime4a_category')->where('id',$id)->get();
            if (!is_null($result)) {
                if (count($result) > 0) {
                    return $result[0]->name;
                }
            }
        }
    }

    function getTypeList() {
        return DB::table('anime4a_type')->get();
    }

    function getTypeName($id) {
        if(!is_null($id)&&strlen($id)>0) {
            $result = DB::table('anime4a_type')->where('id',$id)->get();
            if (!is_null($result)) {
                if (count($result) > 0) {
                    return $result[0]->name;
                }
            }
        }
    }

    function getStatusList() {
        return DB::table('anime4a_status')->get();
    }

    function getStatusName($id) {
        if(!is_null($id)&&strlen($id)>0) {
            $result = DB::table('anime4a_status')->where('id',$id)->get();
            if (!is_null($result)) {
                if (count($result) > 0) {
                    return $result[0]->name;
                }
            }
        }
    }
}
