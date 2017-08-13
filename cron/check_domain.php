<?php
/**
 * CmsTop域名安全性检测工具
 * 原理：可执行PHP的目录不可写，可写的目录不可执行PHP
 *
 * @author xudianyang<120343758@qq.com>
 * @copyright Copyright (c) 2013 Beijing CmsTop Technology Co.,Ltd. (http://www.cmstop.com)
 */
define("__CMSTOP_PATH__", "/data/www/cmstop/");
define('RUN_CMSTOP', true);
define('IN_ADMIN', 1);
require __CMSTOP_PATH__.'cmstop.php';
ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_WARNING & ~E_STRICT);
ini_set('display_errors', 1);	// 是否开启错误日志显示

//CmsTop域名列表 admin/app/img/upload/www/wap/space/api/mobile

$domains = array(
    str_replace("http://", '', rtrim(ADMIN_URL, "/"))  => array(
        'w' => 'N', 'r' => 'Y', 'php' => 'Y', 'name' => '后台'
    ),
    str_replace("http://", '', rtrim(APP_URL, "/"))    => array(
        'w' => 'N', 'r' => 'Y', 'php' => 'Y', 'name' => '前台',
    ),
    str_replace("http://", '', rtrim(IMG_URL, "/"))    => array(
        'w' => 'N', 'r' => 'Y', 'php' => 'N', 'name' => '资源文件',
    ),
    str_replace("http://", '', rtrim(UPLOAD_URL, "/")) => array(
        'w' => 'Y', 'r' => 'Y', 'php' => 'N', 'name' => '附件',
    ),
    str_replace("http://", '', rtrim(WWW_URL, "/"))    => array(
        'w' => 'Y', 'r' => 'Y', 'php' => 'N', 'name' => '静态发布',
    ),
    str_replace("http://", '', rtrim(WAP_URL, "/"))    => array(
        'w' => 'N', 'r' => 'Y', 'php' => 'Y', 'name' => 'WAP',
    ),
    str_replace("http://", '', rtrim(SPACE_URL, "/"))  => array(
        'w' => 'N', 'r' => 'Y', 'php' => 'Y', 'name' => '个人专栏',
    ),
    str_replace("http://", '', rtrim(API_URL, "/"))    => array(
        'w' => 'N', 'r' => 'Y', 'php' => 'Y', 'name' => '数据接口',
    ),
    str_replace("http://", '', rtrim(MOBILE_URL, "/")) => array(
        'w' => 'N', 'r' => 'Y', 'php' => 'Y', 'name' => '移动接口',
    ),
);

// CmsTop后台维护的发布点

$psn = table('psn');
foreach($psn as $domain) {
    $url = parse_url($domain['url']);
    if ($url['host'] && empty($domains[$url['host']])) {
        $domains[$url['host']] = array(
            'w' => 'Y', 'r' => 'Y', 'php' => 'N', 'name' => $domain['name'],
        );
    }
}
unset($psn);

if (STDIN) {

    $i = 0;
    $authkey = config('config', 'authkey');
    $data = array();
    while (!feof(STDIN)) {
        $i++;
        if ($i <= 3) {
            $line   = trim(fgets(STDIN));
            continue;
        }
        $record = array();
        $line   = trim(fgets(STDIN));
        if (empty($line)) continue;
        $domain = explode("|", $line);
        $record['hostname'] = str_encode($domain[0], $authkey);
        $record['ip'] = str_encode($domain[1], $authkey);
        $record['conf'] = str_encode($domain[2], $authkey);
        $record['domain'] = str_encode($domain[3], $authkey);
        $record['directory'] = str_encode($domain[4], $authkey);
        $record['opendir'] = str_encode($domain[5], $authkey);
        $record['php_exec'] = $domain[6];
        $record['php_suffix'] = $domain[7];
        $record['ssi'] = $domain[8] == ALL ? 'Y' : $domain[8];

        if (is_writable($domain[4])) {
            $record['writable'] = 'Y';
        } else {
            $record['writable'] = 'N';
        }

        if (is_readable($domain[4])) {
            $record['readable'] = 'Y';
        } else {
            $record['readable'] = 'N';
        }

        if (array_key_exists($domain[3], $domains)) {

            $pass = array('w' => false, 'r' => false, 'php' => false, );
            $standard = $domains[$domain[3]];
            $record['type'] = 1;
            $record['name'] = $standard['name'];

            if ($record['writable'] == $standard['w']) {
                $pass['w'] = true;
            }

            if ($record['readable'] == $standard['r']) {
                $pass['r'] = true;
            }

            if ($record['php_exec'] == $standard['php']) {
                $pass['php'] = true;
            }

            if ($pass['w'] && $pass['r'] && $pass['php']) {
                $record['level'] = 3;
            } else {
                $record['level'] = 2;
            }

        } else {
            $record['type'] = 2;
            $record['name'] = '未知';
            if ($record['writable'] == 'Y' && $record['php_exec'] == 'Y') {
                $record['level'] = 2;
            } else {
                $record['level'] = 1;
            }
        }
        $record['detecttime'] = time();
        $data[] = $record;
    }

    if (!empty($data)) {
        $db  = factory::db();
        $ip_key = $data[0]['ip'];
        $db->query("delete from `#table_safe_domain` where ip='{$ip_key}'");
        foreach ($data as $k => $domain) {
            $sql = "insert into `#table_safe_domain`(
                    `hostname`, `ip`,
                    `domain`, `conf`, `directory`, `opendir`, `php_exec`, `php_suffix`,
                    `writable`, `readable`, `ssi`, `level`, `type`, `detecttime`, `name`) values(
                    '{$domain[hostname]}', '{$domain[ip]}',
                    '{$domain[domain]}', '{$domain[conf]}', '{$domain[directory]}', '{$domain[opendir]}',
                    '{$domain[php_exec]}', '{$domain[php_suffix]}','{$domain[writable]}',
                    '{$domain[readable]}', '{$domain[ssi]}', '{$domain[level]}', '{$domain[type]}',
                    '{$domain[detecttime]}', '{$domain[name]}')";
            if (!$db->insert($sql)) {
                echo str_decode($domain['domain'], $authkey), " Insert Failure", "\n";
            }
        }
    }
}
