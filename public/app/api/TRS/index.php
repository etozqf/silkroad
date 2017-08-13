<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '/TRS.php';

// TODO: modify blow configures
$configure = array(
	'imageurl' => 'http://trs.dev/wcm/file/read_image.jsp?FileName=%s',
	'authkey'   => '123123',
	'database'  => array(
		'driver' => 'mssql',
		'host'   => '192.168.1.96',
		'port'   => '1433',
		'charset'  => 'utf8',
		'dbname'   => 'trswcmv65',
		'username' => 'sa',
		'password' => '123123'
	)
);

$api = new API_TRS($configure);
$api->dispatch($_GET['action']);