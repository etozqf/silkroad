<?php
/**
 * 将系统的操作日志发送到云端
 *
 * 根据cmstop_admin_log表中的app/controller/action的使用情况统计
 *
 *
 * 用法：
 * 0 2 * * * /usr/bin/php /path/www/htdocs/cmstop/cron/stat.php  1 >>  /var/log/cmstop_stat.log
 *
 * @copyright Copyright (c) 2014 Beijing CmsTop Technology Co.,Ltd. (http://www.cmstop.com)
 * @author xudianyang<xudianyang@phpboy.net>
 */
define('CMSTOP_START_TIME', microtime(true));
define('RUN_CMSTOP', true);
define('CRON_PATH', dirname(__FILE__));
require dirname(CRON_PATH) . '/cmstop.php';

/**
 * 发送统计数据格式
 *
 * array(
    'aca' => array(
        array(
            'system/index/index',
            21,
        ),
        ...
    ),
    'appkey' => '3dk435kdfsdksdnfsdsd',
 * )
 */

// 统计数据发送频率，默认1，单位：天
$frequency  = isset($_SERVER['argv'][1]) ? (float) $_SERVER['argv'][1] : 1;
$time = time();
$start_time = date('Y-m-d H:i:s', $time - $frequency*86400);
$end_time   = date('Y-m-d H:i:s', $time);

// 查询start,end字段段新增日志并整理的发送数据
$setting    = new setting();
$app_key    = $setting->get("cloud", "appkey");
$data = array(
    'aca'    => array(),
    'appkey' => $app_key,
);
$db   = factory::db();
$sql  = "SELECT aca, count(logid) frequency
        FROM `#table_admin_log`
        where time < '{$end_time}' and time > '{$start_time}'
        group by aca";
$stat = $db->select($sql);
foreach ($stat as $key => $record) {
    $tmp = explode('/', $record['aca']);
    if (count($tmp) > 3) {
        unset($tmp[1]);
    }
    $tmp = implode('/', $tmp);
    $data['aca'][$key][] = $tmp;
    $data['aca'][$key][] = $record['frequency'];
}

do {
    if (!$data['aca']) {
        $result = array(
            'state'   => false,
            'error' => '本时段无统计数据',
        );
        break;
    }

    // 向云端发送数据
    $cloud_config = config("cloud", "acastat");
    $api = config("cloud", 'api');
    $request = loader::lib('signature/request', 'cloud');
    $response = $request->execute(
        $api['router'],
        array('sid' => $cloud_config['stat']),
        array('stat' => $data)
    );

    if ($response['state']) {
        $result = array(
            'state'   => true,
            'message' => $response['message'],
        );
    } else {
        $result = array(
            'state' => false,
            'error' => $response['error'],
        );
    }
} while(0);

$log = 'DateTime：' . $start_time . ' 至 ' . $end_time . "\n" . '操作日志：';
if ($result['state']) {
    $log .= '发送成功，详细信息：' . $result['message'] . "\n";
} else {
    $log .= '发送失败，详细信息：' . $result['error'] . "\n";
}

echo $log;