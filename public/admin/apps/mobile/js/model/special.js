$(function() {
    var title = $('#title');
    var description = $('[name=description]');
    var thumb = $('[name=thumb]');
    var contentid = ct.getParam('contentid');

    MobileContent.init();

    // 栏目高度自适应
    var categorysPanel = $('.special-categorys-body');
    ResizeDelegate.start();
    ResizeDelegate.add(categorysPanel, function(ww, wh) {
        this.css('height', $(document).height() - this.offset().top - 12);
    }, true);

    var contentsPanel = $('.special-contents');
    var contentsEmpty = $('.special-contents-empty');
    var btnAddCategory = $('#btn-add-category');
    var CURRENT_CATNAME = null;

    var tplCategoryForm = new Template($('#tpl-category-form').html());
    var tplCategoryItem = new Template($('#tpl-category-item').html());
    var tplCategoryView = new Template($('#tpl-category-view').html());
    var tplContentItem = new Template($('#tpl-content-item').html());

    var fieldCategorys = $('[name=categorys]');

    function localForm(tpl, options, formReady, ok, cancel) {
        var dialog = $('<div></div>'),
            form = null,
            buttons = {},
            submit = function() {
                if (ok && ok(form, dialog) === false) return;
                dialog.dialog('destroy').remove();
            },
            close = function() {
                if (cancel && cancel(form, dialog) === false) return;
                dialog.dialog('destroy').remove();
            };
        ok && (buttons['确定'] = function() {
            form && form.length && submit();
        });
        cancel && (buttons['取消'] = close);
        typeof options != 'object' && (options = {title:options && options.toString() || ''});
        options = $.extend({
            width :450,
            height:'auto',
            minHeight:80,
            maxHeight:700,
            resizable:false,
            modal : true,
            zIndex:550
        }, options, {
            buttons:buttons,
            close:close
        });
        dialog.html(tpl).dialog(options).css('position', 'relative');
        form = dialog.find('form:first');
        form && form.length && form.submit(function(ev) {
            ev.preventDefault();
            ev.stopPropagation();
            submit();
        });
        formReady && formReady(form, dialog);
        return dialog;
    }

    var categorySortInited = false;
    function categorySortable() {
        if (categorySortInited) {
            categorysPanel.sortable('refresh');
        } else {
            categorysPanel.sortable({
                axis: 'y',
                forceHelperSize: true,
                opacity: 0.8,
                create:function() {
                    categorySortInited = true;
                }
            });
        }
    }
    function renderCategory(json) {
        var row = $(tplCategoryItem.render(json));
        row.data('category', json);
        row.find('.edit').click(function() {
            var olddata = row.data('category');
            localForm(tplCategoryForm.render(row.data('category')), {
                title:'修改专题栏目',
                width:300
            }, null, function(form) {
                var name = $.trim(form.find('[name=name]').val());
                if (!name) {
                    ct.error('请输入栏目名称');
                    return false;
                }
                if (name != olddata.name && getCategory(name)) {
                    ct.error('栏目名称已存在');
                    return false;
                }

                var newdata = form.serializeObject();
                newdata.contents = olddata.contents;
                row.replaceWith(renderCategory(newdata));
                categorySortable();
                MobileContent.formChanged = true;
                if (CURRENT_CATNAME && CURRENT_CATNAME == olddata.name) {
                    CURRENT_CATNAME = null;
                    getCategory(newdata.name).click();
                }
            }, function() {});
            return false;
        });
        row.find('.del').click(function() {
            var json = row.data('category');
            ct.confirm('确认要删除该栏目吗？栏目下添加的内容引用也会被删除', function() {
                if (CURRENT_CATNAME && CURRENT_CATNAME == json.name) {
                    contentsPanel.empty();
                    if (row.prev().length) {
                        row.prev().click();
                    } else if (row.next().length) {
                        row.next().click();
                    } else {
                        CURRENT_CATNAME = null;
                        contentsPanel.hide();
                        contentsEmpty.show();
                    }
                }
                row.remove();
                categorySortable();
                MobileContent.formChanged = true;
            });
            return false;
        });
        row.click(function() {
            var json = row.data('category'), current;
            if (CURRENT_CATNAME && CURRENT_CATNAME == json.name) return false;
            CURRENT_CATNAME && (current = getCategory(CURRENT_CATNAME)) && current.removeClass('checked');
            renderContents(row, json, function() {
                row.addClass('checked');
                CURRENT_CATNAME = json.name;
            });
            return false;
        });
        return row;
    }
    function getCategory(name) {
        var row = categorysPanel.children('[data-name='+encodeURIComponent(name)+']');
        return row.length ? row : null;
    }
    function renderContents(category, json, ready) {
        contentsEmpty.hide();
        contentsPanel.show().empty();
        var view = $(tplCategoryView.render(json)).appendTo(contentsPanel);
        var contentEmpty = view.find('.special-list-empty');
        var contentList = view.find('#special-contents');
        var btnAddContent = view.find('.special-contents-button');
        var sortInited = false;

        btnAddContent.click(function() {
            var _dialog = ct.iframe({
                url:'?app=mobile&controller=content&action=picker&multiple=1&modelids=1,2,3,4,7,8,9,10&title='+encodeURIComponent('添加内容'),
                width:500
            }, {
                ok:function(items) {
                    contentEmpty.hide();
                    contentList.show();
                    for (var len = items.length, i = len - 1; i >= 0; i--) {
                        renderContent(items[i]).prependTo(contentList);
                    }
                    saveContents();
                    _dialog.dialog('destory').remove();
                    MobileContent.formChanged = true;
                }
            });
            return false;
        });
        function saveContents() {
            var data = [];
            contentList.children().each(function(i, row) {
                var item;
                item = $(row).data('content');
                item && data.push(item);
            });
            var stored = category.data('category');
            stored.contents = data;
            category.data('category', stored);
        }
        function renderContent(content) {
            var row = $(tplContentItem.render(content));
            row.data('content', content);
            row.find('img').click(function() {
                row.remove();
                contentList.sortable('refresh');
                if (!contentList.children().length) {
                    empty();
                }
                saveContents();
                MobileContent.formChanged = true;
                return false;
            });
            if (sortInited) {
                contentList.sortable('refresh');
            } else {
                contentList.sortable({
                    axis: 'y',
                    forceHelperSize: true,
                    opacity: 0.8,
                    create:function() {
                        sortInited = true;
                    },
                    stop:function() {
                        saveContents();
                    }
                });
            }
            return row;
        }
        function empty() {
            contentEmpty.show();
            contentList.hide();
        }

        if (json.contents && json.contents.length) {
            contentEmpty.hide();
            contentList.show();
            $.each(json.contents, function(index, content) {
                renderContent(content).appendTo(contentList);
            });
            saveContents();
        } else {
            empty();
        }

        $.isFunction(ready) && ready();
    }

    var storedData = JSON.parse(fieldCategorys.val() || '[]');
    if (storedData.length) {
        contentsEmpty.hide();
        contentsPanel.show();
        $.each(storedData, function(index, category) {
            renderCategory(category).appendTo(categorysPanel);
        });
        categorySortable();
        categorysPanel.children(':first').click();
    } else {
        contentsPanel.hide();
        contentsEmpty.show();
    }

    btnAddCategory.click(function() {
        localForm($(tplCategoryForm.render({})), {
            title:'添加专题栏目',
            width:300
        }, function(form) {}, function(form) {
            var name = $.trim(form.find('[name=name]').val());
            if (!name) {
                ct.error('请输入栏目名称');
                return false;
            }
            if (getCategory(name)) {
                ct.error('栏目名称已存在');
                return false;
            }
            var row = renderCategory(form.serializeObject()).appendTo(categorysPanel);
            categorySortable();
            MobileContent.formChanged = true;
            (!CURRENT_CATNAME || CURRENT_CATNAME != name) && row.click();
            return true;
        }, function() {});
    });

    // form
    var lock = false;
    $('form').ajaxForm(function(json) {
        if (json && json.state) {
            lock = true;
            MobileContent.success(json);
        } else {
            lock = false;
            ct.error(json && json.error || '操作失败，请重试');
        }
    }, null, function(form) {
        if (lock) {
            ct.error('正在提交，请稍后');
            return false;
        }

        var categorys = [];
        categorysPanel.children().each(function() {
            var data = $(this).data('category');
            data && categorys.push(data);
        });
        if (!categorys.length) {
            ct.error('请至少创建一个专题栏目');
            return false;
        }
        fieldCategorys.val(JSON.stringify(categorys));

        var category = form.find('[name=catid]');
        if (!category.val()) {
            ct.error('请选择栏目');
            return false;
        }

        var thumb = $('[name="thumb"]').val();
        if (thumb && (['jpg','jpeg','png','bmp','gif'].indexOf(thumb.split('.').pop()) == -1)) {
            ct.warn('列表缩略图不合法');
            return false;
        }

        var thumbSlider = $('[name="thumb_slider"]').val();
        if (thumbSlider && (['jpg','jpeg','png','bmp','gif'].indexOf(thumbSlider.split('.').pop()) == -1)) {
            ct.warn('幻灯片缩略图不合法');
            return false;
        }

        lock = true;
        return true;
    });
});