(function(){
var itemPlaceholder, itemTemplate;
function formatItem(data, callback) {
    $.post('?app=addon&controller=addon&action=activity_format', data, function(json) {
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
        fet('admin.datapicker', function() {
            $.datapicker({
                url: '?app=system&controller=port&action=picker&modelid=7',
                picked: function(items){
                    formatItem(items[0], function(json) {
                        renderItem(json);
                        bindEvents(dialog);
                    });
                },
                multiple: false
            });
        });
        return false;
    });
    dialog.find('[data-role=add]').click(function() {
        ct.assoc.open('?app=activity&controller=activity&action=add&catid='+$('#catid').val(), 'newtab');
        return false;
    });
}
Addon.registerEngine('activity', {
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
        var addon = Addon.readAddon('activity');
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