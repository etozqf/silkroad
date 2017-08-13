<?php
require_once '../source/class/class_core.php';
require_once 'discuz.php';
@include DISCUZ_ROOT.'./config/config_global.php';

if (empty($_config))
{
	exit ('can not read database config');
}

$_config['authkey'] = '123123';

$api = new CMSTOP_API_Discuz($_config);
$api->dispatch($_GET['action']);