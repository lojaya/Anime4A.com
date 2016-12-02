<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/14/2016
 * Time: 10:59 PM
 */
require_once "/kGoogle.class.php";
$url = 'https://photos.google.com/share/AF1QipP6izSAXi-mqKh9ZVCC_zQhqkY76q4oeN_2HuBk7PenoOTmdUqwIFOj_PqXOT4HIQ/photo/AF1QipO3nyIExX19hkWBQyZIbXoJhOAQ8iU69mO_6Dex?key=a2FneDh4QWlheUh3WWRNSEZzX0JTYy1EbXF4TjNB';
$j2t = new J2T();
$j2t->setLink = $url;
$j2t->setFormat = isset($_GET['format']) ? $_GET['format'] : false;
$data = $j2t->run();
$data = str_replace('\\','', $data);

?>

<html>
<head>
    <script type="text/javascript" src="/js/jquery-3.1.0.min.js"></script>
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>" />
</head>
<body>
<?php var_dump($data);?>
<script src="/js/jwplayer-7.8.1/jwplayer.js"></script>
<script>jwplayer.key="1La4Kp4v+HhGBiJ+p5dWO6sb/AyCdbqtYQKR7w==";</script>
<input type="button" id="test" value="test" style="width: 300px; height: 200px;">
<div id="player"></div>
<script>
    $('#test').bind('click', function (e) {
        var _url = 'http://localhost/test-gg';
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: _url,
            type: "post",
            data: {_token: CSRF_TOKEN},
            async: false,
            success: function(data){
                alert(data);
            }
        });
    });

    jwplayer("player").setup({
        playlist: [{
            sources: <?php echo $data;?>
        }],
        modes: [{
            type: "html5"
        },{
            type: "flash",
            src: "jwplayer-7.8.1/jwplayer.flash.swf"
        }],
        primary: "html5",
        provider: "jwplayer-7.8.1/PauMediaProvider.swf",
        width: 720,
        height: 420,
        aspectratio: "16:9"
    });
</script>
</body>
</html>