<?php
/**
 * Created by PhpStorm.
 * User: xia
 * Date: 2017/6/3
 * Time: 15:08
 */

class controller_legal extends article_controller_abstract
{
    public function __construct($app)
    {
        parent::__construct($app);
        $this->legal = loader::model('legal');
        $this->cache = factory::cache();

    }


    public function index()
    {
        $country = $_GET['country'];
        $legal   = $_GET['legal'];
        $page = empty($_GET['page']) ? 1 : intval($_GET['page']);
        $pagesize = 8;

//        $data = $this->legal->getCountry($country,$legal);
//        $listdata = $this->legal->getList($data['countryname'],$legal,$page,$pagesize);

        /*添加缓存*/
        $key = "legal_data".$country.$legal;
        $legal_data = $this->cache->get($key);
        if(empty($legal_data))
        {
            $data = $this->legal->getCountry($country,$legal);
            $listdata = $this->legal->getList($data['countryname'],$legal,$page,$pagesize);
            $legal_data['data'] = $data;
            $legal_data['listdata'] = $listdata;
            $this->cache->set($key,$legal_data,600);
        }
        else
        {
            $data = $legal_data['data'];
            $listdata = $legal_data['listdata'];
        }





        $this->template->assign('country',$data['country']);
        $this->template->assign('countryname',$data['countryname']);
        $this->template->assign('legal',$data['legal']);
        $this->template->assign('list',$listdata['list']);
        $this->template->assign('total',$listdata['total']);
        $this->template->assign('page',$page);
        $this->template->assign('pagesize',$pagesize);
        $this->template->display('legal/law-list.html');
    }
}