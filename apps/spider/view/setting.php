<?php $this->display('header', 'system');?>
<div class="bk_8"></div>
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
	<caption>采集设置</caption>
	<tr>
		<th width="140">清除两个月前记录：</th>
		<td><input id="clearLog" class="button_style_2" type="button" value="清除"></td>
	</tr>
</table>
<script type="text/javascript">
$(function(){
	$('#clearLog').bind('click', function() {
		$.post('?app=spider&controller=setting&action=clear_log', null, function(response) {
			if (response.state) {
				ct.ok('操作成功');
			} else {
				ct.error('操作失败');
			}
		}, 'json');
	});
});
</script>
<?php $this->display('footer', 'system');?>