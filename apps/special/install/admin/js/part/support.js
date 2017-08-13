var document = window.document, docElem = document.documentElement,
    head = document.head || document.getElementsByTagName('head')[0] || document.documentElement,
    $doc = $(document), $fragments = $(document.createElement('DIV')),
    body = null,
    _GUID_STACK = [];
function toInt(num) {
    num = parseInt(num);
    return isNaN(num) ? 0 : num;
}
function toFloat(num) {
    num = parseFloat(num);
    return isNaN(num) ? 0 : num;
}
function toFixed(num) {
    return toFloat(num).toFixed(1);
}
function hasClass(node, className) {
    return (" " + node.className + " ").indexOf(" " + className + " ") > -1;
}
function guid(prefix) {
    var id;
    do {
        id = prefix + (new Date()).getTime().toString().substr(11) + (Math.random()*100).toFixed();
    } while ($.inArray(id, _GUID_STACK) != -1 || $('#'+id).length);
    return id;
}
function pushGuid(id) {
    _GUID_STACK.push(id);
}
function addClass(elem, classNames) {
    return $.className.add(elem, classNames);
}
function removeClass(elem, classNames) {
    return $.className.remove(elem, classNames);
}
function switchClass(elem, orig, add, prefix) {
    if (orig == add) return;
    orig && elem.removeClass(prefix+orig);
    add && elem.addClass(prefix+add);
}
function children(elem, filter){
    return $.find('>'+filter, elem);
}
function moveChilren(src, target){
    var el = src[0].firstChild, temp, nodes = [];
    target = target[0];
    while (el) {
        temp = el;
        nodes.push(el);
        el = el.nextSibling;
        target.appendChild(temp);
    }
    return nodes;
}
function scrollIntoView(elem) {
    var offset = elem.offset(), st = $doc.scrollTop(), h = document.documentElement.clientHeight;
    if (offset.top < st) {
        $doc.scrollTop(offset.top);
    } else if (offset.top + elem[0].offsetHeight - st > h) {
        $doc.scrollTop(offset.top + elem[0].offsetHeight - h);
    }
}
function envUrl(url){
    var hasHash = url.indexOf('?') != -1;
    if (ENV.pageid && !/[&?]pageid=\d+/.test(url)) {
        url += (hasHash ? '&' : '?') + 'pageid='+ENV.pageid;
        hasHash = true;
    }
    if (!/[&?]contentid=\d+/.test(url)) {
        url += (hasHash ? '&' : '?') + 'contentid='+ENV.contentid;
    }
    return url;
}
function macroReplace(str){
    return str.replace(/{(\w+)}/g, function(match, key){
        return (key in ENV.macro) ? ENV.macro[key] : match;
    });
}
function json(url, data, success, error, method) {
    if (typeof data == 'function') {
        method = error;
        error = success;
        success = data;
        data = null;
    }
    if (typeof error == 'string') {
        method = error;
        error = null;
    }
    return $.ajax({
        type:method||'POST',
        dataType:'json',
        data:data||'',
        url:envUrl(url),
        success:success||function(){},
        error:error||function(){}
    });
}
function blink(elem, dur) {
    var i = 0, name = 'diy-blink-hover',
        ival = setInterval(function() {
            elem.hasClass(name)
                ? elem.removeClass(name)
                : elem.addClass(name);
            if (++i > 2) {
                clearInterval(ival);
                ival = null;
            }
        }, dur||250);
    elem.addClass(name);
}
function subtitle(title, limit) {
    var max = limit * 2, length = title.length, l = 0, i = 0, part, s;
    for (i=0; i < length && l <= max; i++) {
        l += title.charCodeAt(i) > 255 ? 2 : 1;
    }
    if (l <= max) {
        return title;
    }
    i = 0, l = 0;
    while (l < limit) {
        var s = title.charCodeAt(i) > 255 ? 2 : 1;
        if (l + s > limit) {
            break;
        } else {
            i++;
            l += s;
        }
    }
    part = title.substr(0, i);
    l += 3;

    i = length;
    while (l < max) {
        var s = title.charCodeAt(i-1) > 255 ? 2 : 1;
        if (l + s > max) {
            break;
        } else {
            i--;
            l += s;
        }
    }
    return part + '...' + title.substring(Math.min(i, length-1), length);
}
function spinning(){
    return $('<div class="diy-spinning"></div>');
}
var getOffset = function() {
    var jq = $(document);
    return function(el){
        jq[0] = el;
        return jq.offset();
    };
}(),
getPosition = function(){
    var jq = $(document);
    return function(el){
        jq[0] = el;
        return jq.position();
    };
}(),
each = function(){
    var re = /\s+/;
    return function(o, fn){
        typeof o == 'string' && (o = o.split(re));
        return $.each(o, fn);
    };
}();

(function(){
    var slice = [].slice;
    $.extend($.event.special, {
        mousewheel: {
            setup: function() {
                if ('onmousewheel' in this) {
                    this.onmousewheel = wheelHandler;
                } else {
                    this.addEventListener('DOMMouseScroll', wheelHandler, false);
                }
            },
            teardown: function() {
                if ('onmousewheel' in this) {
                    this.onmousewheel = null;
                } else {
                    this.removeEventListener('DOMMouseScroll', wheelHandler, false);
                }
            }
        },
        input : {
            setup: function() {
                if ('onpropertychange' in this) {
                    this.onpropertychange = inputHandler;
                } else {
                    this.addEventListener('input', inputHandler, false);
                }
            },
            teardown: function() {
                if ('onpropertychange' in this) {
                    this.onpropertychange = null;
                } else {
                    this.removeEventListener('input', inputHandler, false);
                }
            }
        }
    });
    function wheelHandler(event) {
        var args = slice.call( arguments, 1 ), delta = 0;
        event = $.event.fix(event || window.event);
        event.type = "mousewheel";
        delta = event.wheelDelta
            ? event.wheelDelta / 120
            : (event.detail ? (-event.detail/3) : 0);
        event.delta = delta;
        // Add events and delta to the front of the arguments
        args.unshift(event, delta);
        return $.event.handle.apply(this, args);
    }
    function inputHandler(event){
        var args = slice.call(arguments, 1);
        event = $.event.fix(event || window.event);
        if (event.type == 'propertychange' && event.originalEvent.propertyName !== "value") {
            return;
        }
        event.type = 'input';
        args.unshift(event);
        return $.event.handle.apply(this, args);
    }

    $.fn.extend({
        mousewheel:function(fn){
            return fn ? this.bind("mousewheel", fn) : this.trigger("mousewheel");
        },
        input:function(fn){
            return fn ? this.bind("input", fn) : this.trigger("input");
        },
        isnot:function(filter, is, not){
            return this.each(function(i){
                (typeof filter == 'function'
                    ? filter.call(this)
                    : ($.multiFilter(filter, [this]).length > 0)
                    ) ? (is && is.apply(this, [i]))
                    : (not && not.apply(this, [i]));
            });
        }
    });
})();

var RE = {
    center:/\bmargin *: *(?:[^ ]+ +)?auto *(?:;|$)/i,
    block:/\bdisplay *: *block *(?:;|$)/i,
    offset:/\bmargin-(?:left|right) *: *([^;]*) *(?:;|$)/i,
    ival:/-?[\d\.]+(?:px|pt|em)|[\d\.]+%|auto/i,
    bval:/^-?[\d\.]+(?:px|pt|em)|[\d\.]+%|left|right|center|bottom|top$/i,
    sval:/^[^;'"]+$/,
    percent:/([\d\.]+)%/,
    'text-center':/\btext\-align *: *center *(?:;|$)/i,
    'background-image':/\bbackground-image *: *url *\( *["']?([^;"']*)["']? *\) *(?:;|$)/i,
    'font-bold':/\bfont-weight *: *(bold|bolder|[7-9]00) *(?:;|$)/i,
    'font-italic':/\bfont-style *: *(italic|oblique) *(?:;|$)/i,
    'font-underline':/\btext-decoration *: *(underline) *(?:;|$)/i
};
each('height width color', function(){
    RE[this] = new RegExp('(?:[; ]|^)'+this+' *: *([^;]*) *(?:;|$)', 'i');
});
each('line-height float margin padding font-size font-family letter-spacing '+
    'border-width border-color border-style '+
    'border-top-width border-right-width border-bottom-width border-left-width '+
    'border-top-color border-right-color border-bottom-color border-left-color '+
    'border-top-style border-right-style border-bottom-style border-left-style '+
    'margin-top margin-right margin-bottom margin-left '+
    'padding-top padding-right padding-bottom padding-left '+
    'background-color background-repeat background-position',
function(){
    RE[this] = new RegExp('\\b'+this+' *: *([^;]*) *(?:;|$)', 'i');
});
each('padding margin', function(){
    RE[this+'-all'] = new RegExp('\\b'+this+'-', 'i');
});
each('style width color', function(){
  RE['border-all-'+this] =  new RegExp('\\bborder-(?:top|right|left|bottom)-'+this, 'i');
});