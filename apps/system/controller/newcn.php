<?php
/*
  *处理前台新英文站列表页数据分页
  * author:yangya
  * date:20161026
*/
class controller_newcn extends system_controller_abstract
{

	public function __construct($app)
	{
		parent::__construct($app);
		$this->cache = factory::cache();

	}

	public function news_list()
	{	
		
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$proid = $_GET['proid'];
		$db=&factory::db();
		//获取子栏目信息
		$childids = table('category',263,'childids');
		$catids = array();
		if($childids){
			$sub_type = subcategory(263);
			foreach($sub_type as $k=>$v){
				$catids[] = $v['catid']; 
			}
			$catidss = implode(',',$catids);
		}else{
			$catidss = 263;
		}
		$res = $db->select("select count(contentid) as total from cmstop_content where catid in ($catidss) and contentid in (select contentid from cmstop_content_property where proid=$proid)");
		$total = $res[0]['total'];
		$offset = ($page-1)*12;
		$sql = "select title,url,published from cmstop_content  where status=6 and catid in ($catidss) and contentid in (select contentid from cmstop_content_property where proid=$proid) order by published desc limit $offset,12";
		$list=$db->select($sql);
		$this->template->assign('page', $page);
		$this->template->assign('pagesize', 12);
		$this->template->assign('data',$list);
		$this->template->assign('proid',$proid);
		$this->template->assign('total',$total);
		$this->template->display('newcn/system/news-list.html');
		

	}

	public function views_list()
	{	
		
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$proid = $_GET['proid'];
		$db=&factory::db();
		//获取子栏目信息
		$childids = table('category',261,'childids');
		$catids = array();
		if($childids){
			$sub_type = subcategory(261);
			foreach($sub_type as $k=>$v){
				$catids[] = $v['catid']; 
			}
			$catidss = implode(',',$catids);
		}else{
			$catidss = 261;
		}
		$res = $db->select("select count(contentid) as total from cmstop_content where catid in ($catidss) and contentid in (select contentid from cmstop_content_property where proid=$proid)");
		$total = $res[0]['total'];
		$offset = ($page-1)*12;
		$sql = "select title,url,published from cmstop_content  where status=6 and catid in ($catidss) and contentid in (select contentid from cmstop_content_property where proid=$proid) order by published desc limit $offset,12";
		$list=$db->select($sql);
		$this->template->assign('page', $page);
		$this->template->assign('pagesize', 12);
		$this->template->assign('data',$list);
		if($proid==12){
			$this->template->assign('proid','China Voice');
		}else if($proid==287){
			$this->template->assign('proid','Overseas Think-tanks');
		}
		$this->template->assign('total',$total);
		$this->template->display('newcn/system/views-list.html');
		

	}

	public function projects()
	{
		$this->template->display('newcn/projects.html');
	}

	public function projects_select()
	{
		$proids = rtrim($_GET['proids'],';');
		$proids_arr = explode(';',$proids);
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$db = & factory::db();
		$list = array();
		if(count($proids_arr)>1){
			foreach($proids_arr as $k=>$v){
				$list = $db->select("select contentid from cmstop_content where status=6 and catid=264 and contentid in (select contentid from cmstop_content_property where proid in ($v)) order by published desc");
				if($list){
					foreach($list as $key=>$val){
						$res[$k][] = $val['contentid'];
					}
				}else{
					$res[$k][] = '';
				}
			}
			$diff_contentids = $this->diff($res);
		}else{
			$sql = "select contentid from cmstop_content where status=6 and catid=264 and contentid in (select contentid from cmstop_content_property where proid in ($proids_arr[0])) order by published desc";
			$list=$db->select($sql);
			foreach($list as $k=>$v){
				$diff_contentids[] = $v['contentid'];
			}
		}
		$res_contentids = implode(',',$diff_contentids);
		$total = count($diff_contentids);
		$offset = ($page-1)*12;
		$sql = "select title,url,published from cmstop_content where contentid in ($res_contentids) order by published desc limit $offset,12";
		$list=$db->select($sql);
		foreach($list as $k=>$v){
				$list[$k]['published'] = date("F j, Y",$v['published']);
		}
		$result['data'] = $list;
		$result['total'] = $total;
		$data = $this->json->encode($result);
		$data = (isset($_GET['jsoncallback']))? $_GET['jsoncallback']."($data);": $data;
		echo $data;

	}

	public function diff($arr1)
	{
     $arr = array_intersect($arr1[0],$arr1[1]);
        for($i=2;$i<count($arr1);$i++){
              $min = array_intersect($arr1[$i],$arr);
              $arr = $min;
        } 
        return $arr;  
  }
	
	public function category()
	{	
		$catid = isset($_GET['catid']) ? intval($_GET['catid']) : 1;
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$proid = $_GET['proid'];
		$db=&factory::db();
		//获取子栏目信息
		// $childids = table('category',263,'childids');
		// $catids = array();
		// if($childids){
		// 	$sub_type = subcategory(263);
		// 	foreach($sub_type as $k=>$v){
		// 		$catids[] = $v['catid']; 
		// 	}
		// 	$catidss = implode(',',$catids);
		// }else{
		// 	$catidss = 263;
		// }
		$res = $db->select("select count(contentid) as total from cmstop_content where catid=$catid and status=6");
		$total = $res[0]['total'];
		// $offset = ($page-1)*12+1;
		// $sql = "select title,url,published from cmstop_content  where status=6 and catid=$catid and status=6 order by published desc limit $offset,12";
		// $list=$db->select($sql);
		$this->template->assign('page', $page);
		$this->template->assign('pagesize', 12);
		// $this->template->assign('data',$list);
		$this->template->assign('catid',$catid);
		$this->template->assign('total',$total);
		$this->template->display('newcn/system/category.html');
		

	}

	public function announcement_list()
	{	
		
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$db=&factory::db();
		$res = $db->select("select count(contentid) as total from cmstop_content where catid=275 and status=6");
		$total = $res[0]['total'];
		$offset = ($page-1)*10;
		$sql = "select title,url,published from cmstop_content where status=6 and catid=275 order by published desc limit $offset,10";
		$list=$db->select($sql);
		$this->template->assign('page', $page);
		$this->template->assign('pagesize', 10);
		$this->template->assign('data',$list);
		$this->template->assign('total',$total);
		$this->template->display('newcn/page/announcement-list.html');
		

	}
	
	public function events_list()
	{	
		
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$db=&factory::db();
		$res = $db->select("select count(contentid) as total from cmstop_content where catid=274 and status=6");
		$total = $res[0]['total'];
		$offset = ($page-1)*10;
		$sql = "select title,url,published from cmstop_content where status=6 and catid=274 order by published desc limit $offset,10";
		$list=$db->select($sql);
		$this->template->assign('page', $page);
		$this->template->assign('pagesize', 10);
		$this->template->assign('data',$list);
		$this->template->assign('total',$total);
		$this->template->display('newcn/page/events-list.html');
		

	}

}