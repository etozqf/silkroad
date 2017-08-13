<form name="<?=$controller?>_add" id="<?=$controller?>_add" method="POST" action="?app=<?=$app?>&controller=<?=$controller?>&action=<?=$action?>">
<table id="style_1" width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
	<input type="hidden" name="field" value="textarea" />
	<input type="hidden" name="projectid" value="<?=$pid?>" />
	<input type="hidden" name="fieldid" value="<?=$fid?>" />
	<tr>
		<th><span class="c_red">*</span> 字段名称：</th>
		<td><input type="text" name="setting[fieldname]" value="<?=$setting['fieldname']?>" size="40"/></td>
	</tr>
	<tr>
		<th><span class="c_red">*</span> 变量名：</th>
		<td><input type="text" name="setting[var]" value="<?=$setting['var']?>" size="40"/></td>
	</tr>
	<tr>
		<th> 初始宽度：</th>
		<td><input type="text" size="30" name="setting[width]" value="<?=$setting['width']?>" /></td>
	</tr>
	<tr>
		<th> 初始高度：</th>
		<td><input type="text" size="30" name="setting[height]" value="<?=$setting['height']?>" /></td>
	</tr>
	<tr>
		<th> 默认值：</th>
		<td><textarea name="setting[defaultvalue]"><?=$setting['defaultvalue']?></textarea></td>
	</tr>
</table>
</form>