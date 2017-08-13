<form name="<?=$controller?>_add" id="<?=$controller?>_add" method="POST" action="?app=<?=$app?>&controller=<?=$controller?>&action=add">
<input name="parentid" type="hidden" value="<?=$columnattrid?>"/>
<table border="0" cellspacing="0" cellpadding="0" class="table_form yyss">
  <tr>
    <th width="120">分类名：</th>
    <td>&nbsp;<input type="text" name="name" id="name" size="60"/></td>
  </tr>
  <tr>
    <th width="120">英文名：</th>
    <td>&nbsp;<input type="text" name="alias" id="alias" size="60"/></td>
  </tr>

  <tr>
  	<th>描述：</th>
  	<td>&nbsp;<textarea name="description" cols="60" rows="3"></textarea></td>
  </tr>
  <tr>
  	<th>排序：</th>
  	<td>&nbsp;<input type="text" name="sort" value="0" size="3" maxlength="2"/> 值越大排序越靠后</td>
  </tr>
  <tr>
  	<th></th>
  	<td><input type="submit" value="保存" class="button_style_2"/></td>
  </tr></table>
</form>

<script type="text/javascript">
$('<?=$controller?>_add').validate({
    submitHandler:function(jqform){jqform.submit()}
});
$('#<?=$controller?>_add').ajaxForm('<?=$controller?>.add_submit');
</script>