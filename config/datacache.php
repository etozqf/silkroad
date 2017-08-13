<?php
/**
 * datacache可作为集中式缓存，必须使用redis
 * 如果使用redis作为集中式缓存，那么应注意与session.php配置信息保持一致
 */
return array(
    'storage' => 'redis',
    'caching' => 1,
    'prefix' => 'NzZjM2_',
    'redis' => array(
    	'host' => '127.0.0.1',
	    'port' => 6379,
	    'persistent'=> 0,
	    'auth' => '',
    ),
);
