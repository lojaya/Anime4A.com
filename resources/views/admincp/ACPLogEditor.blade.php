<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 11/12/2016
 * Time: 6:46 PM
 */
?>
<form action="@if(isSet($url)){{ $url.'/save' }}@endif" method="post" enctype="multipart/form-data" class="inputArea" id="InputForm">
    <div class="input_box">
        <div class="title">IP: </div>
        <input type="text" name="ip" style="width: 500px;" value="@if(isSet($data)){{ $data->ip }}@endif">
    </div>
    <div class="input_box">
        <div class="title">Referer: </div>
        <input type="text" name="referer" style="width: 500px;" value="@if(isset($data)){{ $data->referer }} @endif">
    </div>
    <div class="input_box">
        <div class="title">Request Uri: </div>
        <input type="text" name="request_uri" style="width: 500px;" value="@if(isset($data)){{ $data->request_uri }} @endif">
    </div>
    <div class="input_box">
        <div class="title">User Agent: </div>
        <input type="text" name="user_agent" style="width: 500px;" value="@if(isset($data)){{ $data->user_agent }} @endif">
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