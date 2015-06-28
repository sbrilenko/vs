$(function()
{
    var sticky_navigation_offset_top = $('#sticky_navigation').offset().top-5;
    var sticky_navigation = function(){
        var scroll_top = $(window).scrollTop();
        if (scroll_top > sticky_navigation_offset_top) {
            if((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i))) {
                $('#sticky_navigation').css({ 'position': 'absolute', 'top':scroll_top, 'left':0});
                if(!$('#sticky_navigation').hasClass('background-white')) $('#sticky_navigation').addClass('background-white menu-shadow')
                }
            else
            {
                $('#sticky_navigation').css({ 'position': 'fixed', 'top':0, 'left':0 });
                if(!$('#sticky_navigation').hasClass('background-white')) $('#sticky_navigation').addClass('background-white menu-shadow')
            }
        } else {
            $('#sticky_navigation').css({ 'position': 'relative','top': 0});
            if($('#sticky_navigation').hasClass('background-white')) $('#sticky_navigation').removeClass('background-white menu-shadow')
        }
    };
    sticky_navigation();
    $(window).scroll(function() {
        sticky_navigation();
    });
})