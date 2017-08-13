;(function($) {
    /**
     * Zepto.ScrollTo
     * 平滑滚动
     */
    var DEFAULTS = {
        endY: $.os.android ? 1 : 0,
        duration: 200,
        updateRate: 15
    };

    var interpolate = function (source, target, shift) {
        return (source + (target - source) * shift);
    };

    var easing = function (pos) {
        return (-Math.cos(pos * Math.PI) / 2) + .5;
    };

    var scroll = function(settings) {
        var options = $.extend({}, DEFAULTS, settings);

        if (options.duration === 0) {
            window.scrollTo(0, options.endY);
            if (typeof options.callback === 'function') options.callback();
            return;
        }

        var startY = window.pageYOffset,
                startT = Date.now(),
                finishT = startT + options.duration;

        var animate = function() {
            var now = Date.now(),
                    shift = (now > finishT) ? 1 : (now - startT) / options.duration;

            window.scrollTo(0, interpolate(startY, options.endY, easing(shift)));

            if (now < finishT) {
                setTimeout(animate, options.updateRate);
            }
            else {
                if (typeof options.callback === 'function') options.callback();
            }
        };

        animate();
    };

    var scrollNode = function(settings) {
        var options = $.extend({}, DEFAULTS, settings);

        if (options.duration === 0) {
            this.scrollTop = options.endY;
            if (typeof options.callback === 'function') options.callback();
            return;
        }

        var startY = this.scrollTop,
                startT = Date.now(),
                finishT = startT + options.duration,
                _this = this;

        var animate = function() {
            var now = Date.now(),
                    shift = (now > finishT) ? 1 : (now - startT) / options.duration;

            _this.scrollTop = interpolate(startY, options.endY, easing(shift));

            if (now < finishT) {
                setTimeout(animate, options.updateRate);
            }
            else {
                if (typeof options.callback === 'function') options.callback();
            }
        };

        animate();
    };

    $.scrollTo = scroll;

    $.fn.scrollTo = function() {
        if (this.length) {
            var args = arguments;
            this.forEach(function(elem, index) {
                scrollNode.apply(elem, args);
            });
        }
    };
}(Zepto));

var inputfocusblur = function(oInput, oLabel, hidden)
{
    oInput.focus(function() {
        oLabel.addClass(hidden);
    }).blur(function() {
        if(oInput.val().length == 0) {
            oLabel.removeClass(hidden);
        }
    });
}

// 幻灯片标题截字
var slideEllipsis = function(container) {
    var slideContainer = container;
    var ps = slideContainer.find('>.ui-swipeslide-reel>li>a>p>span');
    var points = slideContainer.find('>.ui-swipeslide-bullets');
    var points_w = points.width();
    var viewport_width = $('.ui-container').width() - 5;
    var result_w = viewport_width - points_w;
    ps.each(function() {
        $(this).width(result_w);
    });
}
function limitMaxWidth (el, max) {
    var max = parseInt(max,10);
    if($(window).width() > max) {
        el.css({
            maxWidth: max,
            position: 'relative',
            left: '50%',
            marginLeft: -max/2
        });
    }
}

function slideEllipsisForPicture(num, str)
{
    if (str) {
        var windowWidth = $(window).width();
        var rowCharsNum  = Math.floor(windowWidth/14) - 1;
        var subStringLen = num*rowCharsNum;
        var subString = str.substring(0, subStringLen);
        return subString;
    }
}

function windowHash()
{
    var hash = window.location.hash,
        current;
    if (current = hash.match(/#p=(\d+)/)) {
        return current[1];
    } else {
        return 0;
    }
}

$(function() {

    var viewport_width, viewport_height;

    // 隐藏滚动条
    setTimeout(function(){ window.scrollTo(0, 1);}, 100);

    // 首页 幻灯片调用
    if($('.js-index-slider')[0]) {
        var indexSlider = $('.js-index-slider');
        indexSlider.swipeSlide({
            beforeChange: function(S, currentPage, newPage) {
            },
            afterChange: function(S, currentPage, newPage) {
            },
            autoPlay: 5,
            bulletNavigation: false,
            bulletChangeNum: true
        });

        limitMaxWidth(indexSlider, 640);

        slideEllipsis($('.js-index-slider'));

        $(window).resize(function() {
            slideEllipsis($('.js-index-slider'));
        });
        
    }

    share = {
        url: window.location.href,
        twb: function () {
            window.location = "http://share.v.t.qq.com/index.php?c=share&a=index&url=" + share.url + "&title=" + document.title + "&pic&line1="
        },
        qzone: function () {
            window.location = "http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=" + share.url + "&site=&title=" + document.title + "&pics="
        },
        swb: function () {
            window.location = "http://weibo.cn/ext/share?rt=" + document.title + "&ru=" + share.url
        },
        clickfn: function () {
            ($(".sharet").click(function () {
                share.twb()
            }), $(".shareqzone").click(function () {
                share.qzone()
            }), $(".sharewb").click(function () {
                share.swb()
            }))
        }
    }
    share.clickfn();

    // 首页 客户案例调用
    if($('.js-gallery2-slider')[0]) {
        var gallery2Slider = $('.js-gallery2-slider');
        limitMaxWidth(gallery2Slider, 640);
        gallery2Slider.swipeSlide({
            beforeChange: function(S, currentPage, newPage) {
            },
            afterChange: function(S, currentPage, newPage) {
            },
            bulletNavigation: 'link',
            bulletNavigationObj: $("#js-swipeslide-bullets2-case"),
            bulletChangeNum: false
        });

        slideEllipsis(gallery2Slider);

        $(window).resize(function() {
            slideEllipsis(gallery2Slider);
        });
    }

    // 首页 客户感言调用
    if($('.js-gallery3-slider')[0]) {
        if (!!navigator.userAgent.match(/AppleWebKit.*Mobile.*/)||!!navigator.userAgent.match(/AppleWebKit/)) {
            $(".category-gallery3 .summary").css({"font-size": '10px', "line-height": "15px"});
        }
        var gallery3Slider = $('.js-gallery3-slider');
        gallery3Slider.swipeSlide({
            beforeChange: function(S, currentPage, newPage) {
            },
            afterChange: function(S, currentPage, newPage) {
            },
            bulletNavigation: 'link',
            bulletNavigationObj: $("#js-swipeslide-bullets2-khgy"),
            bulletChangeNum: false
        });

        slideEllipsis(gallery3Slider);

        $(window).resize(function() {
            slideEllipsis(gallery3Slider);
        });
    }



    // goTop goDown
    if ($('.js-gotop')[0] && $(".js-godown")[0]) {
        $(window).scroll(function () {
            var scrollTop = $(window).scrollTop();
            if (scrollTop < 200) {
                $('.js-gotop').css('opacity', 0);
            } else {
                $('.js-gotop').css('opacity', 1);
            }
            if (scrollTop > $(document).height() - $(window).height() - 200) {
                $('.js-godown').css('opacity', 0);
            } else {
                $('.js-godown').css('opacity', 1);
            }
        });
        $('.js-gotop').click(function(){
            if ($('.js-gotop').css('opacity') === '0') return;
            $('html,body').scrollTo({
                endY: 0,
                duration: 500
            });
        });
        $('.js-godown').click(function(){
            if ($('.js-godown').css('opacity') === '0') return;
            $('html,body').scrollTo({
                endY: $(document).height() + $(window).height(),
                duration: 500
            });
        });
    }

    if ($(".ui-article-infos")[0]) {
        var contentShow = $("#content-show");
        var big_font = true;
        var defaultFontSize = 0;
        $(".ui-article-infos .js-font").click(function(){
            if (contentShow.find("p").length > 0) {
                if (big_font) {
                    defaultFontSize = parseInt(contentShow.find("p").css("font-size"), 10) + 1;
                } else {
                    defaultFontSize = parseInt(contentShow.find("p").css("font-size"), 10) - 1;
                }
                contentShow.find("p").css("fontSize", defaultFontSize + 'px');
            }

            if (contentShow[0]) {
                if (big_font) {
                    defaultFontSize = parseInt(contentShow.css("font-size"), 10) + 1;
                } else {
                    defaultFontSize = parseInt(contentShow.css("font-size"), 10) - 1;
                }
                contentShow.css("fontSize", defaultFontSize + 'px');
            }

            if (big_font) {
                $(this).removeClass("big-fontSize").addClass("small-fontSize");
            } else {
                $(this).removeClass("small-fontSize").addClass("big-fontSize");
            }
            if (big_font) {
                big_font = false;
            } else {
                big_font = true;
            }
        });
        contentShow.children('img').css('margin-left', '0');
        contentShow.children('p').each(function(){
            if ($(this).css('text-indent') == '0px') {
                $(this).children("img").css('margin-left', '0');
            }
        });
    }

     var bt = $('.js-menubtn-top'),
        tPanel = $('.js-category-panel');
    // 导航弹出
    bt.click(function() {
        tPanel.toggleClass('js-hidden');
    });

    // 组图浏览
    if($('.js-picture-show')[0])
    {
        var pictureShowContainer = $('.js-picture-show');
        var infoContainer = $('.ui-picture-extra-info');
        var page = $('.js-picture-page');
        pictureShowContainer.css('opacity',0)
        var initPage = parseInt(windowHash(), 10);
        if (initPage < 0 || pictureShowContainer.find("ul>li").length <= initPage) {
            initPage = 0;
        }

        var tophomepage = $('header');

        infoContainer.data('fullscreen', false);
        // 点击隐藏
        pictureShowContainer.find('.ui-picture-single').bind("click", function() {
            if(infoContainer.data('fullscreen') == false) {
                infoContainer.animate({
                    opacity: 0,
                    bottom: -45
                }, 300);
                page.animate({
                    opacity:0,
                    bottom: 40
                }, 300);
                tophomepage.animate({
                    opacity: 0
                });
                infoContainer.data('fullscreen', true);
            } else {
                infoContainer.animate({
                    opacity: 1,
                    bottom:0
                }, 300);

                page.animate({
                    opacity: 1,
                    bottom: 70
                }, 300);
                tophomepage.animate({
                    opacity: 1
                });
                infoContainer.data('fullscreen', false);
            }
        });
        pictureShowContainer.find('.ui-picture-single').on('load', function() {
            var oImg = $(this);
            var h = oImg.height();


            setTimeout(function() {
                var ch = $(window).height();
                oImg.css({
                    marginTop: (ch - h - 84) / 2
                });
            }, 200);
            setTimeout(function() {
                oImg.css({
                    visibility:'visible'
                });
            },200);
            if ($(this).parent().index() == initPage) {
                var summary = $(this).parent().find(".ui-single-summary").text();
                $('.ui-picture-extra-info')[summary ? 'show' : 'hide']()
                infoContainer.find('.ui-single-summary').html(slideEllipsisForPicture(3, summary));
                infoContainer.attr("data-ellipsis", 1);
                infoContainer.attr("data-current-page", 0);
            }
        });

        setTimeout(function()
        {
                viewport_width = $(window).width(),
                viewport_height = $(window).height() - 44;
                var pageTitle = '';
                pictureShowContainer.animate({
                    opacity: 1
                }, 500);

                pictureShowContainer.css('height', viewport_height);
                // pictureShowContainer.find('li').css('height', viewport_height);
                var swipeObject = new SwipeSlide(pictureShowContainer, {
                        first: initPage,
                        bulletNavigation: false,
                        beforeChange: function(S, currentPage, newPage) {
                            if (infoContainer.data('fullscreen') == false) {
                                infoContainer.animate({
                                    opacity: 1,
                                    bottom:0
                                }, 300);
                            }
                            if (pageTitle != '') {
                                $(".ui-pagetitle").html(pageTitle);
                                page = $('.js-picture-page');
                            }
                            setTimeout(function(){ window.scrollTo(0, 1);}, 300);
                        },
                        afterChange: function(S, currentPage, newPage) {
                            if ((parseInt(currentPage, 10)+1) == swipeObject.numPages) {
                                if (infoContainer.data('fullscreen') == false) {
                                    infoContainer.animate({
                                        opacity: 0,
                                        bottom: -45
                                    }, 300);
                                }
                                pageTitle = $(".ui-pagetitle").html();
                                $(".ui-pagetitle").html("推荐图集");
                                return false;
                            }
                            var li = S.slides[currentPage];
                            var summary = $(li).find('.ui-single-summary').html();
                            $('.ui-picture-extra-info')[summary ? 'show' : 'hide']()
                            infoContainer.find('.ui-single-summary').html(slideEllipsisForPicture(3, summary));
                            infoContainer.attr("data-ellipsis", 1);
                            infoContainer.attr("data-current-page", currentPage);
                            page.find('span:first').html(parseInt(currentPage, 10)+1);
                            pageTitle = '';
                        }
                    });

                page.find('span:first').html(parseInt(swipeObject.options.first, 10)+1);
                page.find('span:last').html(swipeObject.numPages - 1);
                var margin_top = ($(window).height() - 54 - Math.ceil($(".ui-picture-list").find("li").length / 2)*(137+10)) / 2;
                pictureShowContainer.find(".ui-swipeslide-slide").eq(swipeObject.numPages - 1).css("margin-top", margin_top + 'px');
        }, 1000);

        infoContainer.click(function(){
            var currentPage = infoContainer.attr("data-current-page");
            var summary = $(".ui-swipeslide-slide").eq(currentPage).find(".ui-single-summary").html();
            if (infoContainer.attr("data-ellipsis") == "1") {
                infoContainer.find('.ui-single-summary').html(summary);
                infoContainer.attr("data-ellipsis", "0");
                infoContainer.find("img").attr('src', details_up);
            } else {
                infoContainer.find('.ui-single-summary').html(slideEllipsisForPicture(3, summary));
                infoContainer.attr("data-ellipsis", "1");
                infoContainer.find("img").attr('src', details_down);
            }
        });
    }
    if($(document.body).height() < $(window).height()) {
        $('.ui-container').css({
            minHeight: $(window).height() - 60
        });
    }

});

// 保存到主屏
var saveToScreen = {
        el: null,

        setIcon: function(src) {
            this.tpl = '<div class="ui-ios-savescreen js-hidden"><p>先点击<img src="'+src+'" width="16" height="12" alt=""><br />再"添加至主屏幕"</p><div class="ios-icon"></div><div></div></div>';
        },

        init:  function(src) {
            var self = this , doc = document;
            if(navigator.standalone === true) return;
            if($.os.iphone && localStorage.getItem('savetoscreen') != 'true') {
                this.setIcon(src);
                var el = this.el = $(this.tpl);
                $(doc.body).append(el);
                setTimeout(function() {
                    setTimeout(function() {
                        self.hide();
                    }, 15*1000);
                    self.show();
                    el.data('height', el.height()+10);
                    self.scroll();
                    el.find(':last').click(function() {
                        self.hide();
                    });
                }, 3000);
            }
        },

        show: function() {
            this.el.removeClass('js-hidden');
        },

        hide: function() {
            var el = this.el;
            el.addClass('js-hidden');
            el.data('close', true);
            localStorage.setItem('savetoscreen', 'true');
        },

        scroll: function() {
            var el = this.el;
            if(el.data('close') === true) return;
            // var doc = document;
            var el = this.el;
            var h = el.data('height');
            
            // var t = window.innerHeight + (doc.documentElement.scrollTop || doc.body.scrollTop);
            el.css('top', window.innerHeight - h);
            // el.animate({
            //  top: t - h
            // }, 400, 'ease-in');
        }
}

