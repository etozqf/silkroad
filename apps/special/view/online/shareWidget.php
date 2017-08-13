<div class="bk_5"></div>
<form>
<table width="95%" border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<th width="80">名称：</th>
			<td><input name="name" type="text" size="35" value="<?=htmlspecialchars($name)?>" /></td>
		</tr>
        <tr>
            <th>分类：</th>
            <td>
                <select name="folder">
                    <option value="1"<?php if ($folder == 1 || ! $folder): ?> selected="selected"<?php endif; ?>>个人文件夹</option>
                    <option value="2"<?php if ($folder == 2): ?> selected="selected"<?php endif; ?>>公有文件夹</option>
                </select>
            </td>
        </tr>
		<tr>
			<th>缩略图：</th>
			<td><input type="text" name="thumb" size="15" value="<?=$thumb?>" /></td>
		</tr>
		<tr>
			<th>备注：</th>
			<td><textarea name="description" rows="2" cols="35"><?=htmlspecialchars($description)?></textarea></td>
		</tr>
	</tbody>
</table>
</form>