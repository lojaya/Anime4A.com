

<?php
$MyFunc = new App\Library\MyFunction;
?>
<script>
    $(document).ready(function(){
        $('[data-toggle^="popover-toggle"]').popover({
            trigger: "hover",
            html: true,
            placement: 'auto right',
            content: function() {
                return $('#'+$(this).attr('data-toggle')).html();
            }
        });
    });
</script>
<div class="paging">
    {{ $films->links() }}
</div>
@foreach ($films as $i)
    <?php
    $tenphim = $MyFunc->nameFormat($i->name);
    ?>
    <div class="item" data-toggle="popover-toggle-{{ $i->id }}" data-container="body">
        <a href="{{Request::root()}}/xem-phim/{{ $tenphim }}/{{ $i->id }}.html" class="info">
            <span class="play">►</span>
            <div class="overlay">
            </div>
            <img class="thumb" src="{{ $i->img }}" alt="{{ $i->name }}" title="{{ $i->name }}" onerror="this.onerror=null;this.src='http://localhost/images/noimg.jpg';" style="display: block;">
            <p class="luotxem">{{ $i->view_count }}</p>
            <span class="episode" title="Số tập anime ">{{ $i->episode_new }}/{{ $i->episode_total}}</span>
        </a>
        <div class="item_name">
            <a href="{{Request::root()}}xem-phim/{{ $tenphim }}/{{ $i->id }}.html" title="a" rel="bookmark" class="grid-title">
                <h2>{{ $i->name }}</h2>
            </a>
        </div>
    </div>
    <div id="popover-toggle-{{ $i->id }}" style="display: none">
        <div class="popoverTitle">{{ $i->name }}</div>
        <div class="popoverContent">
            <div>Số tập: {{ $i->episode_new }}/{{ $i->episode_total}}</div>
            <hr>
            <div>{{ $i->description }}</div>
            <hr>
            <div>Thể loại: </div>
        </div>
    </div>
@endforeach
<div class="paging">
    {{ $films->links() }}
</div>