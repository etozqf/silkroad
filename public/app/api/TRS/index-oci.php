<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '/TRS.php';

// TODO: modify blow configures
$configure = array(
	'imageurl' => 'http://trs.dev/wcm/file/read_image.jsp?FileName=%s',
	'authkey'   => '123123',
	'database'  => array(
		'driver' => 'oci',
		'host'   => '192.168.1.96',
		'port'   => '1521',
		'charset'  => 'utf8',
		'dbname'   => 'ORCL',
		'username' => 'trs',
		'password' => '123123'
	)
);

$api = new API_TRS($configure);
$api->dispatch($_GET['action']);