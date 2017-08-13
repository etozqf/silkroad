(function(window, $){
var document = window.document,
    docElem = document.documentElement;

function fixPosition(context) {
    var origPosition = context.css('position');
    if (!origPosition || origPosition === 'static') {
        context.css('position', 'relative');
    } else {
        origPosition = null;
    }
    return function(){
        origPosition && context.css('position', '');
    };
}
function fixContext(context) {
    if (context.nodeType !== 1) {
        context = (context.nodeType === 9 ? context : context.ownerDocument).body;
    }
    var $context = $(context);
    return context === document.body ? {
        context : $context,
        position : 'fixed',
        width : window.innerWidth || docElem.clientWidth,
        height : window.innerHeight || docElem.clientHeight,
        reset : function(){}
    } : {
        context : $context,
        position : 'absolute',
        width : context.offsetWidth,
        height : context.offsetHeight,
        reset : fixPosition($context)
    };
}
function infoMessage(type, context, message, sec) {
    context = fixContext(context);
    var box = $('<div class="ui-message ui-message-'+type+'">'+
        '<i class="ui-messageicon"></i>'+
        '<span class="ui-messagetext">'+message+'</span>'+
        '<div class="ui-messageclose">'+
    '</div>').appendTo(context.context), iv, h = 0;
    box.css({
        position:context.position,
        left:(context.width - box.outerWidth()) / 2
    }).css({visibility:'visible', display:'none'}).slideDown(100).hover(function(){
        iv && clearTimeout(iv);
    }, function(){
        h || timer();
    });
    function hide(){
        h = 1;
        iv && clearTimeout(iv);
        iv = 0;
        box.slideUp(100, function(){
            context.reset();
            box.remove();
        });
    }
    function timer(){
        iv = setTimeout(hide, (sec || 2) * 1000);
    }
    box.find('.ui-messageclose').click(hide);
    timer();
}
var messageHandler = {
    // 确认做某事
    confirm:function(message, action){
        var context = fixContext(this),
        overlay = $('<div class="ui-messageoverlay"></div>')
            .css('position', context.position).appendTo(context.context),
        box = $('<div class="ui-message ui-message-confirm">' +
            '<i class="ui-messageicon"></i>'+
            '<span class="ui-messagetext">'+message+'</span>'+
            '<div class="ui-messagearea">'+
                '<a class="ui-messagebutton ui-messagebutton-ok" role="ok">好的</a>'+
                '<a class="ui-messagebutton">不要</a>'+
            '</div>'+
        '</div>').appendTo(context.context);
        box.css({
            position:context.position,
            left:(context.width - box.outerWidth()) / 2,
            top:context.height * .38 - box.outerHeight() / 2
        }).css({visibility:'visible', display:'none'}).fadeIn('fast');
        box.find('.ui-messagebutton').click(function(){
            box.fadeOut('fast', function(){
                context.reset();
                box.remove();
                overlay.remove();
            });
            action && this.getAttribute('role') && action();
        });
    },
    // 重要警告
    warn:function(message){
        var context = fixContext(this),
        overlay = $('<div class="ui-messageoverlay"></div>')
            .css('position', context.position).appendTo(context.context),
        box = $('<div class="ui-message ui-message-warn">' +
            '<i class="ui-messageicon"></i>'+
            '<span class="ui-messagetext">'+message+'</span>'+
            '<div class="ui-messagearea">'+
                '<a class="ui-messagebutton ui-messagebutton-ok">知道了</a>'+
            '</div>'+
        '</div>').appendTo(context.context);
        box.css({
            position:context.position,
            left:(context.width - box.outerWidth()) / 2,
            top:context.height * .38 - box.outerHeight() / 2
        }).css({visibility:'visible', display:'none'}).fadeIn('fast');
        box.find('.ui-messagebutton').click(function(){
            box.fadeOut('fast', function(){
                context.reset();
                box.remove();
                overlay.remove();
            });
        });
    },
    // 定时执行
    timer:function(message, action, sec){
        if (typeof sec == 'function' || typeof action != 'function') {
            var swap = action;
            action = sec;
            sec = swap;
        }
        sec = sec || 5;
        message = message.replace('%t', '<b class="ui-messagetimer">'+sec+'</b>');
        var context = fixContext(this),
        overlay = $('<div class="ui-messageoverlay"></div>')
            .css('position', context.position).appendTo(context.context),
        box = $('<div class="ui-message">'+
            '<span class="ui-messagetext">'+message+'</span>'+
            '<div class="ui-messagearea">'+
                '<a class="ui-messagebutton ui-messagebutton-ok">立即</a>'+
            '</div>'+
        '</div>').appendTo(context.context), timer = box.find('.ui-messagetimer'), iv;
        function clause(){
            iv && clearInterval(iv);
            iv = null;
            context.reset();
            overlay.remove();
            box.remove();
            action && action();
            return false;
        }
        box.css({
            position:context.position,
            left:(context.width - box.outerWidth()) / 2,
            top:context.height * .38 - box.outerHeight() / 2
        }).css({visibility:'visible', display:'none'}).fadeIn('fast')
        iv = setInterval(function(){
            timer.text(--sec);
            sec < 1 && clause();
        }, 1000);
        box.find('.ui-messagebutton').click(clause);
    },
    /**
     *
     * @param message string
     * @param detail Array
     * @param sec int
     */
    detail:function(message, detail, sec) {
        var context = fixContext(this),
        box = $('<div class="ui-message ui-message-detail">'+
            '<span class="ui-messagetext">'+message+'</span>'+
            '<div class="ui-messagedetail"><div class="ui-messagedetail-inner">'+detail.join('<br />')+'</div></div>'+
            '<div class="ui-messageclose">'+
        '</div>').appendTo(context.context), text = box.find('.ui-messagetext'),
        drop = box.find('.ui-messagedetail'), iv, h = 0, s = 0;
        text.click(function(){
            drop.stop(true, true)[s ? 'slideUp' : 'slideDown']('fast');
            s = !s;
        });
        box.css({
            position:context.position,
            left:(context.width - box.outerWidth()) / 2,
            width:box.width()
        }).css({visibility:'visible', display:'none'}).slideDown(100).hover(function(){
            iv && clearTimeout(iv);
        }, function(){
            h || timer();
        });
        function hide(){
            h = 1;
            iv && clearTimeout(iv);
            iv = 0;
            box.slideUp(100, function(){
                context.reset();
                box.remove();
            });
        }
        function timer(){
            iv = setTimeout(hide, (sec || 3) * 1000);
        }
        box.find('.ui-messageclose').click(hide);
        timer();

    },
    // 普通提示
    info:function(message, sec){
        infoMessage('info', this, message, sec || 2);
    },
    // 成功提示
    success:function(message, sec){
        infoMessage('success', this, message, sec || 2);
    },
    // 失败提示
    fail:function(message, sec){
        infoMessage('fail', this, message, sec || 3);
    }
};
var slice = [].slice;
$.fn.message = function(type, message, sec, callback) {
    type = type ? type in messageHandler
        ? type : type == 'alert' ? 'warn' : type == 'error' ? 'fail' : 'info' : 'info';
    var args = slice.call(arguments, 1);
    return this.each(function(){
        messageHandler[type].apply(this, args);
    });
};
})(window, jQuery);