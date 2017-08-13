(function(){
var itemPlaceholder, itemTemplate;
function formatItem(data, callback) {
    $.post('?app=mobile&controller=addon&action=picture_format', data, function(json) {
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
    dialog.find('[data-role=pick]').click(function() {
        var _dialog = ct.iframe({
            url:'?app=mobile&controller=content&action=picker&multiple=0&modelid=2&title='+encodeURIComponent('选择组图'),
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
        ct.assoc.open('?app=mobile&controller=picture&action=add', 'newtab');
        return false;
    });
}
MobileAddon.registerEngine('picture', {
    dialogWidth: 400,
    afterRender: function(dialog) {
        itemPlaceholder = dialog.find('#item-placeholder');
        itemTemplate = new Template(dialog.find('#item-template').html());
        dialog.find('.mod-tabs').tabs({
            active: function(index) {
                dialog.find('[name=tabs]').val(index);
            }
        });
    },
    addFormReady: function(form, dialog) {
        bindEvents(dialog);
        dialog.find('[data-role=pick]').trigger('click');
    },
    editFormReady: function(form, dialog) {
        var addon = MobileAddon.readAddon('picture');
        renderItem(addon.data);
        bindEvents(dialog);
    },
    beforeSubmit: function(form, dialog) {
        if (! form[0].contentid || ! form[0].contentid.value) {
            ct.error('挂件内容为空');
            return false;
        }
    },
    afterSubmit: function(form, dialog) {}
});
})();
