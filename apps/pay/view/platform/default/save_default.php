<form name="<?=$controller?>_save" id="<?=$controller?>_save" action="?app=pay&controller=platform&action=save" method="POST">
    <input type="hidden" name="apiid" value="<?= $apiid?>">
	<table class="table_form mar_l_8" cellpadding="0" cellspacing="0" width="98%">
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
		<td><input type="text" name="url" value="<?= $url?>" size="40"></td>
	</tr>
	<tr>
		<th>描述：</th>
		<td><textarea rows="5" cols="50" name="description"><?=htmlspecialchars($description)?> 

您需要修改 
./apps/pay/view/platform/名称/save.php 
根据需要添加字段（可参考示例字段）
		</textarea></td>
	</tr>
	<tr>
		<th>手续费：</th>
		<td><input type="text" name="payfee" value="<?= $payfee?>" size="10"></td>
	</tr>
	<tr>
		<th>示例字段：</th>
		<td><input type="text" name="setting[var]" value="<?= $setting['var']?>" size="30"></td>
	</tr>
	<tr>
		<th>排序：</th>
		<td><input type="text" name="sort" value="<?= $sort?>" size="5"></td>
	</tr>
	</table>
</form>