<?php
/*
 *	移动设备访问手机内容页时，跳转到该页面来判断是否有对应的手机页地址
 *	若有则跳转到对应手机页面上，否则跳回原位置
 */
define('CMSTOP_START_TIME', microtime(true));
define('RUN_CMSTOP', true);

require '../../cmstop.php';

function go_to_www()
{
	$get_data = $_GET;
	unset($get_data['url']);
	$get_data['force'] = 'true';
	header('location:'.$_GET['url'].'?'.http_build_query($get_data));
	exit;
}

function go_to_wap($contentid)
{
	$url = WAP_URL . '?action=show&contentid=' . $contentid;
	if ($page = value($_GET, 'page', false)) {
		$url .= "&page=$page";
	}
	header("location:{$url}");
	exit;
}

$content = loader::model('content', 'system');
$data = $content->get(array('url'=>$_GET['url']), 'contentid, modelid');

if ($data) {
	if (in_array($data['modelid'], setting('wap', 'modelids'))) {
		go_to_wap($data['contentid']);
	}
}
go_to_www();