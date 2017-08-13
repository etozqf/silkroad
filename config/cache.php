<?php
/**
 * cache与具体的storage构成了缓存体系
 * 但在访问量高并发高的场景，集中式的缓存会带来性能问题
 * 所以我们增加了datacache.php配置可用来进行集中式缓存
 * 如此cache可作为本地缓存使用
 */
return array (
    'storage' => 'redis',
    'caching' => 1,
    'prefix' => 'NzZjM2_',
    'path' => CACHE_PATH,
    'debug' => false,
);
