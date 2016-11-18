@extends('templates.master')

<?php
$MyFunc = new App\Library\MyFunction;
?>

@section('stylesheet')
    <link rel="stylesheet" href="{{Request::root()}}/style/ani/style.css" type="text/css" />
    <link rel="stylesheet" href="{{Request::root()}}/style/ani/menu.css" type="text/css" />
    <script type="text/javascript" src="{{Request::root()}}/js/jquery-3.1.0.min.js"></script>
    <script src="{{Request::root()}}/js/anime4a.js" type="text/javascript" charset="utf-8"></script>
@stop

@section('headerBar')
    <div id="header_bar">
        <div id="header_banner">
            <h1>
                <a href="{{Request::root()}}" title="Xem Anime Online">
                    <img class="logo" src="{{Request::root()}}/images/anime4a.com.png" alt="Xem Anime Online" title="Xem Anime Online">
                </a>
            </h1>
            <img class="banner" src="{{Request::root()}}/images/banner1.png" alt="animer4a.com">
        </div>
    </div>
@stop

@section('sign-in-box')
    @if($signed)
        <h1>
            <a href="" title="Anime4A.com" style="text-decoration: none;color:White;">Thoát</a>
        </h1>
    @else
        <h1>
            <a href="{{Request::root()}}/" title="Xem Anime Online" style="text-decoration: none;color:White;">
                Đăng Nhập
            </a> |
            <a href="{{Request::root()}}/" title="Xem Anime Online" style="text-decoration: none;color:White;">
                Đăng Ký
            </a>
        </h1>
    @endif
@stop

@section('header-menu-category')
    @foreach ($category_list as $i)
        <li><a href='{{Request::root()}}/'>{{ $i->name }}</a></li>
    @endforeach
@stop

@section('header-menu-country')
    @foreach ($country_list as $i)
        <li><a href='{{Request::root()}}/'>{{ $i->name }}</a></li>
    @endforeach
@stop

@section('content')
    <div class="breadcrumb"></div>
    <!-- Film Info Region -->
    <div class="film_info">
        <div class="item_play">
            <img src="{{ $filmInfo->img }}" style="width: 192px;height: 250px;"/>
        </div>
        <div class="item_info">
            <div class="name">
                <p></p><h2 class="tenphim">{{ $filmInfo->name }}</h2><p></p>
                <p></p><h3 class="tenkhac">{{ $filmInfo->name_en }}</h3><p></p>
            </div>
            <p><span class="title"><b>Thể loại:</b></span>
                <b class="n_font">
                    <?php
                    $cat = explode(",",$filmInfo->category);
                    $n = count($cat);
                    if($n>0) {
                        for ($i = 0; $i < $n - 1; $i++) {
                            echo '<a>' . $MyFunc->getCategoryName($cat[$i]) . '</a>, ';
                        }

                        echo '<a>' . $MyFunc->getCategoryName($cat[$n - 1]) . '</a>';
                    }
                    ?>
                </b>
            </p>
            <p><span class="title"><b>Type:</b></span>
                <b class="n_font">
                    <a>{{ $MyFunc->getTypeName($filmInfo->type) }}</a>
                </b>
            </p>
            <p><span class="title"><b>Số tập: </b></span><b class="n_font">{{ $filmInfo->episode_new }}/{{ $filmInfo->episode_total }}</b></p>
            <p><span class="title"><b>Status: </b></span>
                <b class="n_font">
                    <?php
                    $statusname = $MyFunc->getStatusName($filmInfo->status);
                    echo '<a href="tag/'.$statusname.'.html">'.$statusname.'</a>';
                    ?>
                </b>
            </p>
            <p><span class="title"><b>Lượt xem: </b></span><b class="n_font">{{ $filmInfo->view_count }}</b></p>
            <p><span class="title"><b>Năm phát sóng: </b></span><b class="n_font">{{ $filmInfo->date_release }}</b></p>
            <p></p>

            <a href="{{Request::root()}}/xem-phim/{{ $MyFunc->nameFormat($filmInfo->name) }}/{{ $filmInfo->id }}.html" class="btn_play">Xem Phim</a>
            <a href="{{Request::root()}}/" class="btn_save">Đánh Dấu</a>
        </div>
        <div class="film_description">
            <div align="center">
                {{ $filmInfo->description }}
            </div>
        </div>
    </div>
    <!-- END Film Info Region -->
@stop

@section('sidebar')
    <!-- SideBar Region -->
    <div id="sidebar">
        <div class="newest_film">
            <div class="title">
                Anime Mới Nhất
            </div>
            <ul class="sidebar_items">

                @foreach ($top5_new as $i)
                    <li class="item">
                        <a class="item_link" href="{{Request::root()}}/xem-thong-tin/{{ $MyFunc->nameFormat($i->name) }}/{{ $i->id }}.html">
                            <img src="{{ $i->img }}" class="item_thumb" onerror="this.onerror=null;this.src='http://localhost/images/noimg.jpg';" >
                            <span class="name">{{ $i->name }}</span>
                            <span class="view">Lượt xem: {{ $i->view_count }}</span>
                            <span class="ep">Số Tập: {{ $i->episode_new }}/{{  $i->episode_total }}</span>
                            <span class="desc">{{ $i->description }}</span>
                        </a>
                    </li>
                @endforeach

            </ul>
        </div>
        <div class="most_view">
            <div class="title">
                Anime Xem Nhiều
            </div>
            <ul class="sidebar_items">

                @foreach ($top5_view as $i)
                    <li class="item">
                        <a class="item_link" href="{{Request::root()}}/xem-thong-tin/{{ $MyFunc->nameFormat($i->name) }}/{{ $i->id }}.html">
                            <img src="{{ $i->img }}" class="item_thumb" onerror="this.onerror=null;this.src='http://localhost/images/noimg.jpg';" >
                            <span class="name">{{ $i->name }}</span>
                            <span class="view">Lượt xem: {{ $i->view_count }}</span>
                            <span class="ep">Số Tập: {{ $i->episode_new }}/{{  $i->episode_total }}</span>
                            <span class="desc">{{ $i->description }}</span>
                        </a>
                    </li>
                @endforeach

            </ul>
        </div>
    </div>
    <!-- END SideBar Region -->
@stop