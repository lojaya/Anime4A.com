<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/8/2016
 * Time: 2:20 AM
 */
?>

@include('pages.userBox')

<div id="header">
    <div id="top_menu">
        <ul id="cssmenu" class="topmenu">
            <li class="switch"><label onclick="" for="css3menu-switcher"></label></li>
            <li class="topmenu"><a href="{{Request::root()}}" >Home</a></li>
            <li class="toproot"  ><a><span>Thể Loại</span></a>
                <ul>
                    @yield('header-menu-category')
                </ul>
            </li>
            <li class="toproot"><a><span>Quốc Gia</span></a>
                <ul>
                    @yield('header-menu-country')
                </ul>
            </li>
            <li class="toproot"><a><span>Năm Sản Xuất</span></a></li>
            <li class="topmenu"><a href="{{Request::root()}}/advanced-search.html">Tìm Kiếm Nâng Cao</a></li>
            <li class="topmenu"><a href="{{Request::root()}}" >Fanpage</a></li>
        </ul>
        <div id="utilitiesRegion">
            <div class="search_region">
                <form id="searchForm">
                    <fieldset>
                        <div class="input" style="width: 240px; background-color: rgb(232, 237, 241); padding-right: 15px;">
                            <input type="text" name="s" id="searchInput" value="@lang('messages.searchText')" style="color: rgb(180, 189, 196);">
                        </div>
                        <input type="submit" id="searchSubmit" value="">
                    </fieldset>
                </form>
                <div id="suggestions">
                    <p id="searchresults">
                    </p>
                </div>
            </div>
            <div class="login_region">

                @if(isSet($userSigned))
                    @if($userSigned->loginHash==hash('sha256', 'Anime4A Login Successful'))
                        <a id="btnLogout" href="{{Request::root()}}/logout">Đăng Xuất</a>
                        <script>
                            $(function () {
                                var logoutButton = $('#btnLogout');
                                logoutButton.on('click', function (e){
                                    e.preventDefault();
                                    var _url = $(this).attr('href');
                                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                                    $.ajax({
                                        url: _url,
                                        type: "post",
                                        data: {_token: CSRF_TOKEN},
                                        async: false,
                                        success: function(data){
                                            var temp = $.parseJSON(data);
                                            var completed = temp.completed;

                                            if(completed)
                                            {
                                                location.href = location.protocol + '//' + location.host + '/Anime4A';
                                            }
                                            else {
                                                var error = temp.error;
                                                alert(error);
                                            }
                                        }
                                    });

                                });
                            });
                        </script>
                    @else
                        <a id="btnLogin">Tài Khoản</a>
                    @endif
                @else
                    <a id="btnLogin">Tài Khoản</a>
                @endif

            </div>
        </div>
    </div>
    <div id="menu_space"></div>

    @yield('headerBar')

</div>
