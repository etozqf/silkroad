<?php $this->display('header', 'system'); ?>
<div class="bk_10"></div>
<form name="sobeyvms_setting" id="sobeyvms_setting" action="?app=sobeyvms&action=setting" method="POST">
	<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
		<caption>索贝VMS接口配置</caption>
		<tr>
			<th width="120">接口地址：</th>
			<td>
				<input type="text" name="apiurl" value="<?php echo $setting['apiurl'];?>" class="w_160" />
			</td>
		</tr>
		<tr>
			<th>partnerToken：</th>
			<td>
				<input type="text" name="token" value="<?php echo $setting['token'];?>" class="w_160" />
			</td>
		</tr>
		<tr>
			<th></th>
			<td>
				<input type="submit" class="button_style_2" value="提交" />
				<input type="button" id="verify" class="mar_l_8 button_style_1" value="测试连接" />
			</td>
		</tr>
	</table>
</form>
<div id="verify-result"></div>

<script type="text/javascript">
	$(function() {
		var verifyResult = $('#verify-result');
		$('#sobeyvms_setting').ajaxForm(function(json) {
			if (json.state) {
				return ct.ok('保存成功');
			}
			return ct.error(json.error || '保存失败');
		});
		$('#verify').bind('click', function() {
			$.post('?app=sobeyvms&action=verify', {
				'apiurl': $('[name="apiurl"]').val(),
				'token': $('[name="token"]').val()
			}, function(req) {
				var html;
				if (!req.state) {
					return ct.error(req.error || '连接失败');
				}
				html = '<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">';
				html += '<caption>从索贝VMS获得的接口参数</caption>';
				for (var i in req.data) {
					html += '<tr><th width="120">' + i + '</th><td>' + req.data[i] + '</td></tr>';
				}
				html += '</table>';
				verifyResult.html(html);
				ct.ok('连接成功:');
			}, 'json');
		});
	});
</script>