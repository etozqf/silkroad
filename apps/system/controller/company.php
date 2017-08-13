<?php
class controller_company extends system_controller_abstract {
    public function __construct($app) {
        parent::__construct($app);
    }


    public function getdata() {
        $catid = 271;
        $page = intval($_GET['page']);
        $page = max(1, $page);
        $length = 12;
        $start = ($page - 1) * $length;
        $type = $_GET['type'] ? $_GET['type'] : 'all';
        $types = array('all', 'agriculture', 'mining', 'manufacutring', 'power', 'publicutility', 'construction', 'infrastructure', 'logistics', 'it', 'realestate', 'mediaculture', 'finance');

        if(!in_array($type, $types)) {
            $json = json_encode(array('error'=>1, 'info'=>'error'));
            $json = $_GET['callback'] ? $_GET['callback'].'('.$json.')' : $json;
            exit($json);
        }

        $catids = table('category', $catid, 'childids');
        $catidstr = $catids ? "c.catid IN ($catids)" : "c.catid=$catid";

        $proids = array(
            'agriculture' => array(67, 68, 69, 71),
            'mining' => array(109, 111, 112, 113, 115, 117, 326),
            'manufacutring' => array(16, 17, 18, 19, 20, 21, 22, 23, 24, 51),
            'power' => array(81, 83, 85, 87, 88),
            'publicutility' => array(327),
            'construction' => array(328),
            'infrastructure' => array(54, 56, 57, 59, 61, 63, 64),
            'logistics' => array(140),
            'it' => array(134),
            'realestate' => array(133),
            'mediaculture' => array(136),
            'finance' => array(122, 123, 127, 130, 131),
        );

        $proids = $proids[$type];

        //all的第一页，使用缓存
        if(!$proids && $page == 1) {
            $cache = factory::cache();
            $json = $cache->get('company_content_default');
            if($json !== false) {
                $json = $_GET['callback'] ? $_GET['callback'].'('.$json.')' : $json;
                exit($json);
            }
        }

        if(!$proids) {
            $sql = "SELECT c.title, c.url, c.contentid, c.published FROM #table_content AS c WHERE c.status=6 AND $catidstr ORDER BY c.published DESC LIMIT $start, $length";

            $countsql = "SELECT count(1) AS count FROM #table_content AS c WHERE c.status=6 AND $catidstr";

        } else {
            $proidstr = implode(',', $proids);
            $sql = "SELECT c.title, c.url, c.contentid, c.published FROM #table_content AS c INNER JOIN #table_content_property AS cp ON c.contentid=cp.contentid WHERE c.status=6 AND $catidstr AND cp.proid IN($proidstr) ORDER BY c.published DESC LIMIT $start, $length";

            $countsql = "SELECT count(1) AS count FROM #table_content AS c INNER JOIN #table_content_property AS cp ON c.contentid=cp.contentid WHERE c.status=6 AND $catidstr AND cp.proid IN($proidstr)";
        }
        $db = factory::db();
        $total = $db->get($countsql);
        $total = intval($total['count']);
        $contents = array();
        if($total) {
            $contents = $db->select($sql);
        }
        foreach($contents as $k=>$val) {
            $contents[$k]['published'] = date('M d,Y', $val['published']);
        }

        $json = array('error'=>0, 'info'=>'success', 'data'=>array('list'=>$contents, 'current'=>$page, 'total'=>$total, 'length'=>$length));
        $json = json_encode($json);

        //all的第一页，使用缓存，缓存10分钟
        if(!$proids && $page == 1) {
            $cache = factory::cache();
            $cache->set('company_content_default', $json, 600);
        }

        $json = $_GET['callback'] ? $_GET['callback'].'('.$json.')' : $json;
        exit($json);

    }

}