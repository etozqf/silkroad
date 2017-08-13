<?php 
return array (
    'storage' => 'redis',
    'maxlifetime' => 1800,
    'cache_limiter' => 'private_no_expire',
    'cache_expire' => '15',
    'cookie_domain' => '.db.silkroad.news.cn',
    'cookie_path' => '/',
    'cookie_lifetime' => '0',
    'db_issame' => 1,
	//'memcache_servers' => 'tcp://127.0.0.1:11211?persistent=1&weight=2&timeout=2&retry_interval=10',
	'redis_servers' => 'tcp://127.0.0.1:6379?auth=&persistent=1&weight=2&timeout=2&retry_interval=10'
);
