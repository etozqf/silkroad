<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$CONFIG['charset']?>" />
<title><?=$head['title']?></title>
<link rel="stylesheet" type="text/css" href="css/admin.css"/>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/config.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/cmstop.js"></script>

<!--kswitch-->
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.switch.js"></script>

<!--dialog-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/jquery-ui/dialog.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.dialog.js"></script>

<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.table.js"></script>

<script type="text/javascript" src="imageEditor/cmstop.imageEditor.js"></script>
<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/list/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.list.js"></script>

<!--sort-->
<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/jquery-ui/dialog.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.ui.js"></script>

<link rel="stylesheet" type="text/css" href="apps/weibo/css/style.css"/>
</head>
<body>
	<div class="bk_10"></div>
	<div class="weibo-box">
		<div class="weibo-banner">
			<h3>微博绑定账号管理</h3>
		</div>
		<div class="weibo-panel">
			<table id="weibo_account" width="96%" cellspacing="0">
				<thead>
					<tr>
						<th width="60">排序</th>
						<th width="160">标识名称</th>
						<th width="120">微博类型</th>
						<th width="240">授权过期时间</th>
						<th width="100">状态</th>
						<th width="60">删除</th>
					</tr>
				</thead>
				<tbody id="sortable"></tbody>
			</table>
			<div class="weibo_add">
				<?php if($tencent):?>
				<img src="apps/weibo/images/tencent_add.png" alt="新增腾讯账号绑定"  class="weibo_add_btn" onclick="weiboAccountAdd('tencent_weibo');" />
				<?php else:?>
				<img src="apps/weibo/images/tencent_add_disable.png" alt="未指定参数"  class="weibo_add_btn" onclick="weiboBind();" />
				<?php endif;?>
				&nbsp;&nbsp;
				<?php if($sina):?>
				<img src="apps/weibo/images/sina_add.png" alt="新增新浪账号绑定" class="weibo_add_btn" onclick="weiboAccountAdd('sina_weibo');" />
				<?php else:?>
				<img src="apps/weibo/images/sina_add_disable.png" alt="未指定参数" class="weibo_add_btn" onclick="weiboBind();" />
				<?php endif;?>
			</div>
		</div>
	</div>
</body>
</html>
<script type="text/javascript" src="apps/weibo/js/account.js"></script>