// JavaScript Document
var _cmstop_ = {
    addEvent : function(oTarget, fnHandler, sEventType){
        if (oTarget.addEventListener) {
            oTarget.addEventListener(sEventType, fnHandler, false);
        } else if (oTarget.attachEvent) {
            oTarget.attachEvent("on" + sEventType, fnHandler);
        } else {
            oTarget["on" + sEventType] = fnHandler;
        }
    },
    removeEvent : function(oTarget, fnHandler, sEventType) {
        if (oTarget.removeEventListener) {
            oTarget.removeEventListener(sEventType, fnHandler, false);
        } else if (oTarget.detachEvent) {
            oTarget.detachEvent("on" + sEventType, fnHandler);
        } else { 
            oTarget["on" + sEventType] = null;
        }
    },
    bind : function(object, fun) {
        return function() {
            return fun.apply(object, arguments);
        }
    },
    bindAsEventListener : function(object, fun) {
        return function(event) {
            return fun.call(object, (event || window.event));
        }
    },
    extend : function(destination,source){
        for(var o in source)
            destination[o] = source[o];
        return destination;
    },
    preventDefault : function(event){
        if(event.preventDefault){
            event.preventDefault();
        }else{
            event.returnValue = false;  
        }   
    },
    getDetail : function(event){
        var detail = 0;
        if(event.wheelDelta){
            detail = - event.wheelDelta / 40;   
        }else{
            detail = event.detail;  
        }   
        return detail;
    },
    getWhich : function(event){
        if ( event.which == null && (event.charCode != null || event.keyCode != null) ) {
            return event.charCode != null ? event.charCode : event.keyCode;
        }       
    },
    get : function(o){
        return document.getElementById(o);  
    },
    getPos : function (e) {
        e = e || window.event;
        var x = e.pageX || (e.clientX + (document.documentElement.scrollLeft || document.body.scrollLeft));
        var y = e.pageY || (e.clientY + (document.documentElement.scrollTop || document.body.scrollTop));
        
        return { 'pageX':x,'pageY':y };
    },
    getS : function (obj) {
        var iTop = obj.offsetTop;
        var iLeft = obj.offsetLeft;
        while (obj.offsetParent)
        {
            iTop += obj.offsetParent.offsetTop;
            iLeft += obj.offsetParent.offsetLeft;
            obj = obj.offsetParent;
        }
        return {top:iTop, left:iLeft}   
    },
    each : function(array, callback, thisObject){
        if(array.forEach){
            array.forEach(callback, thisObject);
        }else{
            for (var i = 0, len = array.length; i < len; i++) { callback.call(thisObject, array[i], i, array);                              }
        }
    },
    getStyle : function( elem, name ) {
         if (elem.style[name])
             return elem.style[name];
         else if (elem.currentStyle)
             return elem.currentStyle[name];
         else if (document.defaultView && document.defaultView.getComputedStyle) {
             name = name.replace(/([A-Z])/g,"-$1");
             name = name.toLowerCase();
             var s = document.defaultView.getComputedStyle(elem,"");
             return s && s.getPropertyValue(name);
         } else
         return null;
    }   
};

/*
工具
*/
var Util = {};
/*
拖拽组件

*/
Util.Drag = function(container, drag, options){
    this.init(container, drag,options); 
}
Util.Drag.prototype = {
    constructor : Util.Drag,
    init : function(container,drag,options){
        options = options || {};
        this.container = container; 
        this.drag = drag;
        this.acceleration = options.acceleration || 1;
        this.x = 0;
        this.y = 0;
        this.restrict = options.restrict || false;
        this.restrictX = options.restrictX || false;
        this.restrictY = options.restrictY || false;
        this.Max = {
            top : 9999999,
            left : 9999999
        };  
        
        this.Min = {
            top : 0,
            left : 0
        };
        
        if(this.restrict){
            this.Max.top = this.container.clientHeight - this.drag.offsetHeight;
            this.Max.left = this.container.clientWidth - this.drag.offsetWidth;
        }
        
        this.startFunc = options.startFunc || function(){};
        this.moveFunc = options.moveFunc || function(){};
        this.endFunc = options.endFunc || function(){};
        
        this.Move = _cmstop_.bindAsEventListener(this,this.move);
        this.Stop = _cmstop_.bindAsEventListener(this,this.stop);
        this.drag.style.cursor = "pointer";    
        _cmstop_.addEvent(this.drag,_cmstop_.bindAsEventListener(this,this.start),"mousedown");
    },
    start : function(E){    
        this.x = E.clientX - this.drag.offsetLeft;
        this.y = E.clientY - this.drag.offsetTop;       
        _cmstop_.addEvent(document,this.Move,"mousemove");
        _cmstop_.addEvent(document,this.Stop,"mouseup");
        this.startFunc.call(this,E, this.container);
        if(document.all){
            _cmstop_.addEvent(this.drag, this.Stop, "losecapture");
            this.drag.setCapture();
            E.cancelBubble = true;
        }else{
            _cmstop_.addEvent(window, this.Stop, "blur");
            E.preventDefault();
        }
    },
    move : function(E){
        window.getSelection ? window.getSelection().removeAllRanges() : document.selection.empty();
        this.moveFunc.call(this,E, this.drag, this.container);
        var tempLeft = E.clientX - this.x,
            tempTop = E.clientY - this.y;
        if(tempLeft % this.acceleration == 0){
            var left =  Math.min(Math.max(tempLeft,this.Min.left),this.Max.left);
            if(!this.restrictX){
                this.drag.style.left = left + "px";
            }
        }
        if(tempTop % this.acceleration == 0){
            var top = Math.min(Math.max(tempTop, this.Min.top),this.Max.top);
            if(!this.restrictY){
                this.drag.style.top = top + "px";
            }   
        }       
    },
    stop : function(E){
        _cmstop_.removeEvent(document,this.Move,"mousemove");
        _cmstop_.removeEvent(document,this.Stop,"mouseup");
        this.endFunc.call(this,E, this.container);
        if(document.all){
            _cmstop_.removeEvent(this.drag, this.Stop, "losecapture");
            this.drag.releaseCapture();
            E.cancelBubble = true;
        }else{
            _cmstop_.removeEvent(window, this.Stop, "blur");
        }
    }
};

/*模拟滚动条*/
function v4Scroll(options){
    this.init(options);
}

v4Scroll.prototype = {
    constructor : v4Scroll,
    init : function(options){
        options = _cmstop_.extend({
            icontainer : "",
            idrag : "",
            plusBtn : "",
            reduceBtn : "",
            panel : "",
            content : "",
            direction : "top",
            acceleration : 5,
            sliderAcc : 1
        }, options || {});
        this.icontainer = _cmstop_.get(options.icontainer);
        this.idrag = _cmstop_.get(options.idrag);
        if(options.plusBtn){
            this.plusBtn = _cmstop_.get(options.plusBtn);
            this.reduceBtn = _cmstop_.get(options.reduceBtn);         
        }   
        this.direction = options.direction;
        if(options.content){
            this.content = _cmstop_.get(options.content);
            this.panel = _cmstop_.get(options.panel);
            
            this.maxSize = {
                "left" : this.panel.scrollWidth - this.content.clientWidth,
                "top" : this.panel.scrollHeight - this.content.clientHeight
            };
        }
        this.acceleration = options.acceleration;
        this.sliderAcc = options.sliderAcc;
        this.Max = {
            top : this.icontainer.clientHeight - this.idrag.offsetHeight,
            left : this.icontainer.clientWidth - this.idrag.offsetWidth     
        };
        this.Min = {
            top : 0,
            left : 0            
        };
        this.timer = null;
        this.dec = -1;
        this.isScroll = false;
        this.isDragAble = false;
        this.addEvent();
    },
    addEvent : function(){
        var self = this,
            isFireFox = navigator.userAgent.indexOf('Firefox') >= 0;
        self.fixedBug();
        if(self.plusBtn){
            _cmstop_.addEvent(self.plusBtn, function(){
                self.goToward("up", self);
            }, "mousedown");
            _cmstop_.addEvent(self.plusBtn, function(){
                clearTimeout(self.timer);
            }, "mouseup");  
            _cmstop_.addEvent(self.reduceBtn, function(){
                self.goToward("down", self);
            }, "mousedown");
            _cmstop_.addEvent(self.reduceBtn, function(){
                clearTimeout(self.timer);
            }, "mouseup");                      
        }
        _cmstop_.addEvent(self.icontainer, function(event){
            self.isDragAble || self.mouseClick(event, self);
        }, "click");
        
        _cmstop_.addEvent(self.icontainer, function(event){
            self.scrollFunc(event, self);
        }, isFireFox ? "DOMMouseScroll" : "mousewheel");        
        
        _cmstop_.addEvent(self.icontainer, function(event){
            self.keydown(event, self);
        }, "keydown");
        
        new Util.Drag(self.icontainer, self.idrag, {
            startFunc : function(E, $obj){
                self.isDragAble = true;
            },
            restrict : true, 
            restrictX : self.direction == "top" ? true : false, 
            restrictY : self.direction == "top" ? false : true,
            moveFunc : function(E, idrag, icontainer){
                if(self.content){
                    self.callback(parseInt(_cmstop_.getStyle(idrag, self.direction)), self.Max[self.direction]);
                }
            },
            endFunc : function(E){      
                setTimeout(function(){
                    self.isDragAble = false;
                },10);
            },
            acceleration : self.sliderAcc
        });     
    },
    run : function (self){
        var self = self || this,        
            style = self.idrag.style;
        if(self.timer){
            clearTimeout(self.timer);
        }
        var acc = self.dec > 0 ? self.acceleration : - self.acceleration;
        var posUp = Math.min(Math.max(parseInt(_cmstop_.getStyle(self.idrag, self.direction)) + acc,self.Min[self.direction]),self.Max[self.direction]);
        if(self.content){
            self.callback(posUp, self.Max[self.direction]);
        }
        style[self.direction] = posUp + "px";
        if(!self.isScroll){
            var arg = arguments.callee;
            self.timer = setTimeout(function(){
                arg.call(self);
            }, 10);
        }   
    },  
    callback : function(distance){
        this.content[this.direction == "top" ? "scrollTop" : "scrollLeft"] = (distance / this.Max[this.direction]) * this.maxSize[this.direction];
        this.fix(distance); 
    },
    fix : function(distance){
        if(distance >= this.Max[this.direction]){
            this.content[this.direction == "top" ? "scrollTop" : "scrollLeft"] = this.maxSize[this.direction];
        }       
    },  
    goToward : function (direction, self){
        self.isScroll = false;
        if(direction == "down"){
            self.dec = 10;
            self.run(self);
        }else{
            self.dec = -10;
            self.run(self);
        }       
    },
    mouseClick : function(event, self){
        var pos = _cmstop_.getPos(event),
            offset = _cmstop_.getS(self.idrag),
            dis = pos[self.direction == "top" ? "pageY" : "pageX"] - offset[self.direction],
            top = parseInt(_cmstop_.getStyle(self.idrag, self.direction)),
            lockTop = Math.min(Math.max(Math.ceil(dis) + top - self.idrag[self.direction == "top" ? "offsetHeight" : "offsetWidth"] / 2,self.Min[self.direction]),self.Max[self.direction]);        
        self.idrag.style[self.direction] = lockTop + "px";
        if(self.content){
            self.callback(parseInt(_cmstop_.getStyle(self.idrag, self.direction)), self.Max[self.direction]); 
        }
        _cmstop_.preventDefault(event);   
    },  
    scrollFunc : function scrollFunc(event, self){
        self.dec = _cmstop_.getDetail(event);
        self.isScroll = true;
        self.run();
        _cmstop_.preventDefault(event);
    },  
    keydown : function scrollFunc1(event, self){
        var which = _cmstop_.getWhich(event);
        if(which === 38){
            self.dec = - 10;
        }
        
        if(which === 40){
            self.dec = 10;
        }
        self.isScroll = true;
        self.run();
        _cmstop_.preventDefault(event);
    },      
    fixedBug : function(){
        var self = this;
        this.icontainer.tabIndex = -1;
        document.all || (this.icontainer.style.outline = "none");
        if(!document.all){
            _cmstop_.addEvent(this.icontainer, function(){
                self.icontainer.focus();
            }, "mouseover");
            _cmstop_.addEvent(this.icontainer, function(){
                self.icontainer.blur();
            }, "mouseout");         
        }   
    }
};

var gallery = function(config) {

    var waterfullConf = config['waterfull'] || {};

    if ($(".gal_scrl_main").length <= 0) {
        return false;
    }

    var ul = $(".gal_scrl_main")[0],
        list = ul.getElementsByTagName("li"),
        len = list.length;

    $('.gal_list_thumb', $(ul)).width($(list[0]).width() * len);

    var gal_element = $('.gallery')
    , gal_border = $('.gallery_warp_border');

    var iscroll = new v4Scroll(config);

    var intervalD = Math.ceil( ul.scrollWidth / len ),
        intervalS = Math.ceil( iscroll.Max.left / len ),
        index = 1,
        photoView = $('.gal_photo_view'),
        photoPrev = _cmstop_.get("photoPrev"),
        photoNext = _cmstop_.get("photoNext"),
        photo = _cmstop_.get("photo"),
        photoIndex = $(".photo_index .current"),
        photoDesc = $('.photo_esc p'),
        photoCount = $('.gal_count');

        // photoCount.text(len);

    var autoT
    , autostatus = 0
    , speed = 5000
    , oPlay = $('.autobtn a', gal_element);


    var allBtn = $('.viewallbtn .view')[0]
    , waterfullPanel = $('.allpictures')
    , gobackBtn = waterfullPanel.next().find('a')
    , waterfullstatus = 0;


    var recPanel = $('.gallery_rec')
    , d
    , lastThumb = $('.gal_list_thumb li[data-index=last]')
    , review = recPanel.find('.rev');


    function removeClass(){
        _cmstop_.each(list, function(o, i) {
            o.className = "";
        });
    }

    function auto() {
        oPlay.toggleClass('play');
        autoT = setInterval(function() {
            index++;
            if($(list[index]).attr('data-index') === 'last' || index >= len ) {
                index = 0;
            }
            Go(index, list[index]);
        }, speed);
        autostatus = 1;
    }

    function pause() {
        oPlay.toggleClass('play');
        clearInterval(autoT);
        autostatus = 0;
    }


    // 滚动到大图
    function toGalView() {
        $("html,body").animate({scrollTop:gal_element.parent().offset().top},1000);
    }


    function Go(i, o) {
        index = i;
        var _distance = 0;
        if(i > 2){
            iscroll.content.scrollLeft = intervalD * (i - 2);       
        }else{
            iscroll.content.scrollLeft = 0;
        }
        _distance = intervalS * i;
        if(i === len - 1){
            _distance = intervalS * (i + 1);
        }
        iscroll.idrag.style.left = Math.min(Math.max(_distance , 0),iscroll.Max.left)  + "px";
        removeClass();
        o.className = "gal_list_active";
        photo.src = o.getElementsByTagName("i")[0].innerHTML;
        photoDesc.text(o.getElementsByTagName("p")[0].innerHTML);
        photoIndex.html(i+1);
    }

    Go(0, list[0]);
        
    _cmstop_.each(list, function(o, i) {
        _cmstop_.addEvent(o, function() {
            Go(i, o);       
        },"click");
    });

    // 上一个，下一个
    _cmstop_.addEvent(photoNext, function() {
        index++;
        if(index >= len){
            index = len - 1;
            return;
        }
        Go(index, list[index]);
    },"click");

    _cmstop_.addEvent(photoPrev, function() {
        index--;
        if(index < 0 ){
            index = 0;
            return;
        }
        Go(index, list[index]);
        
    },"click");


    photoView.on('mouseenter mouseleave', function(){
        $(photoPrev).parent().toggleClass('hidden').next().toggleClass('hidden');
    });



}



