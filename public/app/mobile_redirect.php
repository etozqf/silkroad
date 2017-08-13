<?php
define('CMSTOP_START_TIME', microtime(true));
define('RUN_CMSTOP', true);

require '../../cmstop.php';

$cmstop = new cmstop('frontend');

$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
$url = MOBILE_URL;
if(strpos($agent, 'iphone') || strpos($agent, 'ipad')){
	$iosurl = setting('mobile', 'iphone_version_url');
	if ($iosurl) {
		$url = $iosurl;
	}
}
if(strpos($agent, 'android')){
	$androidurl = setting('mobile', 'android_version_url');
	if ($androidurl) {
		$url = $androidurl;
	}
}
header('Location: '.$url);


?>