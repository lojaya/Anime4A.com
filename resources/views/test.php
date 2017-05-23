<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/14/2016
 * Time: 10:59 PM
 */

include_once('simple_html_dom.php');
?>

<html>
<head>
    <script type="text/javascript" src="/js/jquery-3.1.0.min.js"></script>
    <script type="text/javascript" src="/plugins/ckeditor/js/ckeditor.js"></script>
    <script type="text/javascript" src="/plugins/ckeditor/js/initEditor.js"></script>
    <link rel="stylesheet" href="/plugins/css/default.css">
    <link rel="stylesheet" href="/plugins/toolbarconfigurator/lib/codemirror/neo.css">
    <link rel="stylesheet" href="{{Request::root()}}/style/embed/style.css" type="text/css" />
    <script type="text/javascript" src="{{Request::root()}}/js/jquery-3.1.0.min.js"></script>
    <script src="//vjs.zencdn.net/5.8/video.min.js"></script>
    <link href="//vjs.zencdn.net/5.8/video-js.min.css" rel="stylesheet">
    <script src="/js/jwplayer.js"></script>
    <link href="/js/videojs/videojs-resolution-switcher.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/js/CryptoJS/components/core-min.js.js"></script>
    <script type="text/javascript" src="/js/CryptoJS/rollups/aes.js"></script>
    <style type="text/css">
        .vjs-default-skin .vjs-control-bar { font-size: 125% }

        .video-js .vjs-big-play-button {
            border: none !important;
            border-radius: 0 !important;
        }
        .vjs-playback-rate .vjs-playback-rate-value {
            font-size: 1em;
            line-height: 3;
        }
    </style>
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>" />
</head>
<body>
<?php
$url = 'https://drive.google.com/file/d/0B-2bUftHR2jbakhhYTUwalRkRHM/view';


$a1 = '01.5';
$a2 = '001.5';
if((float)$a1===(float)$a2)
echo 'AAA<br/>';

?>

<input type="button" id="btn" value="Click Here">
<script>
    $('#btn').bind('click', function ($e){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '<?php echo asset('fix');?>',
            type: "post",
            data: {_token: CSRF_TOKEN},
            async: false,
            success: function(data){
                alert(data);
            }
        });
    });
</script>
</body>
</html>