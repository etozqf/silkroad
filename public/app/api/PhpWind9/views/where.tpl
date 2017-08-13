<form>
	<input type="text" name="keyword" />
	<select class="discuz_selectlist" multiple="multiple" name="fids">
		<?php foreach($forumlist as $item):?>
		<option value="<?php echo $item['fid']?>"<?php if(in_array($item['fid'], $options['fids'])):?> selected="selected"<?php endif;?>><?php echo $item['name']?></option>
		<?php endforeach;?>
	</select>
</form>