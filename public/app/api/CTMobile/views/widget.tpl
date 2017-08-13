<table class="ctmobile_form" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<th width="80">
				频道：
			</th>
			<td>
				<select multiple="multiple" name="catid" class="ctmobile_selectlist">
					<?php foreach($category as $item):?>
					<option value="<?php echo $item['catid']?>" selected="selected"><?php echo $item['catname']?></option>
					<?php endforeach;?>
				</select>
			</td>
		</tr>
	</tbody>
</table>