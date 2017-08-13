<?php
define('UPLOAD_URL', 'http://upload.db.silkroad.news.cn/');

require_once 'ctmobile.php';

$_config = array(
    'authkey' => '123123',
    'database' => array(
        'host' => 'localhost',
        'port' => 3306,
        'username' => 'cmstop',
        'password' => 'zxcvbnm',
        'dbname' => 'cmstop',
        'prefix' => 'cmstop_mobile_',
        'pconnect' => 0,
        'charset' => 'utf8'
    ),
    'model' => array(
        '1' => array(
            'name' => '文章',
            'alias' => 'article'
        ),
        '2' => array(
            'name' => '组图',
            'alias' => 'picture'
        ),
        '4' => array(
            'name' => '视频',
            'alias' => 'video'
        ),
        '3' => array(
            'name' => '链接',
            'alias' => 'link'
        ),
        '7' => array(
            'name' => '活动',
            'alias' => 'activity'
        ),
        '8' => array(
            'name' => '投票',
            'alias' => 'vote'
        ),
        '9' => array(
            'name' => '调查',
            'alias' => 'survey'
        ),
        '10' => array(
            'name' => '专题',
            'alias' => 'special'
        )
    )
);

$api = new CMSTOP_API_CTMobile($_config);
$api->dispatch($_GET['action']);