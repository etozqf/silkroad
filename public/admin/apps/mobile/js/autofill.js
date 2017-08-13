(function() {
    function formReady(form, dialog) {
        form.find('.modelset').modelset();
        form.find('.selectree').selectree();
        form.find('.suggest').suggest();

        var tbody = $('tbody[data-port]');
        $('.ui-filter').children('a').click(function() {
            var trigger = $(this);
            trigger.addClass('active').siblings().removeClass('active');
            form[0].port.value = trigger.attr('data-port');
            tbody.hide().find(':input').attr('disabled', true);
            tbody.filter('[data-port=' + form[0].port.value + ']').show().find(':input').removeAttr('disabled');
            return false;
        }).filter('[data-port=' + form[0].port.value + ']').click();
    }

    window.MobileAutofill = {
        init: function(options) {
            window.tableApp = new ct.table('#item_list', {
                baseUrl: '?app=mobile&controller=autofill&action=page',
                rowIdPrefix: 'row_',
                pageField: 'page',
                pageSize: 15,
                dblclickHandler: MobileAutofill.edit,
                rowCallback: function(id, tr) {
                    tr.find('img.edit').click(function() {
                        MobileAutofill.edit(id);
                        return false;
                    });
                    tr.find('img.delete').click(function() {
                        MobileAutofill.del(id);
                        return false;
                    });

                    var disabled = parseInt(tr.attr('data-disabled'), 10);
                    if (disabled) {
                        tr.find('img.disable').hide();
                        tr.find('img.enable').show().click(function() {
                            MobileAutofill.enable(id);
                            return false;
                        });
                    } else {
                        tr.find('img.enable').hide();
                        tr.find('img.disable').show().click(function() {
                            MobileAutofill.disable(id);
                            return false;
                        });
                    }
                },
                jsonLoaded: function (json) {
                    $('#pagetotal').html(json.total);
                },
                template: options.rowTemplate
            });
            tableApp.load();
        },
        add: function() {
            ct.formDialog({
                title: '添加配置',
                width: 450
            }, '?app=mobile&controller=autofill&action=add', function(json) {
                if (json && json.state) {
                    ct.ok('添加成功');
                    tableApp.load();
                    return true;
                }
            }, formReady);
        },
        edit: function(id) {
            ct.formDialog({
                title: '修改配置',
                width: 450
            }, '?app=mobile&controller=autofill&action=edit&id=' + id, function(json) {
                if (json && json.state) {
                    ct.ok('修改成功');
                    tableApp.reload();
                    return true;
                }
            }, formReady);
        },
        enable:function(id) {
            $.getJSON('?app=mobile&controller=autofill&action=toggle&disabled=0&id=' + id, function(json) {
                if (json && json.state) {
                    ct.ok('启用成功');
                    tableApp.reload();
                } else {
                    ct.error(json && json.error || '启用失败');
                }
            });
        },
        disable:function(id) {
            $.getJSON('?app=mobile&controller=autofill&action=toggle&disabled=1&id=' + id, function(json) {
                if (json && json.state) {
                    ct.ok('禁用成功');
                    tableApp.reload();
                } else {
                    ct.error(json && json.error || '禁用失败');
                }
            });
        },
        del:function(id) {
            var length = 1;
            if (!id) {
                id = tableApp.checkedIds();
                length = id.length;
                id = id.join(',');
            }
            if (!id) {
                ct.error('请选择要删除的记录');
                return false;
            }
            ct.confirm('操作不可撤销，确定要删除选择的 <span style="color:red;">' + length + '</span> 条记录吗？', function() {
                $.getJSON('?app=mobile&controller=autofill&action=delete&id=' + id, function(json) {
                    if (json && json.state) {
                        ct.ok('删除成功');
                        tableApp.reload();
                    } else {
                        ct.error(json && json.error || '删除失败');
                    }
                });
            });
            return false;
        }
    };
})();