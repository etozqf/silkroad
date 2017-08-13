;(function($) {
    var DEFAULTS = {
        trigger:'',
        content:'',
        active: 0,
        activeClass: 'active',
        beforeActive:null,
        afterActive:null
    };
    $.fn.tab = function(options) {
        return this.each(function() {
            var opt = $.extend({}, DEFAULTS, options),
                $this = $(this),
                triggers = $this.find(opt.trigger || '> ul > li'),
                contents = $this.find(opt.content || '> div');
            triggers.each(function(index, trigger) {
                var $trigger = $(trigger), $content = contents.eq(index);
                $trigger.click(function() {
                    if ($.isFunction(opt.beforeActive) && opt.beforeActive(index, $trigger, $content) === false) {
                        return;
                    }
                    triggers.not(trigger).removeClass(opt.activeClass);
                    $trigger.addClass(opt.activeClass);
                    contents.not($content[0]).hide();
                    $content.show();
                    $.isFunction(opt.afterActive) && opt.afterActive(index, $trigger, $content);
                    return false;
                });
            }).eq(opt.active).triggerHandler('click');
        });
    };
})(jQuery);