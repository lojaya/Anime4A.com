<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 9/22/2016
 * Time: 3:38 AM
 */
?>
@extends('templates.admincp')

<?php
$MyFunc = new App\Library\MyFunction;
?>

@section('stylesheet')
    <link rel="stylesheet" href="{{Request::root()}}/style/admincp/style.css" type="text/css" />
    <link rel="stylesheet" href="{{Request::root()}}/style/ani/menu.css" type="text/css" />
    <script type="text/javascript" src="{{Request::root()}}/js/jquery-3.1.0.min.js"></script>
    <script type="text/javascript" src="{{Request::root()}}/js/jquery-color.js"></script>
    <script type="text/javascript" src="{{Request::root()}}/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{Request::root()}}/js/admincp.js"></script>
@stop

@section('MainUrl')
    <a href="{{ Request::root() }}" style="display: none" id="MainUrl"></a>
@stop

@section('path')
    <div id="path" style="display: none">
        <input type="text" value="@if(isSet($path)){{ $path }}@endif">
    </div>
@stop

@section('sideBarView')
    <div id="sideBar">
            <div class="btn">
                <a href="{{Request::root()}}/admincp/anime" title="Anime" class="info">Anime</a>
            </div>
            <div class="btn">
                <a href="{{Request::root()}}/admincp/episode" title="Episode" class="info">Episode</a>
            </div>
            <div class="btn">
                <a href="{{Request::root()}}/admincp/video" title="Video" class="info">Video</a>
            </div>
            <div class="btn">
                <a href="{{Request::root()}}/admincp/category" title="Thể Loại" class="info">Category</a>
            </div>
            <div class="btn">
                <a href="{{Request::root()}}/admincp/fansub" title="Fansub" class="info">Fansub</a>
            </div>
            <div class="btn">
                <a href="{{Request::root()}}/admincp/type" title="Loại Phim" class="info">Type</a>
            </div>
            <div class="btn">
                <a href="{{Request::root()}}/admincp/server" title="Server" class="info">Server</a>
            </div>
            <div class="btn">
                <a href="{{Request::root()}}/admincp/status" title="Status" class="info">Status</a>
            </div>
            <div class="btn">
                <a href="{{Request::root()}}/admincp/country" title="Quốc Gia" class="info">Country</a>
            </div>
            <div class="btn">
                <a href="{{Request::root()}}/admincp/director" title="Đạo Diễn" class="info">Director</a>
            </div>
            <div class="btn">
                <a href="{{Request::root()}}/admincp/char" title="Nhân Vật" class="info">Characters</a>
            </div>
            <div class="btn">
                <a href="{{Request::root()}}/admincp/producer" title="Nhà Sản Xuất" class="info">Producer</a>
            </div>
            <div class="btn">
                <a href="{{Request::root()}}/admincp/trailer" title="Trailer" class="info">Trailer</a>
            </div>
            <div class="btn">
                <a href="{{Request::root()}}/admincp/tag" title="Tag" class="info">Tag</a>
            </div>
    </div>

@stop

@section('listView')
    <div id="listView">
    </div>
@stop

@section('editorView')
    <div id="editorView">

    </div>
@stop