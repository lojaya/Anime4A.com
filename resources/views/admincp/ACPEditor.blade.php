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
<form action="@if(isSet($url)){{ $url.'/save' }}@endif" method="post" enctype="multipart/form-data" id="inputArea">
    <div class="input_box">
        <div class="title">Name: </div>
        <input type="text" name="name" style="width: 500px;" value="@if(isSet($data)){{ $data->name }}@endif">
    </div>
    <div class="input_box">
        <div class="title">Description: </div>
        <textarea name="description">@if(isset($data)){{ $data->description }} @endif</textarea>
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