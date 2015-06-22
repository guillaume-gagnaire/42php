$(function(){
    $('#debugbar .cell').click(function(){
        $(this).find('.inner').toggle();
        if ($(this).find('.inner').length)
            $(this).toggleClass('active');
    });
});