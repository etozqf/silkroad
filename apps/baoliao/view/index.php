<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$CONFIG['charset']?>" />
<meta content="IE=EmulateIE7" http-equiv="X-UA-Compatible" />
<title><?=$head['title']?></title>
<link rel="stylesheet" type="text/css" href="css/admin.css"/>
<script type="text/javascript" src="<?php echo IMG_URL?>js/config.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/cmstop.js"></script>

<!--dialog-->
<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/jquery-ui/dialog.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.dialog.js"></script>

<!--validator-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/validator/style.css"/>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.validator.js"></script>

<link href="<?=IMG_URL?>js/lib/tree/style.css" rel="stylesheet" type="text/css" />
<script src="<?=IMG_URL?>js/lib/cmstop.tree.js" type="text/javascript"></script>
<!-- 时间选择器 -->
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.datepicker.js"></script>
<link href="<?=IMG_URL?>js/lib/datepicker/style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
try {
	$.validate.setConfigs({
		xmlPath:'<?=ADMIN_URL?>apps/<?=$app?>/validators/'
	});
	$(ct.listenAjax);
} catch (exc) {};
window.VIDEO_PLAYER_URL = '<?=setting('video', 'player')?>';
</script>
</head>
<body>
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL;?>js/lib/pagination/style.css" />
<link rel="stylesheet" type="text/css" href="apps/baoliao/css/style.css" />
<script type="text/javascript" src="apps/baoliao/js/table.js"></script>
<script type="text/javascript" src="js/related.js"></script>
<link rel="stylesheet" type="text/css" href="css/imagesbox.css">
<script type="text/javascript" src="<?php echo IMG_URL;?>js/lib/jquery.lightbox.js"></script>
<script src="tiny_mce/tiny_mce.js" type="text/javascript"></script>
<script src="tiny_mce/editor.js" type="text/javascript"></script>
<script type="text/javascript" src="apps/baoliao/js/baoliao.js"></script>
<link rel="stylesheet" href="<?php echo IMG_URL;?>js/lib/rte/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL;?>js/lib/cmstop.rte.js"></script>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.datepicker.js"></script>
<link href="<?=IMG_URL?>js/lib/datepicker/style.css" rel="stylesheet" type="text/css" />

<div class="bk_8"></div>
<div class="tag_1">
	<ul id="nav" class="tag_list">
		<li status="2" class="s_3"><a href="javascript:;">全部</a></li>
		<li status="0"><a href="javascript:;">未回复</a></li>
		<li status="1"><a href="javascript:;">已回复</a></li>
	</ul>
	<div class="baoliao-search search f_r">
		<form id="search_form">
			<button style="display:none;"></button>
			<table><tr>
				<td width="260">
					<input id="search_start" type="text" class="datepicker" value="" />
					~<input id="search_end" type="text" class="datepicker mar_r_8" value="" />
				</td>
				<td width="150">
					<a id="search_submit" title="搜索" style="outline:none" href="javascript:;">搜索</a>
					<input id="search_keyword" type="text" size="30" value="" name="keywords" placeholder="关键词" />
				</td>
			</tr></table>
		</form>
	</div>
</div>
<div class="baoliao-sider">
	<form id="list_form">
		<ul id="baoliao_list" class="baoliao-list"></ul>
	</form>
	<div class="baoliao-foot">
		<div class="baoliao-list-check"><input id="select_all" type="checkbox" value="" /></div>
		<input id="delete" class="button_style_1 f_l" type="button" value="删除" />
		<div id="pagination" class="pagination f_r"></div>
	</div>
</div>
<div id="main_panel" class="baoliao-main" style="display:none;">
	<div id="control_bar" class="baoliao-control fixed">
		<input id="baoliao_btn_Reply" class="btn f_l" type="button" value="回复" />
		<input id="baoliao_btn_Edit" class="btn f_l" type="button" value="编辑" />
		<input id="baoliao_btn_Comment" class="btn f_l" type="button" value="管理评论" />
		<input id="baoliao_btn_Relate" class="btn f_l" type="button" value="关联内容" />
		<input id="baoliao_btn_Delete" class="btn f_l" type="button" value="删除" />
	</div>
	<div class="lh_32"></div>
	<div class="clear bk_10"></div>
	<div id="content_title" class="baoliao-content-title"></div>
	<div id="content_date" class="baoliao-content-date"></div>
	<div id="content_comment" class="baoliao-content-comment"></div>
	<div id="content_pv" class="baoliao-content-pv"></div>
	<div id="content_ip" class="baoliao-content-ip"></div>
	<div id="content_video" class="baoliao-video-panel"></div>
	<div id="content_image" class="baoliao-image-panel"></div>
	<div id="content_text" class="baoliao-content"></div>
	<div class="bk_30"></div>
	<div id="source" class="baoliao-source">报料人信息：</div>
	<div class="bk_20"></div>
	<a name="reply"></a>
	<div class="baobiao-repost">
		<div class="baobiao-repost-description f_l">管理员回复</div>
		<a id="repost_edit_exec" class="baobiao-repost-edit f_r" href="javascript:;">编辑</a>
		<span id="repost_last_time" class="f_r"></span>
		<div id="new_reply" class="clear bk_20 baoliao-newply"></div>
		<div id="repost_display" class="baobiao-repost-content" style="display:none;">
			<img class="baobiao-repost-quote" src="apps/baoliao/images/quote-left.png" />
			<div id="repost_context" class="baoliao-repost-text"></div>
			<img class="baobiao-repost-quote" src="apps/baoliao/images/quote-right.png" />
		</div>
		<div id="repost_edit" class="baobiao-repost-content">
			<textarea id="repost_textarea"></textarea>
			<div class="baoliao-repost-submit">
				<input id="repost_submit" class="button_style_2 f_r" type="submit" value="保存">
			</div>
		</div>
	</div>
	<form id="related_form"><ul id="related_data" style="display:none;"></ul></form>
	<div id="popup" class="baoliao-popup" style="display:none">
		<div class="popup-close hand"><img src="images/close.gif" class="f_r" /></div>
		<div id="popup_title" class="baoliao-popup-title f_l clear"></div>
		<a class="hand delete f_r" href="javascript:;"><img width="16" height="16" title="删除" alt="删除" src="images/delete.gif"></a>
		<a class="hand edit f_r" href="javascript:;"><img width="16" height="16" title="编辑" alt="编辑" src="images/edit.gif"></a>
		<a class="hand view f_r" href="javascript:;" target="_blank"><img width="16" height="16" title="访问" alt="访问" src="images/view.gif"></a>
		<input id="inpop" class="baoliao-inpop" type="text" value="" />
	</div>
</div>
<script id="baoliao_list_template" type="text/template">
	<li id="row_[baoliaoid]">
		<div class="baoliao-list-check"><input type="checkbox" name="del[]" value="[baoliaoid]" /></div>
		<div class="baoliao-list-content">
			<div class="baoliao-list-title"><a href="javascript:;" title="[title]">[short_title]</a></div>
			<div class="baoliao-list-date">[date]</div>
			<div class="baoliao-list-comment">评论([comments])</div>
			<div class="baoliao-list-pv">点击([pv])</div>
		</div>
		<div class="baoliao-list-delete"><img src="images/delete.gif" /></div>
	</li>
</script>
<script id="baoliao_image_template" type="text/template">
	<div class="baoliao-image-item">
		<table><tr>
			<td><a class="delete" href="javascript:;" title="删除" style="display:none;"><img src="images/delete.gif" alt="删除" /></a></td>
			<td><img class="baoliao-image" src="[src]" alt="" width="522" /></td>
		</tr></table>
	</div>
</script>
<script id="baoliao_video_template" type="text/template">
	<div class="baoliao-video-item">
		<table><tr>
			<td><a class="delete" href="javascript:;" title="删除" style="display:none;"><img src="images/delete.gif" alt="删除" /></a></td>
			<td>
				<object class="cmstopVideo" width="522" height="418" type="application/x-shockwave-flash" data="[swf]">
					<param name="src" value="[swf]" />
					<param name="allowfullscreen" value="true" />
					<param name="allowscriptaccess" value="true" />
					<param name="wmode" value="Transparent" />
				</object>
			</td>
		</tr></table>
	</div>
</script>