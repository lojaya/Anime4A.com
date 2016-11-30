

<?php
$MyFunc = new App\Library\MyFunction;
?>

<script>
    $(document).ready(function(){
        $('[data-toggle^="popover-sidebar"]').popover({
            trigger: "hover",
            html: true,
            placement: 'auto left',
            content: function() {
                return $('#'+$(this).attr('data-toggle')).html();
            }
        });
    });
</script>
@if(isSet($films))
    @foreach ($films as $i)
        <?php
        $tenphim = $MyFunc->nameFormat($i->name);
        ?>
        <li class="item" data-toggle="popover-sidebar-{{ $i->id }}" data-container="body">
            <a class="item_link" href="{{Request::root()}}/xem-phim/{{ $tenphim }}/{{ $i->id }}.html">
                <img src="{{ $i->img }}" class="item_thumb" onerror="this.onerror=null;this.src='http://localhost/images/noimg.jpg';" >
                <span class="name">{{ $i->name }}</span>
                <span class="view">Lượt xem: {{ $i->view_count }}</span>
                <span class="ep">Số Tập: {{ $i->episode_new }}/{{  $i->episode_total }}</span>
                <span class="desc">{{ $i->description }}</span>
            </a>
            <div id="popover-sidebar-{{ $i->id }}" style="display: none">
                <div class="popoverTitle">{{ $i->name }}</div>
                <div class="popoverContent">
                    <div>Số tập: {{ $i->episode_new }}/{{ $i->episode_total}}</div>
                    <hr>
                    <div>{{ $i->description }}</div>
                    <hr>
                    <div>Thể loại: </div>
                </div>
            </div>
        </li>
    @endforeach
@endif