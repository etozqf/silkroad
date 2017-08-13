<form name="<?=$controller?>_save" id="<?=$controller?>_save" action="?app=pay&controller=platform&action=save" method="POST">
    <input type="hidden" name="apiid" value="<?= $apiid?>">
	<table class="table_form mar_l_8" cellpadding="0" cellspacing="0" width="98%">
	<tr>
		<th width="120">名称：</th>
		<td><?= $name?></td>
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
		</textarea></td>
	</tr>
	<tr>
		<th>手续费：</th>
		<td><input type="text" name="payfee" value="<?= $payfee?>" size="10"></td>
	</tr>
	<tr>
		<th>商户号：</th>
		<td><input type="text" name="setting[pid]" value="<?= $setting['pid']?>" size="30">人民币网关商户编号</td>
	</tr>
	<tr>
		<th>排序：</th>
		<td><input type="text" name="sort" value="<?= $sort?>" size="5"></td>
	</tr>
	<tr>
		<th>说明：</th>
		<td></td>
	</tr>
	</table>
</form>