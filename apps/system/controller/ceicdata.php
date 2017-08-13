<?php
class controller_ceicdata extends system_controller_abstract
{

    private  $langs = array(
        'en' => array(
            'all' => 'All',
            'dataerror' => 'Data loading failed',
            'downerror' => 'downerror',
            'login' => 'Please Login',
            'date' => 'Date',

        ),
        'cn' => array(
            'all' => '全部',
            'dataerror' => '数据加载失败',
            'downerror' => '下载失败',
            'login' => '请先登录',
            'date' => '日期',
        ),
    );

    public function __construct($app) {
        parent::__construct($app);
    }

    public function getcategory() {
        $lang = $_GET['lang'] == 'en' ? 'en' : 'cn';
        $langs = $this->langs[$lang];

        $code = $_GET['code'];
        $level = intval($_GET['level']);
        $level = !$level ? 1 : $level;
        $cache = factory::cache();
        if(!$code) {
            $code = 'CN';
        }
        if(($menus = $cache->get('ceiccategory_'.$lang.'_'.$code)) !== false) {
            $return = array('error'=>0, 'info'=>'success', 'data'=>$menus);
            $return = json_encode($return);
            $return = $_GET['callback'] ? $_GET['callback'].'('.$return.')' : $return;
            exit($return);
        }

        $data = $this->getcategorydata($code, $level, $lang);
        if(!$data || $data['code']) {
            $return = array('error'=>1, 'info'=>$langs['dataerror']);
            $return = json_encode($return);
            $return = $_GET['callback'] ? $_GET['callback'].'('.$return.')' : $return;
            exit($return);
        }
        $data = $data['data']['menus'];

        $menus = array();
        foreach($data as $val) {
            $temp = array(
                'cid'=> $val['seriesCode'] ? $val['parentNodeCode'] : $val['nodeCode'],
                'ispid'=> $val['seriesCode'] ? 0 : 1,
                'scid'=> $val['seriesId'] ? $val['seriesId'] : '',//(string)$val['seriesCode'],
                'nodelevel' => $val['nodeLevel'],
                'cname'=> $val['name'] ? $val['name'] : $val['nodeName']
            );
            $menus[] = $temp;
        }
        $cache->set('ceiccategory_'.$lang.'_'.$code, $menus, 3600);

        $return = array('error'=>0, 'info'=>'success', 'data'=>$menus);
        $return = json_encode($return);
        $return = $_GET['callback'] ? $_GET['callback'].'('.$return.')' : $return;
        exit($return);

    }

    public function searchcategory(){
        $text = htmlspecialchars($_GET['text']);
        $lang = $_GET['lang'] == 'en' ? 'en' : 'cn';
        $langs = $this->langs[$lang];
        $data = $this->searchcategorydata($text, $lang);
        if(!$data || $data['code']) {
            $return = array('error'=>1, 'info'=>$langs['dataerror']);
            $return = json_encode($return);
            $return = $_GET['callback'] ? $_GET['callback'].'('.$return.')' : $return;
            exit($return);
        }
        $data = $data['data']['menus'];

        $menus = array();
        foreach($data as $val) {
            $temp = array(
                'cid'=> $val['seriesCode'] ? $val['parentNodeCode'] : $val['nodeCode'],
                'ispid'=> $val['seriesCode'] ? 0 : 1,
                'scid'=> $val['seriesId'] ? $val['seriesId'] : '',
                'nodelevel' => $val['nodeLevel'],
                'cname'=> $val['name'] ? $val['name'] : $val['nodeName']
            );
            $menus[] = $temp;
        }
        $return = array('error'=>0, 'info'=>'success', 'data'=>$menus);
        $return = json_encode($return);
        $return = $_GET['callback'] ? $_GET['callback'].'('.$return.')' : $return;
        exit($return);
    }

    public function query() {
        $length = 50;
        $page = intval($_GET['page']);
        $page = max($page, 1);
        $start = ($page - 1) * $length;
        $scid = $_GET['scid'];

        $lang = $_GET['lang'] == 'en' ? 'en' : 'cn';
        $langs = $this->langs[$lang];

        $querys = $_GET['query'];
        $querywhere = array();
        $selectwheres = array();
        if($querys) {
            foreach($querys as $query) {
                $queryarr = strpos($query['value'], '|') ? explode('|', $query['value']) : (array)$query['value'];
                $qa = 0;
                foreach($queryarr as $q) {
                    if($q == $langs['all']) {
                        $qa = 1;
                    }
                    !$qa && $querywhere[] = array('timePoint'=>$q);
                    $selectwheres[$query['code']][] = $q;
                }
            }
        }
        $data = $this->getdata($scid, $querywhere, $page, $length, $lang);

        if(!$data || $data['code']) {
            $return = array('error'=>1, 'info'=>$langs['dataerror']);
            $return = json_encode($return);
            $return = $_GET['callback'] ? $_GET['callback'].'('.$return.')' : $return;
            exit($return);
        }

        $tabletitles = $maptitles = $mapdata = $tabledata = array();
        $tabletitles[] = array('name'=>$langs['date']);
        $titlename = $data['data']['title']['engName'];
        if($data['data']['title']['displayUnit']) {
            $titlename .= ' ('.$data['data']['title']['displayUnit'].')';
        }
        $tabletitles[] = array('name'=>$titlename);
        $maptitles[] = array('name'=>(string)$data['data']['title']['displayUnit']);

        $mapcategory = array();
        foreach((array)$data['data']['datas'] as $key=>$val) {
            $tabledata[] = array(array('code'=>'date', 'value'=>$val['timePoint']), array('code'=>'value', 'value'=>$val['value']));
            $mapdata[] = array(array('code'=>'value', 'value'=>str_replace(',', '', $val['value'])));
            $mapcategory[] = $val['timePoint'];
        }

        $mapcategory = array_reverse($mapcategory);
        $mapdata = array_reverse($mapdata);

        $wheres = array();
        foreach((array)$data['data']['wheres'] as $key=>$val) {
            $val['code'] = $val['code'] ? $val['code'] : 'date';
            $val['name'] = $langs['date'];
            array_unshift($val['value'], $langs['all']);
            $wheres[] = $val;
        }


        $result = array('mapunit'=>$data['data']['title']['displayUnit'], 'tabletitles'=>$tabletitles, 'tabledata'=>$tabledata, 'maptitles'=>$maptitles, 'mapcategory'=>$mapcategory, 'mapdata'=>$mapdata, 'selectwheres'=>$selectwheres, 'wheres'=>$wheres, 'pageModel'=>$data['data']['pageModel']);


        $return = array('error'=>0, 'info'=>'success', 'data'=>$result);
        $return = json_encode($return);
        $return = $_GET['callback'] ? $_GET['callback'].'('.$return.')' : $return;
        exit($return);
    }

    public function download() {
        $lang = $_GET['lang'] == 'en' ? 'en' : 'cn';
        $langs = $this->langs[$lang];
        header("Content-type: text/html; charset=utf8");
        if(!$this->_userid) {
            $url = (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off' ? 'https://': 'http://').($_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME']).$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
            $type = $lang == 'cn' ? 1 : 2;
            $login_url = $lang == 'cn' ? LOGIN_URL : LOGIN_URL_EN;
            $url = $login_url.'&returnurl='.urlencode($url);
            if($lang == 'cn') {
                $this->showmessage($langs['login'], $url);
            } else {
                $this->showmessage_cn($langs['login'], $url);
            }
            exit();
        }
        $page = intval($_GET['page']);
        $page = max($page, 1);
        $length = 50;

        $downtype = $_GET['downtype'] == 'TXT' ? 'txt' : 'cvs';
        $scid = $_GET['scid'];
        $mname = htmlspecialchars(strip_tags($_GET['name']));

        $querys = $_GET['query'];
        $querys = json_decode($querys,true);
        $querywhere = array();
        $selectwheres = array();
        if($querys) {
            foreach($querys as $query) {
                $queryarr = strpos($query['value'], '|') ? explode('|', $query['value']) : (array)$query['value'];
                $qa = 0;
                foreach($queryarr as $q) {
                    if($q == $langs['all']) {
                        $qa = 1;
                    }
                    !$qa && $querywhere[] = array('code'=>$query['code'], 'value'=>$q);
                    $selectwheres[$query['code']][] = $q;
                }
            }
        }

        if($_GET['querytype'] == 'more') {
            $newlength = intval($_GET['newlength']);
            $newlength = max($newlength, 0);
            $newlength = min($newlength, 500);  //最新记录最多限制500条
            $scids = htmlspecialchars($_GET['scids']);
            $starttime = $_GET['starttime'] ? $_GET['starttime'] : '';
            $endtime = $_GET['endtime'] ? $_GET['endtime'] : '';
            $data = $this->getmoredata($scids, $starttime, $endtime, $newlength, $page, $length, $lang);
        } else {
            $data = $this->getdata($scid, $querywhere, $page, $length);
        }

        if(!$data || $data['code'] <> 0) {
            echo <<<COUCHBASE_DURABILITY_ETOOMANY
<script type="text/javascript">
alert('{$langs[downerror]}');
window.opener=null;
window.close();
</script>
COUCHBASE_DURABILITY_ETOOMANY;
            exit();
        }

        $header = $body = array();
        if($_GET['querytype'] == 'more') {
            $header[] = $langs['date'];
            foreach((array)$data['data']['titles'] as $val) {
                $header[] = $val['path'].'（'.$val['displayUnit'].'）';
            }
            $mname = date('Y-m-d');
            foreach((array)$data['data']['datas'] as $val) {
                foreach($val as $k=>$v) {
                    $val[$k] = str_replace(',', '', $v);
                }
                $body[] = $downtype == 'txt' ?  implode("\t", $val) :  implode(',', $val);
            }

        } else {
            $titlename = $data['data']['title']['engName'];
            if($data['data']['title']['displayUnit']) {
                $titlename .= ' ('.$data['data']['title']['displayUnit'].')';
            }
            $header = array($langs['date'], $titlename);
            foreach((array)$data['data']['datas'] as $val) {
                $val = array($val['date'], $val['value']);
                foreach($val as $k=>$v) {
                    $val[$k] = str_replace(',', '', $v);
                }
                $body[] = $downtype == 'txt' ?  implode("\t", $val) :  implode(',', $val);
            }
        }

        $header = $downtype == 'txt' ? implode("\t", $header) : implode(",", $header);
        $body = implode("\r\n", $body);

        if($downtype == 'txt') {
            header("Content-type:text/plain");
            $filename = $mname.'.txt';
        } else {
            header("Content-type:text/csv");
            $filename = $mname.'.csv';
        }
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=$filename");
        header('Expires:0');
        header('Pragma:public');
        echo  chr(0xEF).chr(0xBB).chr(0xBF).$header."\r\n".$body;
    }

    public function morequery() {
        $lang = $_GET['lang'] == 'en' ? 'en' : 'cn';
        $langs = $this->langs[$lang];
        $page = intval($_GET['page']);
        $page = max($page, 1);
        $length = 50;
        $newlength = intval($_GET['newlength']);
        $newlength = max($newlength, 0);
        $newlength = min($newlength, 500);  //最新记录最多限制500条
        $scids = htmlspecialchars($_GET['scids']);
        $starttime = $_GET['starttime'] ? $_GET['starttime'] : '';
        $endtime = $_GET['endtime'] ? $_GET['endtime'] : '';

        if(!preg_match('/^[\w,]+$/i', $scids)) {
            $return = array('error'=>1, 'info'=>$langs['dataerror']);
            $return = json_encode($return);
            $return = $_GET['callback'] ? $_GET['callback'].'('.$return.')' : $return;
            exit($return);
        }
        if(!$this->_userid && (strpos($scids, ',') !== false || strlen($scids) > 4)) {
            $url = ($lang == 'cn' ? table('page', 105, 'url') : table('page', 106, 'url'))."#$scids|newlength:{$newlength}|starttime:{$starttime}|endtime:{$endtime}|page:{$page}";
            $type = $lang == 'cn' ? 1 : 2;
            $login_url = $lang == 'cn' ? LOGIN_URL : LOGIN_URL_EN;
            $return = array('error'=>1, 'info'=>$langs['login'], 'needlogin'=>1, 'url'=>$login_url.'&returnurl='.urlencode($url));
            $return = json_encode($return);
            $return = $_GET['callback'] ? $_GET['callback'].'('.$return.')' : $return;
            exit($return);
        }
        $cache = factory::cache();
        $cachekey = 'ceic_'.$scids.'_'.$starttime.'_'.$endtime.'_'.$newlength.'_'.$page.'_'.$lang;

        if(($data = $cache->get($cachekey)) !== false) {
            $return = array('error'=>0, 'data'=>$data);
            $return = json_encode($return);
            $return = $_GET['callback'] ? $_GET['callback'].'('.$return.')' : $return;
            exit($return);
        }
        $data = $this->getmoredata($scids, $starttime, $endtime, $newlength, $page, $length, $lang);
        if(!$data || $data['code']) {
            $return = array('error'=>1, 'info'=>$langs['dataerror']);
            $return = json_encode($return);
            $return = $_GET['callback'] ? $_GET['callback'].'('.$return.')' : $return;
            exit($return);
        }
        $titles = $tabletitles = $maptitles = $mapdata = $tabledata = $mapcategory = array();

        foreach((array)$data['data']['titles'] as $val) {
            $titles[] = array(
                'name'=>$val['engName'],
                'frequency'=>$val['frequencyCode'],
                'unit' =>$val['displayUnit'],
                'starttime'=>$val['startDate'],
                'endtime'=>$val['endDate'],
                'region'=>(string)$val['region'],
                'source'=>(string)$val['dataSource'],
                'countryname'=>(string)$val['countryName'],
            );
        }

        $tabletitles[] = array('name'=>$langs['date']);
        foreach($titles as $val) {
            $name = $val['name'].'（'.$val['unit'].'）';
            $arr = $lang == 'en' ? mb_str_split($name, 40) : mb_str_split($name, 25);
            $name = '';
            foreach($arr as $k=>$v) {
                $name .= !$k ? $v : '<br/>'.$v;
            }
            $tabletitles[] = array('name'=>$name);
        }
        $tabledata = (array)$data['data']['datas'];

        $maptitles = $tabletitles;
        array_shift($maptitles);
        foreach((array)$data['data']['datas'] as $val) {
            $mapcategory[] = $val[0];
            foreach($val as $k=>$v) {
                if($k) {
                    $mapdata[$k-1][] = str_replace(',', '', $v);
                }
            }
        }
        $mapcategory = array_reverse($mapcategory);
        foreach($mapdata as $k=>$v) {
            $mapdata[$k] = array_reverse($v);
        }
        $data = array('titles'=>$titles, 'tabletitles'=>$tabletitles, 'tabledata'=>$tabledata, 'mapcategory'=>$mapcategory, 'maptitles'=>$maptitles, 'mapdata'=>$mapdata);
        $cache->set($cachekey, $data, 1800);
        $return = array('error'=>0, 'data'=>$data);
        $return = json_encode($return);
        $return = $_GET['callback'] ? $_GET['callback'].'('.$return.')' : $return;
        exit($return);
    }

    public function location() {
        $scid = htmlspecialchars($_GET['scid']);
        $url =  $_GET['lang'] == 'en' ? table('page', 106, 'url') : table('page', 105, 'url');
        header('location:'.$url.'#'.$scid);
    }

    private function getcategorydata($code, $level = '1', $lang = 'cn'){
        $url = 'http://192.168.110.110/silkroad/rest/api/rule/'.$code.'/'.$level.'/'.$lang.'/ceicMenu';
        $jsonData = json_encode(array());
        $headers = array(
            'app_key: xhsl',
            'app_secret: 57ce586290087fb9a1ea856f'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 5000);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($result, true);
        return $data;
    }

    private function searchcategorydata($text, $lang = 'cn'){
        $url = 'http://192.168.110.110/silkroad/rest/api/rule/'.$text.'/'.$lang.'/queryCeicMenu';
        $jsonData = json_encode(array());
        $headers = array(
            'app_key: xhsl',
            'app_secret: 57ce586290087fb9a1ea856f'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 5000);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($result, true);
        return $data;
    }

    private function getdata($scid, $where = array(), $page=1, $length=20, $lang = 'cn'){
        $url = 'http://192.168.110.110/silkroad/rest/api/rule/'.$scid.'/'.$lang.'/ceic';
        $where = array('pageModel'=>array('rows'=>$length, 'startPage'=>1, 'currentPage'=>$page, 'endPage'=>$page), 'datas'=>$where);
        $jsonData = json_encode($where);
        $headers = array(
            'app_key: xhsl',
            'app_secret: 57ce586290087fb9a1ea856f',
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData),
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 5000);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($result, true);
        return $data;

    }

    private function getmoredata($scids, $starttime = '', $endtime = '', $newlength = 0, $page = 1, $length = 10, $lang = 'cn') {
        if($newlength) { //最新记录
            $url = 'http://192.168.110.110/silkroad/rest/api/abc/'.$scids.'/'.$newlength.'/'.$lang.'/ceicMerge';
        } elseif($starttime && $endtime) { //时间范围查询
            $url = 'http://192.168.110.110/silkroad/rest/api/abc/'.$scids.'/'.$endtime.'/'.$starttime.'/'.$lang.'/ceicMerge';
        } else {
            return array();
        }
        $headers = array(
            'app_key: xhsl',
            'app_secret: 57ce586290087fb9a1ea856f'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 5000);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($result, true);
        return $data;
    }

}

function mb_str_split($str,$split_length=1,$charset="UTF-8"){
    if(func_num_args()==1){
        return preg_split('/(?<!^)(?!$)/u', $str);
    }
    if($split_length<1)return false;
    $len = mb_strlen($str, $charset);
    $arr = array();
    for($i=0;$i<$len;$i+=$split_length){
        $s = mb_substr($str, $i, $split_length, $charset);
        $arr[] = $s;
    }
    return $arr;
}