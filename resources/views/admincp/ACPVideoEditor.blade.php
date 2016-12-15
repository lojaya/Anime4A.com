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
<form action="{{Request::root()}}/admincp/save-video" method="post" enctype="multipart/form-data" class="inputArea" id="InputForm">
    <input type="hidden" name="episode_id" value="@if(isSet($episode_id)){{ $episode_id }}@endif">
    <input type="hidden" name="episode_name" value="@if(isSet($episode_id)){{ \App\DBEpisodes::GetEpisode($episode_id) }}@endif">
    <input type="hidden" id="video_id" name="video_id" value="@if(isSet($video)){{ $video->id }}@endif">
    <div class="input_box">
        <div class="title">Fansub Name - ID: </div>
        <select class="category_value" name="fansub_id" style="width: 500px">
            @if(isSet($fansubList))
                @foreach($fansubList as $i)
                    <option value="{{ $i->id }}" @if(isSet($video)) @if($i->id==$video->fansub_id){{'selected'}}@endif @else @if($i->id==$defaultFansub){{'selected'}}@endif @endif>{{ $i->name.' - '.$i->id }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="input_box">
        <div class="title">Server Name - ID: </div>
        <select class="category_value" name="server_id" style="width: 500px">
            @if(isSet($serverList))
                @foreach($serverList as $i)
                    <option value="{{ $i->id }}" @if(isSet($video)) @if($i->id==$video->server_id){{'selected'}}@endif @endif>{{ $i->name.' - '.$i->id }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="input_box">
        <div class="title">Video url source: </div>
        <input type="text" name="url_source" style="width: 500px;" value="@if(isSet($video)){{ $video->url_source }}@endif">
    </div>
    <div class="input_box">
        <div class="title">Video url download: </div>
        <input type="text" name="url_download" style="width: 500px;" value="@if(isSet($video)){{ $video->url_download }}@endif">
    </div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="submit" class="submit">
    @if(isSet($video))<input type="button" id="delVideoBtn" value="Delete">@endif
</form>

<script>
    $('#delVideoBtn').bind('click', function (e) {
        e.preventDefault();

        if(confirm('Are you sure?')){
            // add new episode array
            var video_id = $('#video_id').val();

            DeleteVideo(video_id);
        }
    });

    function DeleteVideo(video_id) {
        var _url = $('#MainUrl').attr('href') + '/admincp/del-video';
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        var _ep = $('input[name="episode_name"]').val();
        $.ajax({
            url: _url,
            type: "post",
            data: {'id': video_id, _token: CSRF_TOKEN},
            async: false,
            success: function(data){
                // refresh video list
                getVideoList(_ep);
            }
        });
    }
    $(document).ready(function () {
        $('#InputForm').submit(function(e) {
            e.preventDefault();
            var _url = $(this).attr('action');
            var fData = new FormData($(this)[0]);
            var _ep = $('input[name="episode_name"]').val();

            $.ajax({
                url: _url,
                type: "post",
                data: fData,
                processData: false,
                contentType: false,
                async: false,
                success: function(data){
                    $('#editorArea').html(data);
                    // refresh video list
                    getVideoList(_ep);
                    // refresh list view
                    refresh();
                }
            });
            return false;
        });
    })
</script>