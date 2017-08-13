<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?=$CONFIG['charset']?>" />
	<title><?php echo $head['title'];?></title>
	<link rel="stylesheet" type="text/css" href="css/admin.css"/>
	<script type="text/javascript" src="<?php echo IMG_URL;?>js/lib/jquery.js"></script>
	<script type="text/javascript" src="<?php echo IMG_URL;?>js/config.js"></script>
	<script type="text/javascript" src="<?php echo IMG_URL;?>js/cmstop.js"></script>

	<!--dialog-->
	<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL;?>js/lib/jquery-ui/dialog.css" />
	<script type="text/javascript" src="<?php echo IMG_URL;?>js/lib/jquery.ui.js"></script>
	<script type="text/javascript" src="<?php echo IMG_URL;?>js/lib/cmstop.dialog.js"></script>

	<!--validator-->
	<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL;?>js/lib/validator/style.css"/>
	<script type="text/javascript" src="<?php echo IMG_URL;?>js/lib/cmstop.validator.js"></script>

	<!--tree-->
	<link href="<?php echo IMG_URL;?>js/lib/tree/style.css" rel="stylesheet" type="text/css" />
	<script src="<?php echo IMG_URL;?>js/lib/cmstop.tree.js" type="text/javascript"></script>

	<script type="text/javascript" src="<?php echo IMG_URL;?>js/lib/jquery.cookie.js"></script>

	<link rel="stylesheet" type="text/css" href="apps/wechat/css/style.css">
	<script type="text/javascript" src="apps/wechat/js/when.js"></script>

	<script type="text/javascript">
		$(ct.listenAjax);
		$.ajaxSetup({'dataType':'json'});
		var accountList = <?php echo $accountList;?>,
		BaseUrl = '?app=wechat&action=',
		globalAllowedExt = '<?php echo setting('system', 'attachexts');?>'.split('|');
	</script>
</head>
<body>
	<div id="container"></div>
</body>
<script type="text/javascript" src="apps/wechat/js/index.js"></script>