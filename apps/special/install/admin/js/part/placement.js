var elementFromEvent = function(){
    var userAgent = navigator.userAgent.toLowerCase(), v;
    return (window.opera
        ? ((v = /version\/([\d\.]+)/.exec(userAgent)) && toFloat(v[1]) < 10.5)
        : ((v = /webkit\/([\d\.]+)/.exec(userAgent)) && toFloat(v[1]) < 533))
    ? function(e){
        return document.elementFromPoint(e.pageX, e.pageY);
    } : function(e){
        return document.elementFromPoint(e.clientX, e.clientY);
    };
}();
function selectAreaByEvent(e, except) {
    except.style.visibility = 'hidden';
    var el = elementFromEvent(e);
    except.style.visibility = 'visible';
    if (!el) {
        return null;
    }
    if (el.nodeType == 9 || el.nodeName == 'HTML') {
        el = document.body;
    }

    return selectArea(el, e.pageX);
}
function selectArea(node, x) {
    do {
        if (node.nodeType == 1) {
            if (hasClass(node, 'diy-area')) {
                return node;
            }
            if (node.id == 'diy-pannel') {
                return 'cancel';
            }
            if (node == document.body) {
                return null;
            }
            if (hasClass(node, 'diy-border')) {
                node == node.parentNode;
            }
            if (hasClass(node, 'diy-wrapper')) {
                return selectAreaInWrapper(node, x);
            }
            if (hasClass(node, 'diy-frame')) {
                return selectAreaInFrame(node, x);
            }
        }
    } while ((node = node.parentNode));
    return null;
}
function selectAreaInWrapper(node, x) {
    var node = node.firstChild;
    do {
        if (node.nodeType == 1 && node.offsetWidth && hasClass(node, 'diy-frame'))
        {
            return selectAreaInFrame(node, x);
        }
    } while ((node = node.nextSibling));
    return null;
}
function selectAreaInFrame(frame, x) {
    var node = frame.firstChild;
    do {
        if (node.nodeType == 1 && node.offsetWidth && hasClass(node, 'diy-area'))
        {
            var l = getOffset(node).left;
            if (l <= x && l + node.offsetWidth >= x) {
                return node;
            }
        }
    } while ((node = node.nextSibling));
    return null;
}
function selectPlace(el,y) {
    if (! (el = el.firstChild)) return null;
    var temp = null;
    do {
        if (el.nodeType == 1 && hasClass(el, 'diy-placement') && el.offsetHeight)
        {
            var t = el.offsetHeight / 2 + getOffset(el).top,
                p = $.curCSS(el, 'position'),
                abs = (p == 'absolute' || p == 'fixed');
            if (t >= y) {
                if (!abs) {
                    return temp || el;
                }
                if (!temp) {
                    temp = el;
                }
            } else if (!abs) {
                temp = null;
            }
        }
    } while ((el = el.nextSibling));

    return temp;
}
function bindMove(elem) {
    var origPos = null;
    elem.bind('dragInit',function(){
        origPos = {area:elem[0].parentNode, place:elem.next()[0]};
        elem.addClass('diy-move');
    }).bind('dragEnd',function(){
        var newPos = {area:elem[0].parentNode, place:elem.next()[0]};
        if (newPos.area != origPos.area || newPos.place != origPos.place)
        {
            History.log('move', [elem, origPos, newPos]);
        }
        elem.removeClass('diy-move');
        elem.hasClass('diy-wrapper') && setColor(elem);
    }).bind('mouseup', function(){
        elem.removeClass('diy-move');
    });
}
function bindOver(o) {
    o.mouseover(function(e){
        e.stopPropagation();
        var w = $(e.target).closest('.diy-placement'), m = placementPanel[0];
        m.parentNode == w[0] || w.append(m);
    });
}
var R_COLOR = /\bdiy-color\-(\w+)\b/,
    COLORS = ['6694E3', 'f69', '9c9', 'fc3', '0cf', '090', 'c00'];
function getColor(elem){
    if (elem.hasClass('diy-frame')) {
        elem = elem.parent();
    }
    var pw = elem.closest('.diy-frame').parent(), c = 0;
    if (pw.length) {
        var m = R_COLOR.exec(pw[0].className);
        if (m) {
            c = COLORS.indexOf(m[1]) + 1;
            if (c == COLORS.length) {
                c = 0;
            }
        }
    }
    return 'diy-color-'+COLORS[c];
}
function setColor(wrapper){
    wrapper[0].className = wrapper[0].className.replace(R_COLOR, getColor(wrapper));
    wrapper.find('.diy-wrapper').each(function(i,t){
        t.className = t.className.replace(R_COLOR, getColor($(t)));
    });
}

var placeMentDragOptions = {
    selectArea:selectAreaByEvent,
    selectPlace:selectPlace,
    getHolder:function($h) {
        return $('<div class="diy-placeholder"></div>').insertAfter($h);
    },
    getGhost:function($h, top, left, e) {
        $panel.fadeOut('fast');
        var h = $h[0], ow = h.offsetWidth, oh = h.offsetHeight, nh, nw;
        $h.data('origCSSText',h.style.cssText||'').css({
            width:$h.width(),
            position:'absolute',
            top:top,
            left:left,
            margin:0,
            zIndex:151,
            opacity:.5,
            cursor:'move',
            maxHeight:100,
            maxWidth:300,
            overflow:'hidden'
        }).appendTo(document.body);
        nh = h.offsetHeight, nw = h.offsetWidth;
        if (nh < oh) {
            var pageY = e.clientY + $doc.scrollTop();
            if (top + nh < pageY) {
                $h.css('top', pageY - nh/2);
            }
        }
        if (nw < ow) {
            var pageX = e.clientX + $doc.scrollLeft();
            if (left + nw < pageX) {
                $h.css('left', pageX - nw/2);
            }
        }
        return $h;
    },
    fromGhost:function($g, found) {
        $panel.fadeIn();
        var cssText = $g[0].style.cssText;
        $g[0].style.cssText = $g.data('origCSSText');
        if (!found) {
            var k = $g.clone().appendTo(body);
            k[0].style.cssText = cssText;
            $g.appendTo($fragments);
            k.fadeOut('fast', function(){
                k.remove();
            });
            return null;
        }
        return $g;
    }
}, placementPanel = null;
var ImageInput = {
    _finder:null,
    _currentInput:null,
    _init:function(){
        if (ImageInput._finder) return;
        var fd = new Finder({
            baseUrl:envUrl('?app=special&controller=resource&action=%a&path=%p'),
            width:655,
            height:400,
            allowSelectExt:'jpg|jpeg|png|gif',
            allowViewExt:'jpg|png|gif|jpeg:图像文件',
            multi:0,
            events:{
                post:function(checked){
                    if (checked[0] && ImageInput._currentInput) {
                        ImageInput._currentInput.val('{RES_URL}'+checked[0].path);
                        ImageInput._currentInput.input();
                    }
                }
            }
        });
        fd.setEditor(function(data, item){
            var _this = this, icon = item.find('.ui-finder-icon'),
            editor = new ImageEditor(data.path, envUrl('?app=special&controller=resource&action=getConfig&path='+data.path));
            editor.bind('saved', function(json){
                icon.empty().attr('delayimage', json.data.path);
                _this._previewImage(icon);
            });
        }, 'jpg|jpeg|png|gif');
        ImageInput._finder = fd;
    },
    prepare:function(input){
        var selectButton = $('<button class="button_style_1" type="button">选择</button>').click(function(){
            ImageInput._currentInput = input;
            ImageInput._init();
            var m = /^{RES_URL}(.+)$/.exec(input.val());
            ImageInput._finder.check(m ? m[1] : '');
        }), editButton = $('<button class="button_style_1" type="button">编辑</button>').click(function(){
            var val = input.val(), m = /^{RES_URL}([^\?]+)(\?.*)?$/.exec(val),
            editor = new ImageEditor(m ? m[1] : val, envUrl('?app=special&controller=resource&action=getConfig&path='+(m[1]||'')));
            editor.bind('saved', function(json){
                input.val('{RES_URL}'+json.data.path+'?'+(new Date).getTime());
                input.input();
            });
        });
        input.input(function(){
            editButton[input.val() ? 'show' : 'hide']();
        }).input().after(editButton).after(selectButton);
    }
};

$.extend(DIY, {
    setStyle:function(place){
        place.hasClass('diy-widget')
            ? Gen.setWidgetDialog(place)
            : Gen.setFrameDialog(place.children('.diy-frame'));
    },
    setTitle:function(place){
        Gen.setTitleDialog(place.hasClass('diy-widget') ? place : place.children('.diy-frame'));
    },
    remove:function(elem){
        // log history
        History.log('remove', [elem, elem[0].parentNode, elem.next()[0]]);
        // put elem into global fragments
        elem.fadeOut('fast',function(){
            elem.appendTo($fragments);
        });
    },
    setVisible:function(place){
        place.hasClass('diy-hidden') ? place.removeClass('diy-hidden') : place.addClass('diy-hidden');
        History.log('visible', [place, place.hasClass('diy-hidden')]);
    }
});

DIY.registerInit(function(){
    placementPanel = $(TEMPLATE.PLACEMENT_PANEL).mousedown(function(e){
        e.stopPropagation();
        $doc.mousedown();
    }).click(function(e){
        var d = e.target;
        if (d.tagName == 'I') {
            var o = $(this.parentNode);
            DIY[d.getAttribute('action')](o);
            d = null;
        }
    }).appendTo(document.body);
});