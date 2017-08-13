(function(){
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
var htmlspecialchars = (function() {
    var maps = {
        "'": '&#39;',
        '"': '&quot;',
        '<': '&lt;',
        '>': '&gt;'
    };
    return function(html, decode) {
        if (! html) return html;
        for (var key in maps) {
            if (maps.hasOwnProperty(key)) {
                html = html.replace(new RegExp((decode ? maps[key] : key), 'gim'), (decode ? key : maps[key]));
            }
        }
        return html;
    }
})();
var R_TOPIC = /(#([^#]+)#)/g,
    R_TENCENT_AT = /(@([a-z][\w\-]*))/ig,
    R_SINA_AT = /(@([0-9a-zA-Z\u4e00-\u9fa5_-]+))/ig,
    R_URL = /((?:http|ftp|https):\/\/[\w-]+(?:\.[\w-]+)+(?:[\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?)/ig;
function formatTencentText(text) {
    return text.replace(R_URL, function() {
        return '<a href="' + RegExp.$1 + '" target="_blank">' + RegExp.$1 + '</a>';
    }).replace(R_TOPIC, function() {
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
        var row = renderWeibo(index, item, formated).appendTo(weiboContent);
        row.find('.weibo-image img').zoomImage();
        row.find('[data-role=remove]').click(function() {
            var count = row.siblings().length;
            row.remove();
            ! count && renderEmpty();
        });
    });
}
function renderWeibo(index, weibo, formated) {
    var item, result;
    if (formated) {
        result = $(tplItem.render(weibo));
        item = weibo;
    } else {
        switch (weibo.weibo_type) {
            case 'tencent_weibo':
                item = {
                    index: index,
                    platform: 'tencent',
                    avatar: weibo.head ? (weibo.head + '/50') : (IMG_URL + 'images/nohead.jpg'),
                    profile: 'http://t.qq.com/' + weibo.name,
                    username: weibo.nick,
                    vtype: weibo.isvip ? 'tencent-vip' : null,
                    text: formatTencentText(htmlspecialchars(weibo.origtext)),
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
                    text: formatSinaText(htmlspecialchars(weibo.text)),
                    url: 'http://weibo.com/' + weibo.user.id + '/' + sinaMidToURL(weibo.mid),
                    time: formatTimestamp(weibo.created_at),
                    image: weibo.thumbnail_pic ? [weibo.thumbnail_pic, weibo.bmiddle_pic, weibo.original_pic] : null
                };
                break;
            default:
                break;
        }
        result = $(tplItem.render(item));
    }
    result.find('[name=text]').val(item.text); // Hack，直接放入 <textarea>chars</textarea> 中的已转义字符会自动反转
    return result;
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

Addon.registerEngine('weibo', {
    dialogWidth: 630,
    afterRender: function(dialog) {
        dialog.find('.mod-tabs').tabs();
        tplItem = new Template($('#tpl-weibo-item').html());
        tplEmpty = new Template($('#tpl-weibo-empty').html());
        weiboContent = dialog.find('#weibo-content');
        bindEvents(dialog);
    },
    addFormReady: function(form, dialog) {
        renderEmpty();
        dialog.find('[data-role=pick]').trigger('click');
    },
    editFormReady: function(form, dialog) {
        var addon = Addon.readAddon('weibo');
        $.each(addon.data, function(index, item) {
            item.image && !$.isArray(item.image) && (item.image = JSON.parse(item.image));
            addon.data[index] = item;
        });
        renderItems(addon.data, true);
    },
    genData: function(form, dialog) {
        var data = {};
        dialog.find('.mod-weibo').each(function(index, item) {
            item = $(item).find(':input').serializeObject();
            item && item.index && (data[item.index] = item);
        });
        return data;
    },
    beforeSubmit: function(form, dialog) {
        if (! form.find('input').length) {
            ct.error('挂件内容为空');
            return false;
        }
    },
    afterSubmit: function(form, dialog) {}
});
})();