/**
 * Created by Azure Cloud on 11/09/2016.
 */

/****************************
**    START ADMINCP scripts
*****************************/
$(document).ready(function () {
    // Login actions
    var loginButton = $('#btnLogin');
    loginButton.bind('click', function (){
        if($('.loginForm').is(":visible")){
            $('.loginForm').fadeOut();
            clearInterval(window.loginInterval);
        }
        else{
            $('.loginForm').fadeIn();
            window.loginInterval = setInterval(function (){
                if ($('.loginForm').is(":focus")||$('.loginForm>.id').is(":focus")||$('.loginForm>.password').is(":focus")) {

                }else{
                    $('.loginForm').fadeOut();
                    clearInterval(window.loginInterval);
                }
            }, 5000);
        }
    });
    // Khởi tạo vị trí và kích thước các vùng
    var _hListView = $(window).outerHeight() - $('#top_menu').outerHeight() - $('#footer').outerHeight();
    $('#listView').css('height', _hListView + 'px');
    $('#editorView').css('height', _hListView + 'px');
    $('#sideBar').css('height', _hListView + 'px');

    // Khởi tạo trang xem đầu tiên
    var path = $('#path>input').val();
    var data = getList(location.protocol + '//' + location.host + '/Anime4A/admincp/' + path);
    $('#listView').html(data);

    $('.btn').bind('click', function (e) {
        e.preventDefault();
        var url = $(this).find('a').attr('href');
        var data = getList(url);
        $('#listView').html(data);
        $('#editorView').html('');
    });


});
function getList(url) {
    var list = null;
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });
    $.ajax({
        url: url,
        type: "get",
        data: {'type': '', _token: CSRF_TOKEN},
        async: false,
        success: function(data){
            list = data;
        }
    });
    return list;
}
// List View Scripts
function refresh() {
    var _url = $('.url>a').attr('href');
    var data = getList(_url);
    $('#listView').html(data);
}
$(document).on("change","#btnRefresh",function(e) {
    refresh();
});
$(document).on("click","div[id^='item-']",function(e) {
    var _id = $(this).attr('id').split('-')[1];
    var _url = $('.url>a').attr('href');
    var data = _edit(_id, _url);
    $('#editorView').html(data);
});
$(document).on("click","#btnNew",function(e) {
    var _url = $('.url>a').attr('href');
    var data = _new(_url);
    $('#editorView').html(data);
});
$(document).on("click","#btnDel",function(e) {
    if (confirm('Are you sure?')) {
        var searchIDs = $("#item-checked:checked").map(function(){
            return $(this).val();
        }).get();
        var _url = $('.url>a').attr('href');
        var data = _del(searchIDs, _url);
        $('#listView').html(data);
    } else {
    }
});
function _new(_url) {
    var temp = null;
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: _url + '/new',
        type: "post",
        data: {_token: CSRF_TOKEN},
        async: false,
        success: function(data){
            temp = data;
        }
    });
    return temp;
}

function _del(id, _url) {
    var temp = null;
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: _url + '/del',
        type: "post",
        data: {'id': id, _token: CSRF_TOKEN},
        async: false,
        success: function(data){
            temp = data;
        }
    });
    return temp;
}

function _edit(id, _url) {
    var temp = null;
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: _url + '/edit',
        type: "post",
        data: {'id': id, _token: CSRF_TOKEN},
        async: false,
        success: function(data){
            temp = data;
        }
    });
    return temp;
}

// Editor View Script
$(document).on("change","#img",function(e) {
    var tmppath = URL.createObjectURL(e.target.files[0]);
    $(".img_preview>img").fadeIn("fast").attr('src',tmppath);
});
$(document).on("change","#banner",function(e) {
    var tmppath = URL.createObjectURL(e.target.files[0]);
    $(".banner_preview>img").fadeIn("fast").attr('src',tmppath);
});
$(document).on("click",".button_add",function() {
    $parent = $(this).parent();
    $name = $(this).parent().attr("id");
    if($parent.find(".category_value option").length>0)
    {
        $value = $parent.find(".category_value").val();
        $cattext = $parent.find(".category_value :selected").text();
        $parent.find(".category_input").append( "<div class='cat_"+$value+"'><input type='hidden' class='cat_value' name='"+$name+"[]' value='"+$value+"'><h2 class='cat_text'>"+$cattext+"</h2><input type='button' class='del_"+$value+"' value='Xóa'></div>" );
        $parent.find(".category_value").find('option:selected').remove().end();
    }
});
$(document).on("click","input[class^='del']",function() {
    $parent = $(this).parent();
    $parent2 = $parent.parent().parent();
    $value = $parent.find(".cat_value").attr("value");
    $cattext = $parent.find(".cat_text").text();

    $parent.remove();

    $parent2.find(".category_value").append($('<option>', {
        value: $value,
        text: $cattext
    }));
});

$(document).on("change","#anime_id",function(e) {
    $('#episode').empty();
    var _id = $(this).val();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: location.protocol + '//' + location.host + '/Anime4A/admincp/video/getEpisode',
        type: "post",
        data: {'id': _id, _token: CSRF_TOKEN},
        async: false,
        dataType: "json",
        success: function(data){
            $.each(data, function( key, value ) {
                $('#episode').append($('<option>', {
                    value: value.id,
                    text: value.episode
                }));
            });
        }
    });
});
/****************************
**    END ADMINCP scripts
*****************************/