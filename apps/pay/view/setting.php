<?php $this->display('header', 'system');?>
<div class="bk_10"></div>
<form id="pay_setting" action="?app=pay&controller=setting&action=index" method="POST">
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
	<caption>财务管理设置</caption>
	<tr>
		<th>Email通知：</th>
		<td colspan="2"><input id="email1" type="checkbox" name="setting[emailnotice]" value="1"  class="radio" <?php if ($setting['emailnotice']) echo 'checked';?>/> </td>
	</tr>
	<tr>
		<th>邮件标题：</th>
		<td colspan="2"><input id="email2" type="text" name="setting[emailtitle]"  value="<?php echo $setting['emailtitle'] ? $setting['emailtitle'] : '您已成功支付!';?>" size="32"/></td>
	</tr>
	<tr>
		<th class="vtop">邮件内容：</th>
		<td colspan="2"><textarea id="email3"  name="setting[emailcontent]" rows="8"  cols="32">
		<?php echo $setting['emailcontent'] ? $setting['emailcontent']:'HI，$_username 您好！恭喜您已成功支付 $subjuct  ';?></textarea> </td>
	</tr>
	<tr>
		<th></th>
		<td colspan="2" valign="middle"><br/>
		<input type="submit" id="submit" value="保存" class="button_style_2"/>
	</td>
	</tr>
</table>
</form>
<script type="text/javascript">
$(function(){
	$('#pay_setting').ajaxForm('submit_ok');
});
function submit_ok(json) {
	if(json.state) ct.ok(json.message);
	else ct.error(json.error);
}
</script>
<?php $this->display('footer', 'system');?>
