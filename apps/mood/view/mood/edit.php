<div class="bk_10"></div>
<div class="suggest center" style="width:274px;">
	<h2>友情提示</h2>
	<span style="display:block;padding:4px;">相对路径为public/img/apps/mood/</span>
</div>
<div class="bk_10"></div>
<form name="mood_edit" id="mood_edit" method="POST" class="validator" action="?app=mood&controller=mood&action=edit&moodid=<?=$moodid?>">
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
	<input type="hidden" name="moodid" id="moodid" value="<?=$moodid?>"/>
	<tr>
		<th><span class="c_red t_l">*</span> 心情名称：</th>
		<td><input type="text" name="name" value="<?=$name?>" size="20"/></td>
	</tr>
	<tr>
		<th class="t_l"><span class="c_red">*</span> 心情图标：</th>
		<td><input type="text" name="image" value="<?=$image?>" size="40"/></td>
	</tr>
</table>
</form>