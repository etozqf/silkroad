<div class="bk_5"></div>
<form action="?app=special&controller=setting&action=<?=$_GET['action']?>">
<table width="95%" border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<th width="80">分类名称：</th>
			<td>
				<input type="text" name="name" value="<?=$name?>" />
				<?php if(isset($typeid)):?>
				<input type="hidden" name="typeid" value="<?=$typeid?>" />
				<?php endif;?>
			</td>
		</tr>
		<tr>
			<th>排序：</th>
			<td><input type="text" name="sort" size="5" value="<?=$sort?>" /></td>
		</tr>
	</tbody>
</table>
</form>