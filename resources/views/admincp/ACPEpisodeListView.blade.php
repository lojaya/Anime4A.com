<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 11/12/2016
 * Time: 6:02 PM
 */
?>

<div id="toolBox">
    <div class="toolBox_region">
        <a id="btnRefresh">REFRESH</a>
        <a id="btnNew">NEW</a>
    </div>
</div>
<div class="items">
    <div class="url" style="display: none">
        <a href="{{Request::root()}}/admincp/episode"></a>
    </div>
    <div class="item" style="color: red; font-size: 9pt">
        <div style="padding: 0px; display: inline-block; width: 15px;"><b>CB</b></div>
        <div style="padding: 0px; display: inline-block; width: 276px; margin-left: 5px;"><b>Anime</b></div>
    </div>
    @if(isSet($items))
        @foreach($items as $i)
            <div id="item-{{ $i->anime_id }}" class="item">
                <input id="item-checked" type="checkbox" name="checked" value="{{ $i->anime_id }}" class="col0">
                <div class="col1" style="width: 289px">
                    <a>{{ \App\DBAnimes::GetName($i->anime_id) }}</a>
                </div>
            </div>
        @endforeach
    @endif
</div>