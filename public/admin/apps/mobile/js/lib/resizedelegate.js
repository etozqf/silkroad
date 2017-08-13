(function($, win) {
    var NAME = 'resizedelegate',
        FUNC = NAME + '_function',
        $win = $(win),
        started = false;
    win.ResizeDelegate = {
        elements:[],
        add:function(elem, func, trigger) {
            if (!elem || !elem.jquery) {
                throw new TypeError(elem + ' is not a jQuery Object');
            }
            if (!$.isFunction(func)) {
                throw new TypeError(func + ' is not a function');
            }
            elem.data(FUNC, func);
            this.elements.push(elem);
            trigger && this.trigger();
            return this;
        },
        trigger:function() {
            $win.triggerHandler('resize.' + NAME);
        },
        remove:function(elem) {
            var index = $.inArray(this.elements, elem);
            if (index !== false) {
                this.elements.splice(index, 1);
            }
            return this;
        },
        start:function() {
            var _this = this;
            if (started) return _this;
            started = true;
            $win.bind('resize.' + NAME, function() {
                if (!_this.elements.length) return;
                var wh = $win.height(), ww = $win.width();
                $.each(_this.elements, function(i, elem) {
                    var func = elem.data(FUNC);
                    if (func && $.isFunction(func)) {
                        func.apply(elem, [ww, wh]);
                    }
                });
            });
            $win.bind('beforeunload', function() {
                _this.stop();
            });
            return _this;
        },
        stop:function() {
            if (!started) return this;
            $win.unbind('.' + NAME);
            started = false;
            return this;
        }
    };
})(jQuery, window);