; (function ($) {
    $.extend({
        'slide': function (con) {
            var $container = con['container']
                , $imgs = $container.find('li.item')
            , $leftBtn = $container.find('a.prev')
            , $rightBtn = $container.find('a.next')
            , config = {
                interval: con && con.interval || 3500,
                animateTime: con && con.animateTime || 500,
                direction: con && (con.direction === 'right'),
                _imgLen: $imgs.length,
                defaultWidth: con && con.defaultWidth || 1000
            }
            , i = 0
            , getNextIndex = function (y) { return i + y >= config._imgLen ? i + y - config._imgLen : i + y; }
            , getPrevIndex = function (y) { return i - y < 0 ? config._imgLen + i - y : i - y; }
            , silde = function (d) {
                $imgs.eq((d ? getPrevIndex(2) : getNextIndex(2))).css('left', (d ? '-'+config['defaultWidth']*2+'px' : config['defaultWidth']*2+'px'))
                $imgs.animate({
                    'left': (d ? '+' : '-') + '='+config['defaultWidth']+'px'
                }, config.animateTime);
                i = d ? getPrevIndex(1) : getNextIndex(1);
                setPoint(i);
                setTimeout(function() {
                    $imgs.eq(i).find('.text').removeClass('hidden').parent().siblings().find('.text').addClass('hidden');
                }, config.animateTime);
            }
            , setPoint = function(i) {
                var c = $('.pointer');
                $('a',c).eq(i).addClass('current').siblings().removeClass('current');
            }
            , s = setInterval(function () { silde(config.direction); }, config.interval);
            $imgs.eq(i).css('left', 0).end().eq(i + 1).css('left', config['defaultWidth']+'px').end().eq(i - 1).css('left', '-'+config['defaultWidth']+'px');
            $container.find('.wrap').add($leftBtn).add($rightBtn).hover(function () { clearInterval(s); }, function () { s = setInterval(function () { silde(config.direction); }, config.interval); });
           
            $leftBtn.click(function (e) {
                e.preventDefault();
                silde(1);
            });
            $rightBtn.click(function (e) {
                e.preventDefault();
                silde(0);
            });

            $container.on('mouseenter mouseleave', function(e) {
                $leftBtn.toggleClass('hidden');
                $rightBtn.toggleClass('hidden');
            });
        }
    });
}(jQuery));