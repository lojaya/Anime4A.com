@extends('mobile.templates.master')

@section('Title')
    {{ 'Anime Subbed Online' }}
@stop

@section('stylesheet')
    <link rel="stylesheet" href="{{Request::root()}}/style/mobile/style.css" type="text/css" />
    <link rel="stylesheet" href="{{Request::root()}}/style/mobile/menu.css" type="text/css" />
    <script type="text/javascript" src="{{Request::root()}}/js/jquery-3.1.0.min.js"></script>
    <script type="text/javascript" src="{{Request::root()}}/js/jquery-color.js"></script>
    <script type="text/javascript" src="{{Request::root()}}/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{Request::root()}}/js/m.anime4a.js" charset="utf-8"></script>
    <script type="text/javascript" src="{{Request::root()}}/js/searchBox.js" charset="utf-8"></script>
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
                        @include('mobile.templates.NewUpdated')
                    </div>
                </div>
            </div>
        @endif
    @endif
    <!-- END Home Region -->
@stop