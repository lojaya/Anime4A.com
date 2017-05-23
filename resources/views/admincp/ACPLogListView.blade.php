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
    </div>
</div>
<div class="items">
    <div class="url" style="display: none">
        <a href="@if(isSet($url)){{ $url }}@endif"></a>
    </div>
    <div class="item" style="color: red; font-size: 9pt">
        <div style="padding: 0px; display: inline-block; width: 15px;"><b>CB</b></div>
        <div style="padding: 0px; display: inline-block; width: 220px; margin-left: 5px;"><b>IP</b></div>
        <div style="padding: 0px; display: inline-block; width: 100px;"><b>Created At</b></div>
    </div>
    @if(isSet($items))
        @foreach($items as $i)
            <div id="item-{{ $i->id }}" class="item">
                <input id="item-checked" type="checkbox" name="checked" value="{{ $i->id }}" class="col0">
                <div class="col1" style="width: 220px">
                    <a>{{ $i->ip }}</a>
                </div>
                <div class="col2" style="width: 115px">
                    <a>@if($i->created_at){{ $i->created_at->format('d/m/Y h:i') }}@endif</a>
                </div>
            </div>
        @endforeach
    @endif
</div>