<form>
	<input type="text" name="keyword" />
	<input width="200" class="selectree" name="nodeid"
	   url="<?=$basePath?>?action=node&id=%s&jsoncallback=?"
	   paramVal="ID"
	   paramTxt="NAME"
	   paramHaschild="HASCHILDREN"
	   multiple="multiple"
	   alt="选择频道"
	/>
    <input width="200" name="typeid" alt="选择类型" />
	<select name="orderby">
		<?php foreach ($sortset as $f=>$n):?>
		<option value="<?=$f?>"><?=$n?></option>
		<?php endforeach;?>
	</select>
</form>