<html>
<head>
    <link rel="stylesheet" href="{{Request::root()}}/style/embed/style.css" type="text/css" />
    <script type="text/javascript" src="{{Request::root()}}/js/jquery-3.1.0.min.js"></script>
    <script type="text/javascript" src="/js/jwplayer.js"></script>
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>" />
</head>
<body oncontextmenu="return true;">
<div id='player'>
    <img src="/images/poster.jpg" style="width:100%; height:100%">
</div>
<script>
    jwplayer.key='1La4Kp4v+HhGBiJ+p5dWO6sb/AyCdbqtYQKR7w==';
    $.getJSON("http://jsonip.com/?callback=?", function (data) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: '<?php echo asset('ani');?>',
            type: "post",
            data: {'url': "<?php echo $data;?>", 'ip': data.ip, _token: CSRF_TOKEN},
            async: false,
            success: function(data){
                jwplayer('player').setup({
                    sources: JSON.parse(data),
                    width: "100%",
                    height: "100%",
                    image: "/images/poster.jpg",
                    primary: "html5",
                    autostart: 'true'
                });
            }
        });
    });
</script>

</body>
</html>