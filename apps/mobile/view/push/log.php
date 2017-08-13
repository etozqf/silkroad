<?php $this->display('header', 'system'); ?>

<link rel="stylesheet" href="apps/mobile/css/push.css" />

<div class="bk_8"></div>
<div class="tag_1">
    <ul class="tag_list">
        <li><a href="?app=mobile&controller=push&action=index">消息推送</a></li>
        <li class="active"><a href="javascript:void(0);">推送记录</a></li>
    </ul>
</div>

<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>

<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/pagination/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.pagination.js"></script>

<div class="bk_8"></div>
<div class="mar_l_8 mar_r_8" style="width: 900px;">
    <table width="100%" cellpadding="0" cellspacing="0" id="item_list" class="table_list">
        <thead>
        <tr>
            <th width="120" class="bdr_3">推送时间</th>
            <th>内容</th>
            <th width="250">设备</th>
        </tr>
        </thead>
        <tbody id="list_body"></tbody>
    </table>
    <div class="table_foot">
        <div id="pagination" class="pagination f_r"></div>
        <div class="f_r">
            共有 <span id="pagetotal">0</span> 条记录&nbsp;&nbsp;
        </div>
        <p class="f_l">
            <input type="button" onclick="tableApp.reload()" value="刷新" class="button_style_1"/>
        </p>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        var row_template = [
            '<tr id="row_{logid}">',
                '<td class="t_c">{pushed_name}</td>',
                '<td>{content}</td>',
                '<td class="t_c">{devices}</td>',
            '</tr>'
        ].join('');
        window.tableApp = new ct.table('#item_list', {
            baseUrl: '?app=mobile&controller=push&action=log_page',
            rowIdPrefix: 'row_',
            pageField: 'page',
            pageSize: 10,
            jsonLoaded: function (json) {
                $('#pagetotal').html(json.total);
            },
            template: row_template
        });
        tableApp.load();
    });
</script>