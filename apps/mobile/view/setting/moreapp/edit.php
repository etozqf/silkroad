<div class="bk_8"></div>
<form name="setting_moreapp" method="POST" action="?app=mobile&controller=setting&action=moreapp_edit">
    <input type="hidden" name="appid" value="<?=$appid?>" />
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
        <tr>
            <th width="120"><span class="c_red">*</span> 应用名称：</th>
            <td><input type="text" name="name" value="<?=$name?>" size="30" /></td>
        </tr>
        <tr>
            <th><span class="c_red">*</span> 应用图标：</th>
            <td>
                <?php $icon_config = app_config('mobile', 'app.icon'); ?>
                <input style="width:185px;" type="text" name="iconurl" size="50" value="<?=$iconurl?>" data-validator-tips="图标规格为 <?=$icon_config['width']?>x<?=$icon_config['height']?> 的 png 文件" />
                <span id="upload" class="button">上传</span>
            </td>
        </tr>
        <tr>
            <th><span class="c_red">*</span> 应用简介：</th>
            <td>
                <textarea name="description" cols="30" rows="10" style="width:228px;height:40px;resize:vertical;"><?=$description?></textarea>
            </td>
        </tr>
        <tr>
            <th>App Store 地址：</th>
            <td><input type="text" name="appstore_url" value="<?=$appstore_url?>" size="40" /></td>
        </tr>
        <tr>
            <th>Google Play 地址：</th>
            <td><input type="text" name="googleplay_url" value="<?=$googleplay_url?>" size="40" /></td>
        </tr>
    </table>
</form>
<div class="bk_5"></div>