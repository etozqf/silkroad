;(function($) {
var DEFAULT = {
    itemClass:'ui-checkable-item',
    checkedClass:'ui-checkable-item-checked',
    disabledClass:'ui-checkable-item-disabled',
    change:null,
    select:''
};
$.fn.checkable = function(options) {
    return this.each(function() {
        var elem = $(this),
            input = elem.find('input[type=hidden]:first'),
            checked = input.val(),
            opt = $.extend({}, DEFAULT, options),
            initing = true;
        if (checked) {
            checked = checked.split(/\s*,\s*/);
        } else {
            checked = [];
        }
        elem.find('.'+opt.itemClass).each(function() {
            var item = $(this), value = item.attr('data-value'), disabled = parseInt(item.attr('data-disabled'), 10);
            if (disabled) {
                item.addClass(opt.disabledClass);
            }
            item.click(function() {
                if (opt.select == 'radio') {
                    var elem_that = elem.find('.'+opt.checkedClass+'[data-value!="' + value + '"]');
                    elem_that.each(function() {
                        var that = $(this);
                        that.removeClass(opt.checkedClass);
                    })
                }
                if (!disabled) {
                    var index = checked.indexOf(value);
                    if (item.hasClass(opt.checkedClass)) {
                        item.removeClass(opt.checkedClass);
                        index = checked.indexOf(value);
                        checked.splice(index, 1);
                        input.val( (opt.select == 'radio') ? '' : checked.join(',') );
                    } else {
                        item.addClass(opt.checkedClass);
                        index = checked.indexOf(value);
                        index == -1 && checked.push(value);
                        input.val( (opt.select == 'radio') ? value : checked.join(',') );
                    }
                    initing || ($.isFunction(opt.change) && opt.change(checked, elem.find('.'+opt.checkedClass)));
                }
                return false;
            });
            if (checked.indexOf(value) > -1) {
                if (disabled) {
                    item.addClass(opt.checkedClass);
                } else {
                    item.triggerHandler('click');
                }
            }
        });
        initing = false;
        $.isFunction(opt.init) && opt.init(elem.find('.'+opt.checkedClass));
    });
};
})(jQuery);