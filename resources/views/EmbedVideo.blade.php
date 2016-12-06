<html>
<head>
    <link rel="stylesheet" href="{{Request::root()}}/style/embed/style.css" type="text/css" />
    <script type="text/javascript" src="{{Request::root()}}/js/jquery-3.1.0.min.js"></script>
</head>
<body oncontextmenu="return false;">
<div id="player"></div>
<script src="{{ asset('js/jwplayer-7.8.1/jwplayer.js') }}"></script>
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
            src: "<?php echo Request::root();?>/js/jwplayer-7.8.1/jwplayer.flash.swf"
        }],
        skin: {
            name: "bekle"
        },
        primary: "html5",
        provider: "<?php echo Request::root();?>/js/jwplayer-7.8.1/PauMediaProvider.swf",
        width: "100%",
        aspectratio: "16:9"
    });
</script>
</body>
</html>