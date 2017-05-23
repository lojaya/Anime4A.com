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
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>" />
    <script>
        $(function() {
            $("#dateStart").datepicker({ dateFormat: 'yy-mm-dd' });
        });
    </script>
</head>
<body>
<form id="InputForm" method="post" action="http://localhost/remuxVideo" enctype="multipart/form-data">
    Dir: <input type="text" name="dir" style="width: 600px;"><br/>
    Start Date: <input type="text" id="dateStart" name="dateStart" value="2016-01-01"/><br/>
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <input type="submit">
</form>
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
                    alert(data);
                }
            });
            return false;
        });
    })
</script>
</body>
</html>