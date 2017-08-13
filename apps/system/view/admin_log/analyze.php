<?php $this->display('header', 'system');?>
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/tablesorter/style.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/pagination/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.tablesorter.js"></script>
<!-- 时间选择器 -->
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.datepicker.js"></script>
<link href="<?=IMG_URL?>js/lib/datepicker/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
	.table_list {
		margin-left: 100px;
	}
	thead tr td {
		border: none!important;
	}
	form {
		margin: 10px auto;
	}
</style>

<div class="bk_10"></div>
<table class="table_list" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<td colspan="2">
				<form method="get" action=".">
					<input type="hidden" name="app" value="system" />
					<input type="hidden" name="controller" value="adminlog" />
					<input type="hidden" name="action" value="analyze" />
					<input type="text" name="start" class="input_calendar" value="" size="20" placeholder="开始时间" />
					&nbsp;至&nbsp;
					<input type="text" name="end" class="input_calendar" value="" size="20" placeholder="结束时间" />
					&nbsp;
					执行时间 >
					<input type="text" name="dur" value="0" size="3" class="t_r" />
					<input class="button_style_1" type="submit" value="搜索" style="margin-top:-3px;" />
				</form>
			</td>
		</tr>
		<tr>
			<th width="50%">链接地址</th>
			<th width="200">执行时间</th>
			<th width="100">条数</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($data as $item):?>
		<tr>
			<td><?php echo $item['aca'];?></td>
			<td class="t_c"><?php echo $item['dur'];?>秒</td>
			<td class="t_r"><?php echo $item['count'];?></td>
		</tr>
		<?php endforeach;?>
	</tbody>
</table>
<script type="text/javascript">
	$(function(){
		$('input.input_calendar').DatePicker({'format':'yyyy-MM-dd HH:mm:ss'});
	});
</script>