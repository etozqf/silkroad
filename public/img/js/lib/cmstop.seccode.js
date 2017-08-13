/**
 * CmsTop Seccode
 *
 * @depends jquery 1.3.2+, config.js
 */
(function($) {
if ($.fn.seccode) return;
var agent = navigator.userAgent.toLowerCase() ;

var regStr_ie = /msie [\d.]+;/gi ;
!$.fn.bgiframe && ($.fn.bgiframe = (agent.match(regStr_ie) && /msie 6\.0/i.test(navigator.userAgent) ? function(s) {
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

var STYLE = [
    '#fn-seccode{margin:0;padding:5px;border:solid 1px #EEE;background:#FFF;font-size:13px;min-width:155px;overflow:hidden}' +
    '#fn-seccode input{display:block;overflow:hidden;zoom:1;border:solid 1px #e1e1e1;margin-bottom:5px;padding:6px 3px;width:73px;font-size:14px;font-weight:bold;letter-spacing:2px;ime-mode:disabled}' +
    '#fn-seccode img{display:block;float:right;margin:1px 0 0;cursor:pointer}' +
    '#fn-seccode input:hover,#fn-seccode input.hover{border-color:#a3bde3}' +
    '#fn-seccode .fn-seccode-btn{display:block;float:left;margin:0 5px 0 0}' +
    '#fn-seccode .fn-seccode-btn,#fn-seccode .fn-seccode-btn:visited{cursor:pointer;text-decoration:none;display:inline-block;height:20px;line-height:20px;padding:0 5px;border:solid 1px #e1e1e1;background:transparent;color:#666;outline:0}' +
    '#fn-seccode .fn-seccode-btn:hover,#fn-seccode .fn-seccode-btn:focus{background-color:#d5e1f2;border-color:#a3bde3}' +
    '#fn-seccode .fn-seccode-btn:active{background-color:#a3bde3;border-color:#3e6db5}'
].join('');
var TEMPLATE = [
    '<div id="fn-seccode">' +
        '<img title="看不清楚？点击换一张" alt="" />' +
        '<input type="text" autocomplete="off" title="输入验证码" placeholder="验证码" />' +
        '<span class="seccodeNote">输入图中<span class="seccodeColor"></span>字符</span>'+
        '<a href="javascript:void(0);" target="_self" data-role="submit" class="fn-seccode-btn">确定</a>' +
        '<a href="javascript:void(0);" target="_self" data-role="cancel" class="fn-seccode-btn">取消</a>' +
    '</div>'
].join('');
var DEFAULTS = {
    form:null,
    field:'[name=seccode]',
    validate:function() {}
};
var inited = false;
var container, submit, cancel, input, img;
var doc = document, $doc;
var ie6 = agent.match(regStr_ie);
function hide() {
    container.hide();
}
$.fn.seccode = function(options) {
    var args = arguments;
    var seccode = {
        create:function() {
            $doc || ($doc = $(doc));

            var node = doc.createElement('style');
            node.type = 'text/css';
            if (node.styleSheet) {
                node.styleSheet.cssText = STYLE;
            } else {
                node.appendChild(doc.createTextNode(STYLE));
            }
            doc.getElementsByTagName('head')[0].appendChild(node);

            container = $(TEMPLATE).css({
                position:'absolute',
                left:'-9999em',
                top:'-9999em',
                display:'block',
                visibility:'visible'
            }).appendTo(doc.body);
            ie6 && container.bgiframe();
            submit = container.find('[data-role=submit]').click(function() {
                return false;
            });
            cancel = container.find('[data-role=cancel]').click(function() {
                hide();
                return false;
            });
            input = container.find('input').keyup(function(ev) {
                if (ev.keyCode == 13) {
                    submit.trigger('click');
                    return false;
                }
            }).hover(function() {
                input.addClass('hover');
            }, function() {
                input.removeClass('hover');
            });
            if ($('#image_action').attr('action') == 'image') {
                container.find('.seccodeNote').remove();
                img = container.find('img').click(function() {
                    this.src = APP_URL+'?app=system&controller=seccode&action=image&no_border=1&height=30&_='+new Date().getTime();
                    input.val('').focus();
                    return false;
                });
            } else {
                img = container.find('img').click(function() {
                    this.src = APP_URL+'?app=system&controller=seccode&action=image_pro&no_border=1&height=30&_='+new Date().getTime();
                    input.val('').focus();
                    container.find('.seccodeColor').html(decodeURI($.cookie(COOKIE_PRE+'seccode_color')).replace('+', ' '));
                    return false;
                });
            }
            
        },
        render:function(opt) {
            var _this = this, form, field;
            opt = $.extend({}, DEFAULTS, opt);

            if (!opt.form) throw new Error('param form required');
            if (!opt.field) throw new Error('param field required');

            form = opt.form.jquery ? opt.form : $(opt.form);
            if (!form.length) throw new Error('form not exists');
            field = opt.field.jquery ? opt.field : $(opt.field);
            if (!field.length) throw new Error('field not exists');

            _this.unbind('click.seccode').bind('click.seccode', function() {
                var offset = _this.offset();
                img.removeAttr('src');
                container.css(offset).show();
                img.trigger('click');
                submit.unbind('click').click(function() {
                    var value = $.trim(input.val());
                    if (!value) {
                        input.focus();
                        return false;
                    }
                    field.val(value);
                    $.isFunction(opt.validate) && opt.validate();
                    hide();
                    return false;
                });
                input.focus();
                return false;
            });
        }
    };
    return this.each(function() {
        var elem = $(this);
        if (!inited) {
            seccode.create();
            inited = true;
        }
        if (typeof options == 'string') {
            return seccode[options] && seccode[options].apply(elem, Array.prototype.slice.call(args, 1));
        }
        seccode.render.apply(elem, [options]);
    });
};
})(jQuery);