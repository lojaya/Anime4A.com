<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/8/2016
 * Time: 2:20 AM
 */
?>

<div id="userCP" style="display: none">
    <div class="overlay"></div>
    <div class="displayArea">
        <div class="bookmarks" tabindex="170">
            <span>Danh sách Anime đang theo dõi:</span>
            <ul id="userCPBookmarks">
                @include('templates.BookmarkItem')
            </ul>
        </div>
    </div>
</div>

<script>
</script>