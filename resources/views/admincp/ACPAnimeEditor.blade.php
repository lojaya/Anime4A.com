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
<form action="{{Request::root()}}/admincp/anime/save" method="post" enctype="multipart/form-data" class="inputArea" id="InputForm">
    <div class="input_box" style="float: left">
        <div class="title">Anime Name: </div>
        <input type="text" name="name" value="@if(isset($anime)){{ $anime->name }}@endif">
    </div>
    <div class="input_box" style="float: left">
        <div class="title">Anime Name EN: </div>
        <input type="text" name="name_en" value="@if(isset($anime)){{ $anime->name_en }}@endif">
    </div>
    <div class="input_box" style="float: left">
        <div class="title">Anime Name JP: </div>
        <input type="text" name="name_jp" value="@if(isset($anime)){{ $anime->name_jp }}@endif">
    </div>
    <div class="input_box">
        <div class="title">Release Date: </div>
        <input type="date" name="release_date" class="datetime" value="@if(isset($anime)){{ $anime->release_date->format('Y-m-d') }}@endif">
    </div>
    <div class="input_box">
        <div class="title">Nhóm Phim: </div>
        <select class="category_value" name="type">
            @if(isSet($typeList))
                @foreach($typeList as $i)
                    <option value="{{ $i->id }}" @if(isSet($anime)) @if($i->id==$anime->type){{'selected'}}@endif @endif>{{ $i->name }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="input_box" id="category">
        <div class="title">Thể Loại: </div>
        <select class="category_value">
            <?php $cat = array(); if(isSet($anime)) $cat = explode(',', $anime->category);?>
            @if(isSet($categoryList))
                @foreach($categoryList as $i)
                    @if($myFunc->checkCategory($i->id,$cat))
                        <option value="{{ $i->id }}">{{ $i->name }}</option>
                    @endif
                @endforeach
            @endif
        </select>
        <input type="button" class="button_add" value="ADD">
        <div class="category_input">
            @foreach($cat as $i)
                @if(strlen(\App\DBCategory::find($i)['name'])>0)
                <div class="cat_{{ $i }}">
                    <input type="hidden" class="cat_value" name="category[]" value="{{ $i }}">
                    <h2 class="cat_text">
                            {{ \App\DBCategory::find($i)['name'] }}
                    </h2>
                    <input type="button" class="del_{{ $i }}" value="Xóa">
                </div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="input_box" style="float: left">
        <div class="title">Quốc Gia: </div>
        <select class="category_value" name="country">
            @if(isSet($countryList))
                @foreach($countryList as $i)
                    <option value="{{ $i->id }}" @if(isSet($anime)) @if($i->id==$anime->country){{'selected'}}@endif @endif>{{ $i->name }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="input_box" style="float: left">
        <div class="title">Đạo Diễn: </div>
        <select name="director">
            @if(isSet($directorList))
                @foreach($directorList as $i)
                    <option value="{{ $i->id }}">{{ $i->name }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="input_box" style="float: left">
        <div class="title">Nhân Vật: </div>
        <select name="char">
            @if(isSet($charList))
                @foreach($charList as $i)
                    <option value="{{ $i->id }}">{{ $i->name }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="input_box">
        <div class="title">Nhà Sản Xuất: </div>
        <select name="producer">
            @if(isSet($producerList))
                @foreach($producerList as $i)
                    <option value="{{ $i->id }}">{{ $i->name }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="input_box">
        <div class="title">Tập Mới Nhất: </div>
        <input type="number" name="episode_new" min="0" max="9999" value="@if(isset($anime)){{ $anime->episode_new }}@else{{0}}@endif">
    </div>
    <div class="input_box">
        <div class="title">Tổng Số Tập: </div>
        <input type="number" name="episode_total" min="0" max="9999" value="@if(isset($anime)){{ $anime->episode_total }}@else{{0}}@endif">
    </div>
    <div class="input_box">
        <div class="title">Hình Ảnh: </div>
        <div class="img_preview">
            <img src="@if(isset($anime)){{ $anime->img }}@endif" style="position: absolute;width: 150px;height: 200px;left: 600px;top: 0px;">
        </div>
        <input type="file" name="img" id="img">
    </div>
    <div class="input_box">
        <div class="title">Banner: </div>
        <div class="banner_preview">
            <img src="@if(isset($anime)){{ $anime->banner }}@endif" style="position: absolute;width: 340px;height: 190px;left: 415px;top: 205px;">
        </div>
        <input type="file" name="banner" id="banner">
    </div>
    <div class="input_box" style="float: left">
        <div class="title">Status: </div>
        <select name="status">
            @if(isSet($statusList))
                @foreach($statusList as $i)
                    <option value="{{ $i->id }}" @if(isSet($anime)) @if($i->id==$anime->status){{'selected'}}@endif @endif>{{ $i->name }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="input_box" style="float: left">
        <div class="title">Trailer: </div>
        <input type="text" name="trailer" value="@if(isset($anime)){{ $anime->trailer }}@endif">
    </div>
    <div class="input_box">
        <div class="title">Tag: </div>
        <input type="text" name="tag" value="@if(isset($anime)){{ $anime->tag }}@endif">
    </div>
    <div class="input_box">
        <div class="title">Mô Tả: </div>
        <textarea name="description">@if(isset($anime)){{ $anime->description }} @endif</textarea>
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