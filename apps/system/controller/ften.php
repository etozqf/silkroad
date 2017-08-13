<?php
class controller_ften extends system_controller_abstract
{

    public function __construct($app)
    {
        parent::__construct($app);
    }

    public function rank(){
        $lang = $_GET['lang'] == 'en' ? 'en' : 'cn';
        $langs = array(
            'en' => array(
                'dataerror' => 'Data loading failed',
            ),
            'cn' => array(
                'dataerror' => '数据加载失败',
            ),
        );
        $cache = factory::cache();

        $tableid = 'A010';
        $code = '0';

        if(($classes = $cache->get('rank_list_'.$lang)) !== false) {
            $return = array('error'=>0, 'data'=>$classes);
            $return = json_encode($return);
            $return = $_GET['callback'] ? $_GET['callback'].'('.$return.')' : $return;
            exit($return);
        }

        $data = $this->getdata($tableid, $code);
        if(!$data || $data['code']) {
            $return = array('error'=>1, 'info'=>$langs[$lang]['dataerror']);
            $return = json_encode($return);
            $return = $_GET['callback'] ? $_GET['callback'].'('.$return.')' : $return;
            exit($return);
        }
        $classes = array();
        foreach($data['data'] as $val) {
            $url = table('page', '100', 'url'); //classify, url
            $url = $url.'#'.$val['zhengjianhuihangyefenlei'];
            $classes[] = array('name'=>$val['zhengjianhuihangyefenlei'], 'url'=>$url);
        }

        $cache->set('rank_list_'.$lang, $classes, 600);
        $return = array('error'=>0, 'data'=>$classes);
        $return = json_encode($return);
        $return = $_GET['callback'] ? $_GET['callback'].'('.$return.')' : $return;
        exit($return);

    }

    public function search() {
        $lang = $_GET['lang'] == 'en' ? 'en' : 'cn';
        $langs = array(
            'en' => array(
                'dataerror' => 'Data loading failed',
            ),
            'cn' => array(
                'dataerror' => '数据加载失败',
            ),
        );

        $keyword = trim(htmlspecialchars($_GET['query']));
        $list = array();
        if($keyword) {
            $data = $this->getdata('A009', "%25$keyword%25,%25$keyword%25");
            if(!$data || $data['code']) {
                $return = array('error'=>1, 'info'=>$langs[$lang]['dataerror']);
                $return = json_encode($return);
                $return = $_GET['callback'] ? $_GET['callback'].'('.$return.')' : $return;
                exit($return);
            }
            foreach($data['data'] as $val) {
                $url = APP_URL.'?app=system&controller=ften&action=view&gid='.$val['ORGID'];
                $list[] = array('value'=>$val['ZHENGQUANDAIMA'], 'code'=>$val['ZHENGQUANDAIMA'], 'gid'=>$val['ORGID'], 'url'=>$url,  'label'=>$val['ZHENGQUANJIANCHENG'].' ('.$val['ZHENGQUANDAIMA'].') ');
            }
        }
        $return = $list;
        $return = json_encode($return);
        $return = $_GET['callback'] ? $_GET['callback'].'('.$return.')' : $return;
        exit($return);
    }

    public function getcompany() {
        $lang = $_GET['lang'] == 'en' ? 'en' : 'cn';
        $langs = array(
            'en' => array(
                'dataerror' => 'Data loading failed',
            ),
            'cn' => array(
                'dataerror' => '数据加载失败',
            ),
        );
        $classname = trim(htmlspecialchars($_GET['classname']));
        $list = array();
        if($classname) {
            $cache = factory::cache();
            if(($list = $cache->get('company_'.$lang.'_'.$classname)) === false) {
                $data = $this->getdata('A011', $classname);
                if(!$data || $data['code']) {
                    $return = array('error'=>1, 'info'=>$langs[$lang]['dataerror']);
                    $return = json_encode($return);
                    $return = $_GET['callback'] ? $_GET['callback'].'('.$return.')' : $return;
                    exit($return);
                }
                foreach($data['data'] as $val) {
                    $url = APP_URL.'?app=system&controller=ften&action=view&gid='.$val['jigouid'];
                    $list[] = array('name'=>$val['yingwenjiancheng'], 'gid'=>$val['jigouid'], 'url'=>$url);
                }
                $cache->set('company_'.$lang.'_'.$classname, $list, 600);
            }
        }
        $return = array('error'=>0, 'data'=>$list);
        $return = json_encode($return);
        $return = $_GET['callback'] ? $_GET['callback'].'('.$return.')' : $return;
        exit($return);
    }
    
    public function view(){
        $gid = $_GET['gid'];

        if(!$gid) {
            $this->showmessage_cn('Select the company information you want to view!');//请选择要查看的公司信息
        }

        $data = $this->getdata('A001', $gid);
        if(!$data || $data['code']) {
            $this->showmessage_cn('Data loading failed!');//数据请求失败
        }
        if(empty($data['data'])) {
            $this->showmessage_cn('No corresponding company information!');//没有相应的公司信息
        }
        $companyinfo = $data['data'][0];
        $types = array(
            'operate',//操盘必读
            'information', //公司信息
            'sharecapital', //公司股本
            'finance', //财务分析
            'manage', //经营分析
            'mainpositions', //主力持仓
            'fundraising', //公司募资
            'announcement', //公司公告
            'dividend',//分红融资
            'peercomparison', //同行比较
        );
        $type = $_GET['type'];
        $code = $_GET['code'] ? $_GET['code'] : $companyinfo['ZQDM'];
        $daima = $companyinfo['ZQDM'];
        in_array($type, $types) || $type = 'operate';
        $this->template->assign('type', $type);
        $this->template->assign('gid', $gid);
        $this->template->assign('code', $code);
        $this->template->assign('daima', $daima);
        if(in_array($type, $types)) {
            $data = $this->getinfo($type, $gid, $code);
            $this->template->assign('data', $data);
            $this->template->assign('companyinfo', $companyinfo);
            $this->template->display('newcn/f10/view_'.$type.'.html');
        } else {
            $this->showmessage_cn('Select the company information you want to view!');//请选择要查看的公司信息
        }
    }

    private function getinfo($type, $gid, $code){
        $tables = array(
            'operate' => array('A004', 'A021','A022','A023','A024','A025','A026','A027'),
            'information' => array('A028','A029','A030','A031','A032','A033','A034','A035','A036','A037'),
            'sharecapital' => array('A038', 'A039', 'A040'),
            'finance' => array('A041','A042','A043','A044','A045','A046','A047','A048'),
            'manage' => array('A049','A050','A051','A052','A053','A054','A055'),
            'mainpositions' => array('A056'),
            'fundraising' => array('A057','A058','A059','A060'),
            'announcement' => array('A062','A063'),
            'dividend' => array('A064','A065'),
            'peercomparison' => array('A066','A067'),
        );
        $searchcodes = array('A004', 'A023', 'A024', 'A025', 'A029', 'A035', 'A062', 'A063');
        $cache = factory::cache();
        if(($data = $cache->get('ften_view_'.$type.'_'.$gid)) !== false) {
            return $data;
        }
        $data = array();
        foreach((array)$tables[$type] as $tableid) {
            $temp = in_array($tableid, $searchcodes) ? $this->getdata($tableid, $code) : $this->getdata($tableid, $gid);
            if($temp['data']) {
                $data[$tableid] = $temp['data'];
            } else {
                $data[$tableid] = array();
            }
        }
        $cache->set('ften_view_'.$type.'_'.$gid, $data, 300);
        return $data;
    }




    private function getdata($tableid, $code) {
        $url = 'http://192.168.110.110/silkroad/rest/api/rule/'.$tableid.'/'.$code.'/f10Query';
        $jsonData = json_encode(array());
        $headers = array(
            'app_key: xhsl',
            'app_secret: 57ce586290087fb9a1ea856f',
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData),
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($result, true);
        return $data;
    }
}