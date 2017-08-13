<form name="setting_app" method="POST" action="?app=mobile&controller=setting&action=app_add">
    <input type="hidden" name="type" value="<?php echo value($_GET, 'type');?>" />
    <input type="hidden" name="version" value="<?php echo value($_GET, 'version');?>" />
    <div class="app-dialog-title title">添加应用</div>
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
        <tr>
            <th width="100"><span class="c_red">*</span> 应用名称：</th>
            <td><input type="text" name="name" value="" size="30" /></td>
        </tr>
        <tr>
            <th><span class="c_red">*</span> 图标：</th>
            <td>
                <?php $icon_config = app_config('mobile', 'app.icon'); ?>
                <input style="width:280px;" type="text" name="iconurl" size="50" value="" data-validator-tips="图标规格为 <?=$icon_config['width']?>x<?=$icon_config['height']?> 的 png 文件" />
                <span id="upload" class="button">上传</span>
            </td>
        </tr>
        <tr>
            <th><span class="c_red">*</span> 地址：</th>
            <td>
                <input type="text" name="url" value="" size="50" />
            </td>
        </tr>
    </table>
</form>
<div class="bk_5"></div>