(function () {
    'use strict';

    $(function () {
        $('.r-grid').on('mouseenter', '.r-type-panel > a', function (event) {
            var container = $(event.delegateTarget),
                a = $(event.target);
            container.find('.r-type-panel > .now').removeClass('now');
            a.addClass('now');
            container.find('.tab').hide().eq(container.find('.r-type-panel > a').index(a)).show();
        });
    });
}());