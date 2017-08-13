<?php $this->display('header', 'system'); ?>

<link rel="stylesheet" href="apps/mobile/css/base.css" />

<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>

<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/pagination/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.pagination.js"></script>

<!--selectree-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/list/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.list.js"></script>

<script src="<?=IMG_URL?>js/lib/cmstop.tree.js" type="text/javascript"></script>
<link href="<?=IMG_URL?>js/lib/tree/style.css" rel="stylesheet" type="text/css"/>

<script src="<?=IMG_URL?>js/lib/cmstop.suggest.js" type="text/javascript"></script>
<link href="<?=IMG_URL?>js/lib/suggest/style.css" rel="stylesheet" type="text/css"/>

<div class="bk_8"></div>
<div class="mar_l_8 mar_r_8" style="width: 900px;">
    <div class="list-header">
        <input type="button" class="button_style_4" value="添加" onclick="MobileAutofill.add();" />
    </div>
    <div class="bk_5"></div>
    <table width="100%" cellpadding="0" cellspacing="0" id="item_list" class="tablesorter table_list">
        <thead>
        <tr>
            <th width="30" class="t_l bdr_3"><input type="checkbox" id="check_all" class="checkbox_style"/></th>
            <th width="50">序号</th>
            <th>配置名称</th>
            <th width="100">目标频道</th>
            <th width="60">状态</th>
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
            <input type="button" onclick="MobileAutofill.del();" value="删除" class="button_style_1"/>
        </p>
    </div>
</div>
<div class="bk_10"></div>
<div class="suggest w_650 mar_l_8  ">
    <h2>清除错误记录</h2>
    <p>
        如需采集之前采集失败的内容,<br />
        请清除错误记录
    </p>
    <div class="mar_l_8"><button id="clear_error_log" class="button_style_2">清除</button></div>
    <div class="bk_10"></div>
</div>

<script type="text/template" id="tpl-row">
    <tr id="row_{id}" data-disabled="{disabled}">
        <td width="30"><input type="checkbox" class="checkbox_style" name="chk_row" id="chk_row_{id}" value="{id}" /></td>
        <td width="50" class="t_c">{id}</td>
        <td>{name}</td>
        <td width="100" class="t_c">{catname}</td>
        <td width="60" class="t_c">{disabled_name}</td>
        <td width="120" class="t_c">
            <img src="images/edit.gif" alt="编辑" title="编辑" width="16" height="16" class="hand edit"/> &nbsp;
            <img src="images/disable.png" alt="禁用" title="禁用" width="16" height="16" class="hand disable"/>
            <img src="images/enable.png" alt="启用" title="启用" width="16" height="16" class="hand enable"/> &nbsp;
            <img src="images/delete.gif" alt="删除" title="删除" width="16" height="16" class="hand delete"/> &nbsp;
            </td>
        </tr>
</script>

<script type="text/javascript" src="apps/mobile/js/autofill.js"></script>
<script type="text/javascript">
    $(function() {
        MobileAutofill.init({
            rowTemplate: $('#tpl-row').html()
        });
        $('#clear_error_log').bind('click', function() {
            $.getJSON('?app=mobile&controller=autofill&action=clear_error_log&time=' + String((new Date).getTime()).substr(0, 10), function(req) {
                if (req.state) {
                    ct.ok('清除成功');
                } else {
                    ct.ok('清除失败');
                }
            });
        })
    });
</script>
