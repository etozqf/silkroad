fet('lib.jQuery', function() {
    (function($) {
        $.fn.zoomImage || ($.fn.zoomImage = function() {
            return this.each(function() {
                var image = $(this),
                    small = this.src,
                    big = image.parent().attr('href'),
                    box = image.parent().parent();
                big && image.click(function() {
                    if (image.data('zooming')) return false;
                    image.data('zooming', true);
                    if (image.data('zoom') == 'out') { // need zoom in
                        image.css('width', '').attr('src', small);
                        image.data('zoom', 'in');
                        image.removeData('zooming');
                        box.removeClass('weibo-image-zoomout');
                    } else { // need zoom out
                        var loading = $('<div class="weibo-loading"></div>').appendTo(box).css({
                            width: box.innerWidth(),
                            height: box.innerHeight()
                        }).css(box.position());
                        $('<img />').load(function() {
                            loading.remove();
                            image.css({
                                width: Math.min(this.width, box.parent().innerWidth())
                            }).attr('src', big);
                            image.data('zoom', 'out');
                            image.removeData('zooming');
                            box.addClass('weibo-image-zoomout');
                        }).attr('src', big);
                    }
                    return false;
                });
            });
        });
        $(function() {
            $('.mod-weibo img.fn-zoom').zoomImage();
        });
    })(jQuery);
});