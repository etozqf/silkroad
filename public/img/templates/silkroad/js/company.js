/**
 * Created by Administrator on 2016/3/2.
 */
$(function(){
    $(".tbody tr").mousemove(function(){
        $(this).addClass("on");
    });
    $(".tbody tr").mouseleave(function(){
        $(this).removeClass("on");
    });
})