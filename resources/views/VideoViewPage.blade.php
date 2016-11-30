@extends('templates.master')

<?php

use App\Library\PhpAdfLy;
use App\Library\MyFunction;

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
        <li><a href='{{ Request::root() }}/the-loai/{{ $i->id }}.anime4a'>{{ $i->name }}</a></li>
    @endforeach
@stop

@section('header-menu-country')
    @foreach ($country_list as $i)
        <li><a href='{{ Request::root() }}/quoc-gia/{{ $i->id }}.anime4a'>{{ $i->name }}</a></li>
    @endforeach
@stop

@section('MainUrl')
    <a href="{{ Request::root() }}" style="display: none" id="MainUrl"></a>
@stop

@section('content')
    <div class="breadcrumb"></div>
    <!-- Video View Region -->
    <div class="video_player">
        <iframe name='player' id="player" src='{{ Request::root() }}/get-video-@if(isSet($video)&&!is_null($video)){{ $video->id }}@else{{ '0' }}@endif' width='680' height='480' frameborder='0' allowfullscreen></iframe>
        <script type='text/javascript'>
            $(document).ready(function () {
                $('#sidebar').css('margin-top','-480px');
            });
            // Get video file for play
            function getVideoData(_id) {
                var video_url_temp = null;
                var requestUrl = $('#MainUrl').attr('href');

                $.ajax({
                    url: requestUrl + '/get-video-' + _id,
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
            <img src="{{ $anime->img }}" style="width: 150px; height: 200px">
        </div>
        <div class="video_description">
            <div class="">
                <span>{{ $anime->name }}</span>
            </div>
            <div>
                <span>Type: {{ $anime->status }}</span>
            </div>
            <div>
                <span>Số tập: {{ $anime->episode_new }}/{{ $anime->episode_total }}</span>
            </div>
            <div>
                <span>Năm sản xuất: {{ $anime->release_date }}</span>
            </div>
            <div>
                <span>Thể loại: x</span>
            </div>
            <div>
                <span>{{ $anime->description }}</span>
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
                            <div class="item"><a class="epN active" >{{ $i->episode }}</a></div>
                        @else
                            <div class="item"><a class="epN" href="{{Request::root()}}/xem-phim/{{ $MyFunc->nameFormat($MyFunc->getAnimeName($anime_id)) }}/{{ $anime_id }}/{{ $i->id }}.html">{{ $i->episode }}</a></div>
                        @endif
                    @else
                        <div class="item"><a class="epN" href="{{Request::root()}}/xem-phim/{{ $MyFunc->nameFormat($MyFunc->getAnimeName($anime_id)) }}/{{ $anime_id }}/{{ $i->id }}.html">{{ $i->episode }}</a></div>
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

        <!-- START FACEBOOK COMMENT BOX -->
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=289914814743628";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
        <div class="fb-comments" data-href="{{Request::root()}}/xem-phim/{{ $MyFunc->nameFormat($MyFunc->getAnimeName($anime_id)) }}/{{ $anime_id }}.html" data-width="680" data-numposts="5"></div>
        <!-- END FACEBOOK COMMENT BOX -->

        <div class="video_page_advertise">

        </div>
    </div>
    <!-- END Video View Region -->
@stop

@section('controlBar')
    @if(isSet($userSigned))
        @if($userSigned->loginHash==hash('sha256', 'Anime4A Login Successful'))
            <div class="video_control_region">
                <div class="video_control">
                    <div class="item"><a class="video_info abutton" title="Thông Tin">Thông Tin</a></div>
                    <div class="item"><a class="videozoom abutton" title="Phóng To">Phóng To</a></div>
                    <div class="item"><a class="lightoff abutton" title="Tắt Đèn">Tắt Đèn</a></div>
                    <div class="item"><a class="download abutton" title="Download" href="@if(isSet($video)&&!is_null($video)){{ PhpAdfLy::ShortenUrl($video->url_download) }}@endif" target="_blank">Download</a></div>
                    <div class="item"><a class="nextEpBtn abutton" title="Tập Sau">Tập Sau</a></div>
                    <div class="item"><a class="bookmarkBtn abutton" title="Đánh Dấu">Đánh Dấu</a></div>
                    <div class="item"><a class="userCpBtn abutton" title="Danh Sách">Danh sách Anime đang theo dõi</a></div>
                </div>
            </div>

            // USER BOX
            @include('templates.UserBox')

        @else
            <div class="video_control_region">
                <div class="video_control">
                    <div class="item"><a class="video_info abutton" title="Thông Tin">Thông Tin</a></div>
                    <div class="item"><a class="videozoom abutton" title="Phóng To">Phóng To</a></div>
                    <div class="item"><a class="lightoff abutton" title="Tắt Đèn">Tắt Đèn</a></div>
                    <div class="item"><a class="download abutton" title="Download" href="@if(isSet($video)&&!is_null($video)){{ PhpAdfLy::ShortenUrl($video->url_download) }}@endif" target="_blank">Download</a></div>
                    <div class="item"><a class="nextEpBtn abutton" title="Tập Sau">Tập Sau</a></div>
                    <div class="item"><a class="bookmarkBtn abutton" title="Đánh Dấu">Đánh Dấu</a></div>
                    <div class="item"><a class="userCpBtn abutton" title="Danh Sách">Danh sách Anime đang theo dõi</a></div>
                </div>
            </div>
        @endif
    @else
        <div class="video_control_region">
            <div class="video_control">
                <div class="item"><a class="video_info abutton" title="Thông Tin">Thông Tin</a></div>
                <div class="item"><a class="videozoom abutton" title="Phóng To">Phóng To</a></div>
                <div class="item"><a class="lightoff abutton" title="Tắt Đèn">Tắt Đèn</a></div>
                <div class="item"><a class="download abutton" title="Download" href="@if(isSet($video)&&!is_null($video)){{ PhpAdfLy::ShortenUrl($video->url_download) }}@endif" target="_blank">Download</a></div>
                <div class="item"><a class="nextEpBtn abutton" title="Tập Sau">Tập Sau</a></div>
                <div class="item"><a class="bookmarkBtn abutton" title="Đánh Dấu">Đánh Dấu</a></div>
                <div class="item"><a class="userCpBtn abutton" title="Danh Sách">Danh sách Anime đang theo dõi</a></div>
            </div>
        </div>
    @endif
@stop

@section('sidebar')
    <!-- SideBar Region -->
    <div id="sidebar">
        <div class="most_view">
            <div class="titleBar">
                <span>Anime Xem Nhiều</span>
                <div class="findButtons">
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