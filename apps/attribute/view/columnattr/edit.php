<form name="<?=$controller?>_edit" id="<?=$controller?>_edit" method="POST" action="?app=<?=$app?>&controller=<?=$controller?>&action=edit">
<input name="id" type="hidden" value="<?=$id?>"/>
<input name="parentid" type="hidden" value="<?=$parentid?>"/>
<table border="0" cellspacing="0" cellpadding="0" class="table_form yyss">
  <tr>
    <th width="120">分类ID：</th>
    <td>&nbsp;<?=$id?></td>
  </tr>
  <tr>
    <th width="120">分类名：</th>
    <td>&nbsp;<input type="text" name="name" id="name" value="<?=$name?>" size="60"/></td>
  </tr>
  <tr>
    <th width="120">英文名：</th>
    <td>&nbsp;<input type="text" name="alias" id="alias" value="<?=$alias?>" size="60"/></td>
  </tr>
 
  <tr>
  	<th>描述：</th>
  	<td>&nbsp;<textarea name="description" cols="60" rows="3"><?=$description?></textarea></td>
  </tr>
  <tr>
  	<th>排序：</th>
  	<td>&nbsp;<input type="text" name="sort" value="<?=$sort?>" size="3" maxlength="2"/> 值越大排序越靠后</td>
  </tr>
  <tr>
  	<th></th>
  	<td><input type="submit" value="保存" class="button_style_2"/></td>
  </tr>
</table>
</form>

<script type="text/javascript">
$('<?=$controller?>_edit').validate({
    submitHandler:function(jqform){jqform.submit()}
});
$('#<?=$controller?>_edit').ajaxForm('<?=$controller?>.edit_submit');
</script>