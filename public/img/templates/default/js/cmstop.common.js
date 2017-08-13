/**
 * 设置右侧返回顶部的left值，使其能够紧贴在内容区域的右侧
 * @target {[jquery object]} 相对于某个元素
 */
function setScroll(target, elem) {
    var scrollPanel = elem;
    var left        = target.offset().left;
    var width       = target.width();
    scrollPanel.css('left', left+width+10);
}

/**
 * 视频缩略图点击显示播放图标
 */
function overlayPlay(elem) {
    var overlayElem = elem.find('.overlay');
    var thumbLink   = overlayElem.siblings('.thumb-link');
    var w           = thumbLink.width();
    var h           = thumbLink.height();

    var timeSpan = $('.time',elem);
    timeSpan.attr('data-bgc', timeSpan.css('backgroundColor'));

    timeSpan.css({'background-color':'transparent'});
    
    overlayElem.css({
        width: w,
        height: h
    }).find('.overlay-play').css({
        marginLeft: (w-40)/2,
        marginTop: (h-40)/2
    })

    overlayElem.fadeIn('fast');
}

/*小点切换*/
function shoveler(elem) {
    var items         = elem.find('.shoveler-item')
    ,aWidth           = items.first().width()
    ,fullWidth        = aWidth * items.size()
    ,shovelerContains = elem.find('.shoveler-contains')
    ,_go              = function(i, o) {
        shovelerContains.animate({
            'margin-left': -i * aWidth
        }).data('index', i);
        o.addClass('current').siblings().removeClass('current');
    }
    ,t = 5000
    ,T;

    shovelerContains.data('index', 0).width(fullWidth);

    var pointElem = $('.shoveler-point');
    for(var i = 0; i < items.size(); i++) {
        var pointerElem = $('<a href=""></a>').appendTo(pointElem).click(function(e) {
            pause();
            _go($(this).index(), $(this));
            // shovelerContains.animate({
            //     'margin-left': -$(this).index() * aWidth
            // }).data('index', $(this).index());
            // $(this).addClass('current').siblings().removeClass('current');
            e.preventDefault();
        });
        if(i === 0) {
            pointerElem.addClass('current');
        }
    }

    // 自动切换

    var auto = function() {
        T = setInterval(function() {
            var i = parseInt(shovelerContains.data('index'), 10)
            ,ps   = pointElem.find('a')
            ,len  = ps.length;
            i++;
            if(i >= len) {
                i = 0;
            }
            _go(i, ps.eq(i));
        }, t);
    }

    ,pause = function() {
        clearInterval(T);
        auto();
    }

    auto();
}

// 第一个节点是否包含第二个节点
function contains(parentEl, el, container) {
    //contains 方法支持情况：chrome+ firefox9+ ie5+, opera9.64+(估计从9.0+),safari5.1.7+
     if (parentEl == el) {
         return true;
     }
     if (!el || !el.nodeType || el.nodeType != 1) {
         return false;
     }
     if (parentEl.contains ) {
         return parentEl.contains(el);
     }
     if ( parentEl.compareDocumentPosition ) {
         return !!(parentEl.compareDocumentPosition(el) & 16);
     }
     var prEl = el.parentNode;
     while(prEl && prEl != container) {
         if (prEl == parentEl)
             return true;
         prEl = prEl.parentNode;
     }
     return false;
}



$(function(){
    var $body = $(document.body);
    // 手风琴：标题与图文切换
    var accordionElem = $('.js-accordion'), 
        lastAccordElem = accordionElem.find('.m-accordion-item').first().find('.title');

    accordionElem.find('.m-accordion-item .title').mouseenter(function() {
        $(this).next().css('display', 'block');
        $(this).hide();
        lastAccordElem.show().next().css('display','none');
        lastAccordElem = $(this);
    });

    // 返回顶部滚动
    if($('.js-scrollbtns')[0]) {
        
        // 鼠标移上变文字
        var scrollBtns = $('.js-scrollbtns .btn')
        scrollBtns.mouseenter(function(){
            $(this).removeClass('home icon40x40').find('.text').css('display','block');
        }).mouseleave(function(){
            $(this).addClass('home icon40x40').find('.text').css('display','none');
        });

        // 调用插件, 小火箭效果
        $(".scroll-btns").returntop();

        // 设置右侧浮动面板的位置
        // 文章内容页
        // 视频左右窗口页
        // 组图瀑布流页面
        // 专栏频道页
        // 专栏列表页
        if($('.js-returntop')[0]) setScroll($('.js-returntop'), $('.js-scrollbtns'));
    }
    // 首页微信扫描
    if($('.weixinpanel')[0]) {
       setScroll($('.js-returntop'), $('.weixinpanel'));
    }

    // 圆点切换内容
    shoveler($('.js-shoveler'));

    // 二维码提示
    if($('.js-disilicide')[0]) {
        $('.js-disilicide').mouseenter(function(){
            $(this).find('.cover').removeClass('hidden');
        }).mouseleave(function(){
            $(this).find('.cover').addClass('hidden');
        });
    }

    var replyinterval = setInterval(function(){
        if($('.reply-box')[0]) {
            var repCls    = 'reply-box'
            , prepCls     = '.'+repCls
            , repHoverCls = repCls+'-hover'
            , ci          = '>.comment-interaction';
            
            // 评论盖楼换色
            $('.reply-box').mouseenter(function(e) {
                $(this).addClass(repHoverCls).find(ci).removeClass('hidden').addClass('hover');
                $(this).parents(prepCls).removeClass(repHoverCls).find(ci).addClass('hidden').removeClass('hover');
            }).mouseleave(function(e){
                var $rel = $(e.relatedTarget);
                if($rel.is(prepCls)) {
                    $rel.addClass(repHoverCls).find(ci).removeClass('hidden').addClass('hover');
                } else if($rel.is('.reply-box-username')) {
                    $rel = $rel.parents(prepCls);
                    $rel.addClass(repHoverCls).find(ci).removeClass('hidden').addClass('hover');
                }
                $(this).removeClass(repHoverCls).find(ci).addClass('hidden').removeClass('hover');
            });
            clearInterval(replyinterval);
        }
    },300);
    

    // 鼠标移动到大图，蒙版层逐渐消失,css3动画无法实现background-image才用js实现
    // 视频频道页、专栏频道页
    if($('.js-hoveroverlay')[0]) {
        $body.on('mouseenter', '._overlay', function(e) {
            var $this = $(e.target);
            $this.stop().animate({
                'opacity': 0
            });
        });
        $body.on('mouseleave', '._overlay', function(e) {
            var $this = $(e.target);
            $this.stop().animate({
                'opacity': 1
            });
        });
        $body.on('mouseenter', '.titlepanel', function(e) {
            if(!contains($('.titlepanel')[0], e.target)) return ;
                var $this = $(e.target);
                $this.parents('.bigimage').find('._overlay').stop().animate({
                    'opacity': 0
                });
        });
        $body.on('mouseleave', '.titlepanel', function(e) {
            if(!contains($('.titlepanel')[0], e.target)) return ;
                var $this = $(e.target);
                $this.parents('.bigimage').find('._overlay').stop().animate({
                    'opacity': 1
                });
        });
    }


    // 弹窗登录, 依赖dialog插件
    var _dialog;
    function doDialog() {
        _dialog = dialog({
            title:'用户登录',
            content: $('.login-dialog-test'),
            fixed: true,
            skin: 'ui-dialog-login'
        });
        _dialog.showModal();
    }

    if($('.js-ajax-login')[0]) {
        $(document).on('click', '.js-ajax-login', function(e) {
            doDialog();
            e.preventDefault();
        }).on('focus blur', '.js-focus', function(e) {
            $(this).parents('.singup-line').toggleClass('active-border-blue');
        }).on('focus', '.js-focus', function() {
            $(this).siblings('.tip').addClass('hidden');
        });
    }

    // 文本框聚焦和失去焦点提示文本显示与隐藏
    if($('.singup-box')[0]) {
        $('.singup-box').on('click', '.tip', function() {
            var $this = $(this)
            ,oInput = $this.siblings('input[type=text],input[type=password]');
            $this.addClass('hidden');
            oInput.focus();
        }).on('blur', 'input[type=text],input[type=password]', function() {
            if(!$(this).val().length) {
                $(this).siblings('.tip').toggleClass('hidden');
            }
        });
    }

    if($('.m-input-text')) {
        $(document).on('focus','.m-input-text',function() {
            if($(this).val().length && $(this).val() == this.defaultValue) {
                $(this).removeClass('i-gray');
                $(this).val('');
            }
        }).on('blur','.m-input-text',function(){
            if(!$(this).val().length) {
                $(this).addClass('i-gray');
                $(this).val(this.defaultValue);
            }
        })
    }

    // 点击用户头像 依赖dialog插件
    $(document).on('click', '.js-userphoto', function(){
        var _d = dialog({
            follow: this,
            content: $('.usermenu'),
            quickClose: true,
            skin: 'ui-usermenu'
        });
        _d.show();
    });

    // 顶部搜索条
    $(document).on('click', '.js-headsearch', function(e) {
        $(this).addClass('hidden').siblings('.header-search-input').removeClass('hidden');
        e.preventDefault();
    }).on('blur', '.searinput', function() {
        var $this = $(this);
        setTimeout(function(){
            $this.parent().parent().addClass('hidden').siblings('.js-headsearch').removeClass('hidden');
            $this.val('');
        }, 400);
    });


    // 历史页面，依赖datepicker日历插件、dialog插件
    $(document).on('click','.js-datepicker', function(e) {

        var _d = dialog({
            follow: this,
            content: false,
            quickClose: true,
            skin: 'ui-datepicker',
            id: 'datepicker',
            width: 300,
            onshow: function() {
                $(this.__activeElement).toggleClass('active');
            },
            onclose: function() {
                $(this.__activeElement).toggleClass('active');

            }
        })
        ,gid = function(id) {
            return document.getElementById(id);
        }
        , dp_wrap  = gid('content:datepicker');
        _d.show();


        
        var _datepicker = new DatePanel({
            place: dp_wrap,
            format:'yyyy-MM-dd'
        });
        _datepicker.bind('MONTH_PREPARED', function(){

        });
        _datepicker.create();

        e.preventDefault();
    });

    // 点赞+1
    if($('.js-ilove')[0]) {
        $(document).on('click','.js-ilove', function(e) {
            var $this = $(this), offset = $this.offset();
            if($this.data('clicked')) return false;
            $this.addClass('click').data('clicked',1);
            $.getJSON(APP_URL+'?app=digg&controller=digg&action=digg&contentid='+contentid+'&jsoncallback=?&flag=1', function(data){
                if(data > 0) {
                    var pop = $('<div class="pos-a">+1</div>').appendTo(document.body);
                    pop.css({
                        top: offset.top - 10,
                        left: offset.left + 10,
                        color: '#0091e4'
                    });
                    pop.animate({
                        top: offset.top - 40,
                        'opacity': 0
                    },function(){
                        pop.remove();
                        $this.addClass('loved').removeClass('click');
                    });
                    $this.find('span').text(data);
                } else {
                    alert('您已经顶过了');
                    return;
                }
            });
            e.preventDefault();       
        });
    }

    // 稿件页面的左侧的边框根据右侧内容来确定
    if($('.member-main .member-left')[0]) {
        $('.member-left .inner').height(Math.max($('.member-right').height(), 304));
    }

    // 报名页面弹窗
    if($('.js-gojoin')[0]) {
        var _join_dialog;
        $('.js-gojoin').on('click', function(e) {
            _join_dialog = dialog({
                title:'报名',
                content: $('.joinform'),
                fixed: true
            });
            _join_dialog.showModal();
            e.preventDefault();
        });

        $('.joinform .cancel').on('click', function(e){
            _join_dialog.close();
            e.preventDefault();
        });
    }

    // 
    function dowith_weixinad(container) {
        var isIE  = !!window.ActiveXObject
        ,isIE6 = isIE&&!window.XMLHttpRequest
        ,b = container
        ,c = null;

        $(window).on("scroll", function() {
            var d = $(document).scrollTop();

            isIE6 && (b.hide(), clearTimeout(c), c = setTimeout(function() {
                b.show();
                clearTimeout(c)
            },1000), b.css("top", d + 136));
        });

        b.on('click', '.title', function() {
            var $this = $(this)
            ,prev = $('.prev', b)
            ,box = $this.next('.img');
            if(box.is('.prev')) return;
            prev.animate({
                height: 0
            }, 'fast',function(){
                prev.removeClass('prev');
            });
            box.height(0).animate({
                height: '100px'
            }, 'fast', function() {
                box.addClass('prev');
            });
        }).on('click', '.close', function() {
            b.remove();
        });
    }
    if($('.weixinpanel')[0]) dowith_weixinad($('.weixinpanel'));

    (function () {
        var categoryList, links;
        categoryList = $('.category-list');
        if (!categoryList.length) return;
        categoryList
            .on('click', '.open', function (event) {
                $(event.delegateTarget).addClass('all');
            })
            .on('click', '.close', function (event) {
                $(event.delegateTarget).removeClass('all');
            });
        links = categoryList.children('a');
        if (links.first().offset().top === links.last().offset().top) {
            categoryList.children('.open').remove();
            categoryList.children('.close').remove();
        }
    }());
});

// ie下批量处理png24灰背景的问题
function fixpng24(){
    var arVersion = navigator.appVersion.split("MSIE");
    var version = parseFloat(arVersion[1]);
    if ((version >= 5.5) && (document.body.filters)){
       for(var i=0; i<document.images.length; i++){
          var img = document.images[i];
          if (img.src.toLowerCase().slice(-3) == "png"){
             var imgID = (img.id) ? "id='" + img.id + "' " : "";
             var imgClass = (img.className) ? "class='" + img.className + "' " : "";
             var imgTitle = (img.title) ? "title='" + img.title + "' " : "title='" + img.alt + "' ";
             var imgStyle = "display:inline-block;" + img.style.cssText ;
             if (img.align == "left") imgStyle = "float:left;" + imgStyle;
             if (img.align == "right") imgStyle = "float:right;" + imgStyle;
             if (img.parentElement.href) imgStyle = "cursor:pointer;" + imgStyle;
             var strNewHTML = "<span " + imgID + imgClass + imgTitle
             + " style=\"width:" + img.width + "px; height:" + img.height + "px;" + imgStyle
             + "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader"
             + "(src='" + img.src + "', sizingMethod='scale');\"></span>";
             img.outerHTML = strNewHTML;
             i--;
          }
       }
    }
}

$(window).on('load', function() {
    var ie = !!window.ActiveXObject
    ,ie6   = ie&&!window.XMLHttpRequest
    ,ie8   = ie&&!!document.documentMode
    ,ie7   = ie&&!ie6&&!ie8; 
    if(ie6 || ie7 || ie8) fixpng24();
});








