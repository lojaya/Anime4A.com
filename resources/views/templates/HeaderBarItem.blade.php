

<?php
$MyFunc = new App\Library\MyFunction;
?>

@foreach ($headerItems as $i)
    <?php
    $tenphim = $MyFunc->nameFormat($i->name);
    ?>
    <div>
        <a href="{{Request::root()}}/xem-phim/{{ $tenphim }}/{{ $i->id }}.html" data-toggle="popover-header-toggle-{{ $i->id }}" data-container="body">
            <img data-u="image" src="{{ $i->img }}" />
        </a>
        <div id="popover-header-toggle-{{ $i->id }}" style="display: none">{{ $i->name }}</div>
    </div>
@endforeach