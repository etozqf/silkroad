/**
 *
 */
;(function($) {
var DEFAULTS = {
    attribute:null,
    width:null, // 最大宽度
    style:{}
};
var TEMP_NODE;
function adjustText(parent, text, width) {
    TEMP_NODE.text(text).appendTo(parent);
    while (width && TEMP_NODE.width() > width) {
        cut = cut.substr(0, cut.length - 1);
        TEMP_NODE.text();
    }
    return cut;
}
$.fn.overscroll = function(options) {
    TEMP_NODE || (TEMP_NODE = $('<nobr></nobr>'));
    return this.each(function() {
        var elem = $(this), opt, wrap, fulltext, cuttext, tips;
        if (elem.children().length) {
            throw new TypeError('$.fn.overscroll: only support element with one text node.');
        }
        fulltext = elem.text() || elem.attr(opt.attribute);
        opt = $.extend({}, DEFAULTS, options);
        if (!opt.width) {
            throw new TypeError('$.fn.overscroll: no width param specialed.');
        }
        cuttext = adjustText(elem, fulltext, opt.width);
        elem.wrapInner('<nobr></nobr>');
        wrap = elem.find('nobr').text(cuttext);
        wrap.css({
            display:'inline-block',
            zoom:1,
            width:opt.width,
            overflow:'hidden'
        });
        wrap.mouseenter();
        wrap.mouseleave();
    });
};
})(jQuery);