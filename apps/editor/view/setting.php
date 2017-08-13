<?php $this->display('header', 'system');?>
<div class="bk_8"></div>
<form id="editor_setting" method="POST" action="?app=editor&controller=setting&action=index">
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <caption>通用设置</caption>
        <tr>
            <th width="120">图片水印：</th>
            <td><input type="radio" name="setting[watermark]" value="1" class="radio" <?php if ($setting['watermark'] == 1) echo 'checked';?>/> 启用 &nbsp; <input type="radio" name="setting[watermark]" value="0" class="radio" <?php if ($setting['watermark'] == 0) echo 'checked';?>/> 禁用</td>
        </tr>
        <tr>
            <th>缩略图：</th>
            <td>宽 <input type="text" name="setting[thumb_width]" value="<?=$setting['thumb_width']?>" size="4"/>px ~ 高 <input type="text" name="setting[thumb_height]" value="<?=$setting['thumb_height']?>" size="4"/>px
            </td>
        </tr>
    </table>
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <caption>前台设置</caption>
        <tr>
            <th width="120">上传限制：</th>
            <td>上传图片大小不超过 <input type="text" name="setting[upload_max_filesize]" value="<?=$setting['upload_max_filesize']?>" size="5"/> MB</td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td  class="t_c"><input type="submit" class="button_style_2" value="保存" /></td>
        </tr>
    </table>
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <caption>模板管理</caption>
        <tr>
            <th width="120">管理界面：</th>
            <td><input type="button" id="templateManager" class="button_style_2" value="进入" /></td>
        </tr>
    </table>

</form>
<script type="text/javascript">
$(function(){
	$('#editor_setting').ajaxForm(function (response) {
		ct.tips('保存成功');
	});
	$('.tips').attrTips('tips', 'tips_green', 200, 'top');
    $('#templateManager').bind('click', function(){
        ct.assoc.open('?app=editor&controller=template&action=setting', 'newtab');
    });
});
</script>
<?php $this->display('footer', 'system');?>