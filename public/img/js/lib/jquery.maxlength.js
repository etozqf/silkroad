/**
 * 文本框长度计算和超出截取插件
 *
 * @author    micate {@link http://micate.me}
 * @homepage  @github {@link http://github.com/micate/maxLength}
 * @depends   jQuery 1.3.2+
 * @version   $Id$
 */
(function($) {

    var funcs = {
        1: {
            length: function(val) {
                return (val + '').length;
            },
            substr: function(val, length) {
                return (val + '').substr(0, length);
            }
        },
        2: {
            length: function(val) {
                return (val + '').replace(/[^\x00-\xff]/gm, '__').length;
            },
            substr: function(val, length) {
                var parts = (val + '').split(''), part, result = '', current = 0;
                while (part = parts.shift()) {
                    if (part.match(/[\x00-\xff]/)) { // 英文
                        if ((current + 1) > length) {
                            return result;
                        }
                        current += 1;
                    } else { // 中文
                        if ((current + 2) > length) {
                            return result;
                        }
                        current += 2;
                    }
                    result += part;
                }
                return result;
            }
        },
        3: {
            length: function(val) {
                return (val + '').replace(/[^\x00-\xff]/gm, '__').length / 2;
            },
            substr: function(val, length) {
                var parts = (val + '').split(''), part, result = '', current = 0;
                while (part = parts.shift()) {
                    if (part.match(/[\x00-\xff]/)) { // 英文
                        if ((current + 0.5) > length) {
                            return result;
                        }
                        current += 0.5;
                    } else { // 中文
                        if ((current + 1) > length) {
                            return result;
                        }
                        current += 1;
                    }
                    result += part;
                }
                return result;
            }
        }
    };

    var toString = Object.prototype.toString;

    function isString(obj) {
        return toString.call(obj) == '[object String]';
    }

    $.fn.maxLength = function(options) {

        return this.each(function() {
            var opt = $.extend({}, DEFAULTS, options),
                elem = $(this),
                func = funcs[opt.mode],
                update = $.isFunction(opt.update) ? opt.update : null;

            if (isString(opt.limit) || !opt.limit) {
                opt.limit = parseInt(elem.attr(opt.limit || 'data-maxlength'));
            }

            if (opt.display && !$.isFunction(opt.display)) {
                opt.display = opt.display.jquery ? opt.display : $(opt.display);
                if (!opt.display.length) {
                    opt.display = null;
                }
            }

            function updateTips(length) {
                var error = false;
                if (opt.limit && opt.count == $.fn.maxLength.COUNT_LEFT) {
                    length = Math.ceil(opt.limit - length);
                    if (length < 0) {
                        error = true;
                    }
                } else {
                    length = Math.floor(length);
                    if (opt.limit && length > opt.limit) {
                        error = true;
                    }
                }
                if ($.isFunction(opt.display)) {
                    opt.display.apply(elem, [length, error]);
                } else {
                    if (!opt.display) {
                        opt.display = $('<span class="maxlength"></span>').insertAfter(elem);
                    }
                    opt.display.text(length);
                    opt.display[error ? 'addClass' : 'removeClass']('maxlength-error');
                }
            }

            function limitUpdate() {
                var value = elem.val(),
                    length = func.length(value);
                if (opt.prevent && opt.limit && length > opt.limit) {
                    elem.val(func.substr(value, opt.limit));
                    length = opt.limit;
                }
                updateTips(length);
                update && update.apply(elem, [length]);
            }

            // 阻止掉非输入法模式下的超出操作
            elem.bind('keydown', function(ev) {
                var value = elem.val(),
                    length = func.length(value),
                    code = ev.keyCode;
                if (opt.prevent && opt.limit && length >= opt.limit) {
                    if ((code >= 65 && code <= 90 && !(ev.ctrlKey || ev.metaKey)) // a-z
                        || (code >= 48 && code <= 57) // 0-9
                        || $.inArray(code, [192, 173, 61, 219, 220, 221, 222, 59, 188, 190, 191]) > -1) { // other input
                        ev.preventDefault();
                    }
                }
                update && update.apply(elem, [length]);
            });

            // 输入、粘贴或改变
            // 由于中文输入法的问题，放弃 propertychange input
            // 监听 change 以便外部手动触发改变
            elem.bind('paste keyup change', function() {
                setTimeout(function() {
                    limitUpdate();
                }, 0);
            });

            updateTips(func.length(elem.val()));
        });
    };

    $.fn.maxLength.MODE_NORMAL = 1;  // 任何一个字符都算一个长度
    $.fn.maxLength.MODE_MAXIMUM = 2; // 一个英文算一个，一个中文算两个
    $.fn.maxLength.MODE_CHINESE = 3; // 一个中文算一个，两个英文算一个

    $.fn.maxLength.COUNT_LENGTH = 1; // 显示输入长度
    $.fn.maxLength.COUNT_LEFT = 2;   // 显示可输入长度

    $.fn.maxLength.NOOP = function() {};

    var DEFAULTS = {
        limit: 0,                           // 长度限制
        prevent: true,                      // 阻止继续输入
        count: $.fn.maxLength.COUNT_LENGTH, // 计数显示方式
        display: null,                      // 显示提示信息回调或元素
        update: null,                       // 输入框内容改变回调
        mode: $.fn.maxLength.MODE_CHINESE   // 长度计算方式
    };

})(jQuery);