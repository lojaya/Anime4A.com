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
        <div class="ep__{{ $i->episode }}">
            <a class="text">
                {{ $i->episode }}
            </a>
        </div>
    @endforeach
</div>
<script>
    $(document).on("click","div[class^='ep__']",function(e) {
        e.preventDefault();

        if(!$(this).hasClass('active')) {

            var _url = $('#MainUrl').attr('href') + '/admincp/get-video';
            var _ep = $(this).attr('class').split('__')[1];

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: _url,
                type: "post",
                data: {'ep': _ep, _token: CSRF_TOKEN},
                async: false,
                success: function (data) {
                    $('#video_list').html(data);
                }
            });


            $("#episode_list>.items>.active").removeClass('active');
            $(this).addClass('active');
        }
    });
</script>