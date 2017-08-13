(function() {

    var tplItem;
    var listMenu;
    var listSquare;
    var listDisable;
    var openLock = false;
    var options = {};
    var tagList;
    var type;
    var version;

    function loadItems(type, version) {
        listMenu.loading();
        listSquare.loading();
        listDisable.loading();

        $.getJSON('?app=mobile&controller=setting&action=app_list&type='+type+'&version='+version, function(json) {
            listMenu.empty();
            $.each(json.menus, function(i, item) {
                renderItem(item, listMenu);
            });
            listMenu.loading('hide');

            listSquare.empty();
            $.each(json.squares, function(i, item) {
                renderItem(item, listSquare);
            });
            listSquare.loading('hide');

            listDisable.empty();
            $.each(json.disables, function(i, item) {
                renderItem(item, listDisable);
            });
            listDisable.loading('hide');

            var btnAdd = renderItem({
                appid: 0,
                sort: '',
                system: 1,
                iconurl_src: 'apps/mobile/images/app-add.png',
                name_short: '添加'
            }, listSquare).addClass('app-item-add');

            var delay, prev;
            $('.app-row-list').sortable({
                connectWith: '.app-row-list',
                helper: 'clone',
                placeholder: 'app-item-empty',
                items: '.app-item:not(.app-item-readonly)',
                cancel: '.app-item-add',
                start: function(ev, ui) {
                    prev = ui.item.parent();
                },
                stop: function(ev, ui) {
                    $('[data-appid=0]').appendTo(listSquare);
                },
                update: function(ev, ui) {
                    delay && clearTimeout(delay);
                    delay = setTimeout(function() {
                        var parent = ui.item.parent();
                        var data = {
                            index: parent.find('.app-item:not(.app-item-add)').index(ui.item[0]) + 1
                        };
                        if (parent[0] == listMenu[0]) {
                            data['menu'] = 1;
                            data['disabled'] = 0;
                            data['state'] = 'menu';
                        } else if (parent[0] == listSquare[0]) {
                            data['menu'] = 0;
                            data['disabled'] = 0;
                            data['state'] = 'square';
                        } else {
                            data['disabled'] = 1;
                            data['state'] = 'disable';
                        }
                        MobileApp.update(ui.item.find('[name=appid]').val(), data, null, function() {
                            var scope = getScope();
                            loadItems(scope.type, scope.version);
                        });
                    }, 100);
                }
            });
        });
    }

    function formReady(form, dialog, dropdialog) {
        var iconurl = form.find('[name=iconurl]').floatImg({
            url: UPLOAD_URL,
            width: options.iconurl.width,
            heihgt: options.iconurl.height
        }),
        iconurl_ipad = form.find('[name=iconurl_ipad]').floatImg({
            url: UPLOAD_URL,
            width: options.iconurl.width,
            heihgt: options.iconurl.height
        });
        dialog.find('#upload').mobileUploader(function(json) {
            iconurl.val(json.file);
        });
        dialog.find('#upload_ipad').mobileUploader(function(json) {
            iconurl_ipad.val(json.file);
        });
        dialog.find('[data-role=enable]').click(function() {
            MobileApp.update(form[0].appid.value, { disabled: 0 }, function() {
                loadItems(type, version);
            });
            dropdialog.close();
        });
        dialog.find('[data-role=disable]').click(function() {
            MobileApp.update(form[0].appid.value, { disabled: 1 }, function() {
                loadItems(type, version);
            });
            dropdialog.close();
        });
        dialog.find('[data-role=delete]').click(function() {
            MobileApp.del(form[0].appid.value);
            dropdialog.close();
        });
    }

    function renderItem(item, appendTo) {
        var $item = $(ct.renderTemplate(tplItem, item)).appendTo(appendTo);
        var region = appendTo.offset(), form;
        $item.data('item', item);
        if (parseInt(item.system, 10)) {
            $item.addClass('app-item-readonly');
        } else {
            $item.find('.app-item-thumb').attr('title', '拖动以排序或移动');
        }
        $item.click(function() {
            var appid = parseInt($item.find('[name=appid]').val(), 10);
            if (openLock) return false;
            openLock = true;
            $item.dropdialog({
                content: appid
                    ? ('?app=mobile&controller=setting&action=app_edit&appid=' + appid + '&type=' + type + '&version=' + version)
                    : ('?app=mobile&controller=setting&action=app_add' + '&type=' + type + '&version=' + version),
                width: 500,
                region: region,
                closeOnBlur: false,
                closeButton: false,
                buttons: {
                    '确定': function() {
                        form && form.length && form.submit();
                    },
                    '取消': function() {
                        this.close();
                    }
                },
                afterRender: function(dialog) {
                    var dropdialog = this;
                    form = dialog.find('form:first');
                    if (! form.length) return;
                    form[0].onsubmit = null;
                    form.unbind('submit').ajaxForm(function(json) {
                        if (json && json.state) {
                            dropdialog.close();
                            loadItems(type, version);
                        } else {
                            var errorText = '';
                            if (json && json.error) {
                                if (json.error.indexOf("'"+form[0].name.value+"'") > -1) {
                                    errorText = '应用名称重复';
                                } else if (json.error.indexOf("'"+form[0].url.value+"'") > -1) {
                                    errorText = '地址重复';
                                }
                            }
                            if (!errorText) {
                                errorText = appid ? '修改失败，请重试' : '添加失败，请重试';
                            }
                            ct.error(errorText);
                        }
                    });
                    formReady(form, dialog, dropdialog);
                },
                afterClose: function() {
                    openLock = false;
                }
            });
            return false;
        });
        return $item;
    }

    function renderTag() {
        tagList.children('li').bind('click', function() {
            if ($('.mod-dropdialog').is(':visible')) {
                return;
            }
            var li = $(this);
            if (li.hasClass('active')) {
                return;
            }
            li.parent().children('li').removeClass('active');
            li.addClass('active');
            type = li.attr('data-type');
            version = li.attr('data-version');
            loadItems(type, version);
        });
    }

    function getScope() {
        return({
            type: type,
            version: version
        });
    }

    window.MobileApp = {
        init: function(opt) {
            options = opt;
            tplItem = $('#tpl-item').html();
            listMenu = $('#list-menu');
            listSquare = $('#list-square');
            listDisable = $('#list-disable');
            tagList = $('#tag-list');
            renderTag();
            tagList.children('li:first').trigger('click');
        },
        update:function(id, data, callback, error) {
            $.post('?app=mobile&controller=setting&action=app_update&appid=' + id + '&type=' + type + '&version=' + version, data, function(json) {
                if (json && json.state) {
                    ct.ok(json.message || '操作成功');
                    $.isFunction(callback) && callback(json);
                } else {
                    ct.error(json && json.error || '操作失败，请重试');
                    $.isFunction(error) && error(json);
                }
            }, 'json');
        },
        del:function(id) {
            ct.confirm('操作不可撤销，确定要删除该应用吗？', function() {
                $.getJSON('?app=mobile&controller=setting&action=app_delete&appid=' + id + '&type=' + type + '&version=' + version, function(json) {
                    if (json && json.state) {
                        ct.ok('删除成功');
                        $('[data-appid=' + id + ']').remove();
                    } else {
                        ct.error(json && json.error || '删除失败');
                    }
                });
            });
            return false;
        }
    };
})();