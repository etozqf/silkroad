<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '/Enorth.php';

// TODO: modify blow configures
$configure = array(
	'authkey'   => '123123',
	'database'  => array(
		'driver' => 'oci',
		'host'   => '192.168.1.96',
		'port'   => '1521',
		'charset'  => 'utf8',
		'dbname'   => 'db01',
		'username' => 'pub',
		'password' => 'jiangsu8765'
	)
);

$api = new API_Enorth($configure);
$api->dispatch($_GET['action']);