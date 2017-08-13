<form>
	<label>
		<span><em>*</em>页面名称</span>
		<input type="text" name="name" value="<?=$name?>" />
	</label>
	<label>
		<span><em>*</em>文件名称</span>
		<input type="text" name="file" style="width:200px" value="<?=$file?>"<?php if (! $hasIndex): ?> disabled="disabled"<?php endif; ?> /> .shtml
	</label>
	<label>
		<span>网页标题</span>
		<input type="text" name="title" value="" />
	</label>
	<label>
		<span>关键字</span>
		<input type="text" name="keywords" value="" />
	</label>
	<label>
		<span>描述</span>
		<textarea name="description"></textarea>
	</label>
	<label>
		<span>更新频率</span>
		<input type="text" name="frequency" value="3600" style="width:100px" /> 秒
	</label>
</form>