<div class="bk_8"></div>
<form name="<?=$app?>_<?=$controller?>_<?=$action?>" id="<?=$app?>_<?=$controller?>_<?=$action?>" method="post" class="validator" action="?app=<?=$app?>&controller=<?=$controller?>&action=edit">
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
	<tr>
		<th width="80"><span class="c_red">*</span> 园区类别：</th>
		<td><input name="value" type="text" value="<?=$value?>" size="16" maxlength="255"  /><input name="cateid" type="hidden" value="<?=$cateid?>" /></td>
	</tr>
	<tr>
		<th width="80"><span class="c_red">*</span> 类别排序：</th>
		<td><input name="sort" type="text" value="<?=$sort?>" size="16" maxlength="255"  /></td>
	</tr>
	<tr>
		<th width="80"><span class="c_red">*</span> 类别颜色：</th>
		<td><input name="color" type="text" value="<?=$color?>" size="16" maxlength="255"  /><span style="color:#ccc">(十六进制颜色值)</span></td>
	</tr>
	<tr>
		<th width="80"><span class="c_red">*</span> 所属类型：</th>
		<td><!-- <input name="sort" type="text" value="<?=$sort?>" size="16" maxlength="255"  /> -->
			<select id="status" name="category">
				<option value="">请选择</option>
				<option value="1" <?php if($category==1){echo 'selected';}?>>国内</option>
				<option value="2" <?php if($category==2){echo 'selected';}?>>世界</option>
			</select>
		</td>
	</tr>
</table>
</form>