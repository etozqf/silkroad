<div class="bk_10"></div>
<form name="<?=$controller?>_save" id="<?=$controller?>_save" action="?app=pay&controller=platform&action=save" method="POST">
    <input type="hidden" name="apiid" value="<?= $apiid?>">
	<table class="table_form mar_l_8" cellpadding="0" cellspacing="0" width="98%">
	<caption>支付平台配置</caption>
	<tr>
		<th width="120">名称：</th>
		<td><input type="text" name="name" value="<?= $name?>" size="27"></td>
	</tr>
	<tr>
		<th>LOGO：</th>
		<td><input type="text" name="logo" value="<?= $logo?>" size="40"></td>
	</tr>
	<tr>
		<th>网址：</th>
		<td><input type="text" name="url" value="<?= $url?>" size="save" size="40"></td>
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
		<th>合作者身份ID：</th>
		<td><input type="text" name="setting[partner]" value="<?= $setting['partner']?>" size="20"></td>
	</tr>
	<tr>
		<th>安全校验码：</th>
		<td><input type="text" name="setting[key]" value="<?= $setting['key']?>" size="20"></td>
	</tr>
	<tr>
		<th>卖方支付宝帐号：</th>
		<td><input type="text" name="setting[seller_email]" value="<?= $setting['seller_email']?>" size="20"></td>
	</tr>
	<tr>
		<th>服务器通知页面：</th>
		<td><input type="text" name="setting[notify_url]" value="<?= $setting['notify_url']?>" size="50"></td>
	</tr>
	<tr>
		<th>付款后跳转页面：</th>
		<td><input type="text" name="setting[return_url]" value="<?= $setting['return_url']?>" size="50"></td>
	</tr>
	<tr>
		<th>收款方名称：</th>
		<td><input type="text" name="setting[mainname]" value="<?= $setting['mainname']?>" size="20"></td>
	</tr>
	<tr>
		<th>排序：</th>
		<td><input type="text" name="sort" value="<?= $sort?>" size="5"></td>
	</tr>
	</table>
</form>