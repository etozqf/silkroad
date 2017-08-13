<?php
require '../src/wekit.php';
require_once 'phpwind.php';

$config = include_once '../conf/windidconfig.php';
$db_config = include_once '../conf/database.php';
$config = array_merge($config, $db_config);

if (empty($config))
{
	exit ('can not read database config');
}

$config['authkey'] = '123123';

$api = new CMSTOP_API_Phpwind($config);
$api->dispatch($_GET['action']);