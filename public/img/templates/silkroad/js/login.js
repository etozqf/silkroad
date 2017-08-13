//图片滚动
$(function(){
        var container = $(".img-box");
        if (container.length < 1) return;
        var ul = container.find("ul.img-ul");
        var li = ul.find("li");
        li.show();
        var prev = container.find(".prev-img");
        var next = container.find(".next-img");
        var conf =
        {
            container: container,
            ul: ul,
            li: li,
            prev: prev,
            next: next,
            slideDirec: true,								//	滚动方向（true=>左右，false=>上下，默认 true）
            autoRoll: true,								//	是否自动滑动
            slideStep: 1,
            autoRollTime: 10000						//	自动滑动时间间隔
        }
        SL.pictureSlide(conf);
})

//	浮起图层
$(function () {
    var code = $(".right-box");
    SL.dialog({
        container: code
    })
    code.css({margin: '0px'});
    $(".right-box .kf").mouseover(function () {
        $(this).addClass("up")
    });
    $(".right-box .kf").mouseout(function () {
        $(this).removeClass("up")
    });
    $(".right-box .fk").mouseover(function () {
        $(this).addClass("up02")
    });
    $(".right-box .fk").mouseout(function () {
        $(this).removeClass("up02")
    });
})
//弹出框效果
function popbox(obj) {
    if (!(0 <= navigator.userAgent.indexOf("Firefox"))) {
        var a = navigator.appName,
            b = navigator.appVersion.split(";")[1];
        if ("Microsoft Internet Explorer" == a && "MSIE6.0" == b || "Microsoft Internet Explorer" == a && "MSIE7.0" == b) {
            height_e = $(document.body).height();
            $('.exoperation').css("height", height_e);
        }
    }
    $('.exoperation').show();
    $(obj).fadeIn({
        height: 'toggle'
    });
    $(".next-button").click(function () {
        $(".pop_boxl").fadeOut({
            height: 'toggle'
        })
    });
}
function popclose(obj) {
    $('.exoperation').hide();
    $(obj).hide();
}
//弹出居中
$(function () {
    var d = $(window).height();
    var c = $(".pop_box,.pop_boxl").height();
    var b = (d - c) / 2
    $(".pop_box,.pop_boxl").attr("style", "margin-top:" + b + "px");
})



