(function() {
var pageid, pageurl,
    allSections, allSectionIds, publishAllLock;
var a = {
    init:function(option) {
        option || (option = {});
        pageid = option.pageid;
        pageurl = option.pageurl;
        allSections = option.allSections;
        allSectionIds = option.allSectionIds;
    },
    addSection:function(pageid, afterSubmit) {
        ct.formDialog({
            title: '添加区块',
            width: 540
        }, '?app=page&controller=section&action=add&pageid='+pageid, function(json) {
            if (json && json.state) {
                ct.ok('添加成功');
                tableApp && tableApp.load('');
                afterSubmit && afterSubmit(json);
                return true;
            } else {
                ct.error(json && json.error || '添加失败');
            }
        }, function(form, dialog) {
            $(form[0].data).editplus({
                buttons:(form.find('[name="type"]:checked').val() == 'auto' ? 'fullscreen,wrap,|,db,content,discuz,phpwind,shopex,|,loop,ifelse,elseif,|,preview' : 'fullscreen,wrap,visual'),
                width:410,
                height:200
            });
            a.bindFieldsEvent(form);
            dialog.find('input[name=type]:not(:checked)').click(function(){
                var f = form[0], n = f.name.value, c = f.description.value, t = this.value;
                dialog.load('?app=page&controller=section&action=add&pageid='+pageid+'&type='+t,
                    function(){
                        dialog.trigger('ajaxload');
                        var frm = dialog.find('form:eq(0)'), _f = frm[0];
                        _f.name.value = n;
                        _f.description.value = c;
                    });
            });
        });
    },
    bindFieldsEvent:function(form) {
        form.find('.fields-delete').each(function() {
            $(this).click(function() {
                var row = $(this).closest('.fields-row-custom');
                if (row.siblings('.fields-row-custom').length) {
                    row.remove();
                } else {
                    row.find(':text').val('');
                }
                return false;
            });
        });
        form.find('.fields-add').click(function() {
            var lastRow = form.find('.fields-row-custom:last'),
                row = lastRow.clone(true);
            row.find(':text').val('');
            lastRow.after(row);
            return false;
        });
        form.find('[fn-toggle]').click(function() {
            $($(this).attr('fn-toggle')).toggle();
        });
    },
    publishSection:function(sectionid) {
        $.post('?app=page&controller=section&action=publish','sectionid='+sectionid, function(json){
            if (json.state) {
                ct.ok(json.info);
            } else {
                ct.error(json.error);
            }
        }, 'json');
    },
    publishAllSections:function(pageid) {
        if (publishAllLock) return false;
        ct.confirm('生成 <span style="color:red;">全部区块</span> 需要较长时间，确定要开始生成吗?', function() {
            var index = 0, sectionId, sectionName,
                messageBox, message;
            publishAllLock = true;
            function showMessage(message) {
                var sub = '<sub></sub> ';
                if (! messageBox) {
                    messageBox = $('<div class="ct_tips success"></div>').css({
                        position: 'fixed',
                        zIndex: 999,
                        minWidth: 200
                    }).appendTo(document.body);
                }
                messageBox.html(sub + message).css(ct.pos('center', messageBox.outerWidth(true), messageBox.outerHeight(true)));
            }
            function hideMessage() {
                messageBox && messageBox.remove();
            }
            function publishNext() {
                if ((sectionId = allSectionIds[index++])) {
                    sectionName = allSections[sectionId];
                    message = '正在生成区块 <span style="color:green;">' + sectionName + '</span> ...';
                    showMessage(message);
                    $.post('?app=page&controller=section&action=publish', 'sectionid=' + sectionId, function(json) {
                        if(json.state) {
                            showMessage(message + ' 成功');
                        } else {
                            showMessage(message + ' 失败' + (json.error ? '：' + json.error : ''));
                        }
                        setTimeout(publishNext, 500);
                    }, 'json');
                } else {
                    hideMessage();
                    ct.tips('生成 <span style="color:green;">全部区块</span> 操作完成');
                    publishAllLock = false;
                }
            }
            publishNext();
        });
    },
    copySection:function(sectionid) {
        ct.formDialog({
            title: '克隆区块',
            height: 300
        }, '?app=page&controller=section&action=copy&sectionid='+sectionid, function(json) {
            if (json.state) {
                ct.ok(json.info);
                tableApp && tableApp.load('');
                return true;
            } else {
                ct.error(json.error);
                return false;
            }
        });
    },
    moveSection:function(sectionid) {
        ct.form('移动区块','?app=page&controller=section&action=move&sectionid='+sectionid, 250, 300, function(json) {
            if (json.state) {
                ct.ok(json.info);
                tableApp && tableApp.load('');
                return true;
            } else {
                ct.error(json.error);
                return false;
            }
        });
    },
    changeSectionType:function(sectionid) {
        ct.formDialog({
            title: '区块类型转换'
        }, '?app=page&controller=section&action=change_type&sectionid='+sectionid, function(json) {
            if (json.state) {
                ct.ok(json.info);
                tableApp && tableApp.load('');
                return true;
            } else {
                ct.error(json.error);
                return false;
            }
        });
    },
    jumpToEditPage:function(pageid) {
        ct.assoc.open('?app=page&controller=page&action=view&pageid='+pageid+'&force=1', 'newtab');
    },
    jumpToEditSection:function(sectionid, tr, page) {
        page = page || pageid;
        ct.assoc.open('?app=page&controller=page&action=view&pageid='+page+'&sectionid='+sectionid+'&force=1', 'newtab');
    },
    jumpToPreviewSection:function(sectionid) {
        var url = '?app=page&controller=section&action=preview&pageid='+pageid+'&sectionid='+sectionid;
        ct.openWindow.startLoop();
        $.getJSON(url+'&detect=1', function(json) {
            if (!json.state) {
                ct.openWindow.endLoop();
                ct.warn('无预览');
                return;
            }
            if (json.open) {
                ct.openWindow(url+'&gen='+Math.random()+'#'+sectionid, 'previewsection_'+sectionid);
            } else {
                ct.openWindow.endLoop();
                $('<div class="section-preview">'+json.html+'</div>').dialog({
                    title:'预览区块'
                });
            }
        });
    },
    sectionPriv:function(sectionid) {
        ct.iframe({
            url: '?app=page&controller=section_priv&action=index&sectionid='+sectionid,
            width: 350,
            height: 300
        }, {}, null, function() {
            tableApp && tableApp.reload();
        });
    },
    sectionProperty:function(sectionid) {
        var url = '?app=page&controller=section&action=property&sectionid='+sectionid;
        ct.formDialog({
            title: '设置区块属性',
            width: 540
        }, url, function(json) {
            if (json.state) {
                tableApp && tableApp.load('');
                return true;
            } else {
                ct.error(json.error || '设置失败');
                return false;
            }
        }, function(form) {
            form[0].data && $(form[0].data).editplus({
                buttons:'fullscreen,wrap,|,db,content,loop,ifelse,elseif',
                width:410,
                height:200
            });
            a.bindFieldsEvent(form);
        });
    },
    removeSection:function(sectionid, tr) {
        ct.confirm('确定要删除区块'+(tr ? '<strong class="c_red">'+tr.find('.section-item').text()+'</strong>' : '')+'吗？', function() {
            $.post('?app=page&controller=section&action=remove','sectionid='+sectionid, function(json) {
                if (json.state) {
                    ct.ok('删除成功');
                    tableApp && tableApp.load('');
                } else {
                    ct.error(json.error || '删除失败');
                    return false;
                }
            },'json');
        });
    },
    restoreSection:function(sectionid, tr) {
        ct.confirm('确定要恢复区块'+(tr ? '<strong class="c_red">'+tr.find('.section-item').text()+'</strong>' : '')+'吗？', function() {
            $.post('?app=page&controller=section&action=restore','sectionid='+sectionid, function(json) {
                if (json.state) {
                    ct.ok('恢复成功');
                    tableApp && tableApp.load('');
                } else {
                    ct.error(json.error || '恢复失败');
                    return false;
                }
            },'json');
        });
    },
    deleteSection:function(sectionid, tr) {
        ct.confirm('操作不可恢复，确定要彻底删除区块'+(tr ? '<strong class="c_red">'+tr.find('.section-item').text()+'</strong>' : '')+'吗？', function() {
            $.post('?app=page&controller=section&action=delete','sectionid='+sectionid, function(json) {
                if (json.state) {
                    ct.ok('区块已被彻底删除');
                    tableApp && tableApp.load('');
                } else {
                    ct.error(json.error || '删除失败');
                }
            },'json');
        });
    },
    fillSectionData:function(sectionid) {
        if (!sectionid) {
            sectionid = (tableApp.checkedIds() || []).join(',');
        }
        if (!sectionid.length) {
            ct.warn('请选择要填充的区块');
            return false;
        }
        ct.formDialog({
            title:'填充区块数据',
            width:300
        }, '?app=page&controller=section&action=fill_data&sectionid='+sectionid, function(json) {
            ct.ok('填充完成');
            return true;
        });
    },
    editPage:function(sectionid) {
        ct.form('编辑页面属性','?app=page&controller=page&action=edit&pageid='+sectionid, 380, 290, function(json) {
            if (json.state) {
                ct.ok(json.info || '修改成功');
                pageurl = json.data.url;
                return true;
            }
        });
    },
    pagePriv:function(pageid) {
        ct.iframe({
            url: '?app=page&controller=page_priv&action=index&pageid='+pageid,
            width: 350,
            height: 300
        });
    },
    templateEdit:function(pageid) {
        ct.assoc.open('?app=system&controller=template&action=edit&pageid='+pageid,'newtab');
    },
    visualEdit:function(pageid) {
        ct.openWindow('?app=page&controller=page&action=visualedit&pageid='+pageid, 'view_edit');
    },
    viewPage:function(pageid) {
        ct.openWindow(pageurl, 'viewpage_'+pageid);
    },
    logs:function(pageid) {
        ct.iframe({
            url:'?app=page&controller=page&action=logs&pageid='+pageid,
            width: 600,
            height: 490
        });
    },
    publishPage:function(pageid) {
        $.post('?app=page&controller=page&action=publish','pageid='+pageid, function(json) {
            if (json.state) {
                ct.ok(json.info);
            } else {
                ct.error(json.error);
            }
        },'json');
    },
    highlightSection:function() {
        function blink(elem, dur, callback) {
            var i = 0, name = 'over',
                ival = setInterval(function() {
                    elem.hasClass(name)
                        ? elem.removeClass(name)
                        : elem.addClass(name);
                    if (++i > 4) {
                        clearInterval(ival);
                        ival = null;
                        $.isFunction(callback) && callback();
                    }
                }, dur||250);
            elem.addClass(name);
        }
        return function(sectionid) {
            var rows = $('#list_body').find('>tr'),
                row = rows.filter('[sectionid='+sectionid+']');
            if (row.length) {
                rows.filter('.row_chked').trigger('unCheck');
                rows.removeClass('over');
                row[0].scrollIntoView();
                blink(row, 250, function() {
                    row.trigger('check');
                });
            }
        };
    }()
};
window.app = a;
})();