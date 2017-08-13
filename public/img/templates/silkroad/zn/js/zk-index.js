//滚动新闻
(function(ns){
    function Scroll(element){
        var content = document.createElement("div");
        var container = document.createElement("div");
        var _this =this;
        var cssText = "position: absolute; visibility:hidden; left:0; white-space:nowrap;";
        this.element = element;
        this.contentWidth = 0;
        this.stop = false;

        content.innerHTML = element.innerHTML;

        //获取元素真实宽度
        content.style.cssText = cssText;
        element.appendChild(content);
        this.contentWidth = content.offsetWidth;

        content.style.cssText= "float:left;";
        container.style.cssText = "width: " + (this.contentWidth*2) + "px; overflow:hidden";
        container.appendChild(content);
        container.appendChild(content.cloneNode(true));
        element.innerHTML = "";
        element.appendChild(container);

        container.onmouseover = function(e){
            clearInterval(_this.timer);

        };

        container.onmouseout = function(e){
            _this.timer = setInterval(function(){
                _this.run();
            },30);


        };
        _this.timer = setInterval(function(){
            _this.run();
        }, 30);
    }

    Scroll.prototype = {

        run: function(){

            var _this = this;
            var element = _this.element;

            element.scrollLeft = element.scrollLeft + 1;

            if(element.scrollLeft >=  this.contentWidth ) {
                element.scrollLeft = 0;
            }
        }
    };
    ns.Scroll = Scroll;
}(window));
window.onload=function(){
    var sc = new Scroll(document.getElementById("scroll"));
}
////幻灯片
//$(function(){
//    var sWidth = $(".zx-home-banner").width();
//    var len = $(".silder_con li").length;
//    var index = 0;
//    var picTimer;
//
//    var btn = "<a class='prev'>Prev</a><a class='next'>Next</a>";
//    $("zx-home-banner").append(btn);
//
//    $(".silder_nav li").mouseenter(function() {
//        index = $(".silder_nav li").index(this);
//        showPics(index);
//    }).eq(0).trigger("mouseenter");
//
//    $(".zx-home-banner .prev,.zx-home-banner .next").hover(function(){
//        $(this).stop(true,false).animate(300);
//    },function() {
//        $(this).stop(true,false).animate(300);
//    });
//
//
//    // Prev
//    $(".zx-home-banner .prev").click(function() {
//        index -= 1;
//        if(index == -1) {index = len - 1;}
//        showPics(index);
//    });
//
//    // Next
//    $(".zx-home-banner .next").click(function() {
//        index += 1;
//        if(index == len) {index = 0;}
//        showPics(index);
//    });
//
//    //
//    $(".silder_con").css("width",sWidth * (len));
//
//    // mouse
//    $(".zx-home-banner").hover(function() {
//        clearInterval(picTimer);
//    },function() {
//        picTimer = setInterval(function() {
//            showPics(index);
//            index++;
//            if(index == len) {index = 0;}
//        },3000);
//    }).trigger("mouseleave");
//
//    // showPics
//    function showPics(index) {
//        var nowLeft = -index*sWidth;
//        $(".silder_con").stop(true,false).animate({"left":nowLeft},300);
//        $(".silder_nav li").removeClass("current").eq(index).addClass("current");
//        $(".silder_nav li").stop(true,false).animate(300).eq(index).stop(true,false).animate(300);
//    }
//});
 //图片滚动
$(function(){
        var sWidth = $(".scroll-img").width();
        var len = $(".scroll-img ul li").length;
        var index = 0;
        var picTimer;
        $(".scroll-img ul").css("width",sWidth * (len));
        // mouse
        $(".scroll-img").hover(function() {
            clearInterval(picTimer);
        },function() {
            picTimer = setInterval(function() {
                showPics(index);
                index++;
                if(index == len) {index = 0;}
            },3000);
        }).trigger("mouseleave");
        // showPics
        function showPics(index) {
            var nowLeft = -index*sWidth;
            $(".scroll-img ul").stop(true,false).animate({"left":nowLeft},300);

        }
    });

