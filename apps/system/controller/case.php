<?php
/*
  *处理前台案例页面点击属性动态获取文章关联数据
  * author:zhudi
  * date:20160307
*/
class controller_case extends system_controller_abstract
{

	public function __construct($app)
	{
		parent::__construct($app);

	}


	/*
	 *根据属性id获得对应的文章内容
	 */
	public function flashdata()
	{
		$db=factory::db();
		$list=$db->select("select c.contentid as contentid,c.catid as catid,c.thumb as thumb,c.url as url,c.title as title,c.published,c.sourceid,a.author from #table_content as c left join #table_article as a on c.contentid=a.contentid where c.status=6 and  c.catid=250 order by c.published desc limit 0,10");
		foreach($list as $keys=>&$values)
		{
			$values['date']=date('Y-m-d',$values['published']);
			$values['hour']=date('H:i',$values['published']);
			unset($values['published']);
			if($values['sourceid']){
				$values['source'] = table('source',$values['sourceid'],'name');
			}
			// $result=$db->get("select description from #table_article where contentid={$values['contentid']}");
		
			//$description=empty($result)?null:$result['description'];
			$description = str_natcut(description($values['contentid']),90,'...');
			$list[$keys]['description']=$description;
		}

		  if(!empty($list)){
		  	$arr=array('status'=>'success','content'=>$list);
		  }else{
		  	$arr=array('status'=>'error','content'=>'暂时没有内容');
		  }

		  $list=$this->json->encode($arr);
		  echo $_GET['jsoncallback'] ? $_GET['jsoncallback']."($list)" : $list;
	}


	/*
	 *根据属性id获得对应的文章内容
	 */
	public function zhudi()
	{
		
		$proid=!empty($_GET['proid'])?intval($_GET['proid']):0;
		$parentid=52;
		$catid=self::get_sub_category($parentid);
		$db=factory::db();
		$list=$db->select("select c.contentid as contentid,c.catid as catid,c.thumb as thumb,c.url as url,c.title as title,c.published from #table_content as c inner join #table_content_property as cp on c.contentid=cp.contentid where status=6 and cp.proid={$proid} and c.catid in({$catid}) order by published desc limit 0,10");
		foreach($list as $keys=>&$values)
		{
			$values['date']=date('Y-m-d',$values['published']);
			$values['hour']=date('H:i',$values['published']);
			unset($values['published']);
			$result=$db->get("select description from #table_article where contentid={$values['contentid']}");
		
			$description=empty($result)?null:$result['description'];
			$list[$keys]['description']=$description;
		}

		  if(!empty($list)){
		  	$arr=array('status'=>'success','content'=>$list);
		  }else{
		  	$arr=array('status'=>'error','content'=>'暂时没有内容');
		  }

		  $list=$this->json->encode($arr);
		  echo $_GET['jsoncallback'] ? $_GET['jsoncallback']."($list)" : $list;


	}

	/**
	  *查看栏目下是否存在子栏目
	  *Return:String(以逗号分形式返回)
	**/
	private static function get_sub_category($catid)
	{
		static $instance;
		$keys=$catid;
		if(!isset($instance[$keys]))
		{
			$db=factory::db();
			$result=$db->select("select catid from #table_category where catid={$catid} or parentid={$catid}");
			
			foreach($result as $key=>$val)
			{
				$cat_list[]=$val['catid'];
			}
			$catid=implode(",",$cat_list);
			$instance[$keys]=$catid;	
		}

		return $instance[$keys];
	}

	/*
	 *根据属性id获得对应的文章内容
	 */
	public function get_moreat()
	{
		$str=!empty($_GET['str'])?$_GET['str']:0;
		$strid= rtrim($str,','); 
		$parentid=52;
		$catid=self::get_sub_category($parentid);
		$proid=$_GET['proid']?intval($_GET['proid']):0;
		
		if(empty($proid)){
				$idarray=explode(',',$strid);
				$asscon=array();
				$db=factory::db();
				$list=$db->select("select c.contentid as contentid,c.catid as catid,c.thumb as thumb,c.url as url,c.title as title,c.published from #table_content as c where catid=52 and status=6 order by published desc");
				
				foreach($list as $keys=>&$values)
				{
					$values['date']=date('Y-m-d',$values['published']);
					$values['hour']=date('H:i',$values['published']);
					unset($values['published']);
					$result=$db->get("select description from #table_article where contentid={$values['contentid']}");
				
					$description=empty($result)?null:$result['description'];
					$list[$keys]['description']=$description;
					$asscon[]=$values['contentid'];
				}
				 
				 if(!empty($idarray))
				{
					if(count($asscon)>count($idarray))
					{
						$diff=array_diff($asscon,$idarray);
					}
					else
					{
						$diff=array_diff($idarray,$asscon);
					}
				}

					$diff=array_values($diff);
					if(!empty($diff))
					{
						$count=count($diff);
						if($count>=10){
							for($i=0;$i<10;$i++)
							{
								$nextassoc[]=$db->get("select contentid,catid,thumb,url,title,published from #table_content where contentid=$diff[$i]");
							}
						}else{
							for($i=0;$i<$count;$i++)
							{
								$nextassoc[]=$db->get("select contentid,catid,thumb,url,title,published from #table_content where contentid=$diff[$i]");
							}
						}
						
					}else
					{
						$nextassoc=null;
					}

					if(!empty($nextassoc))
					{
						foreach($nextassoc as $keys=>&$values)
						{
							$values['date']=date('Y-m-d',$values['published']);
							$values['hour']=date('H:i',$values['published']);
							unset($values['published']);
							$nextresult=$db->get("select description from #table_article where contentid={$values['contentid']}");
							
							$description=empty($nextresult)?null:$nextresult['description'];
							$nextassoc[$keys]['description']=$description;
							
						}
							$arr=array('status'=>'success','content'=>$nextassoc);
					}	
					 else
					 {
					  	$arr=array('status'=>'error','newsate'=>2,'content'=>'已无更多数据!');
					 }
				   $cloc=$this->json->encode($arr);
		  			echo $_GET['jsoncallback'] ? $_GET['jsoncallback']."($cloc)" : $cloc;

				  exit;

		}
		$db=factory::db();
		$list=$db->select("select c.contentid as contentid,c.catid as catid,c.thumb as thumb,c.url as url,c.title as title,c.published from #table_content as c inner join #table_content_property as cp on c.contentid=cp.contentid where status=6 and cp.proid={$proid} and c.catid in({$catid}) order by c.published desc");
		//已经存在页面的数组
		$idarray=explode(',',$strid);
		$asscon=array();
		foreach($list as $keys=>&$values)
		{
			$values['date']=date('Y-m-d',$values['published']);
			$values['hour']=date('H:i',$values['published']);
			unset($values['published']);
			$result=$db->get("select description from #table_article where contentid={$values['contentid']}");
		
			$description=empty($result)?null:$result['description'];
			$list[$keys]['description']=$description;
			$asscon[]=$values['contentid'];
		}
		if(!empty($idarray)){
			if(count($asscon)>count($idarray))
			{
				$diff=array_diff($asscon,$idarray);
			}
			else
			{
				$diff=array_diff($idarray,$asscon);
			}
		}

		$diff=array_values($diff);
		if(!empty($diff))
		{
			$count=count($diff);
			if($count>=10){
				for($i=0;$i<10;$i++)
				{
					$nextassoc[]=$db->get("select contentid,catid,thumb,url,title,published from #table_content where contentid=$diff[$i]");
				}
			}
			else
			{
				for($i=0;$i<$count;$i++)
				{
					$nextassoc[]=$db->get("select contentid,catid,thumb,url,title,published from #table_content where contentid=$diff[$i]");
				}
			}
						
		}
		else
		{
			$nextassoc=null;
		}
		if(!empty($nextassoc))
		{
				foreach($nextassoc as $keys=>&$values)
				{
					$values['date']=date('Y-m-d',$values['published']);
					$values['hour']=date('H:i',$values['published']);
					unset($values['published']);
					$nextresult=$db->get("select description from #table_article where contentid={$values['contentid']}");
					
					$description=empty($nextresult)?null:$nextresult['description'];
					$nextassoc[$keys]['description']=$description;
					
				}
					$arr=array('status'=>'success','content'=>$nextassoc);
		}
		  
		 else
		 {
		  $arr=array('status'=>'error','content'=>'已无更多数据!');
		 }

		  $cloc=$this->json->encode($arr);
		  echo $_GET['jsoncallback'] ? $_GET['jsoncallback']."($cloc)" : $cloc;
		 

	}

	public function index()
	{	
		//先创建内容html文件
		$this->create();
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$db=&factory::db();
		//获取子栏目信息
		$sub_type = subcategory(249);
		$catids = array();
		foreach($sub_type as $k=>$v){
			$catids[] = $v['catid']; 
		}
		$catidss = implode(',',$catids);
		$res = $db->select("select count(contentid) as total from cmstop_content where catid in ($catidss)");
		$total = $res[0]['total'];
		$offset = ($page-1)*10;

		$list=$db->select("select c.contentid as contentid,c.catid as catid,c.thumb as thumb,c.url as url,c.title as title,c.published,c.sourceid,a.author from #table_content as c left join #table_article as a on c.contentid=a.contentid where c.status=6 and  c.catid in ($catidss) order by c.published desc limit $offset,10");
		foreach($list as $keys=>&$values)
		{
			$values['date']=date('Y-m-d',$values['published']);
			$values['hour']=date('H:i',$values['published']);
			unset($values['published']);
			if($values['sourceid']){
				$values['source'] = table('source',$values['sourceid'],'name');
			}
			// $result=$db->get("select description from #table_article where contentid={$values['contentid']}");
		
			//$description=empty($result)?null:$result['description'];
			$description = str_natcut(description($values['contentid']),90,'...');
			$list[$keys]['description']=$description;
		}
		
		
		// $offset = ($page-1)*9;
		// $data = array();
		// foreach($sub_type as $k=>$v){
		// 	$total=$db->select("select count(spaceid) total from cmstop_space where sub_type=".$v['sid']." and status=3");
		// 	$data["$k"]['total']=$total[0]['total'];
		// 	$result=$db->select("select * from cmstop_space where sub_type=".$v['sid']." and status=3 order by sort limit $offset,9");
		// 	$data["$k"]["data"] = $result;
		// }
		
		$this->template->assign('page', $page);
		$this->template->assign('pagesize', 10);
		$this->template->assign('data',$list);
		$this->template->assign('total',$total);
		$this->template->assign('sub_type',$sub_type);
		$this->template->display('case/case.html');

	}

	public function index_show()
	{
		$db=&factory::db();
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$sub = $_GET['sub_type'];
		$sub_type = subcategory(249);
		$catids = array();
		foreach($sub_type as $k=>$v){
			$catids[] = $v['catid']; 
		}
		$catidss = implode(',',$catids);
		$offset = ($page-1)*10;
		$data = array();
		$res = $db->select("select count(contentid) as total from cmstop_content where catid=$sub");
		$total = $res[0]['total'];
		// $total=$db->select("select count(spaceid) total from cmstop_space where sub_type=$sub and status=3");
		// $data['total']=$total[0]['total'];
		// $result=$db->select("select * from cmstop_space where sub_type=$sub and status=3 order by sort limit $offset,9");
		// 	$data["data"] = $result;
		if($sub=='all'){
			$list=$db->select("select c.contentid as contentid,c.catid as catid,c.thumb as thumb,c.url as url,c.title as title,c.published,c.sourceid,a.author from #table_content as c left join #table_article as a on c.contentid=a.contentid where c.status=6 and  c.catid in ($catidss) order by c.published desc limit $offset,10");

		}else{
			$list=$db->select("select c.contentid as contentid,c.catid as catid,c.thumb as thumb,c.url as url,c.title as title,c.published,c.sourceid,a.author from #table_content as c left join #table_article as a on c.contentid=a.contentid where c.status=6 and  c.catid=$sub order by c.published desc limit $offset,10");

		}
		foreach($list as $keys=>&$values)
		{
			$values['date']=date('Y-m-d',$values['published']);
			$values['hour']=date('H:i',$values['published']);
			unset($values['published']);
			if($values['sourceid']){
				$values['source'] = table('source',$values['sourceid'],'name');
			}
			// $result=$db->get("select description from #table_article where contentid={$values['contentid']}");
		
			//$description=empty($result)?null:$result['description'];
			$description = str_natcut(description($values['contentid']),90,'...');
			$list[$keys]['description']=$description;
		}
		foreach($sub_type as $k=>$v){
			if($sub==$v['catid']){
				$Subtype = $v['abbr'];
			}
		}
		// if($sub==1){
		// 	$Subtype = 'China';
		// }else if($sub==2){
		// 	$Subtype = 'Europe';
		// }else if($sub==3){
		// 	$Subtype = 'Asia';
		// }else if($sub==4){
		// 	$Subtype = 'America';
		// }
		$this->template->assign('page', $page);
		$this->template->assign('sub', $sub);
		$this->template->assign('Subtype', $Subtype);
		$this->template->assign('pagesize', 10);
		$this->template->assign('data',$list);
		$this->template->assign('total',$total);

		$this->template->assign('sub_type',$sub_type);
		$this->template->display('case/case.html');

	}

	public function create()
	{
		$cate = subcategory(249);
		foreach($cate as $k=>$v){
			$dest_file = 'F:\project\silkroad\code\templates\silkroad\case\sub_type\/'.$v['abbr'].'.html';
			$source_file = 'F:\project\silkroad\code\templates\silkroad\case\sub_type\index.html';
			if(!file_exists($dest_file)){
				copy($source_file,$dest_file);
			}
		}
	}


}