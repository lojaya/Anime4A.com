<?php

namespace App\Http\Controllers;

include_once('simple_html_dom.php');
include_once('Drive.php');
use App;
use DateTime;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use App\DBAnimes;
use App\DBVideos;
use App\DBEpisodes;
use App\DBFansub;

use App\Library\GGDrive;

class TestController extends Controller
{

    public function fix(Request $request)
    {
        try {
            $datas = DBAnimes::all();

            foreach ($datas as $data) {
                $basename = basename($data->img);
                $newname = rawurldecode(urlencode(rawurldecode($basename)));
                $data->img = 'http://anime4a.com/img/' . $newname;
                $oldF = $_SERVER['DOCUMENT_ROOT'] . '/img/' . $basename;
                $newF = $_SERVER['DOCUMENT_ROOT'] . '/img/' . $newname;
                rename($oldF, $newF);
                $data->save();
            }
            echo 'DONE!';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function combine(Request $request, $id1, $id2) // ani2 --> ani1
    {
        try {
            $ani1 = DBAnimes::find($id1);
            $ani2 = DBAnimes::find($id2);

            $ep1 = DBEpisodes::where('anime_id', $ani1->id)->get();
            $ep2 = DBEpisodes::where('anime_id', $ani2->id)->get();

            foreach ($ep2 as $item2) {
                $name2= (float)$item2->episode;
                $vid2 = DBVideos::where('episode_id', $item2->id)->get();
                $id = '';

                foreach ($ep1 as $item1) {
                    $name1 = (float)$item1->episode;

                    if($name1===$name2) // Tap trong ani2 co trong ani1
                    {
                        $id = $item1->id; // episode_id moi cho moi video trong ep2 la id cua ep1
                        break;
                    }
                }

                if(!strlen($id)) // Tap trong ani2 KHONG co trong ani1
                {
                    $epNew = new DBEpisodes();
                    $epNew->anime_id = $ani1->id;
                    $epNew->episode = $item2->episode;
                    $epNew->save();
                    $id = $epNew->id; // episode_id moi cho moi video trong ep2 la id cua ep moi tao
                }

                if(strlen($id)) // Cap nhat video trong ep2
                {
                    foreach ($vid2 as $vid) {
                        $vid->episode_id = $id;
                        $vid->save();
                    }
                }
            }

            echo 'DONE!';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function GetLink(Request $request)
    {
        try
        {
            $adminHash = hash('sha256', 'Anime4A.com Admin Signed');
            if(Session::has('AdminSigned')&&Session::get('AdminSigned')==$adminHash)
            {
                $animes = \App\DBAnimes::all();

                foreach ($animes as $anime) {
                    $id = $anime->id;
                    $episodes = App\DBEpisodes::where('anime_id', $id)
                        ->orderBy(\DB::raw('episode + 0'))
                        ->get();
                    echo '<br/>'.$anime->name."<br/>";
                    foreach ($episodes as $episode) {
                        echo 'http://anime4a.com/r/'.$id.'/'.$episode->episode.".mp4<br/>";
                    }
                }
            }
            else
            {
                $userSigned = UsersController::CheckUserLogin();

                if($userSigned->signed) {
                    $user = App\DBUsers::where('username', $userSigned->username)
                        ->get()->first();

                    if(!is_null($user))
                    {
                        if($user->type<0)
                        {
                            Session::put('AdminSigned', $adminHash);

                            $animes = \App\DBAnimes::all();

                            foreach ($animes as $anime) {
                                $id = $anime->id;
                                $episodes = App\DBEpisodes::where('anime_id', $id)
                                    ->orderBy(\DB::raw('episode + 0'))
                                    ->get();
                                echo '<br/>'.$anime->name."<br/>";
                                foreach ($episodes as $episode) {
                                    echo 'http://anime4a.com/r/'.$id.'/'.$episode->episode.".mp4<br/>";
                                }
                            }
                        }
                    }
                }
            }
            return Redirect::route('Index');
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    /**
     * @param Request $request
     * @param $anime_id
     * @param $ep
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function GetRedirectLink(Request $request, $anime_id, $ep)
    {
        try
        {
            $ep_id = App\DBEpisodes::where([
                ['anime_id', '=', $anime_id],
                ['episode', '=', $ep],
            ])->get()->first()->id;


            $videos = App\DBVideos::where([
                ['episode_id', '=', $ep_id]
            ])->get();

            foreach ($videos as $video) {
                if(strrpos($video->url_source, 'google')){
                    $source = array();
                    if(strlen($video->stream_google_url))
                    {
                        $source = $video->stream_google_url;
                    }else{
                        $j2t = new \J2T();
                        $j2t->setLink = $video->url_source;
                        $j2t->setFormat = isset($_GET['format']) ? $_GET['format'] : false;
                        $source = $j2t->run();

                        $video->stream_google_url = $source;
                        $video->save();
                    }

                    $source = json_decode($source);
                    $url = '';

                    for($s = 0; $s<count($source); $s++)
                    {
                        $data = $source[$s];

                        if($data->label == 'hd1080'||$data->label == '1080p'){
							if(isSet($data->file))
								$url=$data->file;
							else
								$url=$data->src;
                            break;
                        }
                        else{
                            if($data->label == 'hd720'||$data->label == '720p'){
							if(isSet($data->file))
								$url=$data->file;
							else
								$url=$data->src;
                                break;
                            }
                            else{
                                if($data->label == 'medium'||$data->label == '360p'){
							if(isSet($data->file))
								$url=$data->file;
							else
								$url=$data->src;
                                    break;
                                }
                            }
                        }
                    }
                    if(strlen($url))
                        return redirect($url);
                }
            }
            return '';
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
    public function remuxVideo(Request $request)
    {
        try{
            $dir = Input::get('dir');
            $dateStart = Input::get('dateStart');

            $date = DateTime::createFromFormat('Y-m-d H:i:s', '2016-01-01 00:00:00');

            if(strlen($dateStart))
                $date = DateTime::createFromFormat('Y-m-d H:i:s', $dateStart.' 00:00:00');
            $date->modify('+1 day');

            $result = array();

            if ($handle = opendir($dir)) {

                while (false !== ($entry = readdir($handle))) {

                    if ($entry != "." && $entry != "..") {
                        if(stripos($entry, '.mp4')!==FALSE) {
                            $key = str_ireplace('ep ', '', $entry);
                            $key = str_ireplace('.mp4', '', $key);
                            $key = sprintf('%04d', $key);
                            $result[$key] = $entry;
                        }
                    }
                }
                closedir($handle);
            }

            uksort($result, "strnatcmp");

            $outputDir = $dir.'\\output\\';
            if(!file_exists($outputDir))
                mkdir($outputDir);

            foreach ($result as $key => $value)
            {
                $file = $dir.'\\'.$value;
                $outputFile = $dir.'\\output\\'.$value;
                $time = $date->format('Y-m-d H:i:s');

                exec('D:\bin\ffmpeg -i "'.$file.'" -c copy -map 0 -metadata creation_time="'.$time.'" "'.$outputFile.'"');

                rename($outputFile, $file);

                $date->modify('+1 day');
            }

            rmdir($outputDir);

            return 'DONE!';
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    private function decodeText($str)
    {
        return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $str);
    }

    private function get_http_response_code($url) {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }

    private function urlEncode($url){
        $basename = basename($url);
        return str_replace($basename, rawurlencode($basename), $url);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function ggphotos(Request $request)
    {
        try{
            if(Input::has('url')){
                $url = Input::get('url');
                $epStart = 1;
                $prefix = 2;
                if(Input::has('epStart'))
                    $epStart = (int) Input::get('epStart');
                if(Input::has('prefix'))
                    $prefix = (int) Input::get('prefix');
                // get HTML Source
                $j2t = new \J2T();
                $j2t->setLink = $url;
                $j2t->setFormat = isset($_GET['format']) ? $_GET['format'] : false;
                $source = $j2t->run();
                $source = $j2t->getSource();

                $_pattern = '/"[a-zA-Z0-9_-]{44}"/';

                $cP = preg_match_all($_pattern, $source, $matches);

                $keyIndex = strpos($url, '?key');
                $start = substr($url, 0, $keyIndex);
                $end = substr($url, $keyIndex);
                $data = $matches[0];
                $count = count($data);

                $result = array();
                $result2 = '';
                for($i = $epStart; $i<=$count+($epStart-1); $i++){
                    $ep = sprintf('%0'.$prefix.'s', $i);
                    $item = $data[$i-$epStart];
                    $item = str_replace('"', '', $item);
                    $value = $ep. ' : ' . $start.'/photo/'.$item.$end;
                    $result[] = $value;
                    $result2 .= $value."\n";
                }
                return $result2;
            }
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
    public function Test(Request $request)
    {
        try
        {
            $url = 'https://photos.google.com/share/AF1QipOoiChNROdigG_PJZAAhZEReQww5_S5hWEO7Tp0n9BiGMkRpfO15ebKcmgUZwpMhA/photo/AF1QipPevyvHNBciwxka7y3f6FfW8EAXoibWm89gXDR5?key=VFF4LV83VVhHYU0zUGY0TVJ5MENSQWtFUEVkRW5n';
            $j2t = new \J2T();
            $j2t->setLink = $url;
            $j2t->setFormat = isset($_GET['format']) ? $_GET['format'] : false;
            $data = $j2t->run();

            $data = $j2t->getSource();
            $_pattern = array(
                'valid_link' => array(
                    '/[0-9]{2}\/[0-9]{3,4}x[0-9]{3,4}\",\"url.*\]/',
                    '/\"url.*\"/'
                ),
                'quality' => array(
                    '/https.*720/',
                    '/https.*medium/',
                    '/https.*small/'
                ),
                'json' => array(
                    '/(.*?)&itag=[0-9]{2}&type=(.*?);\+codecs.*&quality=(.*)/'
                )
            );
            $cP = preg_match($_pattern['valid_link'][0], $data, $matches);
            return var_dump($matches);
            $pattern = $matches[0];
            preg_match($_pattern['valid_link'][1], $pattern, $matches);
            $mediaArr = explode(',url', $matches[0]);

            $data = array();
            foreach($mediaArr as $i =>$value) {
                $value = str_replace('\u003d', '=', $value);
                $value = str_replace('\u0026', '&', $value);
                $value = str_replace('%3A', ':', $value);
                $value = str_replace('%3B', ';', $value);
                $value = str_replace('%3D', '=', $value);
                $value = str_replace('%2F', '/', $value);
                $value = str_replace('%2C', ',', $value);
                $value = str_replace('%22', '"', $value);
                if(preg_match($_pattern['quality'][0], $value, $m))
                {
                    preg_match($_pattern['json'][0],$m[0], $s);
                    $data['content'][] = array(
                        'url' => $s[1],
                        'quality' => $s[3],
                        'type' => $s[2]
                    );
                    unset($mediaArr[$i],$s);
                }
                if(preg_match($_pattern['quality'][1], $value, $m))
                {
                    preg_match($_pattern['json'][0],$m[0], $s);
                    $data['content'][] = array(
                        'url' => $s[1],
                        'quality' => $s[3],
                        'type' => $s[2]
                    );
                    unset($mediaArr[$i],$s);
                }
                if(preg_match($_pattern['quality'][2], $value, $m))
                {
                    preg_match($_pattern['json'][0],$m[0], $s);
                    $data['content'][] = array(
                        'url' => $s[1],
                        'quality' => $s[3],
                        'type' => $s[2]
                    );
                    unset($mediaArr[$i],$s);
                }
            }
            return var_dump($data);
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
    public function Test2(Request $request)
    {
        try
        {
            $url = 'https://photos.google.com/share/AF1QipP_VE9kOHCeLa-M2ERShiEmyw51CzbRgcwoisXYdwTst_KzOETupg_FYPw_mz71-A/photo/AF1QipOpj0iNAEPx3vBIPNrTL3w6AWeXKhGcnb12C01G?key=bjRkQjU3U25qVTdiMlpkTS02cHp4bW4xZjNuRzJR';

            return VideoController::getGoogle($url);
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    private function get_curl($url)
    {
        $user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

        $options = array(
            CURLOPT_CUSTOMREQUEST => "GET",        //set request type post or get
            CURLOPT_POST => false,        //set to GET
            CURLOPT_USERAGENT => $user_agent, //set user agent
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING => "",       // handle all encodings
            CURLOPT_AUTOREFERER => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT => 120,      // timeout on response
            CURLOPT_MAXREDIRS => 10,       // stop after 10 redirects
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);

        $header['errno'] = $err;
        $header['errmsg'] = $errmsg;
        $header['content'] = $content;
        return $content;
    }

    private function run()
    {
        $url='http://animehdo.com/anime-hay/trang-1.html'; //1-27

        $html = file_get_html($url);
        $data = array();
        foreach($html->find('a') as $link) {
            $value = $link->href;
            if(strlen($value) && strpos($value, '#')===FALSE && strpos($value, '/')===FALSE && strpos($value, 'dang-nhap')===FALSE)
                $data[] = 'http://animehdo.com/' . $value;
        }
// $data : danh sach anime
        $url2 = 'http://animehdo.com/one-piece.html';
        $html = file_get_html($url2);

// Lay trang xem phim mac dinh
        $data = array();
        foreach($html->find('a') as $link) {
            $value = $link->href;
            if(strlen($value) && strpos($value, 'xem-phim')!==FALSE)
                $data[] = 'http://animehdo.com/' . $value;
        }

        $url3 = 'http://animehdo.com/xem-phim/hd10024/one-piece.html';
        $html = file_get_html($url3);


// Lay danh sach tap
// Bo [0] va [N]
        $data = array();
        foreach($html->find('a') as $link) {
            $value = $link->href ;
            if(strlen($value) && strpos($value, 'xem-phim')!==FALSE)
            {
                $s = 'http://animehdo.com/' . $value;
                $ep = $link->innertext;
                $data[] = $s;
            }
        }

// Lay du lieu google drive
        $url3 = 'http://animehdo.com/xem-phim/hd10024/one-piece.html';
        $html = file_get_html($url3);

        $_pattern = '!https://drive.google.com/file/d/([a-zA-Z0-9_-]{28})/!';

        $cP = preg_match_all($_pattern, $html, $matches);
        echo $matches[0][0];
    }
}