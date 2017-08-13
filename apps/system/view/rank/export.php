<?php $this->display('header','system');?>
<div class="bk_10"></div>
<div class="tag_1">
	<ul class="tag_list" id="datatype" style="margin-left:145px">
		<li><a href="?app=system&controller=rank&action=index">综合排行</a></li>
	    <li><a href="?app=system&controller=rank&action=rank_pv">点击排行</a></li>
	    <li><a href="?app=system&controller=rank&action=rank_comments">评论排行</a></li>
	    <li><a href="?app=system&controller=rank&action=rank_digg">Digg排行</a></li>
	    <li><a href="?app=system&controller=rank&action=rank_mood">心情排行</a></li>
	    <li><a href="?app=system&controller=rank&action=export" class="s_3">导出</a></li>
	</ul>
</div>
<div style="width:400px;">
	<form id="condition">
		<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_info mar_l_8">
			<tr>
				<th width="80">类型：</th>
				<td width="300">
					<label><input type="radio" name="type" value="pv" checked="checked" />PV</label>&nbsp;
					<label><input type="radio" name="type" value="comments" />comment</label>&nbsp;
					<label><input type="radio" name="type" value="digg" />digg</label>
				</td>
			</tr>
			<tr>
				<th>限制条数：</th>
				<td>
					<input type="number" name="limit" max="1000" min="0" value="1000" />
				</td>
			</tr>
			<tr>
				<th>时间：</th>
				<td>
					<input type="text" name="starttime" class="input_calendar" value="<?php echo date('Y-m-d H:i:s', strtotime(date('Y-m-d 0:0:0')));?>" size="20"/>~
					<input type="text" name="endtime" class="input_calendar" value="<?php echo date('Y-m-d H:i:s');?>" size="20"/>
				</td>
			</tr>
			<tr>
				<th>栏目：</th>
				<td><?php echo element::cate('catid', 'catid', null, array('multiple'=>true));?></td>
			</tr>
		</table>
	</form>
	<div class="bk_10"></div>
	<button id="export" class="button_style_2 mar_l_8">导出</button>
</div>
<script type="text/javascript">
$(function(){
	$('input.input_calendar').DatePicker({'format':'yyyy-MM-dd HH:mm:ss'});
	$('#export').bind('click', function() {
		var queryString = $('#condition').serialize();
		$('<a />').attr({
			'href': '?app=system&controller=rank&action=export&' + queryString,
			'target': '_blank'
		}).appendTo($('body'))[0].click();
	});
});
</script>