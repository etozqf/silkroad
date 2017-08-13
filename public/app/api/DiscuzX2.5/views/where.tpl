<form>
	<input type="text" name="keyword" />
	<select multiple="multiple" name="fids" class="discuz_selectlist">
		<?php foreach($forumlist as $item):?>
		<option value="<?php echo $item['fid']?>" selected="selected"><?php echo $item['name']?></option>
		<?php endforeach;?>
	</select>
	<select name="orderby" class="discuz_selectlist">
		<?php foreach ($data['orderby']['value'] as $item):?>
		<option value="<?php echo $item[0];?>"><?php echo $language[$item[1]];?></option>
		<?php endforeach;?>
	</select>
</form>