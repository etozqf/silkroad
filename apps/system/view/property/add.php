<form name="add" id="property_add" method="POST" action="?app=system&controller=property&action=add">
<input name="parentid" type="hidden" value="<?=$proid?>"/>
<table border="0" cellspacing="0" cellpadding="0" class="table_form yyss">
  <tr>
    <th width="120">属性名：</th>
    <td><input type="text" name="name" id="name" size="20"/></td>
  </tr>
  <tr>
    <th width="120">英文别名：</th>
    <td><input type="text" name="alias" id="alias" size="20"/></td>
  </tr>
  <tr>
  	<th>描述：</th>
  	<td>&nbsp;<textarea name="description" cols="60" rows="3"></textarea></td>
  </tr>
	<tr>
		<th>是否收費：</th>
		<td>
			<label><input type="radio" name="ischarge" value="1" class="radio_style" <?php if ($ischarge == 1) echo 'checked';?> /> 是</label>
			&nbsp;&nbsp;
			<label><input type="radio" name="ischarge" value="0" class="radio_style" <?php if ($ischarge == 0) echo 'checked';?>> 否</label>
		</td>
	</tr>
  <tr>
  	<th>排序：</th>
  	<td>&nbsp;<input type="text" name="sort" value="0" size="3" maxlength="2"/> 值越大排序越靠后</td>
  </tr>
  <tr>
  	<th></th>
  	<td><input type="submit" value="保存" class="button_style_2"/></td>
  </tr>
</table>
</form>

<script type="text/javascript">
$('#property_add').ajaxForm('property.add_submit');
</script>