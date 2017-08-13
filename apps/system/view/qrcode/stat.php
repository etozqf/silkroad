<?php $this->display('header','system');?>

<link rel="stylesheet" href="apps/system/css/qrcode.css" />

<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>

<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/contextMenu/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.contextMenu.js"></script>

<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/pagination/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.pagination.js"></script>

<div class="bk_10"></div>
<div class="tag_1">
    <ul class="tag_list">
        <li><a href="?app=system&controller=qrcode&action=index">二维码生成</a></li>
        <li><a href="javascript:void(0);" class="s_3">生成历史及访问统计</a></li>
    </ul>
</div>

<div class="container">
    <div class="search-options">
        <form method="post" id="query">
            <span>
                <label for="keyword">二维码关键词：</label>
                <input type="text" name="keyword" id="keyword" size="25" />
            </span>
            <span>
                <label for="created_min">生成时间：</label>
                <input type="text" name="created_min" id="created_min" size="20" class="input_calendar" /> ~
                <input type="text" name="created_max" id="created_max" size="20" class="input_calendar" />
            </span>
            <input type="submit" value="搜索" class="button_style_1" />
        </form>
    </div>
    <div class="bk_10"></div>
    <table width="100%" cellpadding="0" cellspacing="0" id="item_list" class="tablesorter table_list">
        <thead>
        <tr>
            <th width="30" class="t_l bdr_3"><input type="checkbox" id="check_all" class="checkbox_style"/></th>
            <th>二维码</th>
            <th width="250">访问量</th>
            <th width="120">生成时间</th>
            <th width="120" class="t_c">管理操作</th>
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
            <input type="button" onclick="QRCode.del();" value="删除" class="button_style_1"/>
        </p>
    </div>
</div>

<script type="text/javascript" src="chart/highcharts.js"></script>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.text-selection.js"></script>
<script type="text/javascript" src="apps/system/js/datapicker.js"></script>
<script type="text/javascript" src="apps/system/js/qrcode.js"></script>
<script type="text/javascript" src="apps/system/js/qrcode_stat.js"></script>
<script type="text/javascript">
$(function() {
    var row_template = [
        '<tr id="row_{qrcodeid}">',
            '<td width="30"><input type="checkbox" class="checkbox_style" name="chk_row" id="chk_row_{qrcodeid}" value="{qrcodeid}" /></td>',
            '<td class="t_l" title="{note}">{note_short}<img tips="&lt;img src=&quot;{qrcode_url}&quot; class=&quot;floatImg&quot; /&gt;" src="images/thumb.gif" class="thumb"></td>',
            '<td class="t_c"><div class="ui-percent"><div class="ui-percent-number">{pv}</div><div class="ui-percent-bar"><div class="ui-percent-bar-inner" style="width:{percent}"></div></div></div></td>',
            '<td class="t_c">{created_name}</td>',
            '<td class="t_c">',
                '<a href="{short_url}" target="blank"><img src="images/view.gif" alt="访问" title="访问" width="16" height="16" class="hand visite"/></a> &nbsp;',
                '<img src="images/txt.gif" alt="查看详细" title="查看详细" width="16" height="16" class="hand view"/> &nbsp;',
                '<img src="images/edit.gif" alt="编辑" title="编辑" width="16" height="16" class="hand edit"/> &nbsp;',
                '<img src="images/delete.gif" alt="删除" title="删除" width="16" height="16" class="hand delete"/>',
            '</td>',
        '</tr>'
    ].join('');
    window.tableApp = new ct.table('#item_list', {
        baseUrl: '?app=system&controller=qrcode&action=page',
        rowIdPrefix: 'row_',
        pageField: 'page',
        pageSize: 15,
        dblclickHandler: 'QRCode.edit',
        rowCallback: function(id, tr) {
            tr.find('img.view').click(function() {
                QRCode.view(id);
                return false;
            });
            tr.find('img.edit').click(function() {
                QRCode.edit(id);
                return false;
            });
            tr.find('img.delete').click(function() {
                QRCode.del(id);
                return false;
            });
            tr.find('img.thumb').attrTips('tips');
        },
        jsonLoaded: function (json) {
            $('#pagetotal').html(json.total);
        },
        template: row_template
    });
    $('.input_calendar').DatePicker({format:'yyyy-MM-dd HH:mm:ss'});
    tableApp.load();
    var query = $('#query').submit(function() {
        tableApp.load(query);
        return false;
    });
});
</script>