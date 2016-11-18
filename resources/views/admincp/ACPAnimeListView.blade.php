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
        <a id="btnDel">DELETE</a>
    </div>
</div>
<div class="items">
    <div class="url" style="display: none">
        <a href="{{Request::root()}}/admincp/anime"></a>
    </div>
    <div class="item" style="color: red; font-size: 9pt">
        <a style="padding: 0px;"><b>CB</b></a> <a><b>Name</b></a>
    </div>
    @if(isSet($items))
        @foreach($items as $i)
            <div id="item-{{ $i->id }}" class="item">
                <input id="item-checked" type="checkbox" name="checked" value="{{ $i->id }}"> <a>{{ $i->name }}</a>
            </div>
        @endforeach
    @endif
</div>