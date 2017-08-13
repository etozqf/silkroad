//搜索
$(function () {
    function focusTxt() {
        var a0 = arguments[0][0],
            a1 = arguments[0][1];
        $('.' + a0).focus(function () {
            if (this.value == this.defaultValue) this.value = '';
        }).blur(function () {
            if (this.value == '') {
                this.value = this.defaultValue;
                if ($(this).is('.' + a1)) $(this).removeClass(a1);
            }
        }).keypress(function () {
            if (!$(this).is('.' + a1)) $(this).addClass(a1);
        });
    }
    focusTxt(['txt-focus']);

    // 收藏
    $(".info .fav").click(function(){
        $(this).toggleClass("on");
        if($(this).hasClass("on")){
            $(this).text("Unfavorite")
        }else{
            $(this).text("Bookmark")
        }
    });
    //报告列表页图片切换
    (function () {

        var container = $(".homebanner1");
        if (container.length < 1) return;
        var ul = container.find("ul.imgs");
        var li = ul.find("li");
        li.show();
        var prev = container.find(".p-left");
        var next = container.find(".p-right");
        var switchBtns = container.find("ul.nums li");

        //	滑动按钮配置
        var switchBtnConf =
        {
            selecter: switchBtns,
            event   : "click",
            activeFn: btn_ActiveFn
        }

        var conf =
        {
            container    : container,
            ul           : ul,
            li           : li,
            prev         : prev,
            next         : next,
            slideDirec   : true,								//	滚动方向（true=>左右，false=>上下，默认 true）
            autoRoll     : true,								//	是否自动滑动
            autoRollTime : 5000,								//	自动滑动时间间隔
            switchBtnConf: switchBtnConf						//	滑动按钮配置
        }
        SL.pictureSlide(conf);

        /**
         *        编号按钮，触发函数
         */
        function btn_ActiveFn(btns, i) {
            btns.attr("class", "");
            btns.eq(i).attr("class", "curr");
        }

    })();
});
$(function () {
    //热点新盘推荐
    var lis = $(".nav-box ul li");
    lis.hover(function () {
            els = $(this);
            els.addClass("curr").siblings().removeClass("curr");
        },
        function () {
            els = $(this);
            els.removeClass("curr");
        });
    $("#userLogin").hover(function () {
            els = $(this);
            els.addClass("click").siblings().removeClass("click");
        },
        function () {
            els = $(this);
            els.removeClass("click");
        });
})
//分页鼠标经过样式
$(function(){
    $(".page ul li a.number").mousemove(function(){
        $(this).addClass("on");
    });
    $(".page ul li a.number").mouseleave(function(){
        $(this).removeClass("on");
    });
})
//tab
function ind_tagitem(id, res) {
    $("#ultab").find(".on").removeClass("on");
    $(res).addClass("on");
    $("#" + id).show().siblings().hide();
}
//侧边栏下拉
$(function(){
    $(".list-ul li").mousemove(function(){
        var box=$(this).find(".list-hidden-box");
        if (box.length <= 0) {
        }else{
            $(this).find(".list-hidden-box").show();
            $(this).addClass("on");
        }
    });
    $(".list-ul li").mouseleave(function(){
        $(this).find(".list-hidden-box").hide();
        $(this).removeClass("on");
    });
})
//
$(function(){
    $(".tab-data-box .tab-data li").mousemove(function(){
        $(this).addClass("on");
    });
    $(".tab-data-box .tab-data li").mouseleave(function(){
        $(this).removeClass("on");
    });
})