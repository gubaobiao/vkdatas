!function () { 
    $('.operate').click(function(){
        $('.mycover').stop(false,true).fadeToggle();
        return false;
    });

    $('.mycover').click(function(e){
        var target = $(e.target);
        if(target.closest('.dialog').length == 0){
            $('.mycover').stop(false,true).fadeToggle();
        }
    });
    
}();