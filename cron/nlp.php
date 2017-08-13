<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

$post  = 'cus=cmstop_test';
$post .= '&ti=李克强主持国务院会议：社保基金可投资地方债';
$post .= '&ctn=<p>国务院总理李克强4月1日主持召开国务院常务会议，部署盘活和统筹使用沉淀的存量财政资金，有效支持经济增长；确定加快发展电子商务的措施，培育经济新动力；决定适当扩大全国社保基金投资范围，更好惠民生、助发展。</p><p>会议认为，改革和完善财政资金管理，盘活沉淀的存量资金统筹用于发展急需的重点领域和薄弱环节，可以提高资金使用效益，更好发挥积极财政政策稳增长、调结构的效用。为此，要抓紧出台方案，完善相关规定，对统筹使用沉淀的存量财政资金建立任务清单和时间表，对工作不力的严肃追责。把“零钱”变成“整钱”，把“死钱”盘活用好。各部门要顾全大局、带头示范，统筹使用的财政资金重点向中西部倾斜，带动有效投资，增加农村贫困地区的公共产品和服务供给。同时，对存量资金较多的部门适当调减来年预算总额，清理整合政府性基金、国有资本经营收益和各部门专项资金，对专项用途资金实行清单制管理。建立事权与支出责任相适应、与财力相匹配的财税制度，提高财政管理绩效，更好支撑经济发展。</p><p>会议指出，发展电子商务等新兴服务业，是“互联网+”行动的重要内容，对于促进传统产业和新兴产业融合发展，减少流通成本，激励创业扩大就业，拉动消费，改善民生，增加金融活力，促进发展升级，具有重要意义。要创新政府管理和服务，积极支持电子商务发展，为其清障搭台，在发展中规范和引导。一要简政放权，放宽电子商务市场主体住所(经营场所)登记条件。</p>';
$post .= '&tag=';
$post .= '&dt=' . time();
$post .= '&sw=';
$post .= '&kc=5';

$result = request('http://localhost:8080/pretreat', $post);
print_r($result);


function request($url, $post = null, $timeout = 10, $sendcookie = true, $options = array(), $method = null)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 35);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout ? $timeout : 30);
    if ($sendcookie) {
        $cookie = '';
        foreach ($_COOKIE as $key => $val) {
            $cookie .= rawurlencode($key) . '=' . rawurlencode($val) . ';';
        }
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    }
    if ($post) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    if ($method) {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
    }

    if (!ini_get('safe_mode') && ini_get('open_basedir') == '') {
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    foreach ($options as $key => $value) {
        curl_setopt($ch, $key, $value);
    }

    $ret = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $content_length = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    if (!$content_length)
        $content_length = curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
    $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    return array(
        'httpcode' => $httpcode,
        'content_length' => $content_length,
        'content_type' => $content_type,
        'content' => $ret
    );
}