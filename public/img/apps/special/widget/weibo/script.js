(function($) {
$.fn.serializeObject || ($.fn.serializeObject = function() {
    var output = {},
        array = this.serializeArray();
    $.each(array, function() {
        if (output[this.name] !== undefined) {
            if (! output[this.name].push) {
                output[this.name] = [output[this.name]];
            }
            output[this.name].push(this.value || '');
        } else {
            output[this.name] = this.value || '';
        }
    });
    return output;
});
})(jQuery);

(function(){
/* 微博内容 content */
var tplItem, tplEmpty,
    weiboContent;
function formatTimestamp(timestamp) {
    var time = new Date(), hour, minute;
    time.setTime(parseInt(timestamp) * 1000);
    hour = time.getHours();
    minute = time.getMinutes();
    return [
        time.getFullYear(), '年',
        time.getMonth() + 1, '月',
        time.getDate(), '日 ',
        (hour > 9 ? hour : '0' + hour), ':',
        (minute > 9 ? minute : '0' + minute)
    ].join('');
}
var sinaMidToURL = function() {
    var keys = [], c;
    for (c = 0; c <= 9; c++) keys.push(c + '');
    for (c = 97; c <= 122; c++) keys.push(String.fromCharCode(c));
    for (c = 65; c <= 90; c++) keys.push(String.fromCharCode(c));
    function toBase62(val) {
        var result = '', tmp = 0;
        while (val != 0) {
            tmp = val % 62;
            result += keys[tmp];
            val = Math.floor(val / 62);
        }
        return result;
    }
    function revert(val) {
        return (val + '').split('').reverse().join('');
    }
    return function(mid) {
        mid = mid + '';
        var url = '',
            rev = revert(mid),
            split = rev.split(/(.{7})/),
            index, part;
        for (index in split) {
            part = split[index];
            if (part === '') continue;
            part = toBase62(revert(part));
            url += part;
        }
        return revert(url);
    };
}();
var R_TOPIC = /(#([^#]+)#)/g,
    R_TENCENT_AT = /(@([a-z][\w\-]*))/ig,
    R_SINA_AT = /(@([0-9a-zA-Z\u4e00-\u9fa5_-]+))/ig,
    R_URL = /(https?:\/\/[^\s\b]+)/ig;
function formatTencentText(text) {
    return text.replace(R_TOPIC, function() {
        return '<a href="http://k.t.qq.com/k/' + encodeURIComponent(RegExp.$2) + '" target="_blank">' + RegExp.$1 + '</a>';
    }).replace(R_TENCENT_AT, function() {
        return '<a href="http://t.qq.com/' + RegExp.$2 + '" target="_blank">' + RegExp.$1 + '</a>';
    });
}
function formatSinaText(text) {
    return text.replace(R_URL, function() {
        return '<a href="' + RegExp.$1 + '" target="_blank">' + RegExp.$1 + '</a>';
    }).replace(R_TOPIC, function() {
        return '<a href="http://s.weibo.com/weibo/' + encodeURIComponent(RegExp.$2) + '" target="_blank">' + RegExp.$1 + '</a>';
    }).replace(R_SINA_AT, function() {
        return '<a href="http://weibo.com/n/' + RegExp.$2 + '" target="_blank">' + RegExp.$1 + '</a>';
    });
}
function renderEmpty() {
    weiboContent.empty().append(tplEmpty.render({}));
}
function renderItems(items, formated) {
    weiboContent.find('.weibo-content-empty').remove();
    $.each(items, function(index, item) {
        var row = $(renderWeibo(index, item, formated)).appendTo(weiboContent);
        row.find('[data-role=remove]').click(function() {
            var count = row.siblings().length;
            row.remove();
            ! count && renderEmpty();
        });
    });
}
function renderWeibo(index, weibo, formated) {
    var item;
    if (formated) {
        return tplItem.render(weibo);
    }
    switch (weibo.weibo_type) {
        case 'tencent_weibo':
            item = {
                index: index,
                platform: 'tencent',
                avatar: weibo.head ? (weibo.head + '/50') : (IMG_URL + 'images/nohead.jpg'),
                profile: 'http://t.qq.com/' + weibo.name,
                username: weibo.nick,
                vtype: weibo.isvip ? 'tencent-vip' : null,
                text: formatTencentText(weibo.text),
                url: 'http://t.qq.com/p/t/' + weibo.id,
                time: formatTimestamp(weibo.timestamp),
                image: weibo.image ? [weibo.image[0] + '/120', weibo.image[0] + '/460'] : null
            };
            break;
        case 'sina_weibo':
            item = {
                index: index,
                platform: 'sina',
                avatar: weibo.user.profile_image_url,
                profile: 'http://weibo.com/' + (weibo.user.domain || 'u/' + weibo.user.id),
                username: weibo.user.screen_name,
                vtype: weibo.user.verified ? (weibo.user.verified_type > 0 ? 'sina-approve_co' : 'sina-approve') : null,
                text: formatSinaText(weibo.text),
                url: 'http://weibo.com/' + weibo.user.id + '/' + sinaMidToURL(weibo.mid),
                time: formatTimestamp(weibo.created_at),
                image: weibo.thumbnail_pic ? [weibo.thumbnail_pic, weibo.bmiddle_pic, weibo.original_pic] : null
            };
            break;
        default:
            break;
    }
    return tplItem.render(item);
}
function bindEvents(form) {
    form.find('[data-role=pick]').click(function() {
        var dialog = ct.iframe({
            url: '?app=weibo&controller=weibo&action=search',
            width: 740
        }, {
            ok: function(items) {
                if (items) {
                    renderItems(items);
                }
                dialog.dialog('close');
            },
            close: function() {
                dialog.dialog('close');
            }
        });
        return false;
    });
}

/* 微博直播 live */
var sinaLive = function() {
    var html,
        liveURL =new Template('<iframe width="{%width%}" height="{%height%}"  frameborder="0" scrolling="no" src="http://widget.weibo.com/livestream/listlive.php?language=zh_cn&width={%width%}&height={%height%}&skin={%skin%}&refer=1&pic={%isShowPic%}&titlebar=1&border=1&publish=1&atalk=1&recomm=0&at=0&atopic={%tags || "cmstop"%}&ptopic={%tags || "cmstop"%}&dpc=1"></iframe>');
    return function(placeholder, input) {
        if (! html) {
            html = $((new Template($('#tpl-weibo-live-sina').html() || '')).render({}));
        }
        html.appendTo(placeholder).find(':input').change(function() {
            var values = html.find(':input').serializeObject();
            input.val(liveURL.render({
                width: values['sina[width]'],
                height: values['sina[height]'],
                tags: values['sina[tags]'],
                skin: values['sina[skin]'],
                isShowPic: values['sina[isShowPic]']
                //wordLength: values['sina[wordLength]'] || '',
                //mblogNum: values['sina[mblogNum]']
            }));
        }).trigger('change').keyup(function() {
            $(this).change();
        });
    };
}();

var qqLive = function() {
    var html,
    liveURL = new Template('<iframe frameborder="0" scrolling="no" src="http://wall.v.t.qq.com/index.php?c=wall&a=index&t={%tags || "cmstop"%}&ak={%appkey%}&w={%width || 0%}&h={%height || 550%}&o={%option || 7%}&s={%style || 0%}{%cs%}" width="{%width || "100%"%}" height="{%height || 550%}"></iframe>');
    return function(placeholder, input) {
        if (! html) {
            html = $((new Template($('#tpl-weibo-live-qq').html() || '')).render({}));
        }
        html.appendTo(placeholder).find(':input').change(function() {
            var values = html.find(':input').serializeObject();
            input.val(liveURL.render({
                tags: values['qq[tags]'],
                width: values['qq[autowidth]'] ? 0 : Math.max(255, Math.min(1024, values['qq[width]'])),
                height: Math.max(300, Math.min(800, values['qq[height]'])),
                style: (values['qq[is_custom]']==1) ? 4 : values['qq[style]'],
                cs: (values['qq[is_custom]']==1) ? "&cs="+(new Array(
                        values['qq[custom_color0]'].substr(1),
                        values['qq[custom_color1]'].substr(1),
                        values['qq[custom_color2]'].substr(1),
                        values['qq[custom_color3]'].substr(1),
                        values['qq[custom_color4]'].substr(1),
                        values['qq[custom_color5]'].substr(1)
                    ).join('_')) : '',
                option: (parseInt(values['qq[thumb]']) + parseInt(values['qq[post]'])),
                appkey: values['qq[appkey]']
            }));
        });
        html.appendTo(placeholder).find(':input').change();
    };
}();

function liveTips(box, provider, label) {
    var url = {
            sina : 'http://open.t.sina.com.cn/wiki/index.php/%E5%BE%AE%E5%8D%9Awidget',
            qq : 'http://open.t.qq.com/apps/wall/explain.php'
        },
        generator = box.find('[data-role=generator]').empty(),
        input = box.find('[name="data[code]"]');
    if (provider == 'sina') {
        sinaLive(generator, input);
        return;
    }
    if (provider == 'qq') {
        qqLive(generator, input);
        return;
    }
}

/* 微博秀 show */
function showTips(box, provider, label) {
    var url = {
        sina : 'http://app.weibo.com/tool/weiboshow',
        qq : 'http://open.t.qq.com/apps/show/explain2.php'
    };
    box.find('[data-role=generator]').html('访问 <a href="' + url[provider] + '" target="_blank">' + label + '微博 微博秀生成器</a> 来生成代码，并粘贴到下面');
}

/* tab 初始化 */
var init = {
    0:function(form, dialog){ // content
        tplItem = new Template($('#tpl-weibo-item').html());
        tplEmpty = new Template($('#tpl-weibo-empty').html());
        weiboContent = dialog.find('#weibo-content');
        try {
            var items = JSON.parse(weiboContent.find('textarea').val());
            weiboContent.empty();
            if (items.length) {
                $.each(items, function(index) {
                    items[index]['image'] && (items[index]['image'] = JSON.parse(items[index]['image']));
                });
                renderItems(items, true);
            } else {
                renderEmpty();
            }
        } catch (ex) {}
        bindEvents(form);
    },
    1:function(form, dialog, tbody){ // live
        tbody.find('[data-role=live-provider]').change(function(){
            liveTips(tbody, this.options[this.selectedIndex].value, this.options[this.selectedIndex].text);
        }).trigger('change');
        if (!form.find('.weibo_color_input').eq(0).hasClass('color-input')) {
            form.find('.weibo_color_input').colorInput();
        }
        $('.qq_style').bind('click', function(e) {
            var o = $(e.currentTarget);
            o.next().click().change();
            $('.qq_style').removeClass('qq_style_cur');
            o.addClass('qq_style_cur');
        });
        $('.is_custom').bind('click', function(e) {
            if (e.currentTarget.value == 0) {
                $('.custom1').hide();
                $('.custom0').show();
            } else if(e.currentTarget.value == 1) {
                $('.custom0').hide();
                $('.custom1').show();
            }
        });
    },
    2:function(form, dialog, tbody){ // show
        tbody.find('[data-role=show-provider]').change(function(){
            showTips(tbody, this.options[this.selectedIndex].value, this.options[this.selectedIndex].text);
        }).trigger('change');
    }
};

DIY.registerEngine('weibo', {
    dialogWidth : 600,
    addFormReady:function(form, dialog) {
        DIY.tabs(dialog,function(i, tbody){
            form[0].method.value = i;
            if (! $.data(this, 'inited')) {
                $.data(this, 'inited', 1);
                init[i](form, dialog, tbody);
            }
        });
    },
    editFormReady:function(form, dialog) {
        DIY.tabs(dialog,function(i, tbody){
            form[0].method.value = i;
            if (! $.data(this, 'inited')) {
                $.data(this, 'inited', 1);
                init[i](form, dialog, tbody);
            }
        }, form[0].method.value);
    },
    afterRender: function(widget) {},
    beforeSubmit:function(form, dialog){
        form.find('tbody:hidden')
        .find('input,select,textarea').each(function(){
            if (!this.disabled) {
                this.setAttribute('notsubmit','1');
                this.disabled = true;
            }
        });
    },
    afterSubmit:function(form, dialog){
        form.find('tbody:hidden')
        .find('input,select,textarea')
        .filter('[notsubmit]').removeAttr('disabled');
    }
});
})();
