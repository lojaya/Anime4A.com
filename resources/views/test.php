<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/14/2016
 * Time: 10:59 PM
 */
use App\Mobile_Detect;
?>

<html>
<head>
    <script type="text/javascript" src="/js/jquery-3.1.0.min.js"></script>
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>" />
</head>
<body>
<div id="player"></div>
<?php
$url = 'https://lh3.googleusercontent.com/3UVe-3lddj4SsDTodWBuFihMdrw8_ZIaNiUZIP4pfRMB83Q9DLBa9fEO_DACyKSB892ySZoE2iA=m18';
$source[] = array(
    'type'      => 'mp4',
    'label'     => '720',
    'file'      => \App\Library\MyFunction::getDirectLink($url),
    'default'   => true
);;
$data = str_replace('\\', '', json_encode($source));
?>

<script src="/js/jwplayer-7.8.1/jwplayer.js"></script>
<script>jwplayer.key="1La4Kp4v+HhGBiJ+p5dWO6sb/AyCdbqtYQKR7w==";</script>
<script type='text/javascript'>
    jwplayer("player").setup({
        playlist: [{
            sources: <?php if(isSet($data)) echo $data; ?>
        }],
        modes: [{
            type: "html5"
        },{
            type: "flash",
            src: "/js/jwplayer-7.8.1/jwplayer.flash.swf"
        }],
        skin: {
            name: "bekle"
        },
        primary: "html5",
        provider: "/js/jwplayer-7.8.1/PauMediaProvider.swf",
        width: 680,
        height: 420,
        aspectratio: "16:9"
    });
</script>
</body>
</html>