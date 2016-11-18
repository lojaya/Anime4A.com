/**
 * Created by Azure Cloud on 11/6/2016.
 */
$(function(){

    var input = $('input#searchInput');
    var divInput = $('div.input');
    var width = divInput.width();
    var outerWidth = divInput.parent().width() - (divInput.outerWidth() - width) - 28;
    var submit = $('#searchSubmit');
    var txt = input.val();

    input.bind('focus', function() {
        if(input.val() === txt) {
            input.val('');
        }
        $(this).animate({color: "#000"}, 300); // text color
        $(this).parent().animate({
            width: '280px',
            backgroundColor: '#fff', // background color
            paddingRight: '43px'
        }, 300, function() {
            if(!(input.val() === '' || input.val() === txt)) {
                submit.fadeIn(300);
                $('#suggestions').fadeIn(); // Show the suggestions box
            }
        }).addClass('focus');
    }).bind('blur', function() {
        $('#suggestions').fadeOut(); // Hide the suggestions box
        $(this).animate({color: '#b4bdc4'}, 300); // text color
        $(this).parent().animate({
            width: '240px',
            backgroundColor: '#e8edf1', // background color
            paddingRight: '15px'
        }, 300, function() {
            if(input.val() === '') {
                input.val(txt)
            }
        }).removeClass('focus');
        submit.fadeOut(100);
    }).keyup(function() {
        lookup($(this).val());
        if(input.val() === '') {
            submit.fadeOut(300);
        } else {
            submit.fadeIn(300);
        }
    });

    submit.on('click', function (e) {
        e.preventDefault();
    })
});