<?php
class controller_hgdata extends system_controller_abstract {

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

    public function query() {
        $length = 50;
        $page = intval($_GET['page']);
        $page = max($page, 1);
        $start = ($page - 1) * $length;
        $lang = $_GET['lang'] == 'en' ? 'en' : 'cn';
        $langs = array(
            'en' => array(
                'codeerror' => 'Wrong code',
                'all' => 'All',
                'dataerror' => 'Data loading failed',
            ),
            'cn' => array(
                'codeerror' => '标码不正确',
                'all' => '全部',
                'dataerror' => '数据加载失败',
            ),
        );
        $mcode = $_GET['code'];
        $mcodes = array(
            'cn' => array('M101CUSD','M101DUSD','M102USD', 'M103USD', 'M104USD', 'M105USD', 'M106USD', 'M107USD', 'M108USD', 'M109USD', 'M110USD', 'M111USD', 'M112USD', 'M113USD', 'M114USD', 'M115USD', 'M116USD', 'JCK_HZ', 'JCK_AGB','JCK_AHG','JCK_AFHD'),

            'en' => array('M101CUSD','M101DUSD','M102USD', 'M103USD', 'M104USD', 'M105USD', 'M106USD', 'M107USD', 'M108USD', 'M109USD', 'M110USD', 'M111USD', 'M112USD', 'M113USD', 'M114USD', 'M115USD', 'M116USD', 'JCK_HZ', 'JCK_AGB','JCK_AHG','JCK_AFHD'),
        );
        if(!in_array($mcode, $mcodes[$lang])) {
            $return = array('error'=>1, 'info'=>$langs[$lang]['codeerror']);
            $return = json_encode($return);
            $return = $_GET['callback'] ? $_GET['callback'].'('.$return.')' : $return;
            exit($return);
        }
        $querys = $_GET['query'];
        $querywhere = array();
        $selectwheres = array();
        if($querys) {
            foreach($querys as $query) {
                $queryarr = strpos($query['value'], '|') ? explode('|', $query['value']) : (array)$query['value'];
                $qa = 0;
                foreach($queryarr as $q) {
                    if($q == $langs[$lang]['all']) {
                       $qa = 1;
                    }
                    !$qa && $querywhere[] = array('code'=>$query['code'], 'value'=>$q);
                    $selectwheres[$query['code']][] = $q;
                }
            }
        }
        $cache = factory::cache();
        if(empty($querywhere) && $page == 1 && $length == 50) {
            if(($result = $cache->get($mcode.'_'.$lang)) !== false) {
                $return = array('error'=>0, 'info'=>'success', 'data'=>$result);
                $return = json_encode($return);
                $return = $_GET['callback'] ? $_GET['callback'].'('.$return.')' : $return;
                exit($return);
            }
        }
        $data = $this->getdata($mcode, $querywhere, $page, $length, $lang);
        if(!$data || $data['code']) {
            $return = array('error'=>1, 'info'=>$langs[$lang]['dataerror']);
            $return = json_encode($return);
            $return = $_GET['callback'] ? $_GET['callback'].'('.$return.')' : $return;
            exit($return);
        }

        $data['pageModel'] = $data['data']['pages'];

        $maplabels = $tabletitles = $maptitles =  $tablekeys = $mapkeys = $titlekeys = $wheres = $titles = array();

        $mapdata = $tabledata = array();

        $tablekey = $mapkey = 0;
        $percents = array();
        $datatypes = array();
        foreach((array)$data['data']['titles'] as $key=>$val) {
            if($val['showTable']) {
                $tabletitles[] = array('code'=>$val['code'], 'name'=>$val['name'], 'ismap'=>($val['showMap'] ? 1 : 0));
                $tablekeys[$tablekey] = $val['code'];
                $tablekey++;
            }
            if($val['showMap']) {
                $maptitles[] = array('code'=>$val['code'], 'name'=>$val['name'], 'dataType'=>$val['dataType']);
                $mapkeys[$mapkey] = $val['code'];
                $mapkey++;
            }
            if($val['showMapLabel']) {
                $maplabels[] = $val['code'];
            }
            $val['dataType'] && $datatypes[] = $val['dataType'];

            $titles[$val['code']] = $val;
        }
        $datatypes = array_unique($datatypes);

        $maplabels = array_reverse($maplabels);
        $maplabelkeys = array();
        foreach($maplabels as $code) {
            $maplabelkeys[$code] = array_search($code, $tablekeys);
        }
        $mapcategory = array();
        $maxcols = 0;
        foreach((array)$data['data']['datas'] as $key=>$val) {
            foreach($val as $j=>$v) {
                $arr = mb_str_split(trim($v), 20);
                $val[$j] = implode('<br/>', $arr);
            }

            if($tablekeys) {
                $table = array();
                foreach($tablekeys as $k=>$code) {
                    $table[] = array('code'=>$code, 'value'=>$val[$k]);
                }
                $tabledata[] = $table;
            }

            if($mapkeys) {
                $table = array();
                foreach($mapkeys as $code) {
                    $mk = array_search($code, $tablekeys);
                    $table[] = array('code'=>$code, 'value'=>$val[$mk], 'dataType'=>$titles[$code]['dataType']);
                }
                $mapdata[] = $table;
            }
            $arrlebel = array();
            foreach($maplabelkeys as $code=>$k) {
                if($code == 'nian' || $code == 'nianyue') {
                    $arrlebel[] = $val[$k];
                } else {
                    $arr = mb_str_split(trim(str_replace('<br/>', '', $val[$k])), ($lang == 'en' ? 15 : 5));
                    $arrlebel = array_merge($arrlebel, $arr);
                    $maxcols = max(count($arrlebel), $maxcols);
                }
            }
            $arrlebel = implode("\r\n", $arrlebel);
            $mapcategory[] = $arrlebel;
        }

        foreach((array)$data['data']['wheres'] as $key=>$val) {
            if($titles[$val['code']]) {
                $val['name'] = $titles[$val['code']]['name'];
                array_unshift($val['value'], $langs[$lang]['all']);
                $wheres[] = $val;
            }
        }

        $piecategory = $mapcategory;
        $piedata = $mapdata;
        $mapcategory = array_reverse($mapcategory);
        $mapdata = array_reverse($mapdata);

        $result = array('datatypes'=>array_values($datatypes), 'maxcols'=>$maxcols, 'piecategory'=>$piecategory,  'piedata'=>$piedata,  'tabletitles'=>$tabletitles, 'tabledata'=>$tabledata, 'maptitles'=>$maptitles, 'mapcategory'=>$mapcategory, 'mapdata'=>$mapdata, 'pageModel'=>$data['pageModel'], 'selectwheres'=>$selectwheres, 'wheres'=>$wheres);

        if(empty($querywhere) && $page == 1 && $length == 50) {
            $cache->set($mcode.'_'.$lang, $result, 600);
        }
        $return = array('error'=>0, 'info'=>'success', 'data'=>$result);
        $return = json_encode($return);
        $return = $_GET['callback'] ? $_GET['callback'].'('.$return.')' : $return;
        exit($return);
    }

    public function download() {
        $lang = $_GET['lang'] == 'en' ? 'en' : 'cn';
        $langs = array(
            'cn' => array(
                'downerror' => 'downerror',
                'all' => 'all',
            ),
            'en' => array(
                'downerror' => '下载失败',
                'all' => '全部',
            ),
        );
        header("Content-type: text/html; charset=utf8");
        if(!$this->_userid) {
            echo <<<COUCHBASE_DURABILITY_ETOOMANY
<script type="text/javascript">
alert('请先登录');
window.opener=null;
window.close();
</script>
COUCHBASE_DURABILITY_ETOOMANY;
            exit();
        }

        $page = intval($_GET['page']);
        $page = max($page, 1);
        $length = 50;
        $downtype = $_GET['downtype'] == 'TXT' ? 'txt' : 'cvs';
        $mcode = $_GET['code'];
        $mname = htmlspecialchars(strip_tags($_GET['name']));
        $mcodes = array('M101CUSD','M101DUSD','M102USD', 'M103USD', 'M104USD', 'M105USD', 'M106USD', 'M107USD', 'M108USD', 'M109USD', 'M110USD', 'M111USD', 'M112USD', 'M113USD', 'M114USD', 'M115USD', 'M116USD', 'JCK_HZ', 'JCK_AGB','JCK_AHG','JCK_AFHD');
        if(!in_array($mcode, $mcodes)) {
            echo <<<COUCHBASE_DURABILITY_ETOOMANY
<script type="text/javascript">
alert('{$langs[$lang][downerror]}');
window.opener=null;
window.close();
</script>
COUCHBASE_DURABILITY_ETOOMANY;
            exit();
        }
        $querys = $_GET['query'];
        $querys = json_decode($querys,true);
        $querywhere = array();
        $selectwheres = array();
        if($querys) {
            foreach($querys as $query) {
                $queryarr = strpos($query['value'], '|') ? explode('|', $query['value']) : (array)$query['value'];
                $qa = 0;
                foreach($queryarr as $q) {
                    if($q == $langs[$lang]['all']) {
                        $qa = 1;
                    }
                    !$qa && $querywhere[] = array('code'=>$query['code'], 'value'=>$q);
                    $selectwheres[$query['code']][] = $q;
                }
            }
        }

        $data = $this->getdata($mcode, $querywhere, $page, $length, $lang);

        if(!$data || $data['code'] <> 0) {
            echo <<<COUCHBASE_DURABILITY_ETOOMANY
<script type="text/javascript">
alert('{$langs[$lang][downerror]}');
window.opener=null;
window.close();
</script>
COUCHBASE_DURABILITY_ETOOMANY;
            exit();
        }

        $header = array();
        foreach((array)$data['data']['titles'] as $val) {
            if($val['showTable']) {
                $header[] =  $val['name'];
            }
        }
        $header = $downtype == 'txt' ? implode("\t", $header) : implode(",", $header);
        $body = array();
        foreach((array)$data['data']['datas'] as $val) {
            foreach($val as $k=>$v) {
                $val[$k] = str_replace(',', '', $v);
            }
            $body[] = $downtype == 'txt' ?  implode("\t", $val) :  implode(',', $val);
        }
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

    public function searchcategory() {
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
        $data = $data['data'];
        $menus = array();
        foreach($data as $val) {
            $temp = array(
                'tablename'=> $val['tableName'],
                'tablecode' => strtoupper(str_replace('t_hg_', '', $val['table'])),
                'lable'=> $val['lable'],
                'name'=> $val['name'],
                'attr'=> $val['attrib'],
            );
            $menus[] = $temp;
        }
        $return = array('error'=>0, 'info'=>'success', 'data'=>$menus);
        $return = json_encode($return);
        $return = $_GET['callback'] ? $_GET['callback'].'('.$return.')' : $return;
        exit($return);
    }

    private function searchcategorydata($text, $lang) {
        $url = 'http://192.168.110.110/silkroad/rest/api/rule/'.$text.'/'.$lang.'/queryMenu';
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

    private function getdata($code, $where = array(), $page=1, $length=20, $lang = 'en'){
        $url = 'http://192.168.110.110/silkroad/rest/api/rule/'.$code.'/'.$lang.'/customs';
        $where = array('pageModel'=>array('rows'=>$length, 'startPage'=>1, 'currentPage'=>$page, 'endPage'=>1), 'datas'=>$where);
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
        foreach((array)$data['data']['datas'] as $key=>$val) {
            foreach((array)$data['data']['datas'][$key] as $j=>$v) {
                $data['data']['datas'][$key][$j] = str_replace('&quot;', '"', $v);
            }
        }
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