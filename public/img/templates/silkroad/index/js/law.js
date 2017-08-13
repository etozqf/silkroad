/**
 * Created by 郭Sir on 2017/5/2.
 */
$(document).ready(function () {
    $(".law-container .menu").on("click","li>a",function () {
        $(this).parents("li").toggleClass("on");
    })
});