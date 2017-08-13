<?php

include_once 'lib/ads.php';

/**
 * CmsTop广告平台
 *
 * 管理地址：http://adm.cmstop.cn
 */
$adsInfo = array(
    'company_name' => '新华丝路数据库',
    'company_url' => 'http://db.silkroad.news.cn/',
    'user_email' => '22520926@qq.com',
    'email_cc' => '22520926@qq.com',
    'user_password'=>'cmstop@094',
);

try {
    $adsClient = new AdsClient($adsInfo);
    $ret = $adsClient->install();
    echo 'success';
} catch (Exception $e) {
    echo 'failed: ' . $e->getMessage();
}
