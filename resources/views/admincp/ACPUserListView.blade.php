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
        <a href="@if(isSet($url)){{ $url }}@endif"></a>
    </div>
    <div class="item" style="color: red; font-size: 9pt">
        <div style="padding: 0px; display: inline-block; width: 15px;"><b>CB</b></div>
        <div style="padding: 0px; display: inline-block; width: 220px; margin-left: 5px;"><b>Userame</b></div>
        <div style="padding: 0px; display: inline-block; width: 100px;"><b>ID</b></div>
    </div>
    @if(isSet($items))
        @foreach($items as $i)
            <div id="item-{{ $i->id }}" class="item">
                <input id="item-checked" type="checkbox" name="checked" value="{{ $i->id }}" class="col0">
                <div class="col1" style="width: 220px">
                    <a>{{ $i->username }}</a>
                </div>
                <div class="col2" style="width: 115px">
                    <a>{{ $i->id }}</a>
                </div>
            </div>
        @endforeach
    @endif
</div>