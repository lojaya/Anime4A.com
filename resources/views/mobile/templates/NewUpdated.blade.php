<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/8/2016
 * Time: 2:20 AM
 */
?>
<div class="paging">
    {{ $films->links() }}
</div>
@foreach ($films as $i)
    <?php
    $tenphim = \App\Library\MyFunction::GetFormatedName($i->name);
    ?>
    <div class="item" itemscope itemtype="http://schema.org/TVEpisode">
        <a itemprop="url" href="{{Request::root()}}/xem-phim/{{ $tenphim }}/{{ $i->id }}.a4a" class="info">
            <img itemprop="image" class="thumb" src="{{ $i->img }}" alt="{{ $i->name }}" title="{{ $i->name }}" onerror="this.onerror=null;this.src='http://localhost/images/noimg.jpg';" style="display: block;">
            <p class="luotxem">{{ $i->view_count }}</p>
            <span class="episode" title="Số tập anime ">{{ $i->episode_new }}/<span itemprop="episodeNumber">{{ $i->episode_total}}</span></span>
        </a>
        <div class="item_name">
            <a href="{{Request::root()}}xem-phim/{{ $tenphim }}/{{ $i->id }}.a4a" title="a" rel="bookmark" class="grid-title">
                <h2 itemprop="name">{{ $i->name }}</h2>
            </a>
        </div>
    </div>
@endforeach
<div class="paging">
    {{ $films->links() }}
</div>