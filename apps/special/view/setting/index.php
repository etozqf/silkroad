<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="IE=EmulateIE7" http-equiv="X-UA-Compatible" />
	<title><?=$head['title']?></title>
	<link rel="stylesheet" type="text/css" href="css/admin.css"/>
	<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.js"></script>
	<script type="text/javascript" src="<?=IMG_URL?>js/config.js"></script>
	<script type="text/javascript" src="<?=IMG_URL?>js/cmstop.js"></script>

	<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/list/style.css"/>
	<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.list.js"></script>
	<!--dialog-->
	<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/jquery-ui/dialog.css" />
	<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.ui.js"></script>
	<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.dialog.js"></script>
	<script type="text/javascript" src="apps/system/js/psn.js"></script>
</head>
<body>
<div class="bk_8"></div>
<form id="special_setting" method="POST" action="?app=special&controller=setting&action=index">
	<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
		<caption>专题设置</caption>
		<tr>
			<th width="120">
				<?=element::tips('专题最后更新或创建时间距当前时间超过此值将不会自动更新')?>
				专题更新期限：
			</th>
			<td>
				<input type="text" name="setting[life]" value="<?=$setting['life']?$setting['life']:'1'?>" size="6" /> 天
			</td>
		</tr>
		<tr>
			<th>专题默认保存位置：</th>
			<td><?php echo element::psn('path', 'setting[psn]', $setting['psn'] ? $setting['psn'] : '{PSN:2}');?></td>
		</tr>
		<tr>
			<th>&nbsp;</th>
			<td  class="t_c"><input type="submit" class="button_style_2" value="保存" /></td>
		</tr>
	</table>
</form>
<script type="text/javascript">
$(function(){
	$('#special_setting').ajaxForm(function(json) {
		ct.tips('保存成功');
	});
	$('.tips').attrTips('tips', 'tips_green', 200, 'top');
	$('select').selectlist();
});
</script>
</body>
</html>