(function($) {

    $.fn.extend({
        returntop: function() {
            var isIE=!!window.ActiveXObject;  
            var isIE6=isIE&&!window.XMLHttpRequest;  
            if (this[0]) {
                var b = this.find('.top').click(function(e) {
                    e.preventDefault();
                    $("html, body").animate({
                        scrollTop: 0
                    },
                    120)
                }).parent(),
                c = null;
                $(window).on("scroll", function() {
                    var d = $(document).scrollTop()
                    ,e = $(window).height();
                    0 < d ? b.css("bottom", "100px") : b.css("bottom", "-206px");
                    isIE6 && (b.hide(), clearTimeout(c), c = setTimeout(function() {
                        b.show();
                        clearTimeout(c)
                    },
                    1E3), b.css("top", d + e - 125))
                });

            }
        }

    })
})(jQuery); 