<?php $this->display('header', 'system');?>
<div class="bk_10"></div>
<form id="setting_edit_mail" action="?app=system&controller=setting&action=edit" method="POST">
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
	<caption>邮件设置</caption>
	<tr>
		<th width="150">发送方式：</th>
		<td>
		<ul>
			<li><input type="radio" name="setting[mail][mailer]" value="1" class="radio" <?php if ($mail['mailer'] == 1) echo 'checked';?>/> 使用PHP的mail函数发送</li>
			<li><input type="radio" name="setting[mail][mailer]" value="2" class="radio" <?php if ($mail['mailer'] == 2) echo 'checked';?>> 通过 SOCKET 连接 SMTP 服务器发送(支持 ESMTP 验证, 推荐方法)</li>
			<li><input type="radio" name="setting[mail][mailer]" value="3" class="radio" <?php if ($mail['mailer'] == 3) echo 'checked';?>> 使用PHP的mail函数发送(仅 Windows 主机下有效, 不支持 ESMTP 验证)</li>
		</ul>
		</td>
	</tr>
	<tr>
		<th>SMTP 服务器：</th>
		<td><input id="smtp_host" type="text" name="setting[mail][smtp_host]" value="<?=$mail['smtp_host']?>" size="50"/></td>
	</tr>
	<tr>
		<th>SMTP 端口：</th>
		<td><input id="smtp_port" type="text" name="setting[mail][smtp_port]" value="<?=$mail['smtp_port']?>" size="50"/></td>
	</tr>
	<tr>
		<th>SMTP 身份验证：</th>
		<td><input type="radio" name="setting[mail][smtp_auth]" value="1" class="radio" <?php if ($mail['smtp_auth']) echo 'checked';?>/>是 <input type="radio" name="setting[mail][smtp_auth]" value="0" class="radio" <?php if (!$mail['smtp_auth']) echo 'checked';?>>否</td>
	</tr>
	<tr>
		<th>SMTP 用户名：</th>
		<td><input id="smtp_username" type="text" name="setting[mail][smtp_username]" value="<?=$mail['smtp_username']?>" size="50"/></td>
	</tr>
	<tr>
		<th>SMTP 密码：</th>
		<td><input id="smtp_password" type="password" name="setting[mail][smtp_password]" value="<?=$mail['smtp_password']?>" size="50"/></td>
	</tr>
	<tr>
		<th>发件人：</th>
		<td><input type="text" name="setting[mail][from]" value="<?=$mail['from']?>" size="50"/></td>
	</tr>
	<tr>
		<th>邮件头分隔符：</th>
		<td>
		<ul>
		  <li><input type="radio" name="setting[mail][delimiter]" value="1" class="radio" <?php if ($mail['delimiter'] == 1) echo 'checked';?>/> 使用 CRLF 作为分隔符(常用, SMTP方式默认分割符)</li>
		  <li><input type="radio" name="setting[mail][delimiter]" value="2" class="radio" <?php if ($mail['delimiter'] == 2) echo 'checked';?>> 使用 LF 作为分隔符(一些Unix主机使用mail函数时需用LF替代CRLF)</li>
		  <li><input type="radio" name="setting[mail][delimiter]" value="3" class="radio" <?php if ($mail['delimiter'] == 3) echo 'checked';?>> 使用 CR 作为分隔符(通常为 Mac 主机, 不常用)</li>
		</ul>
		</td>
	</tr>
	<tr>
		<th>邮件签名：</th>
		<td><textarea name="setting[mail][sign]" rows="6" cols="50" class="bdr"><?=$mail['sign']?></textarea></td>
	</tr>
	<tr>
		<th>测试邮箱：</th>
		<td><input type="text" name="verify_to" value="" size="50" /></td>
	</tr>
	<tr>
		<th></th>
		<td valign="middle">
		  <input type="submit" id="submit" value="保存" class="button_style_2"/>
		  <input type="button" id="verify" value="测试" class="button_style_2"/>
		  </td>
	</tr>
</table>
</form>
<div class="bk_10"></div>
<script type="text/javascript">
$(function(){
	$('#setting_edit_mail').ajaxForm(function(json){
		if(json.state) ct.tips(json.message);
		else ct.error(json.error);
	});
	$('#verify').bind('click', function() {
		var to = $('[name="verify_to"]').val();
		if (!to) {
			return ct.error('请填写测试邮箱');
		}
		$.get('?app=system&controller=setting&action=mail_verify', {
			'to' : to
		}, function(req){
			if (req.state) {
				ct.ok('已发送测试邮件');
			} else {
				ct.error('发送邮件失败');
			}
		}, 'json');
	});
});
</script>
<?php $this->display('footer', 'system');