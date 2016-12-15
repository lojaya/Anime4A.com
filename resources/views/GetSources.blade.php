<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/14/2016
 * Time: 10:59 PM
 */
?>

<html>
<head>
    <script type="text/javascript" src="/js/jquery-3.1.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        body{background: #000; color: white}
        .AnimeName{color: #00FFFF}
        .Episode{color: #cc003a; margin-left: 20px;}
        .UrlSource{color: #1b9114; margin-left: 60px;}

    </style>
</head>
<body>
@if(isSet($userSigned))
    @if($userSigned->loginHash==hash('sha256', 'Anime4A Login Successful')&&$userSigned->admin)
        <?php
        $animes = \App\DBAnimes::all();
        $dataArr = array();

        foreach ($animes as $i)
        {
            echo '<span class="AnimeName">&lt;Anime><br/>'.'<span class="Episode">&lt;Name>'.$i->name."&lt;/Name>"."</span></span><br/>";

            $episodes = \App\DBEpisodes::where('anime_id', $i->id)
                    ->orderBy(\DB::raw('episode + 0'))
                    ->get();
            foreach ($episodes as $ep) {
                echo '<span class="Episode">&lt;Episode><br/>'.'<span class="UrlSource">&lt;Name>'.$ep->episode.'&lt;/Name>'."</span></span><br/>";

                $videos = \App\DBVideos::where('episode_id', $ep->id)
                        ->get();
                foreach ($videos as $video) {
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
                    $fansub = \App\DBFansub::GetName($video->fansub_id);
                    for($s = 0; $s<count($source); $s++)
                    {
                        $data = $source[$s];

                        if($data->label == 'hd1080'){
                            echo '<span class="UrlSource">&lt;Url Fansub="'.$fansub.'">'.$data->file."&lt;/Url>"."</span><br/>";
                            $dataArr[]=$data->file;
                            break;
                        }
                        else{
                            if($data->label == 'hd720'){
                                echo '<span class="UrlSource">&lt;Url Fansub="'.$fansub.'">'.$data->file."&lt;/Url>"."</span><br/>";
                                $dataArr[]=$data->file;
                                break;
                            }
                            else{
                                if($data->label == 'medium'){
                                    echo '<span class="UrlSource">&lt;Url Fansub="'.$fansub.'">'.$data->file."&lt;/Url>"."</span><br/>";
                                    $dataArr[]=$data->file;
                                    break;
                                }
                            }
                        }
                    }
                }

                echo '<span class="Episode">'.'&lt;/Episode>'."</span><br/>";
            }
            echo '<span class="AnimeName">'.'&lt;/Anime>'."</span><br/>";
        }
        ?>
        <br/><br/>
        {{ '*********************************************************************************************************' }}
        <br/><br/>
        <?php
        foreach ($dataArr as $i)
        {
            echo $i."<br/>";
        }
        ?>
        <br/><br/>
        {{ '*********************************************************************************************************' }}
        <br/><br/>
    @endif
@endif</body>
</html>