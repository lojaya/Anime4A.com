<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/8/2016
 * Time: 2:20 AM
 */
?>

<?php
$MyFunc = new App\Library\MyFunction;
?>
@if(isSet($bookmarks)&&!is_null($bookmarks))
    @foreach($bookmarks as $i)
        <?php
        $tenphim = $MyFunc->nameFormat($MyFunc->getAnimeName($i->anime_id));
        ?>
        <hr>
        <li><a href="{{Request::root()}}/xem-phim/{{ $tenphim }}/{{ $i->anime_id }}.html">{{ $MyFunc->getAnimeName($i->anime_id) }}</a><a class="delBtn" href="{{Request::root()}}/bookmark-delete-{{ $i->id }}">Xóa</a></li>
    @endforeach
@endif

<script>
    $('.delBtn').bind('click', function (e) {
        e.preventDefault();
        var _url = $(this).attr('href');
        var _id = _url.substring(_url.lastIndexOf('-')+1);

        var requestUrl = $('#MainUrl').attr('href') + '/bookmark-delete';
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({ // Do an AJAX call
            url: requestUrl,
            type: "post",
            data: {'id': _id, _token: CSRF_TOKEN},
            async: false,
            success: function(data){
                alert('Xóa thành công.');
                $('#userCPBookmarks').html(data);
            }
        });
    })
</script>