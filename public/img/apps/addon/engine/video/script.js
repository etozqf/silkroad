(function(){
var itemPlaceholder, itemTemplate,
    videoInput, otherVideoPlace,
    videoFileExts = ['swf', 'flv', 'rmvb', 'mp4', 'wmv'],
    R_VIDEO_FILE = new RegExp('\\.(' + videoFileExts.join('|') + ')$', 'i'),
    tabIndex;
function formatItem(data, callback) {
    $.post('?app=addon&controller=addon&action=video_format', data, function(json) {
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
        fet('admin.datapicker', function() {
            $.datapicker({
                url: '?app=system&controller=port&action=picker&modelid=4',
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
        ct.assoc.open('?app=video&controller=video&action=add&catid='+$('#catid').val(), 'newtab');
        return false;
    });
}
function loadOtherVideoSelector(place, input) {
    $.getJSON('?app=video&controller=thirdparty&action=getlist', function(json){
        if (!json || !json.state || !json.data || !json.data.length) return;
        $.each(json.data, function(i, item) {
            $('<button type="button" class="button_style_1" value="'+item.id+'">'+item.title+'</button>')
                .appendTo(place)
                .click(function() {
                    var id = $(this).attr('value');
                    var d = ct.iframe({
                            title:'?app=video&controller=thirdparty&action=selector&id='+id,
                            width:800,
                            height:469
                        }, {
                            ok:function(r){
                                input.val(r.video);
                                d.dialog('close');
                            }
                        });
                    return false;
                });
        });
    });
}

fet('cmstop', function() {
    Addon.registerEngine('video', {
        dialogWidth: 400,
        afterRender: function(dialog) {
            var storedData = Addon.readAddon('video');
            itemPlaceholder = dialog.find('#item-placeholder');
            itemTemplate = new Template(dialog.find('#item-template').html());
            videoInput = dialog.find('[name=video]');
            otherVideoPlace = dialog.find('#thirdparty');

            if (storedData) {
                videoInput.val(storedData.data.video);
            }

            dialog.find('.mod-tabs').tabs({
                activeIndex: storedData && storedData.data.tabs || 0,
                active: function(index) {
                    dialog.find('[name=tabs]').val(index);
                    tabIndex = index;
                }
            });
            dialog.find('[data-role=pick-file]').click(function() {
                var type = $(this).attr('data-type');
                switch (type) {
                    case 'vms':
                        var d = ct.iframe({
                            title:'?app=video&controller=vms&action=index&selector=1',
                            width:600,
                            height:519
                        }, {
                            ok: function(video) {
                                videoInput.val('[ctvideo]' + video.id + '[/ctvideo]').trigger('change');
                                d.dialog('close');
                            }
                        });
                        break;
                    default:
                        ct.fileManager(function(file) {
                            videoInput.val(UPLOAD_URL + file.src).trigger('change');
                        }, videoFileExts.join(','), false);
                        break;
                }
                return false;
            });
            loadOtherVideoSelector(otherVideoPlace, videoInput);
        },
        addFormReady: function(form, dialog) {
            bindEvents(dialog);
        },
        editFormReady: function(form, dialog) {
            var addon = Addon.readAddon('video');
            form[0].width.value = addon.data.width;
            form[0].height.value = addon.data.height;
            if (addon.data.tabs == 1) {
                renderItem(addon.data);
            }
            bindEvents(dialog);
        },
        beforeSubmit: function(form, dialog) {
            var currentTab = form.find('.tabs-content-item').eq(tabIndex);
            if ((tabIndex === 0 && ! currentTab.find('[name=video]').val())
                || (tabIndex === 1 && (! currentTab.find('[name=contentid]').val()))) {
                ct.error('挂件内容为空');
                return false;
            }
            form.find('.tabs-content-item').not(':eq(' + tabIndex + ')').find(':input').attr('disabled', true);
        },
        afterSubmit: function(form, dialog) {
            form.find('.tabs-content-item').not(':eq(' + tabIndex + ')').find(':input').attr('disabled', false);
        }
    });
});
})();