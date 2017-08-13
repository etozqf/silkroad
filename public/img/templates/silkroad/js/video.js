/**
 * Created by Administrator on 2016/3/1.
 */
$(function(){
    $(".pic-summary li").mousemove(function(){
        $(this).find(".bg,.min-k").show();
        $(this).find(" h3").addClass("on");
    });
    $(".pic-summary li").mouseleave(function(){
        $(this).find(".bg,.min-k").hide();
        $(this).find(" h3").removeClass("on");
    });
})