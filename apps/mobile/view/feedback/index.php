<?php $this->display('header', 'system'); ?>

<link rel="stylesheet" href="apps/mobile/css/base.css" />

<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>

<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/tablesorter/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.tablesorter.js"></script>

<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/contextMenu/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.contextMenu.js"></script>

<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/pagination/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.pagination.js"></script>

<script type="text/javascript" src="apps/mobile/js/signature.js"></script>

<div class="bk_8"></div>
<div class="mar_l_8 mar_r_8" style="width: 900px;">
    <table width="100%" cellpadding="0" cellspacing="0" id="item_list" class="tablesorter table_list">
        <thead>
        <tr>
            <th width="30" class="t_l bdr_3"><input type="checkbox" id="check_all" class="checkbox_style"/></th>
            <th>意见反馈内容</th>
            <th width="150" class="t_c ajaxer"><div name="email">邮箱</div></th>
            <th width="100" class="t_c ajaxer"><div name="app_version">应用版本</div></th>
            <th width="100" class="t_c ajaxer"><div name="system_version">系统</div></th>
            <th width="60" class="t_c">管理</th>
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
            <?php if (priv::aca('mobile', 'feedback', 'delete')): ?><input type="button" onclick="MobileFeedback.del();" value="删除" class="button_style_1"/><?php endif; ?>
        </p>
    </div>
</div>

<!--右键菜单-->
<ul id="right_menu" class="contextMenu">
    <li class="edit"><a href="#MobileFeedback.view">查看</a></li>
    <?php if (priv::aca('mobile', 'feedback', 'delete')): ?><li class="delete separator"><a href="#MobileFeedback.del">删除</a></li><?php endif; ?>
</ul>

<script type="text/javascript" src="apps/mobile/js/feedback.js"></script>
<script type="text/javascript">
$(function() {
    var row_template = [
        '<tr id="row_{id}">',
            '<td><input type="checkbox" class="checkbox_style" name="chk_row" id="chk_row_{id}" value="{id}" /></td>',
            '<td>{content_short}</td>',
            '<td>{email}</td>',
            '<td>{app_version}</td>',
            '<td>{system_version}</td>',
            '<td class="t_c">',
                '<img src="images/txt.gif" alt="查看" title="查看" width="16" height="16" class="hand view"/> &nbsp;',
                <?php if (priv::aca('mobile', 'feedback', 'delete')): ?>'<img src="images/delete.gif" alt="删除" title="删除" width="16" height="16" class="hand delete"/> &nbsp;',<?php endif; ?>
            '</td>',
        '</tr>'
    ].join('');
    window.tableApp = new ct.table('#item_list', {
        baseUrl: '?app=mobile&controller=feedback&action=page',
        rowIdPrefix: 'row_',
        pageField: 'page',
        pageSize: 15,
        dblclickHandler: 'MobileFeedback.view',
        rowCallback: function(id, tr) {
            tr.find('img.view').click(function() {
                MobileFeedback.view(id);
                return false;
            });
            tr.find('img.delete').click(function() {
                MobileFeedback.del(id);
                return false;
            });
        },
        jsonLoaded: function (json) {
            $('#pagetotal').html(json.total);
        },
        template: row_template
    });
    tableApp.load();
});
</script>