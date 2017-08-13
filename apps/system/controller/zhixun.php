<?php
/*
  *处理前台智讯首页页面tab数据分页
  * author:yangya
  * date:20161011
*/
class controller_zhixun extends system_controller_abstract
{

	public function __construct($app)
	{
		parent::__construct($app);

	}

	public function index()
	{	
		//先创建内容html文件
		// $this->create();
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$db=&factory::db();
		//获取子栏目信息
		$sub_type = subcategory(166);
		
		$res = $db->select("select count(contentid) as total from cmstop_content where catid=204 and status=6");
		$total = $res[0]['total'];
		$offset = ($page-1)*9;

		$list=$db->select("select c.contentid as contentid,c.catid as catid,c.modelid as modelid,c.thumb as thumb,c.url as url,c.title as title,c.published,c.sourceid,a.author from #table_content as c left join #table_article as a on c.contentid=a.contentid where c.status=6 and  c.catid=204 order by c.published desc limit $offset,9");
		
		
		$this->template->assign('page', $page);
		$this->template->assign('pagesize', 9);
		$this->template->assign('data',$list);
		$this->template->assign('total',$total);
		$this->template->assign('sub_type',$sub_type);
		$this->template->display('zhixun/zk-index.html');

	}

	public function index_show()
	{
		$db=&factory::db();
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$sub = $_GET['sub_type'];
		$sub_type = subcategory(166);
		
		$offset = ($page-1)*9;
		$data = array();
		$res = $db->select("select count(contentid) as total from cmstop_content where catid=$sub and status=6");
		$total = $res[0]['total'];
		// $total=$db->select("select count(spaceid) total from cmstop_space where sub_type=$sub and status=3");
		// $data['total']=$total[0]['total'];
		// $result=$db->select("select * from cmstop_space where sub_type=$sub and status=3 order by sort limit $offset,9");
		// 	$data["data"] = $result;
		$list=$db->select("select c.contentid as contentid,c.catid as catid,c.modelid as modelid,c.thumb as thumb,c.url as url,c.title as title,c.published,c.sourceid,a.author from #table_content as c left join #table_article as a on c.contentid=a.contentid where c.status=6 and  c.catid=$sub order by c.published desc limit $offset,9");
		$this->template->assign('page', $page);
		$this->template->assign('sub', $sub);
		
		$this->template->assign('pagesize', 9);
		$this->template->assign('data',$list);
		$this->template->assign('total',$total);

		$this->template->assign('sub_type',$sub_type);
		$this->template->display('zhixun/zk-index.html');
		
	}

	


}