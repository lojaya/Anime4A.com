<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/8/2016
 * Time: 2:20 AM
 */
?>

@foreach ($headerItems as $i)
    @if($i->enabled)
        <?php
        $tenphim = \App\Library\MyFunction::GetFormatedName($i->name);
        ?>
        <div data-p="112.50">
            <a href="{{Request::root()}}/xem-phim/{{ $tenphim }}/{{ $i->id }}.a4a">
                <img data-u="image" src="{{ $i->banner }}" />
                <div id="header-bar-item-name">{{ $i->name }}</div>
            </a>
        </div>
    @endif
@endforeach