<?php $this->display('header');?>
<div class="epaper-nav">
	<div class="epaper-nav-item cur">1.基本设置</div>
	<div class="epaper-nav-item">2.抓取规则</div>
	<div class="epaper-nav-item">3.默认选项设置</div>
</div>
<form id="epaper_add" name="epaper_add" action="?app=epaper&controller=epaper&action=add" method="post">
	<table class="table_form mar_l_8" border="0" width="98%" cellspacing="0" cellpadding="0">
		<caption>基本设置</caption>
		<tr>
			<th width="150"><span class="c_red">*</span>报纸名称：</th>
			<td><input type="text" size="30" value="" name="post[name]"></td>
		</tr>
		<tr>
			<th>页面编码：</th>
			<td>
				<label><input type="radio" name="post[charset]" value="GBK" />GBK</label>
				<label><input type="radio" name="post[charset]" value="UTF-8" checked="checked" />UTF-8</label>
			</td>
		</tr>
		<tr>
			<th>抓取方式：</th>
			<td>
				<label><input type="radio" name="post[type]" value="0" />自动</label>
				<label><input type="radio" name="post[type]" value="1" checked="checked" />手动</label>
			</td>
		</tr>
		<tr>
			<th>状态：</th>
			<td>
				<label><input type="radio" name="post[state]" value="1" checked="checked" />启用</label>
				<label><input type="radio" name="post[state]" value="0" />禁用</label>
			</td>
		</tr>
		<tr>
			<th></th>
			<td>
				<input class="button_style_2" type="submit" value="下一步" />
			</td>
		</tr>
	</table>
</form>
<script type="text/javascript">
$('#epaper_add').ajaxForm(function(json) {
	if (json.state) {
		location.href = '?app=epaper&controller=epaper&action=edit&type=step1&id='+json.id;
	}
}, null, function(form) {
	var r = true;
	$.each(form[0], function(i,k) {
		if(/post\[/.test(k.name) && !k.value) {
			r = false;
		}
	});
	if (!r) {
		ct.error('有项目未填写');
		return false;
	}
});
</script>