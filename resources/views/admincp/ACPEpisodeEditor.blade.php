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
<script>
    $(document).ready(function () {
        getEpisodeList();
    });

    $('#episode_add').bind('click', function (e) {
        e.preventDefault();

        // add new episode
        var value = $('#episode_value').val();
        var anime_id = $("select[name='anime_id'] :selected").val();

        addNewEpisode(anime_id, value);
        $('#anime_id').attr('disabled', 'true');
    });

    $('#episodeList_add').bind('click', function (e) {
        e.preventDefault();

        // add new episode array
        var e1 = $('#episodeList_value1').val();
        var e2 = $('#episodeList_value2').val();

        var anime_id = $("select[name='anime_id'] :selected").val();

        addNewEpisodeList(anime_id, e1, e2);
        $('#anime_id').attr('disabled', 'true');
    });

    $('#video_add').bind('click', function (e) {
        e.preventDefault();

        // add new video
        addNewVideo();
    });

    /**
     *
     * @param edit_id
     * @param value
     */
    function addNewEpisode(anime_id, value) {
        var _url = $('#MainUrl').attr('href') + '/admincp/add-episode';
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: _url,
            type: "post",
            data: {'anime_id': anime_id, 'episode': value, _token: CSRF_TOKEN},
            async: false,
            success: function(data){
                // refesh episode list
                getEpisodeList();
            }
        });
        var path = $('.url>a').attr('href');
        var data = getList(path);
        $('#listView').html(data);
    }

    /**
     *
     * @param anime_id
     * @param e1
     * @param e2
     */
    function addNewEpisodeList(anime_id, e1, e2) {
        var _url = $('#MainUrl').attr('href') + '/admincp/add-episode-list';
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: _url,
            type: "post",
            data: {'anime_id': anime_id, 'e1': e1, 'e2': e2, _token: CSRF_TOKEN},
            async: false,
            success: function(data){
                // refesh episode list
                getEpisodeList();
            }
        });
        var path = $('.url>a').attr('href');
        var data = getList(path);
        $('#listView').html(data);
    }

    function getEpisodeList() {
        var _url = $('#MainUrl').attr('href') + '/admincp/episode2';
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: _url,
            type: "post",
            data: {_token: CSRF_TOKEN},
            async: false,
            success: function(data){
                $('#episode_list').html(data);
            }
        });
    }

    function getVideoList(_ep) {
        var _url = $('#MainUrl').attr('href') + '/admincp/get-video';
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: _url,
            type: "post",
            data: {'ep': _ep, _token: CSRF_TOKEN},
            async: false,
            success: function(data){
                $('#video_list').html(data);
            }
        });
    }

    function addNewVideo() {
        var _url = $('#MainUrl').attr('href') + '/admincp/add-video';
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var defaultFansub = $("select[id='default_fansub_id'] :selected").val();

        $.ajax({
            url: _url,
            type: "post",
            data: {'defaultFansub': defaultFansub,_token: CSRF_TOKEN},
            async: false,
            success: function(data){
                $('#editorArea').html(data);
            }
        });
    }
</script>
<div class="inputArea">
    <div class="input_box">
        <div class="title">Anime Name-ID: </div>
        <select class="category_value" name="anime_id" id="anime_id" style="width: 500px">
            @if(isSet($anime_id))
                <option value="{{ $anime_id }}" selected>{{ \App\DBAnimes::GetName($anime_id) }}</option>
            @else
                @if(isSet($animeList))
                    @foreach($animeList as $i)
                        <option value="{{ $i->id }}">{{ $i->name.' - '.$i->id }}</option>
                    @endforeach
                @endif
            @endif
        </select>
    </div>
    <div class="input_box">
        <div class="title">Default Fansub: </div>
        <select class="category_value" name="default_fansub_id" id="default_fansub_id" style="width: 200px">
            @if(isSet($fansubDefaultList))
                @foreach($fansubDefaultList as $i)
                    <option value="{{ $i->id }}">{{ $i->name.' - '.$i->id }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="input_box">
        <div class="title">Episode: </div>
        <input type="text" id="episode_value" value="">
        <input type="button" id="episode_add" value="ADD">
        <div class="title">Episode List: </div>
        <input type="text" id="episodeList_value1" value="">
        <input type="text" id="episodeList_value2" value="">
        <input type="button" id="episodeList_add" value="ADD">
        <div id="episode_list">
        </div>
    </div>
    <div class="input_box">
        <div class="title">Video: </div>
        <div id="video_list">
        </div>
        <input type="button" id="video_add" value="ADD">
    </div>
</div>
<div id="editorArea">
    @include('admincp.ACPVideoEditor')
</div>