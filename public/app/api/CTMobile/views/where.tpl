<form>
	<input type="text" name="keyword" />
	<select multiple="multiple" name="catid" class="ctmobile_selectlist">
		<?php foreach($category as $item):?>
		<option value="<?php echo $item['catid']?>" selected="selected"><?php echo $item['catname']?></option>
		<?php endforeach;?>
	</select>
</form>