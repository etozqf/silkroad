define(function (require) {

    var $ = require('jquery');
    var ANIMATION_END = 'webkitTransitionEnd mozTransitionEnd MSTransitionEnd otransitionend transitionend';

    var Menu = function () {
        this.confirm = function (title, options) {
            if ($.isArray(title)) {
                options = title;
                title = null;
            }

            if (!$.isArray(options)) {
                return;
            }

            var $element = $('<div class="menu modal">' +
                '<div class="content"></div>' +
            '</div>');
            var $content = $element.find('> .content');
            var $cancel = $('<a tabindex="0" class="item split">取消</a>');

            if (title) {
                $('<h3 class="title">' + title + '</h3>').appendTo($content);
            }

            function close(e) {
                e.preventDefault();
                e.stopPropagation();

                $element.on(ANIMATION_END, function () {
                    $element.remove();
                }).removeClass('visible').addClass('hidden');
            }

            $.each(options, function (index, option) {
                var $option = $('<a tabindex="0" class="item">' + option.label + '</a>');
                $option.on('tap', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    if (option.callback) {
                        option.callback();
                    }

                    close(e);
                });
                option.classes && $option.addClass(option.classes);
                $option.appendTo($content);
            });

            $element.on('tap', close);
            $cancel.on('tap', close);
            $cancel.appendTo($content);

            $element.appendTo(document.body);
            setTimeout(function () {
                $element.addClass('visible');
            }, 0);
        };
    };

    return new Menu();
});