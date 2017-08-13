<?php
define('CMSTOP_START_TIME', microtime(true));
define('RUN_CMSTOP', true);
require '../../cmstop.php';

// 手持设备重定向
if (isset($_REQUEST['m_redirect_url']) && $_REQUEST['m_redirect_url']) {
    // 内容页，获取contentid
    $http_refer = $_REQUEST['m_redirect_url'];
    $pattern    = '#[^\d](\d+)\.shtml#';
    preg_match($pattern, $http_refer, $result);
    if ($result) {
        $contentid = $result[1];
        $contentid && $modelid = table('content', $contentid, 'modelid');
        $controllers = array(1 => 'pcarticle', 2 => 'pcpicture', 4 => 'pcvideo',
                             5 => 'pcinterview', 7 => 'pcactivity', 8 => 'pcvote',
                             9 => 'pcsurvey',
                        );
        if ($modelid && array_key_exists($modelid, $controllers)) {
            $url = MOBILE_URL."{$controllers[$modelid]}/{$contentid}";
            header("location: $url", true, 301);
        }
    }
}

$controller = isset($_GET['controller']) ? $_GET['controller'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : '';
if (empty($controller) || !in_array($controller, array(
    'index', 'comment', 'member', 'qrcode', 'search',
    'article', 'picture', 'link', 'video','video2017','vote',
    'activity', 'survey', 'special', 'eventlive'
))
) {
    $controller = 'index';
}
if (empty($action)) {
    $action = 'index';
}

$cmstop = new cmstop('mobile');
$cmstop->execute('mobile', $controller, $action);
