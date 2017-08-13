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
<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/jquery-ui/dialog.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.ui.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL;?>js/lib/dialog/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.dialog.js"></script>

<!--validator-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/validator/style.css"/>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.validator.js"></script>

<!--tree-->
<link href="<?=IMG_URL?>js/lib/tree/style.css" rel="stylesheet" type="text/css" />
<script src="<?=IMG_URL?>js/lib/cmstop.tree.js" type="text/javascript"></script>

<!--table-->
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>

<!--pagination-->
<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/pagination/style.css" />
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.pagination.js"></script>

<link rel="stylesheet" type="text/css" href="apps/epaper/css/style.css" />
<script type="text/javascript" src="apps/epaper/js/epaper.js"></script>

<script type="text/javascript">
$.validate.setConfigs({
    xmlPath:'<?=ADMIN_URL?>apps/<?=$app?>/validators/'
});
$(ct.listenAjax);
</script>
</head>
<body>