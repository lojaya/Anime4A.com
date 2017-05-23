<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/8/2016
 * Time: 2:20 AM
 */
?>

<script>
    $(document).ready(function(){
        $('[data-toggle^="popover-h-toggle"]').popover({
            trigger: "hover",
            html: true,
            placement: 'auto right',
            content: function() {
                return $('#'+$(this).attr('data-toggle')).html();
            }
        });
    });
</script>
@if(isSet($hotFilms))
    @foreach ($hotFilms as $i)
        @if($i->enabled)
            <?php
            $tenphim = \App\Library\MyFunction::GetFormatedName($i->name);
            ?>
            <div class="item" data-toggle="popover-h-toggle-{{ $i->id }}" data-container="body">
                <a href="{{Request::root()}}/xem-phim/{{ $tenphim }}/{{ $i->id }}.a4a" class="info">
                    <span class="play">►</span>
                    <div class="overlay">
                    </div>
                    <img class="thumb" src="{{ $i->img }}" alt="{{ $i->name }}" title="{{ $i->name }}" onerror="this.onerror=null;this.src='http://localhost/images/noimg.jpg';" style="display: block;">
                    <p class="luotxem">{{ $i->view_count }}</p>
                    <span class="episode" title="Số tập anime ">{{ \App\Library\MyFunction::ShowType($i->type, $i->episode_new, $i->episode_total) }}</span>
                </a>
                <div class="item_name">
                    <a href="{{Request::root()}}/xem-phim/{{ $tenphim }}/{{ $i->id }}.a4a" title="a" rel="bookmark" class="grid-title">
                        <h2>{{ $i->name }}</h2>
                    </a>
                </div>
            </div>
            <div id="popover-h-toggle-{{ $i->id }}" style="display: none">
                <div class="popoverTitle">{{ $i->name }}</div>
                <div class="popoverContent">
                    <div>Số tập: {{ $i->episode_new }}/@if($i->episode_total==0){{ '??' }}@else{{ $i->episode_total}}@endif</div>
                    <hr>
                    <div class="description"><?php echo $i->description; ?></div>
                    <hr>
                    <div>Thể loại: {{ \App\Library\MyFunction::GetCategoryNameString($i->category) }}</div>
                </div>
            </div>
        @endif
    @endforeach
@endif