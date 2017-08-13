;(function($) {
window.MobileRelated = {
    init:function() {
        var rowRelated = $('#row-related');
        var relateds = rowRelated.find('[name=relateds]');
        var list = rowRelated.find('ul');
        rowRelated.find('img').click(function() {
            var tags = rowRelated.find('[name=tags]').val(),
                selected = JSON.parse(relateds.val() || '[]');
            ct.ajaxDialog({
                title:'相关内容',
                width:800
            }, '?app=mobile&controller=content&action=related'+(tags ? '&keywords='+encodeURIComponent(tags) : ''), function(dialog) {
                var form,
                    leftMessage = dialog.find('#left-msg'),
                    rightMessage = dialog.find('#right-msg'),
                    lock,
                    contentsPanel = dialog.find('.related-content-list'),
                    contentsPanelLoaded = dialog.find('span[data-role=loaded]'),
                    contentsPanelTotal = dialog.find('span[data-role=total]'),
                    selectedPanel = dialog.find('.related-selected-list'),
                    selectedPanelTotal = dialog.find('span[data-role=selected]'),
                    total = 0,
                    loaded = 0,
                    page = 0,
                    pagesize = 13,
                    sortInited = false;

                function query(params, clear) {
                    if (lock) return;
                    lock = true;
                    if (clear) {
                        page = 1;
                    } else {
                        page += 1;
                    }
                    params.page = page;
                    params.pagesize = pagesize;
                    $.post('?app=mobile&controller=content&action=related', params, function(json) {
                        if (json && json.data) {
                            leftMessage.hide();
                            total = json.total;
                            if (clear) {
                                contentsPanel.empty();
                                loaded = json.data.length;
                            } else {
                                loaded = loaded + json.data.length;
                            }
                            $.each(json.data, function(index, item) {
                                var row = $([
                                    '<div id="related-content-'+item.contentid+'" class="related-panel-item">',
                                        '<input type="checkbox" />',
                                        '<span class="item-title">'+item.title+'</span>',
                                        '<span class="time">'+item.time+'</span>',
                                    '</div>'
                                ].join('')).appendTo(contentsPanel);
                                row.find(':checkbox').click(function() {
                                    if (this.checked) {
                                        addSelected(item);
                                    } else {
                                        removeSelected(item);
                                    }
                                });
                                var rowSelected = $('#related-selected-'+item.contentid);
                                if (rowSelected.length) {
                                    addSelected(item, rowSelected);
                                }
                            });
                        } else {
                            contentsPanel.empty();
                            leftMessage.show();
                            total = 0;
                            loaded = 0;
                        }
                        contentsPanelTotal.text(total);
                        contentsPanelLoaded.text(loaded);
                        lock = false;
                    }, 'json');
                }

                function addSelected(item, elem) {
                    if (!elem) {
                        elem = $([
                            '<div id="related-selected-'+item.contentid+'" class="related-panel-item">',
                            '<span class="f_r">',
                            '<img class="hand" src="images/del.gif" width="16" height="16" alt="删除" title="删除" />',
                            '</span>',
                            '<span class="item-title" title="拖动以排序">'+item.title+'</span>',
                            '</div>'
                        ].join('')).appendTo(selectedPanel);
                        rightMessage.hide();
                        elem.find('img.hand').click(function() {
                            removeSelected(item);
                            elem.remove();
                        });
                        var inStack = false;
                        $.each(selected, function(index, json) {
                            if (item.contentid == json.contentid) {
                                inStack = true;
                                return false;
                            }
                        });
                        !inStack && selected.push(item);
                        if (sortInited) {
                            selectedPanel.sortable('refresh');
                        } else {
                            var oldIndex, newIndex;
                            selectedPanel.sortable({
                                axis: 'y',
                                handle: '.item-title',
                                forceHelperSize: true,
                                placeholder: 'related-panel-item-placeholder',
                                opacity: 0.8,
                                create: function() {
                                    sortInited = true;
                                },
                                start: function(ev, ui) {
                                    oldIndex = selectedPanel.children().index(ui.item.get(0));
                                },
                                stop: function(ev, ui) {
                                    newIndex = selectedPanel.children().index(ui.item.get(0));
                                    if (newIndex !== oldIndex) {
                                        var tmp = selected[newIndex];
                                        selected[newIndex] = selected[oldIndex];
                                        selected[oldIndex] = tmp;
                                    }
                                }
                            });
                        }
                    }
                    $('#related-content-'+item.contentid)
                        .addClass('selected')
                        .find(':checkbox').attr('checked', true);
                    selectedPanelTotal.text(selected.length);
                }

                function removeSelected(item) {
                    $('#related-content-'+item.contentid)
                        .removeClass('selected')
                        .find(':checkbox').attr('checked', false);
                    $('#related-selected-'+item.contentid).remove();
                    selected.length == 0 && rightMessage.show();
                    $.each(selected, function(index, json) {
                        if (item.contentid == json.contentid) {
                            selected.splice(index, 1);
                            return false;
                        }
                    });
                    selectedPanelTotal.text(selected.length);
                    selectedPanel.sortable('refresh');
                }

                dialog.find('[name=modelid]').selectlist();
                dialog.find('[name=catid]').selectree();
                form = dialog.find('#form-query').submit(function() {
                    query(form.serializeObject(), true);
                    return false;
                });
                query(form.serializeObject(), true);
                contentsPanel.scroll(function() {
                    if (!lock && loaded < total
                        && contentsPanel.scrollTop() + contentsPanel.height() > contentsPanel[0].scrollHeight - 20) {
                        query(form.serializeObject());
                    }
                });

                if (selected.length) {
                    $.each(selected, function(index, item) {
                        addSelected(item);
                    });
                } else {
                    rightMessage.show();
                }
            }, function() {
                relateds.val(JSON.stringify(selected));
                MobileRelated.renderResult(list, selected);
                MobileContent.formChanged = true;
                return true;
            });

            return false;
        });
        MobileRelated.renderResult(list, JSON.parse(relateds.val() || '[]'));
    },
    renderResult:function(list, items) {
        list.empty();
        $.each(items, function(index, item) {
            list.append('<li>'+item.title+'</li>')
        });
    }
};
})(jQuery);