<?php
class controller_encase extends system_controller_abstract
{

	public function __construct($app)
	{
		parent::__construct($app);

	}


	/*
	 *处理前台属性动态获取与文章关联数据
	 * author:zhudi
	 * date:20160310
	 */

	public function engetArticle()
	{
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$proid=$_GET['proid'];
		$db=factory::db();
		$number=$_GET['number'];
		$result=$db->select("select c.contentid as contentid,c.thumb as thumb,c.url as url,c.title as title,c.published from #table_content as c inner join #table_content_property as cp on c.contentid=cp.contentid where cp.proid={$proid} and c.catid=28 order by published desc"); 
		$total=count($result);
		$pages = ceil($total/10);
		if($page ==1)
		{
			$i=0;
		}
		else
		{
			$i=($page-1)*10;
		}
		$strpage=pages_case($total,$page,10,2,null);
		$strpage.='<li style="color: #333;"><em>'.$total.'</em>records</li>';
		$list=$db->select("select c.contentid as contentid,c.thumb as thumb,c.url as url,c.title as title,c.published from #table_content as c inner join #table_content_property as cp on c.contentid=cp.contentid where cp.proid={$proid} and c.catid=28 order by published desc limit {$i},10");
		foreach($list as $keys=>$values)
		{
			$list[$keys]['date']=date('Y-m-d',$values['published']);
			$list[$keys]['hour']=date('H:i',$values['published']);
			unset($values['published']);
			$result=$db->get("select description from #table_article where contentid={$values['contentid']}");
		
			$description=empty($result)?null:$result['description'];
			$list[$keys]['description']=$description;
		}
		 if(!empty($list)){
		  	$arr=array('status'=>'success','number'=>$number,'proid'=>$proid,'content'=>$list,'pages'=>$pages,'strpage'=>$strpage);
		  }else{
		  	$arr=array('status'=>'error','number'=>$number,'content'=>'No content for the time being');
		  }
		  $list=$this->json->encode($arr);
		  echo $_GET['jsoncallback']?$_GET['jsoncallback']."($list)":$list;
	}
}