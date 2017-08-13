$.fn.imagetabs = function(conf) {
    conf = $.extend({
            speed : 5000,
            animate : 1000
        }, conf);

    return this.each(function() {
        var that = $(this); that.curri = 1, that.lasti = 1;
        var oItem = $(".fcon", this);
        var len = oItem.length;
        var pointes = that.find('.pointes');
        var t,
            prev = that.find('.prev'),
            next = that.find('.next');

        oItem.css({position: "absolute"});
        oItem.eq(that.curri).css({"z-index": 9});

        var _go = function(i, o) {
            var active = oItem.eq(that.lasti);
            active.css({"z-index": 9});
            o.css({
                'opacity': 0, 
                'z-index': 10
            })
            .addClass('active')
            .animate({
                opacity: 1
            }, conf.animate, function() {
                active.removeClass('active');
                active.css('z-index', 8);
                that.lasti = that.curri;
            });

            pointCls(i);
        },

        addPoint = function() {
            for(var i = 0; i < len; i++) {
                var $a = $('<a href="javascript:void(0)" hidefocus="true"><i>'+i+'</i></a>');
                if(i == that.curri) {
                    $a.addClass('current');
                }
                pointes.append($a);
            }
        },

        pointCls = function(i) {
            pointes.find('a').eq(i).addClass('current').siblings().removeClass('current');
        },

        auto = function() {
            t = setInterval(function() {
                that['curri']++;
                if(that['curri'] >= len) {
                    that['curri'] = 0;
                }
                _go(that['curri'], oItem.eq(that['curri']));
            }, conf.speed);
        },
        pause = function() {
            clearInterval(t);
        }


        addPoint();
        
        auto();

        prev.click(function(){
            pause();
            that['curri']--;
            if(that['curri'] == -1) {
                that['curri'] = len - 1;
            }
            _go(that['curri'], oItem.eq(that['curri']));
            auto();
        });

        next.click(function(){
            pause();
            that['curri']++;
            if(that['curri'] >= len) {
                that['curri'] = 0;
            }
            _go(that['curri'], oItem.eq(that['curri']));
            auto();
        });

        that.on('mouseenter', function() {
            that.find('.prev').toggleClass('hidden');
            that.find('.next').toggleClass('hidden')
        });
        that.on('mouseleave', function() {
            that.find('.prev').toggleClass('hidden');
            that.find('.next').toggleClass('hidden');
        });
        
        pointes.on('click', 'a', function(e) {
            pause();
            that['curri'] = $(e.target).index();
            _go(that['curri'], oItem.eq(that['curri']));
            auto();
        });
    });
};