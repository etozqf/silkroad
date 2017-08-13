<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '/Founder.php';

// TODO: modify blow configures
$configure = array(
	'authkey'   => '123123',
	'articleUrlRule' => '{PUBDATE}/content_{ARTICLEID}.htm',
	'picturePath' => '/data/',
	'database'  => array(
		'driver' => 'oci',
		'host'   => '10.2.20.242',
		'port'   => '1521',
		'charset'  => 'AL32UTF8',
		'dbname'   => 'yancheng',
		'username' => 'semon',
		'password' => '123456'
	)
);

$api = new API_Founder($configure);
$api->dispatch($_GET['action']);