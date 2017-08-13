function floatBox(html, pos, afterHtml) {
    $('div.floatbox').remove();
    var div = $('<div class="floatbox" style="position:fixed;visibility:hidden"></div>')
        .appendTo(document.body);
    var img = $('<img src="images/close.gif" />').css({
        position:'absolute',
        top:1,
        right:2,
        cursor:'pointer'
    }).click(function(){
            div.remove();
        });

    div.html(img).append(html);
    typeof afterHtml == 'function' && afterHtml(div);
    var doc = $(document);
    var style = cmstop.pos(pos, div.outerWidth(true), div.outerHeight(true));
    style.visibility = 'visible';
    div.css(style);
    return div;
}
(function(){
    var noop = function(){return false};
    var allPageIds = [], allPages = {}, publishAllPagesLock = false;
    var tree;
    var func = {
        init:function(options) {
            options || (options = {});
            tree = new ct.treeTable('#treeTable',{
                idField:'pageid',
                treeCellIndex:0,
                template:options.rowTemplate,
                baseUrl:options.baseUrl || '?app=page&controller=page&action=tree&status=1',
                rowReady:function(id,tr,json) {
                    tr.find('img.hand').click(function(){
                        var a = this.getAttribute('func');
                        a && (ct.func(a) || noop)(id, tr, json, this);
                    }).dblclick(noop);
                    tr.dblclick(function(){
                        (options.edit || page.edit)(id, tr, json);
                    });
                    tr.find('a.edit').click(function(){
                        (options.edit || page.edit)(id, tr, json);
                    });
                    tr.contextMenu('#right_menu', function(action) {
                        if (action == 'viewPage') {
                            app.viewPage(json.url);
                        } else {
                            (app[action] || noop)(id, tr, json, this);
                        }
                    });
                    options.rowReady && options.rowReady(id, tr, json);
                },
                rowsPrepared:function(tbody) {
                    $.each(tbody.children('tr'), function(index, tr) {
                        allPageIds.push(tr.id);
                        allPages[tr.id] = $(tr).find('a:first').text();
                    });
                },
                sort:function(a, b) {
                    var da = $(a).data('json'), db = $(b).data('json'),
                        sa = parseInt(da.sort), sb = parseInt(db.sort);
                    if (sa == sb) {
                        return parseInt(da.created) - parseInt(db.created);
                    }
                    return sa - sb;
                }
            });
            tree.load();
        },
        visualedit:function(pageid, tr) {
            ct.openWindow('?app=page&controller=page&action=visualedit&pageid='+pageid);
        },
        viewPage:function(url){
            ct.openWindow(url);
        },
        edit:function(pageid, tr, json){
            ct.assoc.open('?app=page&controller=page&action=view&pageid='+pageid, 'newtab', json.clickpath.split(','));
        },
        admin:function(pageid, tr, json){
            ct.assoc.open('?app=page&controller=page&action=admin&pageid='+pageid, 'newtab', json && json.clickpath.split(','));
        },
        addPage:function(pageid){
            ct.form('添加页面', '?app=page&controller=page&action=add&parentid='+pageid,
                400, 230, function(json){
                    if (json.state)
                    {
                        var d = json.data;
                        ct.assoc.call('refresh');
                        tree.addRow(d);
                        ct.tips('添加页面成功，2秒后进入该页面','success');
                        setTimeout(function(){
                            ct.assoc.open('?app=page&controller=page&action=view&pageid='+d.pageid, 'current', d.clickpath.split(','));
                        }, 2000);
                        return true;
                    }
                });
        },
        addRootPage:function(){
            ct.form('添加页面', '?app=page&controller=page&action=add&parentid=0', 400, 230,
                function(json){
                    if (json.state)
                    {
                        var d = json.data;
                        ct.assoc.call('refresh');
                        tree.addRow(d);
                        ct.tips('添加页面成功，2秒后进入该页面','success');
                        setTimeout(function(){
                            ct.assoc.open('?app=page&controller=page&action=view&pageid='+d.pageid, 'current', d.clickpath.split(','));
                        }, 2000);
                    }
                });
        },
        pageProperty:function(pageid){
            ct.form('编辑页面属性','?app=page&controller=page&action=edit&pageid='+pageid,
                380, 290,
                function(json) {
                    if (json.state) {
                        ct.tips(json.info, 'success');
                        if (json.data) {
                            tree.updateRow(pageid, json.data);
                        }
                        return true;
                    }
                });
        },
        pageCopy:function(pageid){
            ct.form('页面克隆', '?app=page&controller=page&action=pagecopy&pageid='+pageid, 400, 230,
            function(json){
                if (json.state) {
                    var arrEntities={'lt':'<','gt':'>','nbsp':' ','amp':'&','quot':'"'};
                    var data = json.html.replace(/&(lt|gt|nbsp|amp|quot);/ig,function(all,t){return arrEntities[t];});
                    var html = '<div id="pagebg" class="ui-widget-overlay" style="z-index: 1001;display:block;"></div>';
                    html += '<div id="pagedialog" style="display: block; position: absolute; overflow: hidden; z-index: 1002; outline: 0px none; height: auto; width: 700px; top: 57.28px; left: 257px;" class="ui-dialog dialog-box ui-draggable" tabindex="-1" role="dialog" aria-labelledby="ui-dialog-title-1"><div class="titlebar" unselectable="on" style="-moz-user-select: none;"><span class="pop_title" id="ui-dialog-title-1" unselectable="on" style="-moz-user-select: none;">页面</span><span class="close" role="button" unselectable="on" style="-moz-user-select: none;"></span></div><div class="masker"></div><div class="ui-dialog-content ui-widget-content" style="overflow: auto; max-height: 900px; position: relative; height: 610px; min-height: 86px; width: 700px;"><div class="bk_8"></div>';
                    html += '<table cellspacing="0" cellpadding="0" border="0" width="98%" class="table_form">';
                    html += '<tbody><tr>';
                    html += '<td>以下内容可复制到相应目录下</td>';
                    html += '</tr>';
                    html += '<tr>';
                    html += '<td>';
                    html += '<textarea style="width:650px;height:800px;" id="pagehtml">'+data+'</textarea>';
                    html += '</td>';
                    html += '</tr>';
                    html += '</tbody></table>';
                    $('body').append(html);
                    $('#pagedialog .close').click(function(){
                        $('#pagebg').remove();
                        $('#pagedialog').remove();
                    });
                    return true;
                } else {
                    ct.error(json.error);
                }
            });
        },
        publishPage:function(pageid){
            $.post('?app=page&controller=page&action=publish','pageid='+pageid,
                function(json){
                    if(json.state){
                        ct.ok('生成成功');
                        tree.updateRow(pageid, json.page);
                    }else{
                        ct.error(json.error);
                    }
                },'json');
        },
        publishAllPages:function(){
            if (publishAllPagesLock) return false;
            var self = this;
            ct.confirm('生成 <span style="color:red;">全部页面</span> 需要较长时间，确定要开始生成吗?', function() {
                var index = 0, total = allPageIds.length, pageId, pageName,
                    messageBox, message;
                publishAllPagesLock = true;
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
                    if ((pageId = allPageIds[index++])) {
                        pageName = allPages[pageId];
                        pageId = parseInt(pageId.replace(/^row_/, ''));
                        message = '正在生成页面 <span style="color:green;">' + pageName + '</span> ...';
                        showMessage(message);
                        $.post('?app=page&controller=page&action=publish', 'pageid=' + pageId, function(json) {
                            if(json.state) {
                                showMessage(message + ' 成功');
                                tree.updateRow(pageId, json.page);
                            } else {
                                showMessage(message + ' 失败' + (json.error ? '：' + json.error : ''));
                            }
                            setTimeout(publishNext, 500);
                        }, 'json');
                    } else {
                        hideMessage();
                        ct.tips('生成 <span style="color:green;">全部页面</span> 操作完成');
                        publishAllPagesLock = false;
                    }
                }
                publishNext();
            });
        },
        priv:function(id) {
            ct.iframe({
                url: '?app=page&controller=page_priv&action=index&pageid='+id,
                width: 350,
                height: 300
            });
        },
        logs:function(id) {
            ct.iframe({
                url:'?app=page&controller=page&action=logs&pageid='+id,
                width: 600,
                height: 490
            });
        },
        allPageLogs:function() {
            ct.iframe({
                url:'?app=page&controller=page&action=logs&',
                width: 600,
                height: 490
            });
        },
        remove: function(pageid) {
            ct.confirm('确定要删除此页面吗？',function(){
                $.post('?app=page&controller=page&action=remove', 'pageid='+pageid, function(json){
                    if(json.state){
                        tree.deleteRow(pageid);
                        ct.assoc.call('refresh');
                        ct.ok('页面删除成功');
                    }else{
                        ct.error(json.error);
                    }
                },'json');
            });
        },
        copy: function(pageid) {
            ct.formDialog({
                title: '克隆区块',
                height: 210
            }, '?app=page&controller=page&action=copy&pageid='+pageid, function(json) {
                if (json.state) {
                    if (tree) {
                        tree.load();
                        func.publishPage(json.pageid);
                    }
                    return true;
                } else {
                    ct.error(json.error);
                    return false;
                }
            });
        },
        restore: function(pageid) {
            ct.confirm('确定要恢复此页面吗？', function() {
                $.post('?app=page&controller=page&action=restore', 'pageid='+pageid, function(json){
                    if(json.state){
                        
                        ct.assoc.call('refresh');
                        ct.ok('页面恢复成功');
                    }else{
                        ct.error(json.error);
                    }
                },'json');
            });
        },
        del:function(pageid){
            ct.confirm('此操作不可恢复，确认删除此页面吗？',function(){
                $.post('?app=page&controller=page&action=delete', 'pageid='+pageid,
                    function(json){
                        if(json.state){
                            tree.deleteRow(pageid);
                            ct.assoc.call('refresh');
                            ct.ok('页面已删除');
                        }else{
                            ct.error(json.error);
                        }
                    },'json');
            });
        },
        bakup:function(pageid,tr){
            $.getJSON('?app=page&controller=page&action=bakup&pageid='+pageid,function(json){
                if (json.state) {
                    ct.tips(json.info,'success');
                } else {
                    ct.tips(json.error,'error');
                }
            });
        },
        recoverPage:function(o){
            floatBox('\
    	<input type="text" class="bdr_6" size="30"\
    		url="?app=page&controller=page&action=baksuggest&keyword=%s"\
    	/>', o,
                function(box){
                    box.find('input').autocomplete({
                        itemSelected:function(a, item){
                            box.remove();
                            ct.confirm('此操作可能有意外，确定使用备份文件<b class="c_red">'+item.text+'</b>吗？',function(){
                                $.post('?app=page&controller=page&action=recover',
                                    'bakfile='+encodeURIComponent(item.text),function(json){
                                        if (json.state) {
                                            tree.addRow(json.data);
                                            ct.tips(json.info,'success');
                                        } else {
                                            ct.tips(json.error,'error');
                                        }
                                    },'json');
                            });
                        }
                    });
                });
        },
        recover:function(pageid,tr,json,o){
            floatBox('\
    	<input type="text" class="bdr_6" size="30"\
    		url="?app=page&controller=page&action=baksuggest&pageid='+pageid+'&keyword=%s"\
    	/>', o,
                function(box){
                    box.find('input').autocomplete({
                        itemSelected:function(a, item){
                            box.remove();
                            ct.confirm('此操作可能有意外，确定使用备份文件<b class="c_red">'+item.text+'</b>恢复<b class="c_red">'+json.name+'</b>吗？',function(){
                                $.post('?app=page&controller=page&action=recover&pageid='+pageid,
                                    'bakfile='+encodeURIComponent(item.text),function(json){
                                        if (json.state) {
                                            tree.addRow(json.data);
                                            ct.tips(json.info,'success');
                                        } else {
                                            ct.tips(json.error,'error');
                                        }
                                    },'json');
                            });
                        }
                    });
                });
        },
        test:function(){
            var overlay = $('<div class="overlay"></div>').appendTo(document.body);
            var testbox = $(
                '<div class="test-box">'+
                    '<div class="close">&#x2716;</div>'+
                    '<div class="progress-control">'+
                    '<div class="control">开始检测</div>'+
                    '<div class="progress">'+
                    '<div class="bar">'+
                    '<div class="percent">0%</div>'+
                    '<div class="indicator"></div>'+
                    '</div>'+
                    '</div>'+
                    '</div>'+
                    '<div class="current"></div>'+
                    '<div class="output"></div>'+
                    '</div>').appendTo(document.body);
            var close, progressControl, progress, percent, indicator, control, state, output;
            testbox.find('div').each(function(){
                switch(this.className){
                    case 'close': close = $(this); break;
                    case 'progress-control': progressControl = $(this); break;
                    case 'progress': progress = $(this); break;
                    case 'percent': percent = $(this); break;
                    case 'indicator': indicator = $(this); break;
                    case 'control': control = $(this); break;
                    case 'current': current = $(this); break;
                    case 'output': output = $(this); break;
                }
            });
            var running = false, proceed = 0, errors=0, ival = null, inPing = true, xhr;
            progress.hide();
            function stop(clear){
                running = false;
                progress.hide();
                progressControl.removeClass('wide');
                xhr && xhr.abort();
                control.html('重新检测');
                current.html('检测完毕 <b>共检测:'+proceed+'</b> <b style="color:red">问题数:'+errors+'</b>');
                ival && clearTimeout(ival);
                $.getJSON('?app=page&controller=page&action=stopTest&clear='+(clear||0));
                ct.endLoading();
            }
            function start(){
                if (inPing) return;
                running = true;
                proceed = 0;
                errors = 0;
                percent.html('0%');
                indicator.width('0%');
                progressControl.addClass('wide');
                testbox.removeClass('haserror');
                progress.show();
                control.html('终止检测');
                current.empty();
                output.empty();
                xhr = $.ajax({
                    dataType:'json',
                    url:'?app=page&controller=page&action=test'
                });
                ct.endLoading();
                ival = setTimeout(ping, 50);
            }
            function update(json){
                inPing = false;
                if (!running) return;
                if (json.state) {
                    xhr.abort();
                    var p = Math.floor(json.percent * 100)+'%';
                    percent.html(p);
                    indicator.width(p);
                    proceed = json.proceed;
                    current.html('正在检测:'+json.current);
                    if (json.results && json.results.length) {
                        errors || testbox.addClass('haserror');
                        errors += json.results.length;
                        $.each(json.results, function(){
                            output.append('<p>'+this+'</p>');
                        });
                        output.scrollTop(10000);
                    }
                    if (json.percent == 1 || json.total == 0) {
                        return stop(1);
                    }
                } else if (proceed > 0) {
                    return stop();
                }
                ival = setTimeout(ping, 20);
            }
            function ping(){
                inPing = true;
                $.getJSON('?app=page&controller=page&action=pingTest&proceed='+proceed, update);
                ct.endLoading();
            }
            control.click(function(){
                running ? stop(1) : start();
            });
            close.click(function(){
                running = false;
                xhr && xhr.abort();
                ival && clearTimeout(ival);
                testbox.remove();
                overlay.remove();
            });
            $.getJSON('?app=page&controller=page&action=pingTest', function(json){
                inPing = false;
                json.state && start();
            });
        }
    };
    window.app = func;
})();