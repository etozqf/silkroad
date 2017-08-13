var Reel = (function(){
    var FX_SPEED = 250;
    return function(place){

        place.addClass('diy-reel');

        var _width = place.width(), _height = place.height(), _pagestack = {}, _current = null;

        this.show = function(name){
            if (!(name in _pagestack) || _current == name) {
                return;
            }
            var page = _pagestack[name];
            if ('init' in page) {
                page.shell.css({visibility:'hidden', display:'block'});
                page.init(name, page.shell);
                page.shell.css({visibility:'', display:''});
                delete page.init;
            }
            if (_current) {
                var cur = _pagestack[_current];
                var compare = page.level > cur.level ? 1 : -1;
                cur.shell.stop(true,true).css({
                    position:'absolute',
                    left:0
                }).animate({
                    left:-compare * _width
                }, FX_SPEED, function(){
                    cur.shell.css({
                        display:'none',
                        position:''
                    });
                });

                page.shell.stop(true).css({
                    position:'absolute',
                    left:compare * _width,
                    display:'block'
                }).animate({
                    left:0
                }, FX_SPEED, function(){
                    page.shell.css({
                        position:''
                    });
                    page.show(page.shell);
                });
            } else {
                page.shell.show();
                page.show(page.shell);
            }
            _current = name;

        };

        this.add = function(name, show, init, level){
            _pagestack[name] = {
                level:level||0,
                show:show||function(){},
                init:init,
                shell:$('<div class="diy-reel-shell"></div>').css({width:_width,height:_height}).appendTo(place)
            };

            return this;
        };
    };

})();