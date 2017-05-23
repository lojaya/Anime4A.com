<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/14/2016
 * Time: 10:59 PM
 */
?>

<html>
<head>
    <script type="text/javascript" src="/js/jquery-3.1.0.min.js"></script>
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>" />
</head>
<body>
<form id="InputForm" method="post" action="http://localhost/ggphotos" enctype="multipart/form-data">
    Url: <input type="text" name="url" style="width: 600px;"><br/>
    Start Ep: <input type="text" id="epStart" name="epStart" value="1"/><br/>
    Prefix: <input type="text" id="prefix" name="prefix" value="2"/><br/>
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <input type="submit">
</form>
<div id="result">
    <input type="button" id="copyBtn" value="Copy">
    <textarea id="resultView" style="width: 100%; height: 300px;"></textarea>
</div>
<script>
    $(document).ready(function () {
        $('#InputForm').submit(function(e) {
            e.preventDefault();
            var _url = $(this).attr('action');
            var fData = new FormData($(this)[0]);

            $.ajax({
                url: _url,
                type: "post",
                data: fData,
                processData: false,
                contentType: false,
                async: false,
                success: function(data){
                    $('#resultView').html(data);
                }
            });
            return false;
        });
        $('#copyBtn').bind('click', function (e) {
            $('#resultView').select();
            document.execCommand("copy");
        });
    })
</script>
</body>
</html>