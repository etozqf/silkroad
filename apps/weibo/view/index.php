<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$CONFIG['charset']?>" />
<title><?=$head['title']?></title>
<link rel="stylesheet" type="text/css" href="css/admin.css"/>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/config.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/cmstop.js"></script>

<!--dialog-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/jquery-ui/dialog.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.dialog.js"></script>

<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.table.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/pagination/style.css"/>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.pagination.js"></script>

<script type="text/javascript" src="imageEditor/cmstop.imageEditor.js"></script>
<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/list/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.list.js"></script>

<link href="<?php echo IMG_URL?>js/lib/tree/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.tree.js"></script>

<link rel="stylesheet" type="text/css" href="apps/weibo/css/style.css"/>

<!--selectree-->
<script src="<?=IMG_URL?>js/lib/cmstop.selectree.js"></script>
<link rel="stylesheet" href="<?=IMG_URL?>js/lib/selectree/selectree.css">
<script src="<?=IMG_URL?>js/lib/cmstop.autocomplete.js" type="text/javascript"></script>
</head>
<body>
<div class="weibo-content">
	<div class="weibo-line">
		<h2 class="weibo-leftfloat">转发内容：</h2>
		<a href="javascript:;" class="weibo-rightfloat" onclick="addLink();"><img src="apps/weibo/images/weibo-contentsearch.png" alt="" /></a>
	</div>
	<div class="bk_8"></div>
	<textarea id="weibo-input" class="weibo-textarea" style="font-size:14px;"></textarea>
	<div class="weibo-line" style="height:26x;">
		<div class="weibo-letter-count"><span id="letter-count"></span></div>
	</div>
	<div class="clear"></div>
	<div class="bk_8"></div>
	<div class="bk_8"></div>
	<div class="weibo-picture-list">
		<div id="weibo-pic-left" class="weibo-pic-arrow weibo-pic-left"></div>
		<div id="uploader" class="weibo-pic-box weibo-upload"></div>
		<div id="weibo-pic-panel" class="weibo-pic-panel">
			<!--<div class="weibo-pic-box weibo-pic"></div>-->
		</div>
		<div id="weibo-pic-right" class="weibo-pic-arrow weibo-pic-right"></div>
	</div>
	<div class="bk_8"></div>
	<div class="weibo-line">
		<button id="submitbutton" class="button_style_4 weibo-rightfloat" onclick="postWeibo();">发表微博</button>
	</div>
	<div class="weibo-line">
		<h2 class="weibo-leftfloat">转发账号：
<?php 
if(priv::aca('weibo', 'weibo', 'account')) { 
?>
		<a href="javascript:;" onclick="ct.assoc.open('?app=weibo&controller=weibo&action=account', 'newtab');">设置</a>
<?php 
}
?>
		</h2>
	</div>
	<div class="bk_8"></div>
	<div class="weibo-line weibo-list">
		<h3>腾讯微博:</h3>
		<?php foreach($tencent_list as $item):?>
		<div class="weibo-checkbox weibo-checked"><?php echo $item['name']?><input type="checkbox" name="tencent[]" value="<?php echo $item['weiboid'];?>" checked="checked" style="display:none;" /></div>
		<?php endforeach;?>
	</div>
	<div class="weibo-line weibo-list">
		<h3>新浪微博:</h3>
		<?php foreach($sina_list as $item):?>
		<div class="weibo-checkbox weibo-checked"><?php echo $item['name']?><input type="checkbox" name="sina[]" value="<?php echo $item['weiboid'];?>" checked="checked" style="display:none;" /></div>
		<?php endforeach;?>
	</div>
</div>
<script type="text/javascript" src="apps/weibo/js/index.js"></script>
<script type="text/javascript" src="apps/weibo/js/slider.js"></script>
<script type="text/javascript">
<?php if($_GET['id']):?>
contentid = <?php echo $_GET['id'];?>;
getContent();
<?php endif;?>
</script>