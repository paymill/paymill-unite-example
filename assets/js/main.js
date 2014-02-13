// ACTIVATE TOOLTIP / POPOVER

$(document).ready(function(){
var $body = $('body');
var timer;

$(window).on("scroll", function(){
    "use strict";
    if(!$body.hasClass('disable-hover')){
        $body.addClass('disable-hover');
    }
    timer = setTimeout(function(){
        $body.removeClass('disable-hover');
    },150);
});


    // Pushing Sidebar
    $(".PushSidebar").click(function(e){
        e.preventDefault();
        $body.toggleClass("pushed-body");
    })


// this is needed to hide the Sidebar if it is visible while screen-size changes
var eventFired = 0;
var breakpointTabletView = 992; // defined by BS3

$(window).on('resize', function() {
    "use strict";
    if (!eventFired) {
        if ($(window).width() < breakpointTabletView) {
            eventFired = 1;
            if($body.hasClass("pushed-body")){
                $body.removeClass('pushed-body');
            }
        }
    }else{
        if ($(window).width() >= breakpointTabletView) {
            eventFired = 0;
        }
    }
});

});