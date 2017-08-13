define(function (require) {

    var $ = require('jquery');
    var ANIMATION_END = 'webkitTransitionEnd mozTransitionEnd MSTransitionEnd otransitionend transitionend';

    function Dialog() {

        this.show = function (content, actions) {

            var $element = $('<div class="dialog modal">' +
                '<div class="container">' +
                    '<div class="content">' +
                        '<div class="inner">' + content + '</div>' +
                    '</div>' +
                    '<div class="buttons"></div>' +
                '</div>' +
            '</div>');
            var $buttons = $element.find('.buttons');

            function close(e) {
                e.preventDefault();
                e.stopPropagation();

                $element.on(ANIMATION_END, function () {
                    $element.remove();
                }).removeClass('visible').addClass('hidden');
            }

            $.each(actions, function (index, action) {
                var $action = $('<a class="button">' + action.label + '</a>');
                $action.on('tap', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    if (action.callback) {
                        action.callback();
                    }

                    close(e);
                });
                action.classes && $action.addClass(option.classes);
                $action.appendTo($buttons);
            });

            $element.appendTo(document.body);
            setTimeout(function () {
                $element.addClass('visible');
            }, 0);
        };
    }

    return new Dialog();
});