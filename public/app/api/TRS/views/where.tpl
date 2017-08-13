<form>
	<input type="text" name="keyword" />
	<input width="200" class="selectree" name="channelid"
	   url="<?=$basePath?>?action=channel&id=%s&jsoncallback=?"
	   paramVal="ID"
	   paramTxt="NAME"
	   paramHaschild="HASCHILDREN"
	   multiple="multiple"
	   alt="选择栏目"
	/>
	<select name="orderby">
		<?php foreach ($sortset as $f=>$n):?>
		<option value="<?=$f?>"><?=$n?></option>
		<?php endforeach;?>
	</select>
</form>