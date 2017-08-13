<form name="<?=$controller?>_save" id="<?=$controller?>_save" action="?app=pay&controller=platform&action=save" method="POST">
    <input type="hidden" name="apiid" value="<?= $apiid?>">
	<table class="table_form mar_l_8" cellpadding="0" cellspacing="0" width="98%">
	<tr>
		<th width="120">名称：</th>
		<td><input type="text" name="name" size="27" value="<?= $name?>"></td>
	</tr>
	<tr>
		<th>LOGO：</th>
		<td><input type="text" name="logo" size="40" value="<?= $logo?>"></td>
	</tr>
	<tr>
		<th>网址：</th>
		<td><input type="text" name="url" value="<?= $url?>" size="40" ></td>
	</tr>
	<tr>
		<th>描述：</th>
		<td><textarea rows="3" cols="50" name="description"><?=htmlspecialchars($description)?></textarea></td>
	</tr>
	<tr>
		<th>手续费：</th>
		<td><input type="text" name="payfee" value="<?= $payfee?>" size="10"></td>
	</tr>
	<tr>
		<th>商户代码：</th>
		<td><input type="text" name="setting[merid]" value="<?= $setting[merid]?>" size="40"></td>
	</tr>
	<tr>
		<th>支付提交页面：</th>
		<td><input type="text" name="setting[server_url]" value="<?= $setting['server_url']?>" size="50"></td>
	</tr>
	<tr>
		<th>支付通知页面：</th>
		<td><input type="text" name="setting[return_url]" value="<?= $setting['return_url']?>" size="50"></td>
	</tr>
	<tr>
		<th>排序：</th>
		<td><input type="text" name="sort"size="5" value="<?= $sort?>" ></td>
	</tr>
	</table>
</form>