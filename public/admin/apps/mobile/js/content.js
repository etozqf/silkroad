;$(function() {
function buildQuery(baseUrl, obj, ignoreEmpty) {
    var query = [];
    for (var key in obj) {
        if (obj.hasOwnProperty(key)) {
            if (!ignoreEmpty || (typeof obj[key] != 'undefiend' && obj[key] !== null)) {
                query.push(key + '=' + obj[key])
            }
        }
    }
    query = query.join('&');
    query && (query = ((baseUrl.indexOf('?') > -1) ? '&' : '?') + query);
    return baseUrl + query;
}
function formatIds(ids) {
    if (ids) {
        ids = (ids + '').split(',');
    } else {
        ids = itemlist.checkedIds();
    }
    return ids;
}
var catid = ct.getParam('catid');
window.MobileContent = {
    formChanged:false,
    init:function() {
        var contentid = ct.getParam('contentid'),
            model = ct.getParam('controller');

        // Tips
        $('.tips').attrTips('tips', 'tips_green');

        // 栏目选择

        function getBindIds(items) {
            var checked = [], bind;
            if (items && items.length) {
                $.each(items, function(index, item) {
                    (bind = $(item).attr('data-catid-bind')) && (checked.push(bind));
                });
            }
            return checked.length ? checked.join(',') : '';
        }
        $('.ui-checkable:eq(0)').checkable({
            init:function(items) {
                MobileContent.bindCatids = getBindIds(items);
            },
            change:function(value, items) {
                MobileContent.bindCatids = getBindIds(items);
                MobileContent.formChanged = true;
            },
            select:''
        });
        if ( classify = $('.ui-checkable:last')) {

        }
        $('.ui-checkable:eq(1)').checkable({
            init:function(items) {
                MobileContent.bindCatids = getBindIds(items);
            },
            change:function(value, items) {
                MobileContent.bindCatids = getBindIds(items);
                MobileContent.formChanged = true;
            },
            select:'radio'
        });

        // 日期选择器
        $('input.input_calendar').change(function() {
            MobileContent.formChanged = true;
        }).DatePicker({'format':'yyyy-MM-dd HH:mm:ss'});

        $('form:first').find(':input').change(function() {
            MobileContent.formChanged = true;
        });

        // 页面关闭前的提示
        window.onbeforeunload = function() {
            return MobileContent.formChanged ? '' : null;
        };

        // 锁定内容
        if (ct.getParam('action') == 'edit' && contentid && model) {
            var interval = setInterval(function(){
                MobileContent.lock(model, contentid);
            }, 10000);
            $(window).unload(function () {
                clearInterval(interval);
                MobileContent.unlock(model, contentid);
            });
        }

        $('[data-maxlength]').maxLength({
            prevent: false,
            count: $.fn.maxLength.COUNT_LEFT,
            mode: $.fn.maxLength.MODE_CHINESE,
            display: MobileContent.maxLengthDisplay
        });
    },
    maxLengthDisplay:(function() {
        var tips = $('.maxlength'), timeout;
        if (!tips.length) {
            tips = $('<span class="maxlength validator-tooltips"></span>').hide().appendTo(document.body);
        }
        return function(left, error) {
            var offset = this.offset();
            timeout && clearTimeout(timeout);
            if (error) {
                tips.addClass('validator-error');
                this.addClass('validator-error');
            } else {
                tips.removeClass('validator-error');
                this.removeClass('validator-error');
            }
            tips.text(left >= 0 ? '还可输入 ' + left + ' 个字' : '已超出 ' + Math.abs(left) + ' 个字');
            tips.css({
                left: offset.left + (this.outerWidth(true) - tips.outerWidth(true)),
                top: offset.top - tips.outerHeight(true) - 2
            }).show();
            timeout = setTimeout(function() {
                tips.hide();
            }, 2000);
        };
    })(),
    success:function(json) {
        var successMsg = $('#success-msg'),
            action = ct.getParam('action'),
            type = action == 'add' ? '添加' : '修改',
            message = '恭喜，内容'+type+'成功',
            title = $('#title').val(),
            url = json.data.url ? json.data.url : $('#url').val(),
            buttons = {};

        MobileContent.contentid = json.data.contentid;
        MobileContent.formChanged = false;
        window.onbeforeunload = null;

        if (action == 'add') {
            buttons['继续添加'] = function() {
                location.href = location.href.replace(location.hash, '');
            };
            buttons['修改'] = function() {
                if(json.data && json.data.contentid){
                    var model = ct.getParam('controller');
                    location.href = '?app=mobile&controller='+model+'&action=edit&contentid='+json.data.contentid;
                }
            };
        } else {
            buttons['继续修改'] = function() {
                location.href = location.href.replace(location.hash, '');
            };
        }
        buttons['关闭'] = function() {
            if(top != self) {
                ct.assoc.close()
            } else {
                self.close();
            }
        };

        successMsg.find('[data-role=message]').html(message);
        successMsg.find('[data-role=title]').html(title);
        if (json.data.status == 6) {
            if (url) {
                successMsg.find('[data-role=url]').attr('href', url).text(url);
            } else {
                successMsg.find('[data-role=url-row]').hide();
            }
        } else {
            successMsg.find('tr:gt(0)').not(':last').hide();
        }

        var btnArea = successMsg.find('[data-role=buttons]');
        $.each(buttons, function(text, func) {
            var clazz = text.length <= 2 ? 'button_style_2' : 'button_style_4';
            $('<button class="'+clazz+'" type="button">'+text+'</button>').click(func).appendTo(btnArea);
        });

        $('<div/>').css({
            position:'fixed',
            zIndex:998,
            left:0,
            top:0,
            width:'100%',
            height:'100%',
            margin:0,
            padding:0,
            opacity:.80,
            background:'#FFF'
        }).appendTo(document.body);
        successMsg.css({
            top: $(document).scrollTop() + Math.floor(($(window).height() - successMsg.outerHeight(true)) / 2),
            zIndex:999
        }).show();

        try {
            parent && $('#frame_container iframe', parent.document).filter(function(){
                return this.src.match(/\?app=mobile&controller=content(&action=index)?/i);
            }).each(function(){
                this.contentWindow['itemlist'] && this.contentWindow['itemlist'].reload();
            });
        } catch(e) {}
    },

    addToSlider:function(id) {
        ct.formDialog({
            title:'设为幻灯片',
            width:400
        }, buildQuery('?app=mobile&controller=content&action=slider_add', {
            catid:catid,
            contentid:id
        }), function(json) {
            if (json && json.state) {
                ct.ok(json.message || '设置成功');
                itemlist.reload();
                return true;
            }
        });
    },
    editSlider:function(id) {
        ct.formDialog({
            title:'修改幻灯片',
            width:400
        }, buildQuery('?app=mobile&controller=content&action=slider_edit', {
            catid:catid,
            contentid:id
        }), function(json) {
            if (json && json.state) {
                ct.ok(json.message || '修改成功');
                return true;
            }
        });
    },
    removeFromSlider:function(ids) {
        ids = MobileContent.getIds(ids);
        ids && ct.confirm('确定要将选中的 <span style="color:red;">'+ids.length+'</span> 个内容从幻灯片中移出吗？', function() {
            $.getJSON('?app=mobile&controller=content&action=slider_remove', {
                catid:catid,
                contentid:ids.join(',')
            }, function(json) {
                if (json && json.state) {
                    ct.ok('移出成功');
                    itemlist.reload();
                } else {
                    ct.error(json && json.error || '操作失败，请重试');
                }
            });
        });
    },

    qrcode:function(id) {
        if (id == undefined){
            ct.warn('请选择要操作的记录');
            return false;
        }
        var row = itemlist.getRow(id), item;
        if (!row || !row.length || !(item = row.data('item')) || !item.qrcode) return false;
        ct.assoc.open("?app=system&controller=qrcode&action=index&type=mobile&str="+encodeURIComponent(item.qrcode)+'&note='+encodeURIComponent(item.title)+'&contentid='+item.contentid+'&modelid='+item.modelid, 'newtab');
    },

    publish:function(ids, callback) {
        MobileContent._commonModelExecute(ids, 'publish', function(success, error, lastMessage) {
            MobileContent._commonTips('发布', success, error, lastMessage);
            success && itemlist && itemlist.reload();
            $.isFunction(callback) && callback();
        });
    },
    unpublish:function(ids, callback) {
        MobileContent._commonModelExecute(ids, 'unpublish', '确认要撤稿选中的 <span style="color:red;">{length}</span> 条内容吗？', function(success, error, lastMessage) {
            MobileContent._commonTips('撤稿', success, error, lastMessage);
            success && itemlist && itemlist.reload();
            $.isFunction(callback) && callback();
        });
    },
    pass:function(ids, callback) {
        MobileContent._commonModelExecute(ids, 'pass', '确认要通过选中的 <span style="color:red;">{length}</span> 条内容吗？', function(success, error, lastMessage) {
            MobileContent._commonTips('通过', success, error, lastMessage);
            success && itemlist && itemlist.reload();
            $.isFunction(callback) && callback();
        });
    },
    view:function(id, model) {
        model && typeof model === 'string' || (model = MobileContent.getModel(id));
        model && ct.assoc.open('?app=mobile&controller='+model+'&action=view&contentid='+id, 'newtab');
    },
    edit:function(id) {
        var model = MobileContent.getModel(id);
        if (MobileContent.islock(model, id)) {
            ct.warn('当前文档已被锁定，无法修改');
            return;
        }
        model && ct.assoc.open('?app=mobile&controller='+model+'&action=edit&contentid='+id, 'newtab');
    },
    remove:function(ids) {
        MobileContent._commonModelExecute(ids, 'remove', '确认要删除选中的 <span style="color:red;">{length}</span> 条内容吗？', function(success, error, lastMessage) {
            MobileContent._commonTips('删除', success, error, lastMessage);
            success && itemlist && itemlist.reload();
        });
    },
    del:function(ids) {
        MobileContent._commonModelExecute(ids, 'del', '操作不可恢复，确认要永久删除选中的 <span style="color:red;">{length}</span> 条内容吗？', function(success, error, lastMessage) {
            MobileContent._commonTips('删除', success, error, lastMessage);
            success && itemlist && itemlist.reload();
        });
    },
    restore:function(ids, callback) {
        MobileContent._commonModelExecute(ids, 'restore', function(success, error, lastMessage) {
            MobileContent._commonTips('还原', success, error, lastMessage);
            success && itemlist && itemlist.reload();
            $.isFunction(callback) && callback();
        });
    },

    bumpup:function(id) {
        $.getJSON('?app=mobile&controller=content&action=bumpup&contentid='+id, function(json) {
            if (json && json.state) {
                ct.ok('置顶成功');
                itemlist && itemlist.reload();
            } else {
                ct.ok(json && json.error || '操作失败，请重试');
            }
        });
    },

    islock:function(model, id) {
        var json = $.ajax({
                url: '?app=mobile&controller='+model+'&action=islock&contentid='+id,
                async: false, dataType: "json"
            }).responseText;
        json = (new Function("return "+json))();
        if (!json.state && MobileContent.unlockRow) {
            MobileContent.unlockRow(id);
        }
        return json.state;
    },
    lock:function(model, contentid) {
        $.get('?app=mobile&controller='+model+'&action=lock&contentid='+contentid);
    },
    unlock:function(model, contentid) {
        $.get('?app=mobile&controller='+model+'&action=unlock&contentid='+contentid);
    },

    stick:function(id) {
        $.getJSON('?app=mobile&controller=content&action=stick&contentid='+id, function(json) {
            if (json && json.state) {
                ct.ok('固顶成功');
                itemlist && itemlist.reload();
            } else {
                ct.ok(json && json.error || '操作失败，请重试');
            }
        });
    },
    unstick:function(id) {
        $.getJSON('?app=mobile&controller=content&action=unstick&contentid='+id, function(json) {
            if (json && json.state) {
                ct.ok('取消固顶成功');
                itemlist && itemlist.reload();
            } else {
                ct.ok(json && json.error || '操作失败，请重试');
            }
        });
    },

    log:function(id) {
        ct.iframe({
            url:'?app=mobile&controller=content&action=log'+(id ? ('&contentid='+id) : ''),
            width:750,
            height:435
        });
    },

    pushToSpecial:function(id, row, data) {
        function genIds(titles) {
            var result = [];
            $.each(titles || {}, function(i, n) {
                result.push(n.catid);
            });
            return result.join(',');
        }
        var _dialog = ct.iframe({
            url:'?app=mobile&controller=special&action=recommend&contentid='+id,
            width:800,
            height:445
        }, {
            ok:function(titles) {
                var ids = genIds(titles);
                $.getJSON('?app=mobile&controller=special&action=saveRecommend', {
                    catid:ids,
                    contentid:id
                }, function(json) {
                    if (json && json.state) {
                        ct.ok('推送成功');
                        itemlist && itemlist.reload();
                        _dialog.dialog('close');
                    } else {
                        ct.error(json && json.error || '推送失败');
                    }
                });
            }
        });
    },

    _commonModelExecute:function(ids, action, confirm, callback) {
        var success = 0, error = 0, lastMessage = '';
        ids = MobileContent.getIds(ids);
        if (!ids) return;
        if ($.isFunction(confirm) && !callback) {
            callback = confirm;
            confirm = null;
        }
        function execute() {
            var id = ids.shift(), model = MobileContent.getModel(id) || MobileContent.model;
            if (model) {
                $.getJSON('?app=mobile&controller='+model+'&action='+action+'&catid='+catid+'&contentid='+id, function(json) {
                    if (json && json.state) {
                        success += 1;
                        itemlist.uncheck(id);
                    } else {
                        lastMessage = json && json.error || '';
                        error += 1;
                    }
                    next();
                });
            } else {
                error += 1;
                next();
            }
        }
        function next() {
            if (ids.length) {
                execute();
            } else {
                $.isFunction(callback) && callback(success, error, lastMessage);
            }
        }
        if (confirm) {
            ct.confirm(confirm.replace('{length}', ids.length), execute);
        } else {
            execute();
        }
    },
    _commonTips:function(name, success, error, lastMessage) {
        switch (true) {
            case success == 1 && !error:
                ct.ok(name+'成功');
                break;
            case !success && error == 1:
                ct.error(lastMessage ? lastMessage : (name + '失败'));
                break;
            case success > 1 && !error:
                ct.ok(name+' '+success+' 条内容成功');
                break;
            case !success && error > 1:
                ct.error(name+' '+error+' 条内容失败');
                break;
            case success && error:
                ct.warn(name+' '+success+' 条内容成功，'+error+' 条内容失败');
                break;
            default:
                break;
        }
    },
    getIds:function(ids) {
        ids = formatIds(ids);
        if (!ids.length) {
            ct.warn('未选择内容');
            return false;
        }
        return ids;
    },
    getModel:function(id) {
        if (!id) return false;
        var row = itemlist.getRow(id), item;
        if (!row || !row.length || !(item = row.data('item')) || !item.model) return false;
        return item.model;
    },
    getModelid:function(id) {
        if (!id) return false;
        var row = itemlist.getRow(id), item;
        if (!row || !row.length || !(item = row.data('item')) || !item.modelid) return false;
        return item.modelid;
    }
};
if (typeof $.fn.autocomplete === 'function') {
    $('[autocomplete]').autocomplete();
}
});