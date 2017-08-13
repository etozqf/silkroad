(function(){
var itemPlaceholder, itemTemplate;
function formatItem(data, callback) {
    $.post('?app=addon&controller=addon&action=special_format', data, function(json) {
        callback(json);
    }, 'json');
}
function renderItem(data) {
    var item = $(itemTemplate.render(data));
    item.find('[data-role=url]').attr('href', data.url || 'javascript:void(0);');
    itemPlaceholder.empty().append(item);
}
function appendPx() {
    var value = $.trim(this.value);
    if (value) {
        if (!(value+'').match(/^(?:100|\d{0,2})(?:\.\d{1,2})?%$/)) {
            value = parseInt(value);
            value = value ? (value + 'px') : '';
        }
    }
    this.value = value;
}
function bindEvents(dialog) {
    dialog.find('[data-role=pick]').click(function() {
        fet('cmstop', function() {
            var d = ct.iframe({
                url: '?app=special&controller=special&action=pagePicker',
                width: 650
            }, {
                ok: function(item) {
                    formatItem(item, function(json) {
                        renderItem(json);
                        bindEvents(dialog);
                    });
                    d.dialog('close');
                },
                close: function() {
                    d.dialog('close');
                }
            });
        });
        return false;
    });
    dialog.find('[data-role=add]').click(function() {
        ct.assoc.open('?app=special&controller=special&action=add&catid='+$('#catid').val(), 'newtab');
        return false;
    });
    dialog.find('[name=width]').blur(appendPx);
    dialog.find('[name=height]').blur(appendPx);
}
Addon.registerEngine('special', {
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
        var addon = Addon.readAddon('special');
        form.find('[name=width]').val(addon.data.width);
        form.find('[name=height]').val(addon.data.height);
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