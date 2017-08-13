/**
 * 元素载入中插件
 *
 * @author    micate {@link http://micate.me}
 * @homepage  @github {@link http://github.com/micate/loading}
 * @depends   jQuery 1.3.2+
 * @version   $Id$
 */
(function($) {
    var DEFAULTS = {
        zIndex: 999,
        opacity: 0.85
    };

    function Loading(elem, options) {
        this.options = $.extend({}, DEFAULTS, options);
        this.elem = elem;
        this.state = {};
        this.loading = $('<div class="ui-loading"></div>').hide().appendTo(elem);
        this.show();
    }

    Loading.prototype.show = function() {
        this.state.position = this.elem.css('position');
        if (!this.state.position || this.state.position == 'static') {
            this.elem.css('position', 'relative');
        }
        this.loading.css({
            position: 'absolute',
            zIndex: this.options.zIndex,
            left: 0,
            top: 0,
            width: this.elem.width(),
            height: this.elem.height(),
            opacity: this.options.opacity
        }).show();
    };

    Loading.prototype.hide = function() {
        this.loading.hide();
        this.elem.css('position', this.state.position || '');
    };

    $.fn.loading = function(options) {
        return this.each(function() {
            var _this = $(this),
                instance = _this.data('loading');
            if (instance) {
                instance[typeof options == 'string' ? options : 'show']();
            } else {
                _this.data('loading', new Loading(_this, options));
            }
        });
    };
})(jQuery);