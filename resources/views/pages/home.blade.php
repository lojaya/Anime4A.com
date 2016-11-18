<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/8/2016
 * Time: 2:49 AM
 */
?>

<div class="homepage">
    <div class="title">Anime vừa cập nhật</div>
    <div class="list_movies">
        <div class="items">
            <?php
            foreach ($films as $f){
                $tenphim = nameFormat($f['name']);
                echo '<div class="item">';
                echo '<a href="xem-thong-tin/'.$tenphim.'/'.$f['id'].'.html" class="info">';
                echo '<span class="play">&#9658;</span>';
                echo '<div class="overlay"></div>';
                echo '<img class="thumb" src="'.$f['img'].'" alt="'.$f['name'].'" title="'.$f['name'].'" onerror="this.onerror=null;this.src=\'http://localhost/images/noimg.jpg\';" style="display: block;">';
                echo '<p class="luotxem">'.$f['view_count'].'</p>';
                echo '<span class="episode" title="Số tập anime '.$f['name'].'">'.$f['episode_new'].'/'.$f['episode_total'].'</span></a>';
                echo '<div class="item_name">';
                echo '<a href="xem-thong-tin/'.$tenphim.'/'.$f['id'].'.html" title="'.$f['name'].'" rel="bookmark" class="grid-title">';
                echo '<h2>'.$f['name'].'</h2>';
                echo '</a>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</div>

