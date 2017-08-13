;(function($) {
    $.fn.dd = function (action){
        var p = $.extend({
            width: 70
        }, action || {});
        this.each(function (i, e){
            var $this = $(this);
            $this.find('ul').width(p.width);
            $(e).mouseover(function (){
                $this.find('ul').trigger('display', 'show');
                $(document).one('click', function (){
                    $('div.dropmenu ul').hide();
                });
                $this.mouseout(function (){
                    $this.find('ul').trigger('display', 'hide');
                });
            }).find('ul').bind('display', function(event, display) {
                var element = $(this);
                element.data('display', display);
                setTimeout(function() {
                    element.data('display') === 'show'
                        ? element.show()
                        : element.hide();
                }, 100);
            });
        });
        return this;
    };
})(jQuery);