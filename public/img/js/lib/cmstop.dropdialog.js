/**
 * plugin dropdialog
 */
(function($, undefined) {
var OPTIONS = {
    content: undefined,
    trigger: undefined,
    width: undefined,
    height: undefined,
    marginTop: 2,
    maxWidth: undefined,
    region: {},
    buttons: undefined,
    autoOpen: true,
    closeOnBlur: true,
    closeOnEsc: false,
    closeButton: true,
    zIndex: 100,
    template: '<div class="mod-dropdialog">' +
            '<div class="dropdialog-arrow" data-dropdialog="arrow"></div>' +
            '<div class="dropdialog-close" data-dropdialog="close" title="关闭"></div>' +
            '<div class="dropdialog-content-container" data-dropdialog="container">' +
                '<div class="dropdialog-content" data-dropdialog="content"></div>' +
                '<div class="dropdialog-footer">' +
                    '<div class="dropdialog-buttons" data-dropdialog="buttons"></div>' +
                    '<div class="dropdialog-extra" data-dropdialog="extra"></div>' +
                '</div>' +
            '</div>' +
        '</div>',
    templateButton: '<input type="button" class="button_style_1" value="{text}" />'
};
var CLASSES = {
    LOADING: 'dropdialog-loading',
    SELECTED: 'selected'
};
var DropDialog = function(options) {
    return this.initialize(options);
};
DropDialog.prototype = {
    element: undefined,
    dialog: undefined,
    content: undefined,
    arrow: undefined,
    initialize: function(options) {
        var self = this;
        options = $.extend({}, OPTIONS, options || {});
        this.element = options.element.jquery ? options.element : $(options.element);
        this.options = options;

        $.each('afterRender beforeClose afterClose'.split(' '), function(index, event) {
            options[event] && (self.bind(event, options[event]));
        });

        options.autoOpen && this.render();
        return this;
    },
    render: function() {
        var self = this, options = this.options;
        this.dialog = $(options.template).css({
            zIndex: this.options.zIndex,
            position: 'absolute',
            left: '-99999em',
            top: '-99999em',
            width: options.width || 'auto',
            maxWidth: options.maxWidth || (options.region && options.region.width) || 'none'
        }).appendTo(document.body);
        this.arrow = this.dialog.find('[data-dropdialog=arrow]');
        this.closeButton = this.dialog.find('[data-dropdialog=close]');
        if (options.closeButton) {
            this.closeButton.click(function() {
                self.close();
                return false;
            });
        } else {
            this.closeButton.hide();
        }

        // 内容
        this.content = this.dialog.find('[data-dropdialog=content]');
        if (options.content) {
            if (options.content.nodeName || options.content.jquery) {
                this.content.append(options.content);
                this.updatePosition();
                this.trigger('afterRender', [this.dialog]);
            } else {
                this.updatePosition();
                this.content.addClass(CLASSES.LOADING).load(options.content, function() {
                    self.content.removeClass(CLASSES.LOADING);
                    self.updatePosition();
                    self.trigger('afterRender', [self.dialog]);
                });
            }
        } else {
            this.updatePosition();
        }

        // 按钮区域
        this.buttons = this.dialog.find('[data-dropdialog=buttons]');
        if (options.buttons) {
            for (var text in options.buttons) {
                (function(text, func) {
                    $(options.templateButton.replace('{text}', text)).appendTo(self.buttons).click(function() {
                        $.isFunction(func) && func.apply(self, [self.dialog]);
                    });
                })(text, options.buttons[text]);
            }
        }

        // 附加区域
        this.extra = this.dialog.find('[data-dropdialog=extra]');
        if (options.extra) {
            $.each(options.extra, function(index, elem) {
                elem && self.extra.append(elem);
            });
        }

        this.element.addClass(CLASSES.SELECTED);

        // 失去焦点后关闭
        if (options.closeOnBlur) {
            $(document).bind('click.dropdialog', function(ev) {
                var target = ev.target,
                    dialog = self.dialog[0];
                if (target != dialog && $(target).parents().index(dialog) == -1) {
                    self.close();
                }
            });
        }

        // Esc 按下时关闭
        options.closeOnEsc && $(document).bind('keydown.dropdialog', function(ev) {
            if (ev.keyCode == 27) {
                self.close();
            }
        });
    },
    updatePosition: function() {
        var priviousOffset = {};
        return function() {
           var elementOffset = this.element.offset();
           if (priviousOffset == elementOffset) {
               return this;
           }
           var options = this.options,
               dialogTop = elementOffset.top + this.element.height() + options.marginTop,
               dialogWidth = this.dialog.width(),
               arrowLeft,
               arrowBestLeft = elementOffset.left + Math.floor(this.element.innerWidth() / 2),
               dialogLeft = arrowBestLeft - Math.floor(dialogWidth / 3);
           if (options.region) {
                if (options.region.right) {
                    var maxRight = options.region.right;
                    if (dialogLeft + dialogWidth > maxRight) {
                        dialogLeft = maxRight - dialogWidth;
                        arrowLeft = arrowBestLeft - dialogLeft;
                    }
                } else if (options.region.left) {
                    var minLeft = options.region.left,
                        maxLeft = options.region.left + options.region.width - dialogWidth;
                    if (dialogLeft < minLeft) {
                        dialogLeft = minLeft;
                        arrowLeft = arrowBestLeft - dialogLeft;
                    }
                    if (dialogLeft > maxLeft) {
                        dialogLeft = maxLeft;
                        arrowLeft = arrowBestLeft - dialogLeft;
                    }
                }
           }
           this.dialog.css({
               left: dialogLeft,
               top: dialogTop
           });
           if (arrowLeft) {
               this.arrow.css({
                   left: arrowLeft
               });
           }
           priviousOffset = elementOffset;
        };
    }(),
    open: function() {
        if (! this.dialog) {
            this.dialog = this.render();
        }
        this.show();
        return this;
    },
    show: function() {
        this.dialog.show();
        this.element.addClass(CLASSES.SELECTED);
        return this;
    },
    hide: function() {
        this.dialog.hide();
        this.element.removeClass(CLASSES.SELECTED);
        return this;
    },
    close: function() {
        this.trigger('beforeClose', [this.dialog]);
        this.hide();
        this.dialog.remove();
        $(document).unbind('.dropdialog');
        this.trigger('afterClose');

        // 移除所有绑定的事件
        if (this.events) {
            var events = this.events;
            for (var k in events) {
                for (var i = 0, l = events[k].length; i < l; i++) {
                    events[k][i] = null;
                }
            }
        }

        // 移除 trigger 的样式和 data
        this.element.removeClass(CLASSES.SELECTED).removeData('dropdialog');
    },
    bind: function(event, func) {
        this.events || (this.events = {});
        (event in this.events) || (this.events[event] = []);
        this.events[event].push(func);
        return this;
    },
    trigger: function(event, args) {
        if (this.events && event in this.events) {
            for (var index = 0, func;
                 (func = this.events[event][index++]) && (func.apply(this, args || []) !== false); ) {
            }
        }
        return this;
    }
};
$.fn.dropdialog = function(options) {
    return this.each(function() {
        var self = $(this);
        if (self.data('dropdialog')) {
            return self.data('dropdialog').open();
        }
        options = options || {};
        options.element = self;
        self.data('dropdialog', new DropDialog(options));
    });
};
})(jQuery);