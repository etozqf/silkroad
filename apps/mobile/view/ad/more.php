<?php $this->display('header', 'system'); ?>

<link rel="stylesheet" href="apps/mobile/css/base.css" />
<link rel="stylesheet" href="apps/mobile/css/ad.css" />

<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<script type="text/javascript" src="imageEditor/cmstop.imageEditor.js"></script>
<script type="text/javascript" src="js/cmstop.filemanager.js"></script>
<script type="text/javascript" src="apps/mobile/js/signature.js"></script>

<div class="bk_8"></div>

<?php $this->display('ad/menu');?>

<div class="ad-content">
	<h3>更多应用页广告</h3>
	<div class="ui-tips-yellow" style="width:600px;">
		pad版更多应用页面顶部的广告<br/>
		横屏图片尺寸<?php echo $horizon_width;?>x<?php echo $horizon_height;?><br />
		竖屏图片尺寸<?php echo $vertical_width;?>x<?php echo $vertical_height;?>
	</div>
	<form id="ad-more" class="ad-more" src="?app=mobile&controller=ad&action=more"></form>
	<input id="add" type="button" class="button_style_2" value="添加" />
	<input id="submit" type="button" class="button_style_2" value="保存" />
</div>
<script type="text/javascript">
	var data = eval('(<?php echo $data;?>)');
	var horizonWidth = <?php echo $horizon_width;?>;
	var horizonHeight = <?php echo $horizon_height;?>;
	var verticalWidth = <?php echo $vertical_width;?>;
	var verticalHeight = <?php echo $vertical_height;?>;
</script>

<script type="text/javascript" src="apps/mobile/js/adm/more.js"></script>