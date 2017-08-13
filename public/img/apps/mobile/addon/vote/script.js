(function(){
var itemPlaceholder, itemTemplate,
    tabIndex,
    optionPlaceholder, optionTemplate,
    sortInited = false;
function formatItem(data, callback) {
    $.post('?app=mobile&controller=addon&action=vote_format', data, function(json) {
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
    dialog.find('[data-role=add-option]').click(function() {
        addOption();
        return false;
    });

    // 选取内容
    dialog.find('[data-role=pick]').click(function() {
        var _dialog = ct.iframe({
            url:'?app=vote&controller=vote&action=picker&thumb=1&catid='+(MobileContent.bindCatids || ''),
            width:500
        }, {
            ok:function(item) {
                formatItem(item, function(json) {
                    renderItem(json);
                    bindEvents(dialog);
                });
                _dialog.dialog('close');
            }
        });
        return false;
    });

    dialog.find('[data-role=add]').click(function() {
        ct.assoc.open('?app=vote&controller=vote&action=add', 'newtab');
        return false;
    });
}
function addOption(data) {
    var option = $(renderOption(data)).appendTo(optionPlaceholder);
    var link = option.find('[data-role=add-link]').click(function() {
        pickLink(link.siblings(':text'));
        return false;
    });
    link.siblings(':text').attrTips('tips').change(function() {
        $(this).attr('tips', this.value);
    }).keyup(function() {
        $(this).trigger('change');
    });
    var thumb = option.find('[data-role=add-thumb]').uploader({
        script : '?app=vote&controller=vote&action=upload',
        fileDataName : 'Filedata',
        fileExt : '*.jpg;*.jpeg;*.gif;*.png;',
        multi : false,
        complete : function(response, data) {
            if(response != '0') {
                thumb.siblings(':text').val(response).trigger('change');
            } else {
                ct.error(response.msg);
            }
        }
    }).css({position: 'absolute'});
    thumb.siblings(':text').floatImg({
        width: 200
    });
    option.find('[data-role=sort]').text(optionPlaceholder.children().length);
    if (sortInited) {
        optionPlaceholder.sortable('refresh');
    } else {
        optionPlaceholder.sortable({
            axis: 'y',
            handle: 'td:first',
            forceHelperSize: true,
            placeholder: 'tr-placeholder',
            opacity: 0.8,
            create: function() {
                sortInited = true;
            },
            start: function(ev, ui) {
                $('<td colspan="' + ui.item.children().length + '">&nbsp;</td>').appendTo(ui.placeholder);
                ui.helper.css('background-color', '#FFF');
                ct.IE && ui.helper.css('margin-left', '0px');
            },
            stop: function(ev, ui) {
                sortOptions();
            }
        });
    }
    option.find('[data-role=remove-option]').click(function() {
        if (optionPlaceholder.children().length <= 2) {
            option.find(':text').val('');
        } else {
            option.remove();
        }
        sortOptions();
        return false;
    });
}
function renderOption(data) {
    return optionTemplate.render(data || {});
}
function sortOptions() {
    optionPlaceholder.children().each(function(index, item) {
        $(item).find('[data-role=sort]').text(index + 1);
    });
}
function pickLink(input) {
    $.datapicker({
        picked: function(items) {
            input.val(items[0].url).attr('tips', items[0].url).trigger('change');
        },
        multiple: false
    });
}

fet('cmstop lib.tree', function() {
    MobileAddon.registerEngine('vote', {
        dialogWidth: 650,
        afterRender: function(dialog) {
            var storedData = MobileAddon.readAddon('vote');
            itemPlaceholder = dialog.find('#item-placeholder');
            itemTemplate = new Template(dialog.find('#item-template').html());
            optionPlaceholder = dialog.find('#vote-option-placeholder');
            optionTemplate = new Template(dialog.find('#tpl-vote-option').html());
            dialog.find('.mod-tabs').tabs({
                activeIndex: storedData && storedData.data.tabs || 0,
                active: function(index) {
                    dialog.find('[name=tabs]').val(index);
                    tabIndex = index;
                }
            });
            dialog.find('[name=type]').bind('click', function() {
                if (this.value == 'radio') {
                    $(this).parent().siblings('span').css('visibility', this.checked ? 'hidden' : 'visible');
                } else {
                    $(this).parent().siblings('span').css('visibility', this.checked ? 'visible' : 'hidden');
                }
            });
            dialog.find('[data-role=catid]').selectree();
        },
        addFormReady: function(form, dialog) {
            fet('admin.datapicker admin.uploader', function() {
                bindEvents(dialog);
                addOption();
                addOption();
                var catname = form.find('[name=catname]');
                form.find('[name=catid]').bind('changed', function(ev, checked, storedData) {
                    catname.val(storedData[checked[0]].name);
                });
            });
        },
        editFormReady: function(form, dialog) {
            fet('admin.datapicker admin.uploader', function() {
                var addon = MobileAddon.readAddon('vote'),
                    currentTab = form.find('.tabs-content-item').eq(tabIndex);
                if (tabIndex == 1) {
                    renderItem(addon.data);
                    addOption();
                    addOption();
                } else {
                    var storedData = {};
                    storedData[addon.data.catid] = {
                        catid: addon.data.catid,
                        name: addon.data.catname
                    };
                    addon.data.catid && currentTab.find('[name=catid]').val(addon.data.catid).triggerHandler('changed', [[addon.data.catid], storedData]);
                    currentTab.find('[name=catname]').val(addon.data.catname);
                    currentTab.find('[name=title]').val(addon.data.title);
                    currentTab.find('[name=thumb]').val(addon.data.thumb);
                    currentTab.find('[name=type]').removeAttr('checked').filter('[value=' + addon.data.type + ']')
                    .attr('checked', true).trigger('click');
                    currentTab.find('[name=maxoptions]').val(addon.data.maxoptions || 0);
                    addon.data.display && currentTab.find('[name=display]').removeAttr('checked').filter('[value=' + addon.data.display + ']').attr('checked', true).trigger('click');
                    $.each(addon.data.option, function(index, option) {
                        addOption(option);
                    });
                    currentTab.find('[name=thumb_width]').val(addon.data.thumb_width || 0);
                    currentTab.find('[name=thumb_height]').val(addon.data.thumb_height || 0);
                }
                bindEvents(dialog);
                var catname = form.find('[name=catname]');
                form.find('.tabs-content-item').eq(0).find('[name=catid]').bind('changed', function(ev, checked, storedData) {
                    catname.val(storedData[checked[0]].name);
                });
            });
        },
        beforeSubmit: function(form, dialog) {
            var currentTab = form.find('.tabs-content-item').eq(tabIndex);
            if (tabIndex === 0) {
                if (! currentTab.find('[name=catid]').val()
                    || ! currentTab.find('[name=title]').val()
                    || ! currentTab.find('[name=thumb]').val()
                    || ! currentTab.find('[name=name]:first').val() ) {
                    ct.error('投票信息不完整');
                    return false;
                }
            } else {
                if (! currentTab.find('[name=contentid]').val()) {
                    ct.error('挂件内容为空');
                    return false;
                }
            }
            form.find('.tabs-content-item').not(':eq(' + tabIndex + ')').find(':input').attr('disabled', true);
        },
        genData: function(form, dialog) {
            if (tabIndex === 0) {
                var currentTab = form.find('.tabs-content-item').eq(tabIndex),
                    data = {
                        tabs: tabIndex,
                        catid: currentTab.find('[name=catid]').val(),
                        catname: currentTab.find('[name=catname]').val(),
                        title: currentTab.find('[name=title]').val(),
                        thumb: currentTab.find('[name=thumb]').val(),
                        type: currentTab.find('[name=type]').filter(':checked').val(),
                        maxoptions: currentTab.find('[name=maxoptions]').val(),
                        option: [],
                        display: currentTab.find('[name=display]').filter(':checked').val(),
                        thumb_width: currentTab.find('[name=thumb_width]').val(),
                        thumb_height: currentTab.find('[name=thumb_height]').val()
                    };
                $.each(currentTab.find('.vote-option'), function(index, option) {
                    data.option.push($(option).find(':input').serializeObject());
                });
                return data;
            } else {
                return form.serializeObject();
            }
        },
        afterSubmit: function(form, dialog) {
            form.find('.tabs-content-item').not(':eq(' + tabIndex + ')').find(':input').attr('disabled', false);
        }
    });
});
})();