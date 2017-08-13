<?php $this->display('header', 'system'); ?>

<!--pagination-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/pagination/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.pagination.js"></script>

<!-- autocomplete -->
<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/autocomplete/style.css" />
<script src="<?=IMG_URL?>js/lib/cmstop.autocomplete.js" type="text/javascript"></script>

<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>

<style type="text/css">
    #search-log label,
    #search-log span {
        float: left;
        margin-right: 5px;;
    }
</style>

<div class="operation_area">
    <form action="" id="search-log">
        <label for="createdby" style="height:24px;line-height:24px;margin-right:0;">用户名：</label>
        <span>
            <input type="text" id="createdby" name="createdby_name" size="10" />
        </span>
        <span>
            <input type="text" name="created_min" class="input_calendar" onclick="DatePicker(this,{'format':'yyyy-MM-dd HH:mm:ss'});" /> ~
            <input type="text" name="created_max" class="input_calendar" onclick="DatePicker(this,{'format':'yyyy-MM-dd HH:mm:ss'});" />
        </span>
        <span>
            <input type="submit" class="button_style_1" value="搜索" />
        </span>
    </form>
</div>

<div style="padding: 10px;">
    <table width="100%" cellpadding="0" cellspacing="0" id="item_list" class="table_list">
        <thead>
        <tr>
            <th width="90" class="bdr_3">操作人</th>
            <th width="90">动作</th>
            <th>标题</th>
            <th width="150">IP</th>
            <th width="120">时间</th>
        </tr>
        </thead>
        <tbody id="list_body">
        </tbody>
    </table>
</div>

<div class="table_foot">
    <div id="pagination" class="pagination f_r"></div>
</div>

<script type="text/javascript">
(function() {
    var row_template = '<tr>'+
        '<td class="t_c"><a href="javascript:url.member({createdby});">{createdby_name}</td>'+
        '<td class="t_c">{action_name}</td>'+
        '<td><a href="{url}" target="_blank" title="{title}">{title_short}</td>'+
        '<td class="t_c">{ip_name}</td>'+
        '<td class="t_c">{created_name}</td>'+
        '</tr>';
    var tableApp = new ct.table('#item_list', {
        rowIdPrefix: 'row_',
        rowCallback: function(id, tr) {
            tr.find('a[href=]').removeAttr('href');
        },
        template: row_template,
        baseUrl: '?app=mobile&controller=content&action=log_page&contentid=<?=$contentid?>',
        pageSize: 10
    });
    tableApp.load();

    var form = $('#search-log');
    $('#createdby').autocomplete({
        url:'?app=member&controller=index&action=username&q=%s'
    }).keyup(function(ev) {
        if (ev.keyCode == 13) {
            form.submit();
        }
    });
    form.submit(function() {
        tableApp.load($(this));
        return false;
    });
})();
</script>