(function($){
    $(".controler b,.controler2 a").click(function(){
        var T = $(this);
        if(T.attr("class")=="down") return false;
        J2ROLLING_ANIMATION.st({
            findObject : T,
            main : T.parent().parent().find(".pages"),
            pagSource : T.parent().parent().find(".controler b"),
            className : "down",
            duration : "slow",
            on : $(this)[0].tagName=="A" ? true : false
        });
        return false;
    });

    var J2SETTIME="", J2Time=true,J2ROLLING_ANIMATION = {
        init : function(){
            this.start();
            this.time();
        },
        st : function(o){
            if(J2Time){
                this.animate(o.findObject,o.main,o.className,o.duration,o.pagSource,o.on);
                J2Time = false;
            }
        },
        animate : function(T,M,C,S,P,O){
            var _prevDown = O ? P.parent().find("*[class='"+C+"']") : T.parent().find(T[0].tagName+"[class='"+C+"']"),
                    _prevIndex = _prevDown.index(),
                    _thisIndex = O ? (T.attr("class")=="next" ? _prevIndex+1 : _prevIndex-1) : T.index(),
                    _list = M.find(".item"),
                    p2n = 1;
            _prevDown.removeClass(C);
            if(O){
                if(_thisIndex==-1) _thisIndex=_list.size()-1;
                if(_thisIndex==_list.size()) _thisIndex=0;
                P.eq(_thisIndex).addClass(C);
            }else{
                T.addClass(C);
            }
            if(T.attr("class")=="prev" || _thisIndex<_prevIndex) p2n = false;
            if((T.attr("class")=="next" || _thisIndex>_prevIndex)&&T.attr("class")!="prev") p2n = true;

            !p2n ? _list.eq(_thisIndex).css("left",-M.width()) : '';
            _list.eq(_prevIndex).animate({left:p2n ? -M.width() : M.width()},S,function(){
                $(this).removeAttr("style");
                J2Time = true;
            });
            _list.eq(_thisIndex).animate({left:"0px"},S);
        },
        start : function(){
            $(".controler b, .controler2 a").mouseover(function(){
                window.clearInterval(J2SETTIME);
            }).mouseout(function(){
                J2ROLLING_ANIMATION.time();
            });
        },
        time : function(){
            J2SETTIME = window.setInterval(function(){
                var num = $(".controler b.down").index(),
                        _list = $(" .pages li");
                _list.eq(num).animate({"left":-$(" .pages").width()},"slow",function(){
                    $(this).removeAttr("style");
                    $(".controler b").removeClass("down").eq(num).addClass("down");
                });
                num++;
                if(num==_list.size()){
                    num=0;
                }
                _list.eq(num).animate({"left":"0px"},"slow");
            },5000);
        }
    };
    $("a").click(function(){
        $(this).blur();
    });

    J2ROLLING_ANIMATION.init();
})(this.jQuery);
//园区风光
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
    var sc = new Scroll(document.getElementById("thumb"));
}