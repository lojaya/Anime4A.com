<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/8/2016
 * Time: 2:48 AM
 */
?>

<div id="sidebar">
    <?php
    // Side bar cho trang chủ
    if(isSet($viewmode)) {
        if ($viewmode == "home") {
            ?>
            <div class="newest_film">
                <div class="title">
                    Anime Mới Nhất
                </div>
                <ul class="sidebar_items">

                    <?php

                    foreach ($top5_new as $i) {
                        echo '<li class="item">';
                        echo '<a class="item_link" href="xem-thong-tin/' . nameFormat($i['name']) . '/' . $i['id'] . '.html">';
                        echo '<img src="' . $i['img'] . '" class="item_thumb">';
                        echo '<span class="name">' . $i['name'] . '</span>';
                        echo '<span class="view">Lượt xem: ' . $i['view_count'] . '</span>';
                        echo '<span class="ep">Số Tập: ' . $i['episode_new'] . '/' . $i['episode_total'] . '</span>';
                        echo '<span class="desc">' . $i['description'] . '</span>';
                        echo '</a>';
                        echo '</li>';
                    }
                    ?>

                </ul>
            </div>
            <div class="most_view">
                <div class="title">
                    Anime Xem Nhiều
                </div>
                <ul class="sidebar_items">
                    <?php
                    foreach ($top5_most as $i)
                    {
                        echo '<li class="item">';
                        echo '<a class="item_link" href="xem-thong-tin/'.nameFormat($i['name']).'/'.$i['id'].'.html">';
                        echo '<img src="'.$i['img'].'" class="item_thumb">';
                        echo '<span class="name">'.$i['name'].'</span>';
                        echo '<span class="view">Lượt xem: '.$i['view_count'].'</span>';
                        echo '<span class="ep">Số Tập: '.$i['episode_new'].'/'.$i['episode_total'].'</span>';
                        echo '<span class="desc">'.$i['description'].'</span>';
                        echo '</a>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
            <?php
        }
        // Side bar cho trang con
        else
        {
            ?>

            <div class="newest_film">
                <div class="title">
                    Anime Mới Nhất
                </div>
                <ul class="sidebar_items">

                    <?php
                    foreach ($top5_new as $i) {
                        echo '<li class="item">';
                        echo '<a class="item_link" href="xem-thong-tin/' . nameFormat($i['name']) . '/' . $i['id'] . '.html">';
                        echo '<img src="' . $i['img'] . '" class="item_thumb">';
                        echo '<span class="name">' . $i['name'] . '</span>';
                        echo '<span class="view">Lượt xem: ' . $i['view_count'] . '</span>';
                        echo '<span class="ep">Số Tập: ' . $i['episode_new'] . '/' . $i['episode_total'] . '</span>';
                        echo '<span class="desc">' . $i['description'] . '</span>';
                        echo '</a>';
                        echo '</li>';
                    }
                    ?>

                </ul>
            </div>

            <div class="most_view">
                <div class="title">
                    Anime Xem Nhiều
                </div>
                <ul class="sidebar_items">
                    <?php
                    foreach ($top5_most as $i)
                    {
                        echo '<li class="item">';
                        echo '<a class="item_link" href="xem-thong-tin/'.nameFormat($i['name']).'/'.$i['id'].'.html">';
                        echo '<img src="'.$i['img'].'" class="item_thumb">';
                        echo '<span class="name">'.$i['name'].'</span>';
                        echo '<span class="view">Lượt xem: '.$i['view_count'].'</span>';
                        echo '<span class="ep">Số Tập: '.$i['episode_new'].'/'.$i['episode_total'].'</span>';
                        echo '<span class="desc">'.$i['description'].'</span>';
                        echo '</a>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
            <?php
        }
    }

    ?>
</div>

