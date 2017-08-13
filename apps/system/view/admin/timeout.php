<?php if(isset($isWarn) && $isWarn){ ?>
<div style="height:30px;margin-bottom:10px;padding:10px;color:red;background:rgba(128,128,128,0.3);">您的帐号于<?php echo $warn_time; ?>在<?php echo $warn_ip; ?>登录，如非本人操作，则密码可能已泄露，建议修改密码或紧急冻结帐号。</div>
<?php } else { ?>
<div style="height:40px;"></div>
<?php } ?>
<form id="login" method="post" action="?app=system&controller=admin&action=login">
	<table cellpadding="0" cellspacing="0" border="0" style="margin-left:202px;">
		<tr>
			<td>
				<input type="text" name="username" value="<?php echo $username;?>" style="width:160px;line-height:24px; height: 24px; padding: 0 3px; border: #ddd 1px solid;" readonly />
			</td>
		</tr>
		<tr>
			<td>
				<input type="password" name="password" value="" style="width:160px;line-height:24px;height: 24px; padding: 0 3px; border: #ddd 1px solid;" placeholder="密码" />
			</td>
		</tr>
		<tr>
			<td>
				<input type="text" name="seccode" id="seccode" size="4" maxlength="4" placeholder="验证码" style="width:60px;line-height:24px; height: 24px; padding: 0 3px; border: #ddd 1px solid;" />
				<img class="seccode_image" src="?app=system&controller=seccode&action=image" width="52" height="24" style="cursor:pointer;" alt="验证码,看不清楚?请点击刷新验证码" align="absmiddle"/>
				<a class="seccode_image" href="javascript:;">换一个</a>
			</td>
		</tr>
		<tr>
			<td><button type="submit" style="width:160px;height:24px;line-height:24px;padding:0;margin: 4px 0 0 0;background:#38a4f0;color:#fff;">提交</button></td>
		</tr>
	</table>
</form>
<script type="text/javascript" src="<?php echo IMG_URL;?>js/lib/md5.js"></script>
<script type="text/javascript">
$('.seccode_image').bind('click', function(event) {
	$('img.seccode_image')[0].src = '?app=system&controller=seccode&action=image&id=' + Math.random() * 5;
	return false;
});
$('#seccode').one('focus', function() {
	$('.seccode_image').trigger('click');
});
$('#seccode').focus(function(){
	$('.seccode_image').click();
}); 
$('#seccode').click(function(){
	$('.seccode_image').click();
});
var form = $('#login');
form.submit(function(e){
	$('input[name=password]').val(hex_md5($('input[name=password]').val()));
});
</script>