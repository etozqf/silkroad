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

<!--dialog-->
<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/jquery-ui/dialog.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.dialog.js"></script>

<link rel="stylesheet" type="text/css" href="apps/spider/css/style.css">
</head>
<body>
	<div class="bk_10"></div>
	<table id="table_list" class="tablesorter table_list mar_l_8" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<th class="bdr_3" width="160">定时任务</th>
				<th width="200">上次采集时间</th>
				<th width="60">详细</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	<div class="clear bk_8"></div>
	<div id="pagination" class="pagination mar_l_8"></div>
</body>
<script type="text/javascript">
var cronDetail = function(taskid) {
	ct.iframe({
		url:'?app=spider&controller=cron&action=log&id='+taskid
	});
}
$(function() {
	var row_template = '<tr id="row_{taskid}"><td class="title t_c bdr_l">{title}</td><td class="last_time t_c">{cron_last}</td><td class="t_c bdr_r"><img class="hand log" width="16" height="16" title="查看运行日志" src="images/dialog.gif" onclick="cronDetail({taskid});"></td></tr>';
	var tableApp = new ct.table('#table_list', {
	    rowIdPrefix : 'row_',
	    pageField : 'page',
	    pageSize : 20,
	    template : row_template,
	    num_display_entries : 2,
		jsonLoaded : function(json){
			if (json.total < 20) {
				$('#pagination').hide();
			}
		},
		rowCallback : function(id, tr) {
			var lastTime = tr.children('.last_time'),
			time = parseInt(lastTime.html(), 10) * 1000,
			date = new Date(time),
			dateString = date.getFullYear()+'-'+(date.getMonth()+1)+'-'+(date.getDate())+'&nbsp;'+date.getHours()+':'+date.getMinutes()+':'+date.getSeconds();
			lastTime.html(dateString);
		},
	    baseUrl  : '?app=spider&controller=cron&action=ls_task'
	});
	tableApp.load();
});
</script>