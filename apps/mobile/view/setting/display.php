<?php $this->display('header', 'system'); ?>

<link rel="stylesheet" href="apps/mobile/css/base.css" />

<script type="text/javascript" src="apps/mobile/js/signature.js"></script>

<form action="?app=mobile&controller=setting&action=display" method="POST" class="validator mar_t_8">
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <caption style="margin-top:0;margin-bottom:0;">显示设置</caption>
        <tr>
            <th width="160" style="vertical-align: top; padding-top: 5px;">缩略图对齐方式：</th>
            <td>
                <label><input type="radio" name="config[thumb_align]" value="left"<?=form_element::checked($setting['thumb_align'] == 'left')?> /> 左对齐</label>
                <label><input type="radio" name="config[thumb_align]" value="right"<?=form_element::checked(!$setting['thumb_align'] || $setting['thumb_align'] == 'right')?> /> 右对齐</label>
            </td>
        </tr>
    </table>
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <tr>
            <th width="160">&nbsp;</th>
            <td>
                <div class="bk_8"></div>
                <input type="submit" id="submit" value="保存" class="button_style_2"/>
            </td>
        </tr>
    </table>
</form>

<script type="text/javascript">
    $(function() {
        $('form').ajaxForm(function(json) {
            if (json && json.state) {
                ct.tips('保存成功');
            } else {
                ct.error(json && json.error || '保存失败');
            }
        });
    });
</script>