<?php $this->display('header', 'system');?>
<script type="text/javascript" src="<?php echo IMG_URL;?>js/lib/cmstop.switch.js"></script>
<style type="text/css">
.switchpanel {position: relative; top: -13px;}
</style>
<div class="bk_10"></div>
<div class="bk_10"></div>
<form id="setting_edit_content" action="?app=system&controller=setting&action=edit" method="POST">
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
	<caption>发稿设置</caption>
    <tr>
        <th width="150">权重提示：</th>
        <td><textarea name="setting[weight]" rows="6" cols="50" class="bdr"><?=$weight?></textarea></td>
    </tr>
    <tr>
        <th>默认权重值：</th>
        <td><input type="text" name="setting[defaultwt]" value="<?php echo intval($defaultwt); ?>" size="10"/>&nbsp;&nbsp;（0-100）</td>
    </tr>
	<tr>
		<th>相似内容检测：</th>
		<td>
			<div class="switchpanel"><input class="switch" type="checkbox" value="2" name="setting[repeatcheck]"<?php if($repeatcheck):?> checked="checked"<?php endif;?> /></div>
		</td>
	</tr>
	<tr height="28">
		<th>标签摘要提取开关：</th>
		<td>
			<div class="switchpanel"><input class="switch" type="checkbox" value="1" name="setting[get_tags]"<?php if($get_tags):?> checked="checked"<?php endif;?> /></div>
		</td>
	</tr>
	<tr height="28">
		<th>网编工具箱禁用水印：</th>
		<td>
			<div class="switchpanel"><input class="switch" type="checkbox" value="1" name="setting[toolbox_disable_watermark]"<?php if($toolbox_disable_watermark):?> checked="checked"<?php endif;?> /></div>
		</td>
	</tr>
	<tr>
		<th></th>
		<td><input id="submit" class="button_style_2" type="submit" value="保存" /></td>
	</tr>
</table>
</form>
<script type="text/javascript">
$(function(){
	$('#setting_edit_content').ajaxForm(function(json){
		if(json.state) ct.tips(json.message);
		else ct.error(json.error);
	}, null, function(form) {
		if (!form[0]["setting[get_tags]"].checked) {
			form[0]["setting[get_tags]"].value = 0;
			form[0]["setting[get_tags]"].checked = true;
		}
		if (!form[0]["setting[toolbox_disable_watermark]"].checked) {
			form[0]["setting[toolbox_disable_watermark]"].value = 0;
			form[0]["setting[toolbox_disable_watermark]"].checked = true;
		}
		if (!form[0]["setting[repeatcheck]"].checked) {
			form[0]["setting[repeatcheck]"].value = 0;
			form[0]["setting[repeatcheck]"].checked = true;
		}
	});
	window.ctswitch('.switch');
});
</script>
<?php $this->display('footer', 'system');