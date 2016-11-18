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
    <script>
        $(function () {
            $('#AdvancedSearch').submit(function (e) {
                e.preventDefault();
                // submit code
                var _url = $(this).attr('action');
                var fData = new FormData($(this)[0]);

                $.ajax({
                    url : _url,
                    type: "post",
                    data: fData,
                    processData: false,
                    contentType: false,
                    async: false,
                    success: function(data){
                        $('#FoundItems>.list_movies>.items').html(data);
                    }
                }).fail(function (jqXHR, textStatus, error) {
                    alert(error);
                });
            })
        });
    </script>
    <form action="{{Request::root()}}/advSearch" id="AdvancedSearch" method="post" enctype="multipart/form-data" >
        <div>
            <div class="inputBox">
                <input type="text" id="searchString" style="width: 350px;" value="">
            </div>
            <div class="inputBox">
                <select name="searchYear">
                    <option value="">Theo Năm</option>
                </select>
            </div>
            <div class="inputBox">
                <select name="searchSS">
                    <option value="">Theo Mùa</option>
                </select>
            </div>
            <div class="inputBox">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="submit" id="searchString" value="Tìm Kiếm">
            </div>
        </div>
        <div class="type_selection">
            <div class="title">Thể Loại:</div>
            @if(isSet($category_list))
                @foreach($category_list as $i)
                    <div class="typeCbox">
                        <input type="checkbox" name="TypeSelected" value="{{ $i->id }}" title="{{ $i->name }}">{{ $i->name }}
                    </div>
                @endforeach
            @endif
        </div>
        <div id="FoundItems">
            <div class="list_movies">
                <div class="items">
                </div>
            </div>
        </div>
    </form>
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
        <div class="newest_film">
            <div class="titleBar">
                <span>Anime Mới</span>
                <div style="float: right">
                <!--
                    <a class="buttonD @if($newestFilmSelected == 'D') selected @endif ">D</a>
                    <span>/</span>
                    <a class="buttonW @if($newestFilmSelected == 'W') selected @endif ">W</a>
                    <span>/</span>
                    -->
                    <a class="buttonM @if($newestFilmSelected == 'M') selected @endif ">M</a>
                    <span>-</span>
                    <a class="buttonS @if($newestFilmSelected == 'S') selected @endif ">S</a>
                    <span>-</span>
                    <a class="buttonY @if($newestFilmSelected == 'Y') selected @endif ">Y</a>
                </div>
            </div>
            <ul class="sidebar_items">

            </ul>
        </div>
    </div>
    <!-- END SideBar Region -->
@stop