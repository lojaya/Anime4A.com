<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/8/2016
 * Time: 2:20 AM
 */
?>

@foreach($items as $i)
    <a href="{{Request::root()}}/xem-phim/{{ \App\Library\MyFunction::GetFormatedName($i->name) }}/{{ $i->id }}.a4a">
        <img alt="" src="{{ $i->img }}"/>
        <span class="searchheading">{{ $i->name }}</span>
        <span><?php echo strip_tags($i->description);?></span>
    </a>
@endforeach