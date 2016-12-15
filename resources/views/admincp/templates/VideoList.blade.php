<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 11/12/2016
 * Time: 6:02 PM
 */
?>
<div>
    <input type="hidden" id="editing_episode_id" value="{{ $episode_id }}">
    Name:
    <input type="text" id="editing_episode_name" value="{{ $episode_name }}" title="Name">
    <input type="button" id="editing_episode_saveBtn" value="Save">
    <input type="button" id="editing_episode_delBtn" value="Delete">
    <script>
        $('#editing_episode_saveBtn').bind('click', function (e) {
            e.preventDefault();

            // add new episode
            var _id = $('#editing_episode_id').val();
            var _value = $('#editing_episode_name').val();

            SaveEditingEpisode(_id, _value);
        });

        $('#editing_episode_delBtn').bind('click', function (e) {
            e.preventDefault();

            if(confirm('Are you sure?')){
                // delete episode
                var _id = $('#editing_episode_id').val();

                DeleteEpisode(_id);
            }
        });

        /**
         *
         * @param _id
         * @param value
         *
        */
        function SaveEditingEpisode(_id, value) {
            var _url = $('#MainUrl').attr('href') + '/admincp/episode/save';
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: _url,
                type: "post",
                data: {'episode_id': _id, 'episode': value, _token: CSRF_TOKEN},
                async: false,
                success: function(data){
                    // refesh episode list
                    getEpisodeList();
                }
            });
        }

        /**
         *
         * @param _id
         *
        */
        function DeleteEpisode(_id) {
            var _url = $('#MainUrl').attr('href') + '/admincp/episode/del';
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: _url,
                type: "post",
                data: {'episode_id': _id, _token: CSRF_TOKEN},
                async: false,
                success: function(data){
                    // refesh episode list
                    getEpisodeList();
                }
            });
        }
    </script>
</div>
<div class="items">
    @foreach($items as $i)
        <div class="vid__{{ $i->id }}">
            <input type="hidden" name="fansub_id" value="{{ $i->fansub_id }}">
            <input type="hidden" name="server_id" value="{{ $i->server_id }}">
            <a class="text">
                {{ $i->id }}
            </a>
        </div>
    @endforeach
</div>
<script>
    $(document).on("click","div[class^='vid__']",function(e) {
        e.preventDefault();

        if(!$(this).hasClass('active')) {
            var _url = $('#MainUrl').attr('href') + '/admincp/edit-video';
            var _id = $(this).attr('class').split('__')[1];

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