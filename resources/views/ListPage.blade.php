<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 11/9/2016
 * Time: 1:50 PM
 */
?>
@extends('templates.master')

<?php
$MyFunc = new App\Library\MyFunction;
?>

@section('stylesheet')
    <link rel="stylesheet" href="{{Request::root()}}/style/ani/style.css" type="text/css" />
    <link rel="stylesheet" href="{{Request::root()}}/style/ani/menu.css" type="text/css" />
    <link rel="stylesheet" href="{{Request::root()}}/style/ani/searchBox.css" type="text/css" />
    <script type="text/javascript" src="{{Request::root()}}/js/jquery-3.1.0.min.js"></script>
    <script type="text/javascript" src="{{Request::root()}}/js/jssor.slider-21.1.6.mini.js" charset="utf-8"></script>
    <script type="text/javascript" src="{{Request::root()}}/js/jquery-color.js"></script>
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
@stop

@section('controlBar')
    <div class="video_control_region">
        <div class="video_control">
            @if(isSet($userSigned))
                @if($userSigned->loginHash==hash('sha256', 'Anime4A Login Successful'))
                    <div class="item" style="width: 100%">
                        <b>
                            <a class="abutton">Tài Khoản</a>
                        </b>
                    </div>
                @else
                    <div class="item" style="width: 100%"><b><a class="abutton">Đăng Nhập Để Sử Dụng.</a></b></div>
                @endif
            @else
                <div class="item" style="width: 100%"><b><a class="abutton">Đăng Nhập Để Sử Dụng.</a></b></div>
            @endif
        </div>
    </div>
@stop
