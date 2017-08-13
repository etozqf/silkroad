/// <reference path="~/script/jquery-1.9.1-vsdoc.js" />

//遮罩层html
var MaskDiv = "<div id=\"b44dbfa9b481f3fb\" style=\"width: 1000px; height: 500px; display: none; z-index: 9003;\" class=\"window-mask\"></div>";
/*居中显示弹出窗口 isOpenMask为true说明需要开启遮罩层 isDrag为true说明可以拖动,MoveTitleID为拖动窗体的Title层ID*/
function showPanel(elementId, isOpenMask, isDrag, MoveTitleID) {

    if (isOpenMask == true) {

        //显示遮罩层，并根据页面大小设置遮罩层大小
        if ($("#b44dbfa9b481f3fb").length == 0) {
            $("body").append(MaskDiv);
        }
        $("#b44dbfa9b481f3fb").css("width", $(document).width() + "px").css("height", $(document).height() + "px").show();
    }

    //true 拖动 初始化
    if (isDrag == true) {
        var objdiv = document.getElementById(MoveTitleID);
        if (objdiv != undefined && objdiv != null) {
            rDrag.init(objdiv);
        }
    }

    $("#" + elementId).css("z-index", 10000);
    $("#" + elementId).css({
        position: 'absolute',
        left:'50%',
        top:'50%',
        left: ($(window).width() - $('#' + elementId).outerWidth()) / 2,
        top: ($(window).height() - $('#' + elementId).outerHeight()) / 2 + $(document).scrollTop()
    });
    $("#" + elementId).show();

   
}
/*关闭显示弹出窗口*/
function hidePanel(elementId, isOpenMask) {
    if (isOpenMask == true) {
        $("#b44dbfa9b481f3fb").hide();
    }
    $("#" + elementId).hide();
}

/*开启提示正在读取数据提示，isOpenMask为true说明同时开启遮罩层，popText为null或空或undefined表示“正在读取数据，请稍候...”*/
function openLoading(isOpenMask, popText) {

    if (popText == undefined || popText == null || popText == "") {
        popText = "正在读取数据，请稍候... ";
    }
    var pop = "<div id=\"725d9d62a1fad99f\" class=\"msgbox_layer_wrap\" style=\"display: none\">"
            + "<span style=\"z-index: 10000; color:#404040\" class=\"msgbox_layer\">"
            + "<span class=\"gtl_ico_clear\"></span>"
            + "<img alt=\"\" src=\""+IMG_URL+"/images/loading.gif\" /> " + popText + "<span class=\"gtl_end\"></span>"
            + "</span>"
            + "</div>";
    $("body").append(pop);
    if (isOpenMask == true) {

        //显示遮罩层，并根据页面大小设置遮罩层大小
        if ($("#b44dbfa9b481f3fb").length == 0) {
            $("body").append(MaskDiv);
        }
        $("#b44dbfa9b481f3fb").css("width", $(document).width() + "px").css("height", $(document).height() + "px").show();
    }
    var elementId = "725d9d62a1fad99f";
    $("#" + elementId).css("z-index", 10000);
    $("#" + elementId).css({
        position: 'absolute',
        left: '50%',
        top: '50%',
        left: ($(window).width() - $('#' + elementId).outerWidth()) / 2,
        top: ($(window).height() - $('#' + elementId).outerHeight()) / 2 + $(document).scrollTop()
    });
    $("#" + elementId).show();
}

/*关闭提示正在加载中提示...,isOpenMask为true说明同时关闭遮罩层*/
function colseLoading(isOpenMask) {
    if (isOpenMask == true) {
        $("#b44dbfa9b481f3fb").hide();
    }
    var elementId = "725d9d62a1fad99f";
    $("#" + elementId).hide();
    $("#" + elementId).remove();
}

/*显示提示  isTrue则显示正确图标，PopText为提示文字*/
function showPrompt(IsTrue, PopText) {

    var pop = "<div id=\"fa08a2c5dd6b9593\" class=\"msgbox_layer_wrap\" style=\"display: none\">"
            + "<span style=\"z-index: 10000; color: #B80101\" class=\"msgbox_layer\">"
            + "<span class=\"gtl_ico_clear\"></span>"
            + "<bb>操作成功！</bb><span class=\"gtl_end\"></span>"
            + "</span>"
            + "</div>";
    if (IsTrue == true) {
        //成功提示
        pop = "<div id=\"fa08a2c5dd6b9593\" class=\"msgbox_layer_wrap\" style=\"display: none\">"
            + "<span style=\"z-index: 10000; color: #B80101\" class=\"msgbox_layer\">"
            + "<span class=\"gtl_ico_succ\"></span>"
            + "<bb>" + PopText + "</bb><span class=\"gtl_end\"></span>"
            + "</span>"
            + "</div>";
    }
    else if (IsTrue == false) {
        //错误提示
        pop = "<div id=\"fa08a2c5dd6b9593\" class=\"msgbox_layer_wrap\" style=\"display: none\">"
            + "<span style=\"z-index: 10000; color: #B80101\" class=\"msgbox_layer\">"
            + "<span class=\"gtl_ico_fail\"></span>"
            + "<bb>" + PopText + "</bb><span class=\"gtl_end\"></span>"
            + "</span>"
            + "</div>";
    }
    else {
        //感叹号提示
        pop = "<div id=\"fa08a2c5dd6b9593\" class=\"msgbox_layer_wrap\" style=\"display: none\">"
            + "<span style=\"z-index: 10000; color: #B80101\" class=\"msgbox_layer\">"
            + "<span class=\"gtl_ico_hits\"></span>"
            + "<bb>" + PopText + "</bb><span class=\"gtl_end\"></span>"
            + "</span>"
            + "</div>";
    }
    var elementId = "fa08a2c5dd6b9593";
    $("body").append(pop);
    $("#" + elementId).css("z-index", 10000);
    $("#" + elementId).css({
        position: 'absolute',
        left: '50%',
        top: '50%',
        left: ($(window).width() - $('#' + elementId).outerWidth()) / 2,
        top: ($(window).height() - $('#' + elementId).outerHeight()) / 2 + $(document).scrollTop()
    });
    $("#" + elementId).show();

    /*延迟2秒，关闭提示*/
    window.setTimeout(function () {
        $("#" + elementId).hide();
        $("#" + elementId).remove();
    }, 2000);
}

/*
alert($(window).height()); //浏览器当前窗口可视区域高度 
alert($(document).height()); //浏览器当前窗口文档的高度 
alert($(document.body).height());//浏览器当前窗口文档body的高度 
alert($(document.body).outerHeight(true));//浏览器当前窗口文档body的总高度 包括border padding margin 
alert($(window).width()); //浏览器当前窗口可视区域宽度 
alert($(document).width());//浏览器当前窗口文档对象宽度 
alert($(document.body).width());//浏览器当前窗口文档body的高度 
alert($(document.body).outerWidth(true));//浏览器当前窗口文档body的总宽度 包括border padding margin 
 
*/

var rDrag = {

    o: null,

    init: function (o) {
        o.onmousedown = this.start;
    },
    start: function (e) {

        var o;
        e = rDrag.fixEvent(e);
        e.preventDefault && e.preventDefault();
        rDrag.o = o = this.parentNode;
        o.x = e.clientX - rDrag.o.offsetLeft;
        o.y = e.clientY - rDrag.o.offsetTop;

        rDrag.o.style.cursor = "move";

        document.onmousemove = rDrag.move;
        document.onmouseup = rDrag.end;

    },
    move: function (e) {//鼠标移动
        e = rDrag.fixEvent(e);
        var oLeft, oTop;
        oLeft = e.clientX - rDrag.o.x;
        oTop = e.clientY - rDrag.o.y;
        rDrag.o.style.left = oLeft + 'px';
        rDrag.o.style.top = oTop + 'px';

    },
    end: function (e) {//放松鼠标
        e = rDrag.fixEvent(e);
        rDrag.o.style.cursor = "auto";
        rDrag.o = document.onmousemove = document.onmouseup = null;

    },
    fixEvent: function (e) {
        if (!e) {
            e = window.event;
            e.target = e.srcElement;
            e.layerX = e.offsetX;
            e.layerY = e.offsetY;
        }

        return e;
    }
};
