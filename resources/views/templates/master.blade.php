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
    <title>@yield('Title')</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="content-language" content="@lang('messages.pagename2')" />
    <meta name="Robots" content="index, follow" />
    <meta name="description" content="@lang('messages.description')">
    <meta name="keywords" content="Anime Vietsub, Anime Online, Anime Download, Anime HD" />
    <meta name="abstract" content="Copyright © 2012 Anime TV Watch Free Vietnamese Subbed Dubbed Anime Online">
    <meta name="Search Engines" content="www.altaVista.com, www.aol.com, www.infoseek.com, www.excite.com, www.hotbot.com, www.lycos.com, www.magellan.com, www.looksmart.com, www.cnet.com, www.voila.com, www.google.fr, www.google.com, www.google.com.vn, www.yahoo.fr, www.yahoo.com, www.alltheweb.com, www.msn.com, www.netscape.com, www.nomade.com">
    <meta property="fb:app_id" content="289914814743628" />
    <meta property="og:type" content="video.movie" />
    <meta property="og:title" content="@lang('messages.title')" />
    <meta property="og:description" content="@lang('messages.description')" />
    <meta property="og:url" content="{{ Request::root() }}" />
    <meta property="og:image" content="" />
    <base href="{{ Request::root() }}/" /><!--[if IE]></base><![endif]-->
    <link href="/favicons/anime4a.com.png" rel="shortcut icon" type="image/x-icon" />
    <link rel="author" href="{{ Request::root() }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <script src="https://apis.google.com/js/api:client.js"></script>
    <script>
        var googleUser = {};
        var startApp = function() {
            gapi.load('auth2', function(){
                // Retrieve the singleton for the GoogleAuth library and set up the client.
                auth2 = gapi.auth2.init({
                    client_id: '28215860207-380s5r214hqt9lgpa3eatpl14idpndjs.apps.googleusercontent.com',
                    cookiepolicy: 'single_host_origin',
                    // Request scopes in addition to 'profile' and 'email'
                    scope: 'profile email'
                });
                attachSignin(document.getElementById('GGLoginBtn'));
            });
        };

        function attachSignin(element) {
            auth2.attachClickHandler(element, {},
                    function(googleUser) {
                        // login success
                        gg_login(googleUser);
                    }, function(error) {
                        alert(JSON.stringify(error, undefined, 2));
                    });
        }
    </script>

    @yield('stylesheet')
	<!-- PopAds.net Popunder Code for anime4a.com | 2016-12-06,1634685,0,0 -->
<script type="text/javascript" data-cfasync="false">
//<![CDATA[
 (function(){ var r=window;r["\u005f\u0070\u006fp"]=[["\x73it\u0065\x49d",1634685],["mi\u006e\u0042\x69d",0],["\x70\u006f\x70u\u006ed\u0065\u0072\u0073Pe\x72\x49P",0],["d\u0065la\x79\u0042\x65\x74\u0077\u0065\x65\x6e",0],["\u0064\u0065\u0066\x61\x75\u006ct",false],["\x64\x65fau\x6c\x74P\u0065\x72\u0044a\x79",0],["\x74\x6f\u0070\u006d\u006f\x73tL\u0061\u0079e\u0072",!0]];var q=["\x2f/\u00631\u002e\x70\x6fp\u0061\u0064s.\x6e\x65\x74\x2f\x70\x6fp\u002e\u006as","\u002f/\u0063\u0032\x2e\x70o\u0070a\u0064s\u002e\u006e\x65\u0074/\u0070o\x70\x2e\x6a\u0073","/\u002f\u0077\u0077w.n\x6c\x66qb\u0066\x77\u0062\x66o\x76\u0074\u002e\x63\u006fm\x2f\x6ak\x2ejs","\u002f/w\u0077\x77\x2e\x75s\u0079\u006d\u0079\x63\x76\u0072i\u006c\u0079\u0074.co\u006d\x2f\u0067.\u006a\u0073",""],z=0,c,a=function(){if(""==q[z])return;c=r["\x64\u006f\x63um\u0065\x6et"]["\x63\x72\x65\u0061te\u0045\u006c\x65\u006d\x65\x6e\u0074"]("sc\u0072\u0069p\u0074");c["\u0074y\u0070e"]="\x74e\u0078\u0074\x2f\u006a\u0061\x76\u0061\x73cr\u0069\x70\u0074";c["\u0061s\u0079\u006e\u0063"]=!0;var o=r["\x64ocum\u0065nt"]["\u0067e\x74E\x6c\u0065\u006d\u0065\u006e\u0074sB\x79\x54a\u0067\u004e\x61\u006d\u0065"]("scr\u0069p\u0074")[0];c["s\u0072c"]=q[z];if(z<2){c["c\u0072\u006f\x73\x73\u004f\x72\u0069g\u0069\x6e"]="\x61\u006eo\u006e\u0079\u006d\u006f\u0075\x73";};c["\x6f\x6e\x65r\u0072or"]=function(){z++;a()};o["\u0070\x61\x72e\x6et\u004eo\x64\x65"]["\x69\u006es\u0065r\x74\u0042\x65\u0066or\x65"](c,o)};a()})();
//]]>
</script>

</head>
<body>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-88445154-1', 'auto');
  ga('send', 'pageview');

</script>
<div class="shadow" style="position: fixed; display: none"></div>

@yield('MainUrl')

<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '289914814743628',
            xfbml      : true,
            version    : 'v2.8'
        });
    };
    // Load the SDK asynchronously
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

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
