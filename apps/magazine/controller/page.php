<?php
/**
 * 往期回顾管理
 *
 * @aca 分页管理
 */
 class controller_page extends magazine_controller_abstract
{
    private $edition,$magazine,$page,$pagesize,$mid,$fields,$order,$limit;

    function __construct($app)
    {
        parent::__construct($app);
        
    }

    /**
     * 分页
     *
     * @aca 浏览
     */
    function pagesize(){
        $db = & factory::db();
        $this->page = isset($_GET['page']) ? max(intval($_GET['page']),1) : 1;
        $this->pagesize = empty($_GET['pagesize']) ? 10 : intval($_GET['pagesize']);
        $this->limit = ($page-1)*$pagesize;
        $this->mid = intval($_GET['mid']);
        $this->where['catid'] =  empty($_GET['catid']) ? 51 : intval($_GET['catid']);
        $data = array();
        //查询数据结果
        $sql = "select * from #table_magazine_edition where mid = $this->mid ORDER BY publish DESC";
        $this->data = $db->page($sql,$this->page,$this->pagesize);
        $alias = table('magazine',$this->mid,'alias');
        foreach($this->data as $k=>$v){
            $this->data[$k]['alias'] = $alias;
        }
       
        //查询数据总条数
        $tsql = "select count(eid) total from #table_magazine_edition where mid = $this->mid";
        if (!is_null($this->page)) $this->total = $db->select($tsql)[0]['total'];
        $data['total'] = $this->total;
        $data['data'] = $this->data;
        // $data['data'] = $db->select("select * from cmstop_magazine_edition where mid = $this->mid ORDER BY publish DESC limit $this->limit,$this->pagesize");
        $result = $this->json->encode($data);
        echo ($_GET['jsoncallback']."(".$result.")");
    }

}
?>
