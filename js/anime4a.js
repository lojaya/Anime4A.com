/**
 * Created by Azure Cloud on 10/13/2016.
 */

/****************************
**    START HOMEPAGE scripts
*****************************/
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

    // Danh sách anime xem nhiều
    films_data = getAnimesList($('#sidebar>.most_view>.titleBar>div>.buttonM'), 'MostViewList', 'M');
    $('#sidebar>.most_view>.sidebar_items').html(films_data);
    // END LOAD ANIMES DATA

});

/****************************
**    END HOMEPAGE scripts
*****************************/

/****************************
**    START FILTER
*****************************/
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

    // Danh sách anime mới nhất
    //
    $('#sidebar>.newest_film>.titleBar>div>.buttonD').on( "click", function(e) {
        var films_data = getAnimesList($(this), 'NewestList', 'D');
        $('#sidebar>.newest_film>.sidebar_items').html(films_data);
    });
    $('#sidebar>.newest_film>.titleBar>div>.buttonW').on( "click", function(e) {
        var films_data = getAnimesList($(this), 'NewestList', 'W');
        $('#sidebar>.newest_film>.sidebar_items').html(films_data);
    });
    $('#sidebar>.newest_film>.titleBar>div>.buttonM').on( "click", function(e) {
        var films_data = getAnimesList($(this), 'NewestList', 'M');
        $('#sidebar>.newest_film>.sidebar_items').html(films_data);
    });
    $('#sidebar>.newest_film>.titleBar>div>.buttonS').on( "click", function(e) {
        var films_data = getAnimesList($(this), 'NewestList', 'S');
        $('#sidebar>.newest_film>.sidebar_items').html(films_data);
    });
    $('#sidebar>.newest_film>.titleBar>div>.buttonY').on( "click", function(e) {
        var films_data = getAnimesList($(this), 'NewestList', 'Y');
        $('#sidebar>.newest_film>.sidebar_items').html(films_data);
    });

    // Danh sách anime xem nhiều
    //
    $('#sidebar>.most_view>.titleBar>div>.buttonD').on( "click", function(e) {
        var films_data = getAnimesList($(this), 'MostViewList', 'D');
        $('#sidebar>.most_view>.sidebar_items').html(films_data);
    });
    $('#sidebar>.most_view>.titleBar>div>.buttonW').on( "click", function(e) {
        var films_data = getAnimesList($(this), 'MostViewList', 'W');
        $('#sidebar>.most_view>.sidebar_items').html(films_data);
    });
    $('#sidebar>.most_view>.titleBar>div>.buttonM').on( "click", function(e) {
        var films_data = getAnimesList($(this), 'MostViewList', 'M');
        $('#sidebar>.most_view>.sidebar_items').html(films_data);
    });
    $('#sidebar>.most_view>.titleBar>div>.buttonS').on( "click", function(e) {
        var films_data = getAnimesList($(this), 'MostViewList', 'S');
        $('#sidebar>.most_view>.sidebar_items').html(films_data);
    });
    $('#sidebar>.most_view>.titleBar>div>.buttonY').on( "click", function(e) {
        var films_data = getAnimesList($(this), 'MostViewList', 'Y');
        $('#sidebar>.most_view>.sidebar_items').html(films_data);
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
        case 'NewestList':
            requestUrl += '/get-list-newestAnime';
            $('#sidebar>.newest_film>.titleBar>div>.selected').removeClass('selected');
            break;
        case 'MostViewList':
            requestUrl += '/get-list-mostView';
            $('#sidebar>.most_view>.titleBar>div>.selected').removeClass('selected');
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
/****************************
**   END FILTER
*******************************/

/****************************
**   START VIEWPAGE scripts
*****************************/
// Video View Page Animation
$(document).ready(function () {
    // Cuộn tới vị trí đầu Player
    $(window).scroll(function () {
        $(this).delay(1000).queue(function () {

            var pos = $(window).scrollTop();
            var offset = $("div#player").offset().top;
            if (Math.abs(pos - offset) <= 50) {
                $('html,body').animate({
                        scrollTop: offset - 40
                    },
                    'fast');
            }

            $(this).dequeue();
        });
    });
});

// Script Zoom or Light Off Player
var playerZoom = false;
$(document).ready(function () {
    $(".videozoom").on( "click", function() {
        playerZoom = !playerZoom;
        if(playerZoom) {
            $(this).text("Thu Nhỏ");
            $(this).attr('title', "Thu Nhỏ");
            $("#player").attr('width', 980);
            $("#player").attr('height', 572);
            $("#sidebar").css("margin-top", "0");
            $(".shadow").css("height", $(document).height());
        }
        else {
            $(this).text("Phóng To");
            $(this).attr('title', "Phóng To");
            $("#player").attr('width', 680);
            $("#player").attr('height', 480);
            $("#sidebar").css("margin-top", "-480px");
            $(".shadow").css("height", $(document).height());
        }
        $('html,body').animate({
                scrollTop: $("#player").offset().top - 40},
            'slow');
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
    });

    $('.video_control>.item>.video_info').on( "click", function(e) {
        if($('.video_detail').css('display')==='none')
        {
            $('.video_player').fadeOut();
            $('.video_detail').fadeIn();
            $('#sidebar').css('margin-top', '-' + $('.video_detail').height() + 'px');
            $(this).text('Xem Phim');
            $(this).attr('title', "Xem Phim");
        }
        else
        {
            $('.video_player').fadeIn();
            $('.video_detail').fadeOut();
            $('#sidebar').css('margin-top', '-' + $('.video_player').height() + 'px');
            $(this).text('Thông Tin');
            $(this).attr('title', "Thông Tin");
        }
    });
});

/*****************************
**   END VIEWPAGE scripts
******************************/