<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$CONFIG['charset']?>" />
<title><?=$head['title']?></title>
<?=$head['resource']?>
</head>
<body>
<?php if (empty($_GET['modelid']) || $_GET['modelid'] == 1):?>
<div class="tabs">
	<ul>
		<li port="cmstop">CmsTop</li>
		<?php foreach($ports as $v):
		if ($v['disabled']) continue;
		?>
		<li port="<?=$v['port']?>"><?=$v['name']?></li>
		<?php endforeach;?>
	</ul>
</div>
<?php endif;?>
<div id="where"></div>
<div id="box"></div>
<div class="button-area">
	<button onclick="PICKER.ok()" type="button">确定</button>
	<button onclick="PICKER.cancel()" type="button">取消</button>
</div>
<script type="text/javascript">
fet.setAlias({IMG_URL:"<?=IMG_URL?>"});
PICKER.init();</script>
</body>
</html>