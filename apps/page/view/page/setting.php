<?php $this->display('header', 'system');?>

<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/list/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.list.js"></script>

<div class="bk_8"></div>
<form id="page_setting" name="page_setting" method="POST" action="?app=page&controller=setting&action=index">
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <caption>页面设置</caption>
        <tr>
            <th width="100">区块历史记录：</th>
            <td>
                保留 <input size="3" type="text" name="section_log_retain" value="<?=$setting['section_log_retain']?>" />
                <?=element::date_unit('section_log_retain_unit', $setting['section_log_retain_unit'] ? $setting['section_log_retain_unit'] : 86400 * 30)?>
            </td>
        </tr>
        <tr>
            <th>区块操作记录：</th>
            <td>
                保留 <input size="3" type="text" name="section_history_retain" value="<?=$setting['section_history_retain']?>" />
                <?=element::date_unit('section_history_retain_unit', $setting['section_history_retain_unit'] ? $setting['section_history_retain_unit'] : 86400 * 30)?>
            </td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td  class="t_c"><input type="submit" class="button_style_2" value="保存" /></td>
        </tr>
    </table>
</form>
<script type="text/javascript">
    $(function(){
        $('select').selectlist();
        $('#page_setting').ajaxForm(function (response) {
            ct.tips('保存成功');
        });
    });
</script>
<?php $this->display('footer', 'system');?>