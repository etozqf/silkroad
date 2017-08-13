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
        $('#model-live-program').ajaxForm(function(json) {
            if (!json.state) {
                return ct.error(json.error || '提交失败');
            }
            var successDialog = ct.tips($('#success-template').html(), 'ok', 'center', 0);
            successDialog.find('sub, a').remove();
            $('#success-and-edit').bind('click', function() {
                location.href = BASE_URL + 'program_edit&id=' + json.id;
            });
            $('#success-and-close').bind('click', function() {
                ct.assoc.close();
            });
        });
        // 绑定 autocomplete
        $('[autocomplete="1"]').autocomplete({
            autoFill:false,
            showEvent:'focus'
        });
        // 绑定 datepicker
        $('input.input_calendar').DatePicker({
            'format':'yyyy-MM-dd HH:mm:ss'
        });
        // 绑定 selectlist
        $('.selectlist').selectlist();
        // 标签建议
        $('#title').bind('change', function(){
            var title = $('#title').val();
            if (title) {
                $.post('?app=system&controller=tag&action=get_tags', {
                    title: title
                }, function(response){
                    if (response.state)
                    {
                        typeof(tagInput) == 'object' && tagInput.addItem(response.data);
                    }
                }, 'json');
            }
        });
    };

    /**
     * 列表初始化
     * @param  {String} action
     */
    var indexInit = function(action) {
        // 表格参数
        var tableApp = new ct.table('#list-table', {
            baseUrl: BASE_URL + 'program_ls',
            template: $('#table-template').html(),
            rowIdPrefix: 'row-',
            pageField: 'page',
            pageSize: PAGE_SIZE,
            rowCallback: function(id, tr) {
                bindEdit(tr.find('.program-edit'));
                bindDelete(tr.find('.program-delete'));
                tr.find('.program-created').html(_time2String(tr.find('.program-created').html()));
            },
            dblclickHandler: function(id, tr, json) {
                tr.find('.program-edit').trigger('click');
            }
        });
        // 绑定编辑事件
        var bindEdit = function(elm) {
            elm.bind('click', function() {
                var id = $(this).parents('tr').attr('data-id');
                ct.assoc.open(BASE_URL + 'program_edit&id=' + id, 'newtab');
            });
        };
        // 绑定删除事件
        var bindDelete = function(elm) {
            elm.bind('click', function() {
                var id = $(this).parents('tr').attr('data-id');
                ct.confirm('确认删除', function() {
                    $.post(BASE_URL + 'program_rm', {
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
        $('#program-add').bind('click', function() {
            ct.assoc.open(BASE_URL + 'program_add', 'newtab');
        });
        // 绑定刷新事件
        $('#program-list-refresh').bind('click', function() {
            tableApp.reload();
        });
        // 批量删除
        $('#program-list-batch-delete').bind('click', function() {
            var ids = [];
            $(':checked').each(function() {
                var trId, id;
                trId = $(this).parents('tr').attr('id');
                id = trId.match(/row-(\d+)/)
                if (!id) {
                    return true;
                }
                ids.push(id[1]);
            });
            if (ids.length === 0) {
                return;
            }
            ct.confirm('确定要删除所选内容?', function() {
                $.post(BASE_URL + 'program_rm', {
                    'id': ids.join(',')
                }, function(res) {
                    if (!res.state) {
                        ct.error(res.error || '删除失败');
                    }
                    tableApp.reload();
                }, 'json');
            });
        });
        // 绑定搜索事件
        $('#submit-form').bind('submit', function() {
            var where, channel;
            where = $(this).serializeObject();
            channel = $('#proids').find('.checked').attr('data-channel') >>> 0;
            if (channel) {
                where.channel_id = channel;
            }
            tableApp.load(where);
            return false;
        }).find('a').bind('click', function() {
            $(this).parents('form').trigger('submit');
        });
        // 绑定频道选择
        $('#proids').find('a').bind('click', function() {
            var $this, channel;
            $this = $(this);
            if ($this.hasClass('checked')) {
                return;
            }
            $this.parent().find('.checked').removeClass('checked');
            $this.addClass('checked');
            channel = $this.attr('data-channel') >>> 0;
            tableApp.load({
                channel_id: channel
            });
            $('#submit-form').trigger('reset');
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