<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/8/2016
 * Time: 2:20 AM
 */
?>

<div id="header">
    <div id="top_menu">
        <ul id="cssmenu" class="topmenu">
            <li class="switch"><label onclick="" for="css3menu-switcher"></label></li>
            <li class="topmenu"><a href="{{Request::root()}}/admincp" >Home</a></li>
            <li class="topmenu"><a href="{{Request::root()}}" >Anime4A</a></li>
        </ul>
        <div id="utilitiesRegion">
        </div>
    </div>
    <div id="menu_space"></div>

    @yield('headerBar')

</div>
