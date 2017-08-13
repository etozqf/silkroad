<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2017/6/3
 * Time: 15:08
 */

class controller_getcontent extends article_controller_abstract
{
    public function __construct($app)
    {
        parent::__construct($app);
        $this->legal = loader::model('getcontent');

    }


    public function index()
    {
        $catid = $_GET['catid'];
        $page = empty($_GET['page']) ? 1 : intval($_GET['page']);
        $pagesize = 8;
        $data = $this->legal->getdata($catid);
        $listdata = $this->legal->getList($catid,$page,$pagesize);

        $this->template->assign('data',$data);
        $this->template->assign('list',$listdata['list']);
        $this->template->assign('total',$listdata['total']);
        $this->template->assign('page',$page);
        $this->template->assign('pagesize',$pagesize);
        $this->template->display('cn/list/11.html');

//        echo "<pre>";
//        echo $data['countryname'],$legal;
//        var_dump($list);
//        var_dump($data);
    }
}
