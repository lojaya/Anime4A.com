<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 11/12/2016
 * Time: 6:02 PM
 */
?>
<div class="items">
    @foreach($items as $i)
        <div class="vid-{{ $i->id }}">
            <input type="hidden" name="fansub_id" value="{{ $i->fansub_id }}">
            <input type="hidden" name="server_id" value="{{ $i->server_id }}">
            <a class="text">
                {{ $i->id }}
            </a>
        </div>
    @endforeach
</div>
<script>
    $(document).on("click","div[class^='vid-']",function(e) {
        e.preventDefault();

        if(!$(this).hasClass('active')) {
            var _url = $('#MainUrl').attr('href') + '/admincp/edit-video';
            var _id = $(this).attr('class').split('-')[1];

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: _url,
                type: "post",
                data: {'id': _id, _token: CSRF_TOKEN},
                async: false,
                success: function (data) {
                    $('#editorArea').html(data);
                }
            });
            $("#video_list>.items>.active").removeClass('active');
            $(this).addClass('active');
        }
    });
</script>