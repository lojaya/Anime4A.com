@extends('templates.master')

<?php
$MyFunc = new App\Library\MyFunction;
?>

@section('stylesheet')
    <link rel="stylesheet" href="{{Request::root()}}/style/ani/style.css" type="text/css" />
    <link rel="stylesheet" href="{{Request::root()}}/style/ani/menu.css" type="text/css" />
    <link rel="stylesheet" href="{{Request::root()}}/style/ani/searchBox.css" type="text/css" />
    <script type="text/javascript" src="{{Request::root()}}/js/jquery-3.1.0.min.js"></script>
    <script type="text/javascript" src="{{Request::root()}}/js/jquery-color.js"></script>
    <script type="text/javascript" src="{{Request::root()}}/js/jssor.slider-21.1.6.mini.js" charset="utf-8"></script>
    <script type="text/javascript" src="{{Request::root()}}/js/anime4a.js" charset="utf-8"></script>
    <script type="text/javascript" src="{{Request::root()}}/js/searchBox.js" charset="utf-8"></script>
@stop

@section('headerBar')
    <div id="header_bar">
        <div class="bg_overlay"></div>
    </div>
@stop

@section('header-menu-category')
    @foreach ($category_list as $i)
        <li><a href='#'>{{ $i->name }}</a></li>
    @endforeach
@stop

@section('header-menu-country')
    @foreach ($country_list as $i)
        <li><a href='#'>{{ $i->name }}</a></li>
    @endforeach
@stop

@section('content')
    <div class="breadcrumb"></div>
    <!-- Video View Region -->
    <div class="video_player">
        <!--<div class="video_name"><b><a>{{ $MyFunc->getAnimeName($anime_id) }}</a></b></div>-->

        <div id="player"></div>
        <script src="{{Request::root()}}/js/jwplayer/jwplayer.js"></script>
        <script>jwplayer.key="1La4Kp4v+HhGBiJ+p5dWO6sb/AyCdbqtYQKR7w==";</script>
        <script type='text/javascript'>

            // Setup video player
            jwplayer("player").setup({
                sources: [{
                    file: 'http://thenewcode.com/assets/videos/polina.mp4',
                    label: "HD"
                }],
                "image": "../images/bg1.jpg",
                "label": "Quality",
                "type": "video/mp4",
                "autostart": false,
                skin: {
                    name: "seven"
                },
                "height": 480,
                "width": 680
            });

            $(document).ready(function () {
                $('#sidebar').css('margin-top','-480px');
            });

            window.onload=function () {
                loadPlayer();
            };

            // Script for load video player
            function loadPlayer() {
                var data = getVideoData();

                // Set download link
                if(data){
                    $('.video_control .item .download').attr("href", data);
                }
                // Load video
                jwplayer("player").load([{
                    file: data,
                    image: "../images/bg1.jpg"
                }]);
            }

            // Get video file for play
            function getVideoData() {
                var video_url_temp = null;
                var requestUrl = $('#MainUrl').attr('href');
                $.ajax({
                    url: requestUrl + '/get-video-data',
                    type: 'get',
                    data: {},
                    async: false,
                    success: function(data, status) {
                        video_url_temp = data;
                    },
                    error: function(xhr, desc, err) {
                        ;
                    }
                });
                return video_url_temp;
            }

        </script>
    </div>
    <div class="video_detail" style="display: none">
        <div class="video_img">
            <img src="" style="width: 150px; height: 200px">
        </div>
        <div class="video_description">
            <div class="">
                <span>Anime</span>
            </div>
            <div>
                <span>Type: x</span>
            </div>
            <div>
                <span>Số tập: x/x</span>
            </div>
            <div>
                <span>Năm sản xuất: YYYY</span>
            </div>
            <div>
                <span>Thể loại: x</span>
            </div>
            <div>
                <span>::.....</span>
            </div>
        </div>
    </div>
    <div class="video_selection">
        <script>
            $(document).ready(function (){
               $('.video_selection>.group>.item').bind('click', function (e) {
                   var url = $(this).find('a').attr('href');
                   if(url)
                       location.href = url;
               });
            });
        </script>
        <div class="group">
            <div class="title">
                Tập Phim:
            </div>
            @if(isSet($episode_list))
                @foreach ($episode_list as $i)
                    @if(isSet($episode_id))
                        @if($i->id==$episode_id)
                            <div class="item"><a class="active" >{{ $i->episode }}</a></div>
                        @else
                            <div class="item"><a class="n" href="{{Request::root()}}/xem-phim/{{ $MyFunc->nameFormat($MyFunc->getAnimeName($anime_id)) }}/{{ $anime_id }}/{{ $i->id }}.html">{{ $i->episode }}</a></div>
                        @endif
                    @else
                        <div class="item"><a class="n" href="{{Request::root()}}/xem-phim/{{ $MyFunc->nameFormat($MyFunc->getAnimeName($anime_id)) }}/{{ $anime_id }}/{{ $i->id }}.html">{{ $i->episode }}</a></div>
                    @endif
                @endforeach
            @endif
        </div>
        <div class="group">
            <div class="title">
                Fansub:
            </div>
            @if(isSet($fansub_list))
                @foreach ($fansub_list as $i)
                    @if(isSet($fansub_id))
                        @if($i->fansub_id==$fansub_id)
                            <div class="item"><b><a class="active" >{{ $MyFunc->getFansubName($i->fansub_id) }}</a></b></div>
                        @else
                            <div class="item"><b><a href="{{Request::root()}}/xem-phim/{{ $MyFunc->nameFormat($MyFunc->getAnimeName($anime_id)) }}/{{ $anime_id }}/{{ $episode_id }}/{{ $i->fansub_id }}.html">{{ $MyFunc->getFansubName($i->fansub_id) }}</a></b></div>
                        @endif
                    @else
                        <div class="item"><b><a href="{{Request::root()}}/xem-phim/{{ $MyFunc->nameFormat($MyFunc->getAnimeName($anime_id)) }}/{{ $anime_id }}/{{ $episode_id }}/{{ $i->fansub_id }}.html">{{ $MyFunc->getFansubName($i->fansub_id) }}</a></b></div>
                    @endif
                @endforeach
            @endif
        </div>
        <div class="group">
            <div class="title">
                Server:
            </div>
            @if(isSet($server_list)&&isSet($server_id))
                @foreach ($server_list as $i)
                    @if(isSet($server_id))
                        @if($i->server_id==$server_id)
                            <div class="item"><b><a class="active" >{{ $MyFunc->getServerName($i->server_id) }}</a></b></div>
                        @else
                            <div class="item"><b><a href="{{Request::root()}}/xem-phim/{{ $MyFunc->nameFormat($MyFunc->getAnimeName($anime_id)) }}/{{ $anime_id }}/{{ $episode_id }}/{{ $fansub_id }}/{{ $i->server_id }}.html">{{ $MyFunc->getServerName($i->server_id) }}</a></b></div>
                        @endif
                    @else
                        <div class="item"><b><a href="{{Request::root()}}/xem-phim/{{ $MyFunc->nameFormat($MyFunc->getAnimeName($anime_id)) }}/{{ $anime_id }}/{{ $episode_id }}/{{ $fansub_id }}/{{ $i->server_id }}.html">{{ $MyFunc->getServerName($i->server_id) }}</a></b></div>
                    @endif
                @endforeach
            @endif
        </div>
        <div class="video_page_advertise">

        </div>
    </div>
    <!-- END Video View Region -->
@stop

@section('controlBar')
    <div class="video_control_region">
        <div class="video_control">
            <div class="item"><a class="video_info abutton">Thông Tin</a></div>
            <div class="item"><a class="videozoom abutton">Phóng To</a></div>
            <div class="item"><a class="lightoff abutton">Tắt Đèn</a></div>
            <div class="item"><a class="download abutton">Download</a></div>
            <div class="item"><a class="abutton">Tập Sau</a></div>
        </div>
    </div>
@stop

@section('sidebar')
    <!-- SideBar Region -->
    <div id="sidebar">
        <div class="most_view">
            <div class="titleBar">
                <span>Anime Xem Nhiều</span>
                <div style="float: right">
                    <a class="buttonD @if($mostViewSelected == 'D') selected @endif ">D</a>
                    <span>-</span>
                    <a class="buttonW @if($mostViewSelected == 'W') selected @endif ">W</a>
                    <span>-</span>
                    <a class="buttonM @if($mostViewSelected == 'M') selected @endif ">M</a>
                    <span>-</span>
                    <a class="buttonS @if($mostViewSelected == 'S') selected @endif ">S</a>
                    <span>-</span>
                    <a class="buttonY @if($mostViewSelected == 'Y') selected @endif ">Y</a>
                </div>
            </div>
            <ul class="sidebar_items">
            </ul>
        </div>
    </div>
    <!-- END SideBar Region -->
@stop