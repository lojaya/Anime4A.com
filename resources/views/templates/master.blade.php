<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/8/2016
 * Time: 1:57 AM
 */
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>@lang('messages.pagename')</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="content-language" content="@lang('messages.pagename2')" />
    <meta name="Robots" content="index, follow" />
    <meta name="description" content="@lang('messages.description')">
    <meta name="keywords" content="Anime Vietsub, Anime Online, Anime Download, Anime HD" />
    <meta name="abstract" content="Copyright © 2012 Anime TV Watch Free Vietnamese Subbed Dubbed Anime Online">
    <meta name="Search Engines" content="www.altaVista.com, www.aol.com, www.infoseek.com, www.excite.com, www.hotbot.com, www.lycos.com, www.magellan.com, www.looksmart.com, www.cnet.com, www.voila.com, www.google.fr, www.google.com, www.google.com.vn, www.yahoo.fr, www.yahoo.com, www.alltheweb.com, www.msn.com, www.netscape.com, www.nomade.com">
    <meta property="fb:app_id" content="" />
    <meta property="og:type" content="video:movie" />
    <meta property="og:title" content="@lang('messages.title')" />
    <meta property="og:description" content="@lang('messages.description')" />
    <meta property="og:url" content="http://localhost/" />
    <meta property="og:image" content="" />
    <base href="http://localhost/" /><!--[if IE]></base><![endif]-->
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link href="http://localhost/favicons/anime4a.com.png" rel="shortcut icon" type="image/x-icon" />
    <link rel="author" href="http://localhost" />
    @yield('stylesheet')
</head>
<body>
<div class="shadow" style="position: fixed; display: none"></div>
@yield('MainUrl')
<div id="wrap">

    @include('pages.header')

    <div id="container">
        <div id="pagebody">

            @yield('content')

            @yield('sidebar')

        </div>
    </div>

    @yield('controlBar')

    @include('pages.footer')

</div>
</body>
</html>
