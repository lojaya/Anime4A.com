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
<form action="{{Request::root()}}/admincp/episode/save" method="post" enctype="multipart/form-data" id="inputArea">
    <div class="input_box">
        <div class="title">Anime Name-ID: </div>
        <select class="category_value" name="anime_id" style="width: 500px">
            @if(isSet($animeList))
                @foreach($animeList as $i)
                    <option value="{{ $i->id }}" @if(isSet($episode)) @if($i->id==$episode->anime_id){{'selected'}}@endif @endif>{{ $i->name.' - '.$i->id }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="input_box">
        <div class="title">Episode: </div>
        <input type="text" name="episode" value="@if(isset($episode)){{ $episode['episode'] }}@endif">
    </div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="submit" class="submit">
</form>
<script>
    $(document).ready(function () {
        $('#inputArea').submit(function(e) {
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