<form name="setting_app" method="POST" action="?app=mobile&controller=setting&action=app_edit">
    <div class="app-dialog-title title">
        <span class="app-dialog-actions">
            <?php if (!$system): ?>
            <?php if ($disabled): ?>
            <span class="app-dialog-action" data-role="enable">启用</span>
            <?php else: ?>
            <span class="app-dialog-action" data-role="disable">禁用</span>
            <?php endif; ?>
            <?php endif; ?>
            <?php if (!$system && !$builtin): ?>
            <span class="app-dialog-action" data-role="delete">删除</span>
            <?php endif; ?>
        </span>
        修改应用
    </div>
    <input type="hidden" name="appid" value="<?=$appid?>" />
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
        <tr>
            <th width="100"><span class="c_red">*</span> 应用名称：</th>
            <td><input type="text" name="name" value="<?=$name?>" size="30" /></td>
        </tr>
        <tr>
            <th><span class="c_red">*</span> 图标：</th>
            <td>
                <?php $icon_config = app_config('mobile', 'app.icon'); ?>
                <input style="width:280px;" type="text" name="iconurl" size="50" value="<?=$iconurl?>" data-validator-tips="图标规格为 <?=$icon_config['width']?>x<?=$icon_config['height']?> 的 png 文件" />
                <span id="upload" class="button">上传</span>
            </td>
        </tr>
        <tr>
            <th><span class="c_red">*</span> 地址：</th>
            <td style="line-height:24px;">
                <?php if ($builtin): ?>
                <?=$url?>
                <?php else: ?>
                <input type="text" name="url" value="<?=$url?>" size="50" />
                <?php endif; ?>
            </td>
        </tr>
    </table>
</form>
<div class="bk_5"></div>