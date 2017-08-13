<div id="created_x" class="th_pop" style="display:none;width:150px">
    <div>
        <a href="javascript: tableApp.load('created_min=<?=date('Y-m-d', TIME)?>');">今日</a> |
        <a href="javascript: tableApp.load('created_min=<?=date('Y-m-d', strtotime('yesterday'))?>&created_max=<?=date('Y-m-d', strtotime('yesterday'))?>');">昨日</a> |
        <a href="javascript: tableApp.load('created_min=<?=date('Y-m-d', date::this_week(true))?>');">本周</a> |
        <a href="javascript: tableApp.load('created_min=<?=date('Y-m-01', strtotime('this month'))?>');">本月</a>
    </div>
    <ul>
        <?php for ($i=2; $i<=7; $i++) { $createdate = date('Y-m-d', strtotime("-$i day")); ?>
        <li><a href="javascript: tableApp.load('created_min=<?=$createdate?>&created_max=<?=$createdate?>');"><?=$createdate?></a></li>
        <?php } ?>
    </ul>
</div>
<div style="margin-left:100px;">
    <table width="98%" cellpadding="0" cellspacing="0" id="item_list" class="table_list">
        <thead>
        <tr>
            <th width="30" class="t_l bdr_3"><input type="checkbox" id="check_all" /></th>
            <?php foreach (array_values($fields) as $index => $field): if ($index > 7) continue; ?>
            <th id="<?=$field['fieldid']?>"><?=$field['label']?></th>
            <?php endforeach; ?>
            <th  class="ajaxer" width="180"><em class="more_pop" name="created_x"></em><div name="created">报名时间</div></th>
            <th width="80">管理操作</th>
        </tr>
        </thead>
        <tbody id="list_body">
        </tbody>
    </table>
    <div class="table_foot">
        <div id="pagination" class="pagination f_r"></div>
        <p class="f_l">
            <input type="button" name="pass" onclick="sign.pass();" value="通过" class="button_style_1"/>
            <input type="button" name="remove" onClick="sign.del();" value="删除" class="button_style_1"/>
        </p>
    </div>
    <div class="clear"></div>
</div>

<!--右键菜单-->
<ul id="right_menu" class="contextMenu">
    <li class="view"><a href="#sign.view">查看</a></li>
    <li class="remove"><a href="#sign.pass">通过</a></li>
    <li class="edit"><a href="#sign.edit">编辑</a></li>
    <li class="remove"><a href="#sign.del">删除</a></li>
</ul>

<script type="text/javascript">
    var row_template = '<tr id="row_{signid}">'+
        '<td><input type="checkbox" class="checkbox_style" name="chk_row" id="chk_row_{signid}" value="{signid}" /></td>'+
        <?php foreach (array_values($fields) as $index => $field): if ($index > 7) continue; ?>
        <?php if ($index == 0): ?>
        '<td><a href="javascript:sign.view({signid});" tips="ID：{signid}<br />报名时间：{created}）<br />审核：{checkedby}（{checked}）" class="title_list"><?=activityField::renderTemplate($field['type'], $field)?></a></td>'+
        <?php else: ?>
        '<td class="t_c"><?=activityField::renderTemplate($field['type'], $field)?></td>'+
        <?php endif; ?>
        <?php endforeach; ?>
        '<td class="t_c">{created}</td>'+
        '<td class="t_c"><img src="images/view.gif" alt="访问" width="16" height="16" class="hand view" /> &nbsp;<img src="images/edit.gif" alt="编辑" width="16" height="16" class="hand edit"/> &nbsp;<img src="images/sh.gif" title="通过" width="16" height="16" class="hand pass" /></td>'+
    '</tr>';
</script>