(function() {
function timestampToDate(t) {
    var d = new Date();
    if ((t + '').length == 10) t *= 1000;
    d.setTime(t);
    return d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDate()+' '+ d.getHours()+':'+ d.getMinutes()+':'+d.getSeconds();
}
function createCtrl(content){
    var span = null, ival,
        ctrl = $('<div class="section-handle">'+
            '<b class="ml" title="左移"></b>'+
            '<b class="view" title="查看"></b>'+
            '<b class="edit" title="编辑"></b>'+
            '<b class="del" title="删除"></b>'+
            '<b class="mr" title="右移"></b>'+
            '</div>').hover(ishow, ihide).appendTo(content),
        func = {
            ml:function(){
                h.moveitemleft(content);
                ihide('now');
            },
            mr:function(){
                h.moveitemright(content);
                ihide('now');
            },
            view:function(){
                h.viewitem(content);
                ihide('now');
            },
            edit:function(){
                h.edititem(content);
                ihide('now');
            },
            del:function(){
                h.delitem(content);
                ihide('now');
            }
        };
    ctrl.find('b').click(function(){
        span && func[this.className]();
    });
    ctrl.bind('ishow',function(e, s, m){
        span = s;
        ishow(e, m);
    });
    ctrl.bind('ihide',function(e, s){
        span = s;
        ihide(e);
    });
    function show(m){
        var pos = span.position(), ctrlWidth, left;
        m && (left = m.pageX - (content.offset().left - content.position().left));
        ctrl.css('display', 'block');
        ctrlWidth = ctrl.outerWidth();
        ctrl.css({
            top:pos.top + content.offsetParent()[0].scrollTop - ctrl.outerHeight(),
            left:m ? Math.max(pos.left, Math.min(Math.max(pos.left, left - ctrlWidth/2), pos.left + span.outerWidth() - ctrlWidth))
                   : pos.left + (span.outerWidth() - ctrlWidth)/2
        });
    }
    function ishow(e, m){
        ival && clearTimeout(ival);
        if (!span) return;
        if (ctrl.is(':visible')) {
            ival = null;
        } else {
            ival = setTimeout(function() {
                show(m);
            }, 100);
        }
    }
    function ihide(e){
        ival && clearTimeout(ival);
        if (ctrl.is(':hidden')) return;
        if (e == 'now') {
            ival = null;
            ctrl.css('display','none');
        } else {
            ival = setTimeout(function(){
                ctrl.css('display','none');
            }, 300);
        }
    }
    return ctrl;
}
function createAdder(cell) {
    var ul = cell.find('>ul'),
        add = ul.find('.section-handle-add');
    if (!add.length) {
        add = $('<b class="section-handle-add" title="添加项"></b>').click(function() {
            h.additem(cell);
        });
    }
    add.appendTo(ul);
}

var itemMoveLock = false;
var dragSortInited = 0;
var table, rows;
var h = {
    init:function(options){
        table = options.table;
        rows = options.rows;
    },
    scantable:function(table){
        var rows = $(table[0].rows).filter(':gt(0)');
        rows.each(function(){
            h.readyrow($(this));
        });
    },
    readyitem:function(li, item){
        var ctrl = createCtrl(li);
        var a = li.find('a').click(function(e){
            h.edititem(li);
            return false;
        });
        item && item.color && a.css('color', item.color);
        item && a.attr('tips',item.tips);
        li.hover(function(ev) {
            ctrl.triggerHandler('ishow', [li, ev]);
        }, function() {
            ctrl.triggerHandler('ihide', [li]);
        });
    },
    readyrow:function(row){
        var cells = row[0].cells;
        var c1 = $(cells[1]);
        c1.find('li').each(function(){
            h.readyitem($(this));
        });
        createAdder(c1);
        $('>img',cells[2]).click(function(){
            h[this.getAttribute('action')](c1,row);
        });

        // 通过拖动的方法排序
        var tbody = $('tbody#sortable');
        if (dragSortInited) {
            tbody.sortable('refresh');
        } else {
            tbody.sortable({
                axis: 'y',
                handle: 'td:first',
                forceHelperSize: true,
                placeholder: 'tr-placeholder',
                opacity: 0.8,
                create: function() {
                    dragSortInited = 1;
                },
                start: function(ev, ui) {
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

                    var oldIndex = parseInt(ui.item.find('td:first').html()),
                        newIndex = tbody.find('>tr').index(ui.item.get(0)) + 1,
                        diff = oldIndex - newIndex;
                    if (diff) {
                        CURRENT_SECTION_CHANGED = true;
                        $.post("?app=page&controller=section&action=" + (diff > 0 ? 'uprow' : 'downrow'), {
                            row: oldIndex - 1,
                            sectionid: CURRENT_SECTIONID,
                            num: Math.abs(diff)
                        });
                    }
                    tbody.find('>tr').each(function(index, tr) {
                        var $tr = $(tr);
                        $tr.find('td:first').text(index + 1);
                        $tr.find('td[row]').attr('row', index);
                    });
                }
            });
        }
    },
    newitem:function(item) {
        var li = $('<li url="'+item.url+'" col="'+item.col+'"><a href="" target="_blank">'+item.title+'</a></li>');
        h.readyitem(li, item);
        return li;
    },
    additem:function(cell){
        var url = '?app=page&controller=section&action=additem&sectionid='+CURRENT_SECTIONID+'&row='+cell.attr('row');
        var opener = ct.ajaxDialog({
            title:'添加项',
            width:860
        }, url, function(dialog) {
            h.prepareItem(dialog, true, opener, function(data) {
                CURRENT_SECTION_CHANGED = true;
                cell.find('>ul').append(h.newitem(data));
                createAdder(cell);
            });
        }, function() {
            opener.trigger('submit');
            return false;
        }, function() {
            return true;
        });
    },
    edititem:function(li){
        var row = li.parents('td').attr('row'),
            col = li.attr('col'),
            url = '?app=page&controller=section&action=edititem&sectionid='+CURRENT_SECTIONID+'&row='+row+'&col='+col,
            a = li.find('a');
        var opener = ct.ajaxDialog({
            title:'编辑条目：'+a.text(),
            width:860
        }, url, function(dialog) {
            h.prepareItem(dialog, false, opener, function(data) {
                CURRENT_SECTION_CHANGED = true;
                h.itemattr(li, a, data);
                a.removeClass();
                if (data.icon) {
                    a.addClass('icon-' + data.icon);
                }
            });
        }, function() {
            opener.trigger('submit');
            return false;
        }, function() {
            return true;
        });
    },
    prepareItem:function(dialog, multiple, opener, callback) {
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
                        form.remove();
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
            var forms = formList.find('.section-form');
            if (forms.length) {
                var fm = forms.eq(0);
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
            h.bind_item_form_event(form, form);
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
    },
    bind_item_form_event:function(form, dialog) {
        var title = form.find('input[name=title]').maxLength();
        var colorPicker = title.parent().nextAll('img');
        colorPicker.titleColorPicker(title, colorPicker.next('input'));
        form.find('input[name="thumb"]').imageInput();
    },
    itemattr:function(li,a,item){
        // edit a title,text,url
        a.text(item.title||'')
            .attr('href', item.url||'')
            .attr('tips', item.tips);
        item.color && a.css('color', item.color);
        // edit li url
        li.attr('url', item.url);
    },
    delitem:function(li){
        ct.confirm('确定删除项“<b style="color:red">'+li.text()+'</b>”？',
            function(){
                var row = li.parents('td').attr('row');
                var col = li.attr('col');
                var url = '?app=page&controller=section&action=delitem';
                var data = 'sectionid='+CURRENT_SECTIONID+'&row='+row+'&col='+col;
                $.post(url, data, function(json){
                    if (json.state) {
                        CURRENT_SECTION_CHANGED = true;
                        // change next-all-li col value
                        var c = parseInt(col);
                        li.nextAll('li').each(function(){
                            this.setAttribute('col',c++);
                        });
                        // remove this li
                        li.remove();
                    } else {
                        ct.error(json.error);
                    }
                }, 'json');
            });
    },
    moveitemleft:function(li){
        if (itemMoveLock) return;
        var prevli = li.prev('li');
        if (!prevli.length) return;
        var row = li.parents('td').attr('row');
        var col = li.attr('col');
        var url = '?app=page&controller=section&action=leftitem';
        var data = 'sectionid='+CURRENT_SECTIONID+'&row='+row+'&col='+col;
        itemMoveLock = true;
        $.post(url, data, function(json){
            if (json.state) {
                CURRENT_SECTION_CHANGED = true;
                li.attr('col', prevli.attr('col'));
                prevli.attr('col', col);
                li.after(prevli);
            }
            itemMoveLock = false;
        },'json');
    },
    moveitemright:function(li){
        if (itemMoveLock) return;
        var nextli = li.next('li');
        if (!nextli.length) return;
        var row = li.parents('td').attr('row');
        var col = li.attr('col');
        var url = '?app=page&controller=section&action=rightitem';
        var data = 'sectionid='+CURRENT_SECTIONID+'&row='+row+'&col='+col;
        itemMoveLock = true;
        $.post(url, data, function(json){
            if (json.state) {
                CURRENT_SECTION_CHANGED = true;
                li.attr('col', nextli.attr('col'));
                nextli.attr('col', col);
                li.before(nextli);
            }
            itemMoveLock = false;
        },'json');
    },
    viewitem:function(li){
        ct.openWindow(li.attr('url'));
    },
    delrow:function(cell,row,force){
        var next = function() {
            var url = '?app=page&controller=section&action=delrow';
            var rowid = cell.attr('row');
            var data = 'sectionid='+CURRENT_SECTIONID+'&row='+rowid;
            $.post(url,data,function(json){
                if (json.state) {
                    CURRENT_SECTION_CHANGED = true;
                    var r = parseInt(rowid);
                    row.nextAll('tr').each(function(){
                        var cells = this.cells;
                        cells[1].setAttribute('row',r++);
                        cells[0].innerHTML = r;
                    });
                    row.remove();
                    if (rows != 0 && $(table[0].tBodies[0]).find('>tr').length < rows) {
                        h.addrow(table, 'last', true);
                    }
                }
            }, 'json');
        };
        if (force) {
            next();
        } else {
            ct.confirm('此操作不可恢复，确认删除此行吗？', next);
        }
    },
    downrow:function(cell,row){
        if (itemMoveLock) return;
        var nexttr = row.next('tr');
        if (!nexttr.length) return;
        var url = '?app=page&controller=section&action=downrow';
        var rowid = cell.attr('row');
        var data = 'sectionid='+CURRENT_SECTIONID+'&row='+rowid;
        itemMoveLock = true;
        $.post(url, data, function(json){
            if (json.state) {
                CURRENT_SECTION_CHANGED = true;
                // switch ul
                var ncell = $(nexttr[0].cells[1]);
                var ul = cell.find('>ul');
                cell.prepend(ncell.find('>ul'));
                ul.prependTo(ncell);
            }
            itemMoveLock = false;
        }, 'json');
    },
    uprow:function(cell,row){
        if (itemMoveLock) return;
        var prevtr = row.prev('tr');
        if (!prevtr.length) return;
        var url = '?app=page&controller=section&action=uprow';
        var rowid = cell.attr('row');
        var data = 'sectionid='+CURRENT_SECTIONID+'&row='+rowid;
        itemMoveLock = true;
        $.post(url, data, function(json){
            if (json.state) {
                CURRENT_SECTION_CHANGED = true;
                // switch ul
                var pcell = $(prevtr[0].cells[1]);
                var ul = cell.find('>ul');
                cell.prepend(pcell.find('>ul'));
                ul.prependTo(pcell);
            }
            itemMoveLock = false;
        }, 'json');
    },
    addrow:function(table, n, skip_additem){
        var tbody = table[0].tBodies[0];
        var l = tbody.rows.length;
        var url = '?app=page&controller=section&action=addrow';
        if (!n) {
            n = 0;
        } else if (n == 'last') {
            n = l;
        }
        var data = 'sectionid='+CURRENT_SECTIONID+'&pos='+n;
        $.post(url, data, function(json){
            if (!json.state) {
                ct.tips('添加行失败','error');
                return;
            }
            CURRENT_SECTION_CHANGED = true;
            n < l && $(tbody).find(n > 0 ? ('>tr:gt('+(n-1)+')') : '>tr').each(function(i){
                $('td:eq(0)', this).html(n+i+2);
                $('td:eq(1)', this).attr('row', n+i+1);
            });
            var tr = $('<tr>\
            <td class="t_c" width="30">'+(n+1)+'</td>\
            <td row="'+n+'">\
                <ul class="inline w_120"></ul>\
            </td>\
            <td class="t_c" width="30">\
                <img alt="删除" width="16" height="16" class="hand" action="delrow" src="images/del.gif" />\
            </td>\
        </tr>');
            n == 0 ? tr.prependTo(tbody) :
                (n == l ? tr.appendTo(tbody) : $(tbody).find('>tr:eq('+(n-1)+')').after(tr));
            h.readyrow(tr);

            var trs = $(tbody).find('>tr');
            if (rows != 0 && trs.length > rows) {
                var lastrow = trs.filter(':last');
                var cells = lastrow[0].cells;
                var c1 = $(cells[1]);
                h.delrow(c1, lastrow, true);
            }

            !skip_additem && h.additem($(tr[0].cells[1]));
        },'json');
    }
};
window.handaction = h;
})();