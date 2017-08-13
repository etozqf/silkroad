<?php
define('CMSTOP_START_TIME', microtime(true));
define('RUN_CMSTOP', true);
define('IN_API', 2);
define('IS_AJAX', 1);
require '../../../cmstop.php';
require 'abstract.php';

// 过滤接口
$app = isset($_GET['app']) ? $_GET['app'] : '';
$apps = array('mobile');
if(!in_array($app, $apps)) exit('{"state": false, "message": "app not exists"}');

$cmstop = new cmstop('api');
$cmstop->execute();