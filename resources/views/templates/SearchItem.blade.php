<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/8/2016
 * Time: 2:20 AM
 */
?>

<?php
$MyFunc = new App\Library\MyFunction;
?>
@foreach($items as $i)
    <a href="{{Request::root()}}/xem-phim/{{ $MyFunc->nameFormat($i->name) }}/{{ $i->id }}.a4a">
        <img alt="" src="{{ $i->img }}"/>
        <span class="searchheading">{{ $i->name }}</span>
        <span>{{ $i->description }}</span>
    </a>
@endforeach