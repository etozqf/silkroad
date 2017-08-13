<?php
return array(
    'tencent_weibo' => array(
        'name' => '腾讯微博',
        'alias' => 'tencent_weibo',
        'authorize_url' => 'https://open.t.qq.com/cgi-bin/oauth2/authorize',
        'authorize_callback_url' => short_url(APP_URL.'?app=system&controller=auth&action=authorize_callback&platform=tencent_weibo'),
        'revoke_url' => 'https://open.t.qq.com/api/auth/revoke_auth?format=json',
        'revoke_callback_url' => short_url(APP_URL.'?app=system&controller=auth&action=revoke_callback&platform=tencent'),
        'access_token_url' => 'https://open.t.qq.com/cgi-bin/oauth2/access_token',
    ),
    'sina_weibo' => array(
        'name' => '新浪微博',
        'alias' => 'sina_weibo',
        'authorize_url' => 'https://api.weibo.com/oauth2/authorize',
        'authorize_callback_url' => short_url(APP_URL.'?app=system&controller=auth&action=authorize_callback&platform=sina_weibo'),
        'revoke_url' => '',
        'revoke_callback_url' => short_url(APP_URL.'?app=system&controller=auth&action=revoke_callback&platform=sina'),
        'access_token_url' => 'https://api.weibo.com/oauth2/access_token',
    ),
);