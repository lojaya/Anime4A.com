<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 11/12/2016
 * Time: 6:46 PM
 */
?>
<?php
use App\Library\MyFunction;
$myFunc = new MyFunction();
?>
<form action="@if(isSet($url)){{ $url.'/save' }}@endif" method="post" enctype="multipart/form-data" class="inputArea" id="InputForm">
    <div class="input_box">
        <div class="title">Username: </div>
        <input type="text" name="username" style="width: 500px;" value="@if(isSet($data)){{ $data->username }}@endif">
    </div>
    <div class="input_box">
        <div class="title">Facebook ID: </div>
        <input type="text" style="width: 500px;" value="@if(isSet($data)){{ $data->fb_id }}@endif">
    </div>
    <div class="input_box">
        <div class="title">Google ID: </div>
        <input type="text" style="width: 500px;" value="@if(isSet($data)){{ $data->gg_id }}@endif">
    </div>
    <div class="input_box">
        <div class="title">Type: </div>
        <input type="text" name="type" style="width: 500px;" value="@if(isSet($data)){{ $data->type }}@endif">
    </div>
    <div class="input_box">
        <div class="title">Created At: </div>
        <input type="text" style="width: 500px;" value="@if(isSet($data)){{ $data->created_at }}@endif">
    </div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="submit" class="submit">
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
                    $('#editorView').html(data);
                    refresh();
                }
            });
            return false;
        });
    })
</script>