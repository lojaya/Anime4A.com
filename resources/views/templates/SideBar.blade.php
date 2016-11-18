

<?php
$MyFunc = new App\Library\MyFunction;
?>

@foreach ($films as $i)
    <li class="item">
        <a class="item_link" href="{{Request::root()}}/xem-phim/{{ $MyFunc->nameFormat($i->name) }}/{{ $i->id }}.html">
            <img src="{{ $i->img }}" class="item_thumb" onerror="this.onerror=null;this.src='http://localhost/images/noimg.jpg';" >
            <span class="name">{{ $i->name }}</span>
            <span class="view">Lượt xem: {{ $i->view_count }}</span>
            <span class="ep">Số Tập: {{ $i->episode_new }}/{{  $i->episode_total }}</span>
            <span class="desc">{{ $i->description }}</span>
        </a>
    </li>
@endforeach