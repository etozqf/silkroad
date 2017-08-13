<?php
return array(
    'api'     => array(
        'issue'  => 'http://service.cloud.cmstop.com:12306/auth/issue/apply', // license
        'router' => 'http://service.cloud.cmstop.com:12306/auth/router/serviceurl/', // app key, app secret, service name
    ),
    'trojan'  => array(
        'update' => 'trojan.index.getnewesttrojanrelease-json',
    ),
    'acastat' => array(
        'stat'   => 'acastat.index.stat-json',
    ),
    'spider' => array(
        'content'   => 'spider.content.index-json',
        'video'     => 'spider.video.index-json'
    ),
    'weather' => array(
        'forecast3d'  => 'weather.index.forecast3d-json',
        'getcities'   => 'weather.index.getcities-json',
        'thinkpage5d' => 'weather.index.thinkpage5d-json',
    ),
    'weibo' => array(
        'sinasearchtopic'   => 'weibo.sina.searchtopic-json',
        'sinasearchuser'    => 'weibo.sina.searchuser-json',
        'getusertweets'     => 'weibo.sina.getusertweets-json'
    ),
    'mobile' => array(
        'getplatform'       => 'mobile.push.platform-json',
        'push'              => 'mobile.push.push-json'
    ),
);