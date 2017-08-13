(function() {
    var PAGE_SIZE = 10,
        BASE_URL = '?app=mobile&controller=live&action=';

    /**
     * 页面初始化
     * @param  {String} action
     */
    var init = function(action) {
        switch (action) {
            case 'add':
            case 'edit':
                postInit(action);
                break;
            case 'index':
                indexInit(action);
                break;
        }
        $('.tips').attrTips('tips', 'tips_green');
    };

    /**
     * 添加/修改初始化
     * @param  {String} action
     */
    var postInit = function(action) {
        $('#model-live-channel').ajaxForm(function(json) {
            if (!json.state) {
                return ct.error(json.error || '提交失败');
            }
            var successDialog = ct.tips($('#success-template').html(), 'ok', 'center', 0);
            successDialog.find('sub, a').remove();
            $('#success-and-edit').bind('click', function() {
                location.href = BASE_URL + 'channel_edit&id=' + json.id;
            });
            $('#success-and-close').bind('click', function() {
                ct.assoc.close();
            });
        });
    };

    /**
     * 列表初始化
     * @param  {String} action
     */
    var indexInit = function(action) {
        // 表格参数
        var tableApp = new ct.table('#list-table', {
            baseUrl: BASE_URL + 'channel_ls',
            template: $('#table-template').html(),
            rowIdPrefix: 'row-',
            pageField: 'page',
            pageSize: PAGE_SIZE,
            rowCallback: function(id, tr) {
                bindEdit(tr.find('.channel-edit'));
                bindDelete(tr.find('.channel-delete'));
                tr.find('.channel-created').html(_time2String(tr.find('.channel-created').html()));
            },
            dblclickHandler: function(id, tr, json) {
                tr.find('.channel-edit').trigger('click');
            },
            jsonLoaded: function() {
                var startIndex, endIndex;
                $('#list-table > tbody').sortable({
                    "handle": "td:first-child",
                    "cursor": "move",
                    "axis": "y",
                    "start": function(event, ul) {
                        var ths = $('#list-table > thead > tr > th');
                        startIndex = ul.item.parent().children().index(ul.item);
                        ul.item.children('td').each(function(index) {
                            this.width = ths.eq(index).width() + 1;
                        });
                    },
                    "stop": function(event, ul) {
                        var lastSort, newSort = {}, tds;
                        var setNewSort = function(i) {
                            td = tds.eq(i);
                            newSort[td.attr('data-id')] = lastSort;
                            lastSort = td.attr('data-sort');
                        }
                        tds = ul.item.parent().children();
                        endIndex = tds.index(ul.item);
                        if (endIndex === startIndex) {
                            // do nothing
                            return;
                        }
                        lastSort = ul.item.attr('data-sort');
                        if (endIndex > startIndex) {
                            for (var i = startIndex; i < endIndex; i++) {
                                setNewSort(i);
                            }
                        } else {
                            for (var i = startIndex; i > endIndex; i--) {
                                setNewSort(i);
                            }
                        }
                        newSort[ul.item.attr('data-id')] = lastSort;
                        $.post(BASE_URL + 'channel_sort', newSort, function(res) {
                            if (!res.state) {
                                return ct.error(res.error || '响应异常');
                            }
                            tableApp.reload();
                        }, 'json');
                    }
                });
            }
        });
        // 绑定编辑事件
        var bindEdit = function(elm) {
            elm.bind('click', function() {
                var id = $(this).parents('tr').attr('data-id');
                ct.assoc.open(BASE_URL + 'channel_edit&id=' + id, 'newtab');
            });
        };
        // 绑定删除事件
        var bindDelete = function(elm) {
            elm.bind('click', function() {
                var id = $(this).parents('tr').attr('data-id');
                ct.confirm('确认删除', function() {
                    $.post(BASE_URL + 'channel_rm', {
                        'id': id
                    }, function(res) {
                        if (!res.state) {
                            ct.error(res.error || '删除失败');
                        }
                        tableApp.reload();
                    }, 'json');
                });
            });
        };
        // 绑定添加事件
        $('#channel-add').bind('click', function() {
            ct.assoc.open(BASE_URL + 'channel_add', 'newtab');
        });
        // 绑定刷新事件
        $('#channel-list-refresh').bind('click', function() {
            tableApp.reload();
        });
        // 绑定搜索事件
        $('#submit-form').bind('submit', function() {
            tableApp.load($(this).serializeObject());
            return false;
        }).find('a').bind('click', function() {
            $(this).parents('form').trigger('submit');
        });
        tableApp.load();
    };

    /**
     * 格式化时间
     * @param  {Number/String} 时间戳(秒)
     * @return {String} 时间字符串
     */
    var _time2String = function(time) {
        var date, string = '';
        time = Number(time) * 1000;
        if (!time) {
            return '';
        }
        date = new Date(time);
        string = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
        return string;
    };

    init(action);
})();