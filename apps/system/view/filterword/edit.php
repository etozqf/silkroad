<div class="bk_8"></div>
<form name="<?=$app?>_<?=$controller?>_<?=$action?>" id="<?=$app?>_<?=$controller?>_<?=$action?>" method="post" class="validator" action="?app=<?=$app?>&controller=<?=$controller?>&action=edit&filterwordid=<?=$filterwordid?>">
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
	<tr>
		<th width="80"><span class="c_red">*</span> 敏感词：</th>
		<td><input type="text" name="pattern" id="pattern" value="<?=$pattern?>" size="20"/></td>
	</tr>
</table>
</form>