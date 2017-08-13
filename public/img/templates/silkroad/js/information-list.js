//列表鼠标经过样式
$(function(){
    $(".summary-list li").mousemove(function(){
        $(this).addClass("on");
    });
    $(".summary-list li").mouseleave(function(){
        $(this).removeClass("on");
    });

    $(".bor-trbs .list-ul li").mousemove(function(){
    		$(this).addClass("yang");
    });
    $(".bor-trbs .list-ul li").mouseleave(function(){
    		$(this).removeClass("yang");
    })
})
