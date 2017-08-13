<?php console($content['title']);?>
<div class="bk_8"></div>
<form name="content_slider" method="POST" action="?app=mobile&controller=content&action=slider_add">
    <input type="hidden" name="catid" value="<?=$catid?>" />
    <input type="hidden" name="contentid" value="<?=$contentid?>" />
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
        <tr>
            <th width="60"><span class="c_red">*</span> 标题：</th>
            <td><input type="text" name="title" value="<?=htmlspecialchars($content['title']);?>" size="40" /></td>
        </tr>
        <tr>
            <th><span class="c_red">*</span> 图片：</th>
            <td>
                <?php $slider_config = app_config('mobile', 'config.slider'); ?>
                <?php $attr = 'data-validator-tips="'.mobile_element::width_height('图标规格：', $slider_config['width'], $slider_config['height']).'"'; ?>
                <?=element::image('thumb', $content['thumb_slider'], 20, 1, $attr)?>
            </td>
        </tr>
    </table>
</form>
<div class="bk_5"></div>