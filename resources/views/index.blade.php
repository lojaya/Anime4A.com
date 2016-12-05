@extends('templates.master')

@section('Title')
    {{ 'Anime Subbed Online' }}
@stop

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
        <!-- SILDER -->
        <script type="text/javascript">
            $(document).ready(function ($) {
                // Carousel Slider
                var jssor_1_options = {
                    $AutoPlay: true,
                    $AutoPlaySteps: 4,
                    $SlideDuration: 160,
                    $SlideWidth: 194,
                    $SlideSpacing: 3,
                    $Cols: 5,
                    $ArrowNavigatorOptions: {
                        $Class: $JssorArrowNavigator$,
                        $Steps: 4
                    },
                    $BulletNavigatorOptions: {
                        $Class: $JssorBulletNavigator$,
                        $SpacingX: 1,
                        $SpacingY: 1
                    }
                };

                var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);

                /*responsive code begin*/
                /*you can remove responsive code if you don't want the slider scales while window resizing*/
                function ScaleSlider() {
                    var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
                    if (refSize) {
                        refSize = Math.min(refSize, 980);
                        jssor_1_slider.$ScaleWidth(refSize);
                    }
                    else {
                        window.setTimeout(ScaleSlider, 30);
                    }
                }
                ScaleSlider();
                $(window).bind("load", ScaleSlider);
                $(window).bind("resize", ScaleSlider);
                $(window).bind("orientationchange", ScaleSlider);
                /*responsive code end*/
            });
            $(document).ready(function(){
                $('[data-toggle^="popover-header-toggle"]').popover({
                    trigger: "hover",
                    html: true,
                    placement: 'bottom',
                    content: function() {
                        return $('#'+$(this).attr('data-toggle')).html();
                    }
                });
            });
        </script>
        <div id="jssor_1" style="position: relative; margin: 0 auto; top: 0px; left: 0px; width: 980px; height: 254px; overflow: hidden; visibility: hidden;">
            <!-- Loading Screen -->
            <div data-u="loading" style="position: absolute; top: 0px; left: 0px;">
                <div style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
                <div style="position:absolute;display:block;background:url('{{Request::root()}}/images/loading.gif') no-repeat center center;top:0px;left:0px;width:100%;height:100%;"></div>
            </div>
            <div data-u="slides" style="cursor: default; position: relative; top: 0px; left: 0px; width: 980px; height: 254px; overflow: hidden;">

                @include('templates.HeaderBarItem')

            </div>
            <!-- Bullet Navigator
            <div data-u="navigator" class="jssorb03" style="bottom:10px;right:10px;">
                <div data-u="prototype" style="width:21px;height:21px;">
                    <div data-u="numbertemplate"></div>
                </div>
            </div>
            -->
            <!-- Arrow Navigator -->
            <span data-u="arrowleft" class="jssora03l" style="top:0px;left:8px;width:55px;height:55px;" data-autocenter="2"></span>
            <span data-u="arrowright" class="jssora03r" style="top:0px;right:8px;width:55px;height:55px;" data-autocenter="2"></span>
        </div>
        <!-- SILDER END -->
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
    <!-- Home Region -->
    @if(isSet($seaching))
        @if($seaching)
            <div id="search_movies">
                <div class="titleBar">
                    <span>
                        @if(isSet($breadcrumb))
                            {{ $breadcrumb->key }} ><a>{{ $breadcrumb->value }}</a>
                        @endif
                    </span>
                </div>
                <div class="list_movies search_movies">
                    <div class="items">
                        @include('templates.SearchAnime')
                    </div>
                </div>
            </div>
        @else
            <div id="hot_movies">
                <div class="titleBar">
                    <span>
                        Anime Hot
                    </span>
                </div>
                <div class="list_movies search_movies">
                    <div class="items">
                        @include('templates.AnimeHot')
                    </div>
                </div>
            </div>
        @endif
    @endif
    <div id="homepage">
        <div class="titleBar">
            <span>
                Anime vừa cập nhật
            </span>
            <div class="findButtons">
                <a class="buttonD @if($homepageSelected == 'D') selected @endif ">Ngày</a>
                <span style="color: #2FAF4F">-</span>
                <a class="buttonW @if($homepageSelected == 'W') selected @endif ">Tuần</a>
                <span style="color: #2FAF4F">-</span>
                <!--
                <a class="buttonM @if($homepageSelected == 'M') selected @endif ">Tháng</a>
                <span style="color: #2FAF4F">-</span>
                <a class="buttonS @if($homepageSelected == 'S') selected @endif ">Mùa</a>
                <span style="color: #2FAF4F">-</span>
                <a class="buttonY @if($homepageSelected == 'Y') selected @endif ">Năm</a>
                <span style="color: #2FAF4F">-</span>
                -->
                <a class="buttonA @if($homepageSelected == 'A') selected @endif ">Tất Cả</a>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $(document).on('click', '.pagination a', function (e) {
                    e.preventDefault();
                    var _url = $(this).attr('href');

                    //check if paging by category
                    var n = _url.indexOf('category/');
                    var _id = 0;
                    if(n>0)
                        _id = _url.split('category/')[1].split('?')[0];

                    //check if paging by country
                    n = _url.indexOf('country/');
                    if(n>0)
                        _id = _url.split('country/')[1].split('?')[0];

                    //check if paging by year
                    n = _url.indexOf('year/');
                    if(n>0)
                        _id = _url.split('year/')[1].split('?')[0];

                    // else paging by default(last updated)
                    var page = _url.split('page=')[1];
                    // element display data
                    var parent = $(this).parent().parent().parent().parent();
                    getPosts(page, _id, _url, parent);
                });
            });
            function getPosts(page, _id, _url, selector) {
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url : _url,
                    type: "post",
                    data: {'id': _id, 'page': page, _token: CSRF_TOKEN},
                    async: false,
                    success: function(data){
                        selector.html(data);
                    }
                }).fail(function (jqXHR, textStatus, error) {
                    alert(error);
                });
            }
        </script>
        <div class="list_movies">
            <div class="items">
                @include('templates.NewUpdated')
            </div>
        </div>
    </div>
    <!-- END Home Region -->
@stop

@section('controlBar')
    @if(isSet($userSigned))
        @if($userSigned->loginHash==hash('sha256', 'Anime4A Login Successful'))
            <div class="video_control_region">
                <div class="video_control">
                    <div class="item" style="width: 100%">
                        <a class="userCpBtn abutton" title="Danh sách Anime đang theo dõi">Danh sách Anime đang theo dõi</a>
                    </div>
                </div>
            </div>

            <!-- USER BOX -->
            @include('templates.UserBox')
            <!-- END USER BOX -->

        @else
        @endif
    @else
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