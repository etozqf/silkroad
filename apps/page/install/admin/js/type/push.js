(function() {
var table,
    rowsTop,
    rowsTopCount,
    uncheckCount,
    rowsNormal,
    tableLogs,
    tplLog,
    tplItem,
    check = false;

var sortable = function() {
    var oldIndex, newIndex;
    var options = {
        axis: 'y',
        handle: 'td:first',
        forceHelperSize: true,
        placeholder: 'tr-placeholder',
        opacity: 0.8,
        start: function(ev, ui) {
            oldIndex = parseInt(ui.item.find('td:first').text());

            $('<td colspan="' + ui.item.children().length + '">&nbsp;</td>').appendTo(ui.placeholder);
            ui.placeholder.height(ui.item.height());
            var totalWidth = 0, noWidth;
            ui.helper.children().each(function() {
                var td = $(this);
                if (td.attr('width')) {
                    totalWidth += td.outerWidth();
                } else {
                    noWidth = td;
                }
            });
            noWidth && noWidth.width(ui.item.closest('tbody').innerWidth() - totalWidth);
            ct.IE && ui.helper.css('margin-left', '0px');
        },
        stop: function(ev, ui) {
            ui.item.children().each(function() {
                $(this).css('width', '');
            });

            updateSort();
            newIndex = parseInt(ui.item.find('td:first').text());
            if (newIndex - oldIndex) {
                $.getJSON('?app=page&controller=section_recommend&action=updownItem&sectionid='+CURRENT_SECTIONID, {
                    old:oldIndex,
                    now:newIndex
                }, function(json) {
                    if (json.state) {
                        CURRENT_SECTION_CHANGED = true;
                        getSection(function(section) {
                            renderItems(section);
                        });
                    } else {
                        ct.error(json && json.error || '更新排序失败');
                    }
                });
            }
        }
    };
    return function() {
        // 置顶项目
        rowsTop.sortable($.extend({}, options, {
            items: 'tr[filled]'
        }));
        // 非置顶项目
        rowsNormal.sortable($.extend({}, options, {
            items: 'tr[filled]'
        }));
    };
}();

function timestampToDate(t) {
    var d = new Date();
    if ((t + '').length == 10) t *= 1000;
    d.setTime(t);
    return d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDate()+' '+ d.getHours()+':'+ d.getMinutes()+':'+d.getSeconds();
}

// 获取区块信息
function getSection(callback) {
    $.getJSON('?app=page&controller=section_recommend&action=getData&sectionid='+CURRENT_SECTIONID, function(json) {
        if (json.state) {
            callback && callback(json);
        } else {
            ct.error(json.error);
        }
    });
}

function dialogReady(dialog, multiple, opener, callback) {
    var tplForm = new Template($('#tpl-form').val()),
        formList = dialog.find('#section-form-list'),
        index = 1,
        lastFocusedRow, submitLock;

    function fillItemToForm(item) {
        var form;
        if (multiple) {
            formList.children().each(function(i, f) {
                if (!f.title) return true;
                if (f.title.value) return true;
                form = $(f);
                return false;
            });
            if (!form) form = addForm();
        } else {
            form = formList.children(':first');
        }
        form[0].recommendid && (form[0].recommendid.value = '');
        form[0].contentid && (form[0].contentid.value = '');
        form.find('table :input').val('');
        var data = item.data;
        delete item.data;
        data = $.extend(item, data);
        for (var key in data) {
            if (key && form[0][key]) {
                if (data[key]) {
                    if (key == 'time') {
                        $(form[0][key]).val(timestampToDate(data[key]));
                    } else {
                        $(form[0][key]).val(data[key]);
                    }
                } else {
                    $(form[0][key]).val('');
                }
            }
        }
        if (item.recommendid) {
            form.attr('data-recommendid', item.recommendid);
        } else {
            form.removeAttr('data-recommendid');
        }
        form.find(':input').trigger('change').trigger('keyup');
    }
    function submit(form) {
        formList.scrollTop(form.scrollTop());
        submitLock = true;
        form.ajaxSubmit({
            success:function(json) {
                if (json && json.state) {
                    callback && callback(json.data);
                    form.addClass('submited');
                    setTimeout(submitNext, 100);
                } else {
                    submitLock = false;
                    ct.error(json && json.error || '添加失败');
                }
            },
            dataType:'json'
        });
    }
    function submitNext() {
        var forms = formList.find('.section-form').not('.submited');
        if (forms.length) {
            var fm = forms.filter(':last');
            fm.trigger('submit');
            var rl = fm.attr('val_result');
            if(rl == "false") submitLock = false;
        } else {
            opener && opener.dialog('close');
        }
    }
    function toggleFirstCloseState() {
        var allForms = formList.children();
        if (allForms.length == 1) {
            allForms.eq(0).find('.remove').hide();
        } else {
            allForms.eq(0).find('.remove').show();
        }
    }
    function addForm() {
        var form = $(tplForm.render({index:index++})),
            title = form.find('[data-role=title]');
        var icon = form.find('[name=icon]').iconlist({
            container:form[0].title
        }).bind('changed',function(e, t, o){
            var ctrl = icon.data('listObj').ctrl;
            if (!ctrl) return;
            if (form[0].icon) {
                form[0].iconsrc && (form[0].iconsrc.value = (form[0].icon.value ? o.find('img').attr('src') : ''));
            }
            $(form[0].title).css('padding-left', ctrl.outerWidth() + 2);
        });
        form.find('.remove').click(function() {
            removeForm(form);
        });
        form.find('[name=title]').change(function() {
            title.text(this.value);
        });
        formReady(form, form);
        page.validate(form);
        form.validate({
            submitHandler:submit
        });
        formList.append(form);
        toggleFirstCloseState();
        formList.scrollTop(formList[0].scrollHeight);
        return form;
    }
    function removeForm(form) {
        if (!form[0]) return false;
        if (form[0].recommendid && form[0].recommendid.value) {
            checkList.children('[recommendid='+form[0].recommendid.value+']').removeClass('checked');
        }
        form.nextAll('.section-form').each(function() {
            var index = $(this).find('[data-role=index]');
            index.text(parseInt(index.text()) - 1);
        });
        form.remove();
        toggleFirstCloseState();
        index--;
        return true;
    }

    addForm();
    dialog.find('#add-input').click(addForm);
    dialog.find('#pick-content').click(function() {
        $.datapicker({
            multiple:multiple,
            picked:function(items) {
                $.each(items, function(i, item) {
                    item.time = item.published;
                    fillItemToForm(item);
                });
            }
        });
    });
    opener.bind('submit', function() {
        if (!submitLock) {
            //submitLock = true;
            submitNext();
        }
    });

    var checkList = dialog.find('#check-list'),
        tplRowCheck = new Template($('#tpl-row-check').val()),
        checkListCount = dialog.find('[data-role=check-count]'),
        checkLock, checkTotal = 0, checkLoaded = 0, checkPage = 1;
    function queryCheck(empty) {
        if (checkLock) return;
        checkLock = true;
        if (empty) {
            checkPage = 1;
            checkTotal = 0;
            checkLoaded = 0;
        }
        $.getJSON('?app=page&controller=section_recommend&action=page', {
            sectionid:CURRENT_SECTIONID,
            status:0,
            page:checkPage++,
            pagesize:10
        }, function(json) {
            checkLock = false;
            empty && checkList.empty();
            if (json && json.data) {
                json.total && (checkTotal = parseInt(json.total));
                checkLoaded += json.data.length;
                $.each(json.data, function(i, n) {
                    var row = $(tplRowCheck.render(n));
                    formList.find('[data-recommendid='+n.recommendid+']').size() && row.addClass('checked');
                    row.click(function() {
                        if (row.hasClass('checked')) return;
                        multiple || (lastFocusedRow && lastFocusedRow.removeClass('checked'));
                        row.addClass('checked');
                        fillItemToForm(n);
                        lastFocusedRow = row;
                    });
                    row.find('.remove').click(function() {
                        $.getJSON('?app=page&controller=section&action=rejectRecommend', {
                            recommendid:n.recommendid
                        }, function(json) {
                            if (json.state) {
                                ct.ok('否决成功');
                                removeForm(formList.find('[data-recommendid='+n.recommendid+']'));
                                queryCheck(true);
                                queryDeleted(true);
                            } else {
                                ct.ok('否决失败');
                            }
                        });
                        return false;
                    });
                    checkList.append(row);
                });
            }
            checkListCount.text(checkTotal);
        });
    }
    checkList.scroll(function() {
        if (!checkLock
            && checkLoaded < checkTotal
            && checkList.scrollTop() + checkList.height() > checkList[0].scrollHeight - 30) {
            queryCheck();
        }
    });
    queryCheck();

    var deletedList = dialog.find('#delete-list'),
        tplRowDeleted = new Template($('#tpl-row-deleted').val()),
        deletedLock, deletedTotal, deletedLoaded = 0, deletedPage = 1;
    function queryDeleted(empty) {
        if (deletedLock) return;
        deletedLock = true;
        if (empty) {
            deletedPage = 1;
            deletedTotal = 0;
            deletedLoaded = 0;
        }
        $.getJSON('?app=page&controller=section_recommend&action=page', {
            sectionid:CURRENT_SECTIONID,
            status:2,
            pagesize:10
        }, function(json) {
            deletedLock = false;
            empty && deletedList.empty();
            if (json && json.data) {
                json.total && (deletedTotal = parseInt(json.total));
                deletedLoaded += json.data.length;
                $.each(json.data, function(i, n) {
                    var row = $(tplRowDeleted.render(n));
                    deletedList.append(row);
                });
            }
        });
    }
    deletedList.scroll(function() {
        if (!deletedLock
            && deletedLoaded < deletedTotal
            && deletedList.scrollTop() + deletedList.height() > deletedList[0].scrollHeight - 30) {
            queryDeleted();
        }
    });
    queryDeleted();

    dialog.find('.tabs').each(function() {
        var tabs = $(this), contents = tabs.next('.tab-contents'),
            triggers = tabs.find('>ul>li'),
            targets = contents.children();
        triggers.each(function() {
            var trigger = $(this),
                index = triggers.index(trigger),
                target = targets.eq(index);
            trigger.click(function() {
                triggers.removeClass('active');
                trigger.addClass('active');
                targets.hide();
                target.show();
            });
        });
    });
}

function formReady(form, dialog) {
    var title = form.find('input[name=title]').maxLength();
    var colorPicker = title.parent().nextAll('img');
    colorPicker.titleColorPicker(title, colorPicker.next('input'));
    form.find('input[name="thumb"]').imageInput();
}

function add() {
    var opener = ct.ajaxDialog({
        title:'添加内容',
        width:check ? 860 : 540
    }, '?app=page&controller=section_recommend&action=add&sectionid='+CURRENT_SECTIONID+'&check='+check, function(dialog) {
        dialogReady(dialog, true, opener, function() {
            CURRENT_SECTION_CHANGED = true;
            getSection(function(section) {
                renderItems(section);
            });
        });
    }, function() {
        opener.trigger('submit');
        return false;
    }, function() {
        return true;
    });
}

// 编辑条目
function edit(row) {
    var opener = ct.ajaxDialog({
        title:'修改内容',
        width:check ? 860 : 540
    }, '?app=page&controller=section_recommend&action=edit&sectionid='+CURRENT_SECTIONID+'&row='+row+'&check='+check, function(dialog) {
        dialogReady(dialog, false, opener, function() {
            CURRENT_SECTION_CHANGED = true;
            getSection(function(section) {
                renderItems(section);
            });
        });
    }, function() {
        opener.trigger('submit');
        return false;
    }, function() {
        return true;
    });
}

// 删除条目
function removeItem(row) {
    $.getJSON('?app=page&controller=section_recommend&action=removeItem&sectionid='+CURRENT_SECTIONID+'&row='+row, function(json) {
        if (json.state) {
            getSection(function(section) {
                CURRENT_SECTION_CHANGED = true;
                renderItems(section, function() {
                    tableLogs.load('');
                });
            });
        } else {
            ct.error(json.error);
        }
    });
}

// 取消置顶
function unStickItem(row) {
    $.getJSON('?app=page&controller=section_recommend&action=unStickItem&sectionid='+CURRENT_SECTIONID+'&row='+row, function(json) {
        if (json.state) {
            CURRENT_SECTION_CHANGED = true;
            getSection(function(section) {
                ct.ok('取消置顶成功');
                renderItems(section);
            });
        } else {
            ct.error(json.error);
        }
    });
}

// 置顶
function stickItem(row) {
    $.getJSON('?app=page&controller=section_recommend&action=stickItem&sectionid='+CURRENT_SECTIONID+'&row='+row, function(json) {
        if (json.state) {
            CURRENT_SECTION_CHANGED = true;
            getSection(function(section) {
                ct.ok('置顶成功');
                renderItems(section);
            });
        } else {
            ct.error(json.error);
        }
    });
}

// 渲染条目
function renderItem(item) {
    var row = $(tplItem.render(item));
    row.find('[action=unStick]').click(function() {
        row.attr('filled') && unStickItem(parseInt(row.find('td:first').text()));
        return false;
    });
    row.find('[action=stick]').click(function() {
        row.attr('filled') && stickItem(parseInt(row.find('td:first').text()));
        return false;
    });
    row.find('[action=edit]').click(function() {
        row.attr('filled') && edit(parseInt(row.find('td:first').text()));
        return false;
    });
    row.find('[action=remove]').click(function() {
        row.attr('filled') && ct.confirm('操作不可恢复，确定要删除该条目吗？', function() {
            removeItem(parseInt(row.find('td:first').text()));
        });
        return false;
    });
    row.find('[tips]').attrTips('tips', null, 350);
    return row;
}

// 渲染表格
function renderItems(section, callback) {
    var data = section.data,
        i = 1;
    rowsTop.children().remove();
    rowsNormal.children().remove();
    if (data && data.length) {
        var item;
        while ((item = data.shift())) {
            item.index = i++;
            renderItem(item).appendTo(parseInt(item.istop) ? rowsTop : rowsNormal);
        }
    }
    rowsTopCount = rowsTop.children().length;
    updateSort();
    sortable();
    $.isFunction(callback) && callback();
}

function updateSort() {
    rowsTop.children('tr').each(function(index, tr) {
        $(tr).find('td:first').text(index + 1);
    });
    rowsNormal.children('tr').each(function(index, tr) {
        $(tr).find('td:first').text(rowsTopCount + index + 1);
    });
}

// 使用历史记录
function useLog(id, tr) {
    ct.confirm('确定要使用该记录吗？', function() {
        $.getJSON('?app=page&controller=section_recommend&action=useLog&sectionid='+CURRENT_SECTIONID+'&recommendid='+id, function(json) {
            if (json.state) {
                CURRENT_SECTION_CHANGED = true;
                tr.find('[action]').css('visibility', 'hidden');
                getSection(function(section) {
                    renderItems(section);
                });
            } else {
                ct.error(json.error);
            }
        });
    });
}

// 编辑历史记录
function editLog(id) {
    ct.formDialog({
        title:'修改记录',
        width:500
    }, '?app=page&controller=section_recommend&action=editLog&sectionid='+CURRENT_SECTIONID+'&recommendid='+id, function(json) {
        if (json.state) {
            ct.ok('修改成功');
            tableLogs.reload();
            return true;
        } else {
            ct.error(json.error);
            return false;
        }
    }, formReady);
}

// 删除历史记录
function removeLog(id) {
    ct.confirm('操作不可恢复，确定要删除该记录吗？', function() {
        $.getJSON('?app=page&controller=section_recommend&action=removeLog&sectionid='+CURRENT_SECTIONID+'&recommendid='+id, function(json) {
            if (json.state) {
                ct.ok('删除成功');
                tableLogs.load('');
            } else {
                ct.error(json.error);
            }
        });
    });
}

window.pushaction = {
    init:function(container){
        getSection(function(section) {
            tplItem = new Template($('#tpl-item').val());
            table = container.find('table.table_info:first');
            rowsTop = table.find('>tbody:eq(0)');
            rowsNormal = table.find('>tbody:eq(1)');
            uncheckCount = container.find('[data-role=uncheck-count]');
            renderItems(section);

            var addRow = container.find('#add-row');
            check = parseInt(addRow.attr('check'));
            addRow.click(function() {
                add();
                return false;
            });

            if ($('#section_log').length) {
                // 初始化 logs
                tplLog = $('#tpl-log').val();
                tableLogs = new ct.table('#section_log', {
                    rowIdPrefix:'log_',
                    pageField:'page',
                    pageSize:5,
                    rowCallback:function(id, tr) {
                        if (rowsTop.children('[recommendid='+id+']').length
                            || rowsNormal.children('[recommendid='+id+']').length
                            || tr.attr('isdeleted') == 1) {
                            tr.find('[action]').css('visibility', 'hidden');
                            if (tr.attr('isdeleted') == 1) {
                                tr.find('.label-remove').css('visibility', 'visible');
                            }
                        } else {
                            tr.find('[action=use-log]').click(function() {
                                useLog(id, tr);
                                return false;
                            });
                            tr.find('[action=edit-log]').click(function() {
                                editLog(id);
                                return false;
                            });
                            tr.find('[action=remove-log]').click(function() {
                                removeLog(id);
                                return false;
                            });
                        }
                        tr.find('[tips]').attrTips('tips', null, 350);
                    },
                    template:tplLog,
                    baseUrl:'?app=page&controller=section_recommend&action=page&sectionid='+CURRENT_SECTIONID+'&status=1&sort=0'
                });
                tableLogs.load();
                container.find('[data-role=refresh-logs]').click(function() {
                    tableLogs.reload();
                    return false;
                });
            }
        });
    }
};
})();