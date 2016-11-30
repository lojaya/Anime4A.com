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

<div id="userCP" style="display: none">
    <div class="overlay"></div>
    <div class="displayArea">
        <div class="bookmarks" tabindex="170">
            <span>Danh sách Anime đang theo dõi:</span>
            <ul id="userCPBookmarks">
                @if(isSet($bookmarks)&&!is_null($bookmarks))
                    @foreach($bookmarks as $i)
                        <?php
                        $tenphim = $MyFunc->nameFormat($MyFunc->getAnimeName($i->anime_id));
                        ?>
                        <hr>
                        <li><a href="{{Request::root()}}/xem-phim/{{ $tenphim }}/{{ $i->anime_id }}.html">{{ $MyFunc->getAnimeName($i->anime_id) }}</a><a class="delBtn" href="{{Request::root()}}/bookmark-delete-{{ $i->id }}">Xóa</a></li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>

<script>
</script>