<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_info">
    <tr>
        <th width="160">时间</th>
        <th width="150">编辑者</th>
        <th width="90">操作</th>
    </tr>
    <?php foreach ($logs as $key => $value): ?>
    <tr>
        <td class="t_c"><?=date('Y-m-d H:i:s', $value['created']);?></td>
        <td class="t_c"><a
            href="javascript:url.member(<?=$value['createdby']?>);"><?=username($value['createdby']);?></a></td>
        <td class="t_c">
            <a href="javascript:;" onclick="page.viewLog(<?=$value['logid']?>); ">预览</a> |
            <a href="javascript:;" onclick="page.restoreLog(<?=$value['logid']?>);">恢复</a>
        </td>
    </tr>
    <?php endforeach;?>
</table>