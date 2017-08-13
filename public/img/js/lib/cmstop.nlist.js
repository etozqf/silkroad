/**
 * CmsTop Multi-level list plugin
 *
 * @depends jquery 1.3.2+
 */
;(function($) {
var DEFAULTS = {
    initUrl:'',
    dataUrl:'',
    paramId:'id',
    paramValue:'value',
    paramParentId:'parentid',
    paramName:'name',
    paramHasChild:'hasChild',
    ending:true,
    maxWidth:0,
    message:{
        loading:'正在加载，请稍后',
        title:'选择',
        repick:'重新选取',
        ok:'确定',
        cancel:'取消'
    }
};
var PREFIX = 'ui-nlist-';
var CLS_CHECK = 'checked';
var doc = document;
var CACHE = {};
var TEMPLATES = {
    LOADING:'<div class="ui-nlist-loading">{text}</div>',
    RESULT:[
        '<span class="ui-nlist-result">',
        '<span class="ui-nlist-label"></span>',
        '<a href="javascript:void(0);" class="ui-nlist-repick">{text}</a>',
        '</span>'
    ].join(''),
    CONTAINER:[
        '<div class="ui-nlist">',
        '<div class="ui-nlist-header">{title}</div>',
        '<div class="ui-nlist-content"></div>',
        '<div class="ui-nlist-footer">',
        '<a class="ui-nlist-btn">{ok}</a>',
        '<a class="ui-nlist-btn">{cancel}</a>',
        '</div>',
        '</div>'
    ].join(''),
    LEVEL:'<div class="ui-nlist-level" data-level="{level}"></div>'
};

!$.fn.bgiframe && ($.fn.bgiframe = ($.browser.msie && /msie 6\.0/i.test(navigator.userAgent) ? function(s) {
    s = $.extend({
        top : 'auto',
        left : 'auto',
        width : 'auto',
        height : 'auto',
        opacity : true,
        src : 'about:blank'
    }, s);
    var html = '<iframe class="bgiframe" frameborder="0" tabindex="-1" src="'+s.src+'"'+
        'style="display:block;position:absolute;z-index:-1;'+
        (s.opacity !== false?'filter:Alpha(Opacity=\'0\');':'')+
        'top:'+(s.top=='auto'?'expression(((parseInt(this.parentNode.currentStyle.borderTopWidth)||0)*-1)+\'px\')':prop(s.top))+';'+
        'left:'+(s.left=='auto'?'expression(((parseInt(this.parentNode.currentStyle.borderLeftWidth)||0)*-1)+\'px\')':prop(s.left))+';'+
        'width:'+(s.width=='auto'?'expression(this.parentNode.offsetWidth+\'px\')':prop(s.width))+';'+
        'height:'+(s.height=='auto'?'expression(this.parentNode.offsetHeight+\'px\')':prop(s.height))+';'+
        '"/>';
    return this.each(function() {
        if ( $(this).children('iframe.bgiframe').length === 0 )
            this.insertBefore( document.createElement(html), this.firstChild );
    });
} : function() { return ''; }));

function template(tpl, data) {
    return tpl.replace(/\{([^\}]+?)\}/gm, function(match, key) {
        var parts, part, result = data, type, defaultValue;
        key = key.split('||');
        parts = key[0].split('.');
        defaultValue = key[1] || '';
        while (part = parts.shift()) {
            if (!(result = result[part])) break;
        }
        type = typeof result;
        return (type != 'undefined' && result !== null) ? result : defaultValue;
    });
}
function getJSON(url, param, success) {
    var key = url,
        ctr = url.indexOf('?') > -1 ? '&' : '&';
    if ($.isFunction(param)) {
        success = param;
        param = null;
    }
    param && (param = $.param(param));
    param && (key = url + ctr + param);
    if (key in CACHE) {
        success(CACHE[key]);
    } else {
        $.getJSON(url, param, function(json) {
            CACHE[key] = json;
            success(json);
        });
    }
}
function NList(elem, options) {
    var opt = this.options = $.extend(true, {}, DEFAULTS, options);
    this.elem = elem.jquery ? elem : $(elem);
    this.tplItem = [
        '<a class="ui-nlist-item" data-id="{', opt.paramId, '}" data-hasChild="{', opt.paramHasChild, '}" data-value="{', opt.paramValue, '}">',
        '<nobr>{', opt.paramName, '}</nobr>',
        '</a>'
    ].join('');
    this.render();
}
NList.prototype = {
    find:function(name, context) {
        return (context || this.container).find('.'+PREFIX+name);
    },
    render:function() {
        var _this = this,
            opt = _this.options;
        _this.elem.hide();
        _this.container = $(template(TEMPLATES.CONTAINER, opt.message)).css({
            position:'absolute',
            left:'-9999em',
            top:'-9999em',
            display:'block',
            visibility:'visible'
        }).appendTo(doc.body);
        _this.container.bgiframe();
        _this.loading = $(template(TEMPLATES.LOADING, {text:opt.message.loading}));
        _this.header = _this.find('header');
        _this.content = _this.find('content');
        _this.footer = _this.find('footer');
        _this.btnOK = _this.footer.find('a:first').click(function() {
            _this.save();
            return false;
        });
        _this.btnCancel = _this.footer.find('a:last').click(function() {
            _this.hide();
            return false;
        });
        _this.result = $(template(TEMPLATES.RESULT, {
            text:opt.message.title
        })).insertAfter(_this.elem);
        _this.label = _this.find('label', _this.result).hide();
        _this.repick = _this.find('repick', _this.result).click(function() {
            _this.show();
            return false;
        });
        _this.renderResult();
    },
    renderLevel:function(level, parentid, data) {
        var _this = this,
            opt = _this.options,
            levelContainer = _this.content.find('[data-level='+level+']');
        if (levelContainer.length) {
            levelContainer.empty();
        } else {
            levelContainer = $(template(TEMPLATES.LEVEL, {level:level})).appendTo(_this.content);
        }
        levelContainer.show();
        if (!data) {
            levelContainer.empty().append(_this.loading);
            _this.query(level, parentid, function(json) {
                _this.renderLevel(level, parentid, json);
            });
            return;
        }
        $.each(data, function(index, item) {
            item.level = level + 1;
            levelContainer.append(template(_this.tplItem, item));
        });
        if (_this.container.width() > opt.maxWidth) {
            _this.container.width(opt.maxWidth);
        }
        levelContainer.children().click(function() {
            var item = $(this),
                levelNext = levelContainer.next();
            item.siblings().removeClass(CLS_CHECK);
            item.addClass(CLS_CHECK);
            levelNext.nextAll().hide();
            parseInt(item.attr('data-hasChild')) && _this.renderLevel(level + 1, item.attr('data-id'));
            return false;
        });
    },
    renderResult:function() {
        var _this = this,
            opt = _this.options,
            value = _this.elem.val(), params;
        if (value) {
            params = {};
            params[opt.paramValue] = value;
            $.getJSON(opt.initUrl, params, function(json) {
                _this.label.text(json[opt.paramName]).show();
                _this.repick.text(opt.message.repick);
            });
        } else {
            _this.label.hide();
            _this.repick.text(opt.message.title);
        }
    },
    query:function(level, parentid, callback) {
        var _this = this,
            opt = _this.options,
            params = {};
        params.level = level;
        params[opt.paramParentId] = parentid;
        getJSON(opt.dataUrl, params, function(json) {
            callback(json);
        });
    },
    show:function() {
        var _this = this, offset = _this.result.offset();
        _this.container.css({
            left:offset.left,
            top:offset.top + _this.result.outerHeight(true) + 5
        }).show();
        _this.renderLevel(0, 0);
    },
    hide:function() {
        this.container.hide();
        this.content.children(':gt(0)').hide();
    },
    save:function() {
        var checked = this.options.ending
            ? (this.content.children(':visible:last').find('.'+CLS_CHECK))
            : (this.content.find('.'+CLS_CHECK+':visible:last'));
        if (checked.length) {
            this.elem.val(checked.attr('data-value'));
            this.renderResult();
            this.hide();
        }
    }
};
$.fn.nlist = function(options) {
    var args = Array.prototype.slice.call(arguments);
    args.splice(1);
    return this.each(function() {
        var _this = $(this), instance = _this.data('nlist');
        if (instance) {
            if (typeof options == 'string' && options in instance) {
                return $.isFunction(instance[options] ? instance[options].apply(instance, args) : instance[options]);
            }
        } else {
            _this.data('nlist', new NList(_this, options));
        }
    });
};
})(jQuery);