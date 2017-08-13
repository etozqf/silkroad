(function(){
var itemPlaceholder, itemTemplate;
function formatItem(data, callback) {
    $.post('?app=mobile&controller=addon&action=video_format', data, function(json) {
        callback(json);
    }, 'json');
}
function renderItem(data) {
    var item = $(itemTemplate.render(data));
    item.find('[data-role=url]').attr('href', data.url || 'javascript:void(0);');
    item.find('[name=url]').val(data.url);
    itemPlaceholder.empty().append(item);
}
function bindEvents(dialog) {
    dialog.find('[data-role=pick-content]').click(function() {
        var _dialog = ct.iframe({
            url:'?app=mobile&controller=content&action=picker&multiple=0&modelid=4&title='+encodeURIComponent('选择视频'),
            width:500
        }, {
            ok:function(item) {
                formatItem(item, function(json) {
                    renderItem(json);
                    bindEvents(dialog);
                });
                _dialog.dialog('destory').remove();
            }
        });
        return false;
    });
    dialog.find('[data-role=add]').click(function() {
        ct.assoc.open('?app=mobile&controller=video&action=add', 'newtab');
        $.cookie('addonvideo', '', {"expires":-1});
        cookieName = COOKIE_PRE + 'addonvideo';
        p = setInterval(function() {
            if ($.cookie(cookieName)) {
                clearInterval(p);
                item = eval('('+$.cookie(cookieName)+')');
                formatItem(item, function(json) {
                    if (json.thumb && json.thumb.substr(0, 4) !== 'http') {
                        json.thumb = UPLOAD_URL + json.thumb;
                    }
                    if (json.thumbig && json.thumbig.substr(0, 4) !== 'http') {
                        json.thumbig = UPLOAD_URL + json.thumbig;
                    }
                    renderItem(json);
                    bindEvents(dialog);
                });
                $.cookie(cookieName, '', {"expires":-1});
            }
        }, 5000);
        return false;
    });
}

fet('cmstop', function() {
    MobileAddon.registerEngine('video', {
        dialogWidth: 400,
        afterRender: function(dialog) {
            itemPlaceholder = dialog.find('#item-placeholder');
            itemTemplate = new Template(dialog.find('#item-template').html());
        },
        addFormReady: function(form, dialog) {
            bindEvents(dialog);
            dialog.find('[data-role=pick-content]').trigger('click');
        },
        editFormReady: function(form, dialog) {
            var addon = MobileAddon.readAddon('video');
            renderItem(addon.data);
            bindEvents(dialog);
        },
        beforeSubmit: function(form, dialog) {
            if (!form.find('[name=contentid]').val()) {
                ct.error('挂件内容为空');
                return false;
            }
        }
    });
});
})();