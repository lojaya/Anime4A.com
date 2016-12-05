<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/8/2016
 * Time: 2:20 AM
 */
?>

@foreach ($headerItems as $i)
    <?php
    $tenphim = \App\Library\MyFunction::GetFormatedName($i->name);
    ?>
    <div>
        <a href="{{Request::root()}}/xem-phim/{{ $tenphim }}/{{ $i->id }}.a4a" data-toggle="popover-header-toggle-{{ $i->id }}" data-container="body">
            <img data-u="image" src="{{ $i->img }}" />
        </a>
        <div id="popover-header-toggle-{{ $i->id }}" style="display: none">{{ $i->name }}</div>
    </div>
@endforeach