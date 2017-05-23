/**
 * Created by Azure Cloud on 10/13/2016.
 */

/*** START HOMEPAGE scripts ***/
$(document).ready(function () {
    // Login actions
    var loginButton = $('#btnLogin');
    var closeButton = $('#userBox>.closeBtn');

    loginButton.on('click', function (){
        $('#userBox').fadeIn();
    });
    closeButton.on( "click", function (){
        $('#userBox').fadeOut();
    });
    $('#userBox>.overlay').on( "click", function (){
        $('#userBox').fadeOut();
    });

    // Khởi tạo vị trí menu, searchBox, loginBox và background overlay
    var headerBarOffsetRight = ($(window).width() - ($("#container").offset().left + $("#container").outerWidth()));
    var headerBarOffsetLeft = $("#container").offset().left;

    $('#cssmenu').css("margin-left", headerBarOffsetLeft - $(".topmenu>a").outerWidth());
    var loginButtonWidth = $(".login_region").outerWidth();
    $("#utilitiesRegion").css("padding-right", headerBarOffsetRight - loginButtonWidth);

    // START LOAD ANIMES DATA
    // Ajax load animes data
    // Danh sách anime mới cập nhật
    $('#homepage>.titleBar>div>.buttonA').addClass('selected');

    // END LOAD ANIMES DATA

});

/*** END HOMEPAGE scripts ***/

/*** START FILTER ***/
$(document).ready(function () {
    // Danh sách anime mới cập nhật
    $('#homepage>.titleBar>div>.buttonD').on( "click", function(e) {
        var films_data = getAnimesList($(this), 'NewUpdated', 'D');
        $('#homepage>.list_movies>.items').html(films_data);
    });
    $('#homepage>.titleBar>div>.buttonW').on( "click", function(e) {
        var films_data = getAnimesList($(this), 'NewUpdated', 'W');
        $('#homepage>.list_movies>.items').html(films_data);
    });
    $('#homepage>.titleBar>div>.buttonM').on( "click", function(e) {
        var films_data = getAnimesList($(this), 'NewUpdated', 'M');
        $('#homepage>.list_movies>.items').html(films_data);
    });
    $('#homepage>.titleBar>div>.buttonS').on( "click", function(e) {
        var films_data = getAnimesList($(this), 'NewUpdated', 'S');
        $('#homepage>.list_movies>.items').html(films_data);
    });
    $('#homepage>.titleBar>div>.buttonY').on( "click", function(e) {
        var films_data = getAnimesList($(this), 'NewUpdated', 'Y');
        $('#homepage>.list_movies>.items').html(films_data);
    });
    $('#homepage>.titleBar>div>.buttonA').on( "click", function(e) {
        var films_data = getAnimesList($(this), 'NewUpdated', 'A');
        $('#homepage>.list_movies>.items').html(films_data);
    });
});

function getAnimesList(selector, filterMode, filterType) {
    var films_data = null;

    var requestUrl = $('#MainUrl').attr('href');
    switch (filterMode){
        case 'NewUpdated':
            requestUrl += '/get-list-newUpdated';
            $('#homepage>.titleBar>div>.selected').removeClass('selected');
            break;
        default:
            ;
    }
    selector.addClass('selected');

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: requestUrl,
        type: "post",
        data: {'type': filterType, 'page': 1, _token: CSRF_TOKEN},
        async: false,
        success: function(data){
            films_data = data;
        }
    });
    return films_data;
}
// Search scripts
function lookup(inputString) {
    if(inputString.length === 0) {
        $('#suggestions').fadeOut(); // Hide the suggestions box
    } else {
        $('#suggestions').fadeIn(); // Show the suggestions box

        var requestUrl = $('#MainUrl').attr('href') + '/search';
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({ // Do an AJAX call
            url: requestUrl,
            type: "post",
            data: {'searchString': inputString, _token: CSRF_TOKEN},
            async: false,
            success: function(data){
                if(data)
                    $('#searchresults').html(data); // Fill the suggestions box
            }
        });
    }
}
/*** END FILTER ***/

/*** START VIEWPAGE scripts ***/
// Video View Page Animation
$(document).ready(function () {
    // Cuộn tới vị trí đầu Player
    var scrollTimeout = null;
    $(window).scroll(function () {
        if ( scrollTimeout !== null ) {
            clearTimeout( scrollTimeout );
        }
        scrollTimeout = setTimeout( scrollendHandler, 500 );
    });

});

function scrollendHandler() {
    // this code executes on "Scroll End"
    var pos = $(window).scrollTop();
    var offset = $("#player").offset().top;
    var d = Math.abs(pos - offset);
    if (d <= 50) {
        scrollToPlayer();
    }
    scrollTimeout = null;
}

// Script Zoom, Light On/Off or View Video Info
var playerZoom = false;
$(document).ready(function () {
    $(".videozoom").on( "click", function() {
        if($('.video_player').css('display')==='none')
        {
            alert('Hãy trở lại trang xem phim');
        }else {
            playerZoom = !playerZoom;
            if(playerZoom) {
                $(this).text("Thu Nhỏ");
                $(this).attr('title', "Thu Nhỏ");
                $(".video_player").css('width', '980px');
                $("#sidebar").css("margin-top", "0");
                $(".shadow").css("height", $(document).height());
            }
            else {
                $(this).text("Phóng To");
                $(this).attr('title', "Phóng To");
                $(".video_player").css('width', '680px');
                $("#sidebar").css("margin-top", "-420px");
                $(".shadow").css("height", $(document).height());
            }

            scrollToPlayer();
        }
    });

    $(".lightoff").on( "click", function() {
        $(".shadow").toggle();
        if($("#top_menu").css("z-index")>200)
        {
            $(this).text("Bật Đèn");
            $(this).attr('title', "Bật Đèn");
            $("#top_menu").css("z-index", 198);
        }else {
            $(this).text("Tắt Đèn");
            $(this).attr('title', "Tắt Đèn");
            $("#top_menu").css("z-index", 201);
        }
        scrollToPlayer();
    });

    $('.video_control>.item>.videoInfo').on( "click", function(e) {
        if($('.video_detail').css('display')==='none')
        {
            $('.video_player').css('display', 'none');
            $('.video_detail').fadeIn();
            $('#sidebar').css('margin-top', '0px');
            /*$('#sidebar').css('margin-top', '-' + $('.video_detail>.videoInfo').height() + 'px');*/
            $(this).addClass('videoPlay');
            $(this).text('Xem Phim');
            $(this).attr('title', "Xem Phim");
        }
        else
        {
            if($('.video_player').width()>=980){
                $('#sidebar').css('margin-top', '0px');
            }
            else
                $('#sidebar').css('margin-top', '-420px');
            $('.video_player').fadeIn();
            $('.video_detail').css('display', 'none');
            $(this).removeClass('videoPlay');
            $(this).text('Thông Tin');
            $(this).attr('title', "Thông Tin");
        }

        scrollToPlayer();
    });
});
function scrollToPlayer() {
    $('html,body').animate({
            scrollTop: $("#player").offset().top - 40},
        'fast');
}
/*** END VIEWPAGE scripts ***/

/*** START USERCP scripts ***/
$(document).ready(function () {
    // script for delete a bookmark
    $('.delBtn').bind('click', function (e) {
        e.preventDefault();
        var _url = $(this).attr('href');
        var _id = _url.substring(_url.lastIndexOf('-')+1);

        var requestUrl = $('#MainUrl').attr('href') + '/bookmark-delete';
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({ // Do an AJAX call
            url: requestUrl,
            type: "post",
            data: {'id': _id, _token: CSRF_TOKEN},
            async: false,
            success: function(data){
                alert('Xóa thành công.');
                $('#userCPBookmarks').html(data);
            }
        });
    });

    // scripts for save a bookmark
    $('.bookmarkBtn').bind('click', function (e) {
        var _url = window.location.href;
        var _id = _url.substring(_url.indexOf('xem-phim/'));
        _id = _id.substring(_id.indexOf('/')+1);
        _id = _id.substring(_id.indexOf('/')+1);
        if(_id.indexOf('/')>0)
            _id = _id.substring(0, _id.indexOf('/'));
        if(_id.indexOf('.')>0)
            _id = _id.substring(0, _id.indexOf('.'));


        var requestUrl = $('#MainUrl').attr('href') + '/bookmark';
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({ // Do an AJAX call
            url: requestUrl,
            type: "post",
            data: {'id': _id, _token: CSRF_TOKEN},
            async: false,
            success: function(data){
                if(data){
                    alert('Lưu thành công.');
                }
                else{
                    alert('Lưu thất bại hoặc đã lưu.');
                }
            }
        });
    });

    // scripts for redirect to next episode
    $('.nextEpBtn').bind('click', function (e) {
        var x = $('.epN'); //returns the matching elements in an array

        var _N = -1;
        for (i = 0; i < x.length; i++) {
            if($(x[i]).hasClass('active'))
            {
                _N = i + 1;
                break;
            }
        }
        if(_N>=0)
            $(location).attr('href', $(x[_N]).attr('href'));
    });

    $('.userCpBtn').bind('click', function (e) {
        userCPToggle();
    });

    $('#userCP>.overlay').bind('click', function (e) {
        userCPToggle();
    });

});
// scripts for show/hide user control panel
function userCPToggle() {
    if($('#userCP').css('display')==='none'){
        $("body").css("overflow", "hidden");
        $('#userCP').fadeIn();
        $('.userCpBtn').text("Đóng");
        $('.userCpBtn').attr('title', "Đóng");
    }
    else{
        $("body").css("overflow", "auto");
        $('#userCP').fadeOut();
        $('.userCpBtn').text("Danh sách Anime đang theo dõi");
        $('.userCpBtn').attr('title', "Danh sách Anime đang theo dõi");
    }
    $('html,body').animate({
            scrollTop: $("#header").offset().top},
        'fast');
}
/*** END USERCP scripts ***/