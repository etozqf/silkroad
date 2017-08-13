<div class="bk_5"></div>
<form action="?app=special&controller=setting&action=editTemplate">
<table width="95%" border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<th width="80">模版名称：</th>
			<td>
				<input type="text" name="name" value="<?=$name?>" />
				<?php if(isset($entry)):?>
				<input type="hidden" name="entry" value="<?=$entry?>" />
				<?php endif;?>
			</td>
		</tr>
		<tr>
			<th>描述：</th>
			<td>
				<textarea name="description" style="width:200px;height:50px;"><?=htmlspecialchars($description)?></textarea>
			</td>
		</tr>
		<tr>
			<th>缩略图：</th>
			<td><input type="text" name="thumb" size="15" value="<?=$thumb?>" /></td>
		</tr>
	</tbody>
</table>
</form>