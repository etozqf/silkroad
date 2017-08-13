<?php $this->display('header', 'system');?>
<div class="bk_10"></div>
<form id="payview_setting" action="?app=payview&controller=setting&action=index" method="POST">
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
	<caption>付费阅读基本设置</caption>
	<tr>
		<th width="120">付费主栏目ID：</th>
		<td><input type="text" name="setting[catid]" value="<?=$setting['catid']?>" size="30"/></td>
	</tr>
	<tr>
		<th width="120">用户权限缓存时间：</th>
		<td><input type="text" name="setting[cache_time]" value="<?=$setting['cache_time']?>" size="30"/>秒</td>
	</tr>
	<tr>
		<th width="120">邮寄费用：</th>
		<td><input type="text" name="setting[post_fees]" value="<?=$setting['post_fees']?>" size="30"/>元</td>
	</tr>
	<tr>
		<th width="120">订单过期关闭期限：</th>
		<td><input type="text" name="setting[order_close_time]" value="<?=$setting['order_close_time']?>" size="30"/>天（如不设置默认为7天）</td>
	</tr>
	<tr>
		<th></th>
		<td valign="middle"><br/>
		<input type="submit" id="submit" value="保存" class="button_style_2"/>
	</td>
	</tr>
</table>
</form>
<script type="text/javascript">
$(function(){
	$('#payview_setting').ajaxForm('submit_ok');
});

function submit_ok(json) {
	if(json.state) ct.ok(json.message);
	else ct.error(json.error);
}
</script>
<?php $this->display('footer', 'system');?>
