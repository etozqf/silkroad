<div class="bk_10"></div>

<form id="<?=$app?>_<?=$controller?>_<?=$action?>" method="POST" class="validator" action="?app=<?=$app?>&controller=<?=$controller?>&action=<?=$action?>">
<input type="hidden" name="listid" id="listid" value="<?=$listid?>"/>
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
	<tr>
		<th width="80">专辑名称：</th>
		<td><input type="text" name="listname" id="listname" value="<?=$listname?>" style="width:300px" /></td>
	</tr>
	<tr>
		<th>排序方式：</th>
		<td>
            <input type="radio" name="sorttype" value="0" />正序&nbsp;&nbsp;
            <input type="radio" name="sorttype" value="1" />倒序
        </td>
	</tr>
</table>
</form>

<script language="javascript">
    var sorttype = '<?=$sorttype?>';
    $(function(){
        sorttype || (sorttype = 0);
        $("input[name=sorttype]").eq(sorttype).attr('checked',true);
    });
</script>
