<?php
class controller_information extends system_controller_abstract
{

	public function __construct($app)
	{
		parent::__construct($app);

	}

 /*
  *处理前台属性动态获取与文章关联数据
  * author:zhudi
  * date:201603111
  */

	public function gettopnews()
	{
		$db=factory::db();
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$nthpage=$page-1;
		$pagesize=empty($_GET['pagesize'])?10:intval($_GET['pagesize']);
		$list=$db->select("select contentid,url,title,weight,published from #table_content  where status=6 and catid=25 order by weight desc limit {$nthpage},{$pagesize}");
		$total=count($list);
		$pages = ceil($total/$pagesize);
		if($page ==$pagesize)
		{
			$i=0;
		}
		else
		{
			$i=($page-1)*$pagesize;
		}
		$strpage=pages_case($total,$page,$pagesize,2,null);
		$strpage.='<li style="color: #333;"><em>'.$total.'</em>records</li>';
		$list=$db->select("select contentid,url,title,weight,published from #table_content  where catid=25 order by weight desc limit {$i},{$pagesize}");
		foreach($list as $keys=>$values)
		{
			$list[$keys]['date']=date('Y-m-d',$values['published']);
			$list[$keys]['hour']=date('H:i',$values['published']);
			unset($values['published']);
		}
		if(!empty($list))
		  {
		  	$arr=array('status'=>'success','content'=>$list,'pages'=>$pages,'strpage'=>$strpage);
		  }
		  else{
		  	$arr=array('status'=>'error','content'=>'暂时没有内容');
		  }
		  $list=$this->json->encode($arr);
		echo $_GET['jsoncallback']?$_GET['jsoncallback']."($list)":$list;
	}

	/*
  *处理前台属性动态获取与文章关联数据
  * author:zhudi
  * date:201603111
  */

	public function getallnews()
	{
		$db=factory::db();
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$nthpage=$page-1;
		$pagesize=empty($_GET['pagesize'])?10:intval($_GET['pagesize']);
		$list=$db->select("select contentid,url,title,weight,published from #table_content where status=6 and catid=25 order by published desc limit {$nthpage},{$pagesize}");
		$total=count($list);
		$pages = ceil($total/$pagesize);
		if($page ==$pagesize)
		{
			$i=0;
		}
		else
		{
			$i=($page-1)*$pagesize;
		}
		$strpage=pages_case($total,$page,$pagesize,2,null);
		$strpage.='<li style="color: #333;"><em>'.$total.'</em>records</li>';
		$list=$db->select("select contentid,url,title,weight,published from #table_content  where status=6 and catid=25 order by published desc limit {$i},{$pagesize}");
		foreach($list as $keys=>$values)
		{
			$list[$keys]['date']=date('Y-m-d',$values['published']);
			$list[$keys]['hour']=date('H:i',$values['published']);
			unset($values['published']);
		}
		if(!empty($list))
		  {
		  	$arr=array('status'=>'success','content'=>$list,'pages'=>$pages,'strpage'=>$strpage);
		  }
		  else{
		  	$arr=array('status'=>'error','content'=>'暂时没有内容');
		  }
		  $list=$this->json->encode($arr);
		echo $_GET['jsoncallback']?$_GET['jsoncallback']."($list)":$list;
	}



   /*
  	*处理前台属性动态获取与文章关联数据
  	* author:zhudi
  	* date:201603111
  	*/
	public function getArticle()
	{
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);//当前页数
		$pagesize=empty($_GET['pagesize'])?10:intval($_GET['pagesize']);
		$proid=$_GET['proid'];
		$db=factory::db();	
		$list=$db->select("select c.contentid as contentid,c.url as url,c.title as title,c.published from #table_content as c inner join #table_content_property as cp on c.contentid=cp.contentid where status=6 and cp.proid={$proid} and c.catid=25 order by published desc"); 
		$total=count($list);
		$pages = ceil($total/$pagesize);
		if($page ==$pagesize)
		{
			$i=0;
		}
		else
		{
			$i=($page-1)*$pagesize;
		}
		$strpage=pages_case($total,$page,$pagesize,2,null);
		$strpage.='<li style="color: #333;"><em>'.$total.'</em>records</li>';
		$list=$db->select("select c.contentid as contentid,c.url as url,c.title as title,c.published from #table_content as c inner join #table_content_property as cp on c.contentid=cp.contentid where status=6 and cp.proid={$proid} and c.catid=25 order by published desc limit {$i},{$pagesize}"); 
		foreach($list as $keys=>$values)
		{
			$list[$keys]['date']=date('Y-m-d',$values['published']);
			$list[$keys]['hour']=date('H:i',$values['published']);
			unset($values['published']);
		}
		 if(!empty($list)){
		  	$arr=array('status'=>'success','content'=>$list,'proid'=>$proid,'pages'=>$pages,'strpage'=>$strpage);
		  }else{
		  	$arr=array('status'=>'error','content'=>'暂时没有内容');
		  }
		  $list=$this->json->encode($arr);
		  echo $_GET['jsoncallback']?$_GET['jsoncallback']."($list)":$list;
	}
}