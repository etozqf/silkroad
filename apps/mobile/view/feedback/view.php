<div class="ui-table-view">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_form">
        <tr>
            <th width="70" style="vertical-align: top; padding-top: 5px;">内容：</th>
            <td>
                <div class="ui-table-view-content"><?=htmlspecialchars($content)?></div>
            </td>
        </tr>
        <tr>
            <th>邮箱：</th>
            <td><?php if ($email): ?><a href="mailto:<?=$email?>?subject=回复：意见反馈"><?=htmlspecialchars($email)?></a><?php else: ?>无<?php endif; ?></td>
        </tr>
        <tr>
            <th>应用版本：</th>
            <td><?=htmlspecialchars($app_version)?></td>
        </tr>
        <tr>
            <th>系统：</th>
            <td><?=htmlspecialchars($system_version)?></td>
        </tr>
    </table>
</div>