<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$CONFIG['charset']?>" />
<title><?=$head['title']?></title>
<link rel="stylesheet" type="text/css" href="css/admin.css"/>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/config.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/cmstop.js"></script>

<!--table-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/pagination/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.pagination.js"></script>

<link rel="stylesheet" type="text/css" href="apps/spider/css/style.css">
</head>
<body>
	<table id="log_list" class="tablesorter table_list" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th class="bdr_3" width="160">开始时间</th>
				<th width="160">结束时间</th>
				<th width="80">采集条数</th>
				<th width="80">失败条数</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	<div class="clear bk_8"></div>
	<div class="clear bk_8"></div>
	<div id="pagination" class="pagination mar_l_8"></div>
	<div class="clear bk_8"></div>
</body>
<script type="text/javascript">
$(function() {
	var timeParse = function(t) {
		var time = parseInt(t, 10) * 1000,
		date = new Date(time);
		return date.getFullYear()+'-'+(date.getMonth()+1)+'-'+(date.getDate())+'&nbsp;'+date.getHours()+':'+date.getMinutes()+':'+date.getSeconds();
	}
	var row_template = new Array('<tr id="row_{id}">',
				'<td class="start_time t_c">{start_time}</td>',
				'<td class="end_time t_c">{end_time}</td>',
				'<td class="t_c">{total}</td>',
				'<td class="t_c">{failed}</td>',
			'</tr>').join('');
	var tableApp = new ct.table('#log_list', {
	    rowIdPrefix : 'row_',
	    pageField : 'page',
	    pageSize : 8,
	    template : row_template,
	    num_display_entries : 2,
		jsonLoaded : function(json){
			if (json.total < 20) {
				$('#pagination').hide();
			}
		},
		rowCallback : function(id, tr) {
			var startTime = tr.children('.start_time'),
			endTime = tr.children('.end_time');
			startTime.html(timeParse(startTime.html()));
			endTime.html(timeParse(endTime.html()));
		},
	    baseUrl  : '?app=spider&controller=cron&action=ls_log&id=<?php echo $taskid;?>'
	});
	tableApp.load();
});
</script>
</script>