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

function go_to_mobile($url)
{
	header("location:{$url}");
	exit;
}

$content = loader::model('content', 'system');
$data = $content->get(array('url'=>$_GET['url']), 'contentid');

if (!$contentid = value($data, 'contentid', false)) {
	go_to_www();
}

$mobile_content = loader::model('mobile_content', 'mobile');
$mobile_data = $mobile_content->get($contentid, 'url');
if ($mobile_data && !empty($mobile_data['url'])) {
	go_to_mobile($mobile_data['url']);
} else {
	go_to_www();
}