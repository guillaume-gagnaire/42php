$(function(){
    $('.off-canvas-wrap').swipe({
        swipeRight: function(){
            $('.off-canvas-wrap').foundation('offcanvas', 'show', 'move-right');
        },
        swipeLeft: function(){
            $('.off-canvas-wrap').foundation('offcanvas', 'hide', 'move-right');
        }
    });
});