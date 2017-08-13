<style type="text/css">
    .table_form th,
    .table_form td {
        line-height: 24px;
    }
</style>
<div class="bk_8"></div>
<form name="setting_category" method="POST" action="?app=mobile&controller=setting&action=category_edit">
    <input type="hidden" name="catid" value="<?=$catid?>" />
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
        <tr>
            <th width="120"><span class="c_red">*</span> 频道名称：</th>
            <td><input type="text" name="catname" value="<?=$catname?>" size="30" /></td>
        </tr>
        <tr>
            <th>系统栏目绑定：</th>
            <td>
                <input id="catid_bind" name="catid_bind" width="150"
                    url="?app=system&controller=category&action=cate&dsnid=&catid=%s"
                    initUrl="?app=system&controller=category&action=name&catid=%s"
                    paramVal="catid"
                    paramTxt="name"
                    value="<?=$catid_bind?>"
                    multiple="1"
                    alt="选择栏目" />
            </td>
        </tr>
        <tr>
            <th><span class="c_red">*</span> 图标：</th>
            <td>
                <?php $icon_config = app_config('mobile', 'category.icon'); ?>
                <input style="width:280px;" type="text" name="iconurl" size="50" value="<?=$iconurl?>" data-validator-tips="图标规格为 <?=$icon_config['width']?>x<?=$icon_config['height']?> 的 png 文件" />
                <span id="upload" class="button">上传</span>
            </td>
        </tr>
        <tr>
            <th>显示幻灯片：</th>
            <td>
                <label>
                    <input type="radio" name="display_slider" value="1"<?=form_element::checked($display_slider)?> /> 启用
                </label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                    <input type="radio" name="display_slider" value="0"<?=form_element::checked(!$display_slider)?> /> 禁用
                </label>
            </td>
        </tr>
        <tr>
            <th>幻灯片数量：</th>
            <td><input type="text" name="slider_size" value="<?=$slider_size?>" size="8" /></td>
        </tr>
<!--和前台没有关联        
        <tr>
            <th>客户端默认显示：</th>
            <td>
                <label>
                    <input type="radio" name="default_display" value="1"<?=form_element::checked($default_display)?> /> 是
                </label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                    <input type="radio" name="default_display" value="0"<?=form_element::checked(!$default_display)?> /> 否
                </label>
            </td>
        </tr>
-->        
        <tr>
            <th>状态：</th>
            <td>
                <label>
                    <input type="radio" name="disabled" value="0"<?=form_element::checked(!$disabled)?> /> 启用
                </label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                    <input type="radio" name="disabled" value="1"<?=form_element::checked($disabled)?> /> 禁用
                </label>
            </td>
        </tr>
    </table>
</form>
<div class="bk_5"></div>