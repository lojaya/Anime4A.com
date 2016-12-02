@extends('templates.master')

<?php

use App\Library\PhpAdfLy;
use App\Library\MyFunction;
use App\DBAnimes;
use App\DBType;
use App\DBCategory;

$MyFunc = new App\Library\MyFunction;
?>

@section('stylesheet')
    <link rel="stylesheet" href="{{Request::root()}}/style/ani/style.css" type="text/css" />
    <link rel="stylesheet" href="{{Request::root()}}/style/ani/menu.css" type="text/css" />
    <link rel="stylesheet" href="{{Request::root()}}/style/ani/searchBox.css" type="text/css" />
    <script type="text/javascript" src="{{Request::root()}}/js/jquery-3.1.0.min.js"></script>
    <script type="text/javascript" src="{{Request::root()}}/js/jquery-color.js"></script>
    <script type="text/javascript" src="{{Request::root()}}/js/jssor.slider-21.1.6.mini.js" charset="utf-8"></script>
    <script type="text/javascript" src="{{Request::root()}}/js/bootstrap.min.js"></script>
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
        <div id="player"></div>
        <script src="/js/jwplayer-7.8.1/jwplayer.js"></script>
        <script>jwplayer.key="1La4Kp4v+HhGBiJ+p5dWO6sb/AyCdbqtYQKR7w==";</script>
        <script type='text/javascript'>
            jwplayer("player").setup({
                playlist: [{
                    sources: <?php if(isSet($data)) echo $data; ?>
                }],
                modes: [{
                    type: "html5"
                },{
                    type: "flash",
                    src: "jwplayer-7.8.1/jwplayer.flash.swf"
                }],
                primary: "html5",
                provider: "jwplayer-7.8.1/PauMediaProvider.swf",
                width: 680,
                height: 420,
                aspectratio: "16:9"
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
    @if(isSet($anime))
    <div class="video_detail" style="display: none">
        <div class="video_img">
            <img src="{{ $anime->img }}" style="width: 150px; height: 200px">
        </div>
        <div class="video_info">
            <div class="video_name">
                <span>{{ $anime->name }}</span>
            </div>
            <div class="video_category">
                <?php $cat = explode(',', $anime->category);?>
                <span>Type: @foreach($cat as $i)<a>{{ DBCategory::GetName($i).',' }}</a>@endforeach</span>
            </div>
            <div class="video_ep">
                <span>Số tập: {{ $anime->episode_new }}/{{ $anime->episode_total }}</span>
            </div>
            <div class="video_release">
                <span>Năm sản xuất: {{ date_format($anime->release_date,"Y") }}</span>
            </div>
            <div class="video_type">
                <span>Thể loại: <a>{{ DBType::GetName($anime->type) }}</a></span>
            </div>
            <div class="video_description">
                <span>{{ $anime->description }}</span>
            </div>
        </div>
    </div>
    @endif
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
        <div class="fb-comments" data-href="{{Request::root()}}/xem-phim/{{ $MyFunc->nameFormat($MyFunc->getAnimeName($anime_id)) }}/{{ $anime_id }}.html" data-width="680" data-colorscheme="dark" data-numposts="5"></div>
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

            <!-- USER BOX -->
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
            <script>
                $('.bookmarkBtn').bind('click', function (e) {
                    $('#userBox').fadeIn();
                });
                $('.userCpBtn').bind('click', function (e) {
                    $('#userBox').fadeIn();
                });
            </script>
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
        <script>
            $('.bookmarkBtn').bind('click', function (e) {
                $('#userBox').fadeIn();
            });
            $('.userCpBtn').bind('click', function (e) {
                $('#userBox').fadeIn();
            });
        </script>
    @endif
@stop

@section('sidebar')
    <!-- SideBar Region -->
    <div id="sidebar" style="margin-top: -420px;">
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