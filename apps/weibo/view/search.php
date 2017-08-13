<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$CONFIG['charset']?>" />
<title><?=$head['title']?></title>
<link rel="stylesheet" type="text/css" href="css/admin.css"/>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/config.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/cmstop.js"></script>
<!--list-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/list/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.list.js"></script>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.template.js"></script>

<link rel="stylesheet" type="text/css" href="apps/weibo/css/search.css">
<script type="text/javascript" src="apps/weibo/js/search.js"></script>
</head>
<body>
<div class="bk_8"></div>
<div class="weibo-search-condition">
	<form id="search_form" method="get" action="?" onsubmit="weiboSearch(this);return false;">
		<select name="search_type">
			<option value="keyword" selected="selected">关键词</option>
			<option value="user">用户</option>
		</select>
		<input type="text" name="keyword" class="weibo-search-keyword" value="" />
		<input class="button_style_1" type="submit" name="" value="腾讯微博" onclick="weibo.type='tencent_weibo';" />
		<input class="button_style_1" type="submit" name="" value="新浪微博" onclick="weibo.type='sina_weibo';" />
	</form>
</div>
<div class="weibo-search-container">
	<div class="weibo-search-list">
		<table id="weibo_select_list" cellspacing="0" cellpadding="0" style="width:326px;">
			<!--选中的微博列表-->
		</table>
	</div>
	<div class="weibo-search-right-arrow"></div>
	<div id="scroll_div" class="weibo-search-list">
		<table id="weibo_list" cellspacing="0" cellpadding="0" style="width:340px;">
			<!--搜素结果列表-->
		</table>
	</div>
</div>
<div class="weibo-search-bg">
	<input type="button" class="weibo-search-submit button_style_1" name="" value="取消" onclick="window.dialogCallback.close();">
	<input type="button" class="weibo-search-submit button_style_1" name="" value="确定" onclick="window.dialogCallback.ok(result);" />
</div>
<div id="get_user" class="weibo-getuser" style="display:none;">
	<p>搜用户<span id="get_user_keyword" class="weibo-getuser-keyword"></span></p>
	<ul id="get_user-list" class="weibo-getuser-list"></ul>
	<a id="next_user" class="weibo-search-next_user" href="javascript:;">下一页</a>
	<a id="prev_user" class="weibo-search-prev_user" href="javascript:;">上一页</a>
</div>
</body>
</html>