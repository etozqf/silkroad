<form name="<?=$controller?>_save" id="<?=$controller?>_save" action="?app=pay&controller=platform&action=save" method="POST">
    <input type="hidden" name="apiid" value="<?= $apiid?>">
	<table class="table_form mar_l_8" cellpadding="0" cellspacing="0" width="98%">
	<tr>
		<th width="120">名称：</th>
		<td><input type="text" name="name" size="27"></td>
	</tr>
	<tr>
		<th>LOGO：</th>
		<td><input type="text" name="logo" size="40"></td>
	</tr>
	<tr>
		<th>网址：</th>
		<td><input type="text" name="url" size="save" size="40"></td>
	</tr>
	<tr>
		<th>描述：</th>
		<td><textarea rows="3" cols="50" name="description"></textarea></td>
	</tr>
	<tr>
		<th>排序：</th>
		<td><input type="text" name="sort"size="5"></td>
	</tr>
	</table>
</form>