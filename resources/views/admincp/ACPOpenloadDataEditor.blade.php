<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 11/12/2016
 * Time: 6:46 PM
 */
?>
<form action="@if(isSet($url)){{ $url.'/save' }}@endif" method="post" enctype="multipart/form-data" class="inputArea" id="InputForm">
    <input type="hidden" name="id" value="@if(isSet($data)){{ $data->id }}@endif">
    <div class="input_box">
        <div class="title">Name: </div>
        <input type="text" name="name" style="width: 500px;" value="@if(isSet($data)){{ $data->name }}@endif" disabled>
    </div>
    <div class="input_box">
        <div class="title">Fansub: </div>
        <select class="category_value" name="fansub_id" id="fansub_id" style="width: 200px">
            @if(isSet($fansubList))
                @foreach($fansubList as $i)
                    <option value="{{ $i->id }}">{{ $i->name.' - '.$i->id }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="input_box">
        <div class="title">Server: </div>
        <select class="category_value" name="server_id" id="server_id" style="width: 200px" disabled>
            <option value="3">Openload</option>
        </select>
    </div>
    <div class="input_box">
        <div class="title">Urls: </div>
        <textarea name="urls"></textarea>
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