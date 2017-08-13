<?php
class controller_api_record extends api_controller_abstract
{
	function __construct($app)
	{
		parent::__construct($app);
	}

	/*
     * 查询得到cmstop_user_behavior(用户行为跟踪记录) 表中的数据
     * 提供三种查询模式：
     * 1、按当天查询，时间范围从00:00-23:59:59 根据参数mark值进行判断，mark=1时，代表查询当天
       2、按指定时间范围查询，根据传值strt与end，进行时间判断
       3、普通查询，进行分页查询，每次查询500条数据。
	*/
	public function index()
	{
		$db=factory::db();
		$mark = !empty($_REQUEST['mark'])?'1':'';
		if (!empty($_GET['strt']) && !empty($_GET['end']))
		{
			$where="where creatime between ".$_GET['strt']." and ".$_GET['end']."";
		}
		else if (!empty($_POST['strt']) && !empty($_POST['end'])){
			$where="where creatime between ".$_POST['strt']." and ".$_POST['end']."";
		}
		else
		{
			$where="";	
		}
		
		$page=!empty($_REQUEST['page'])?intval($_REQUEST['page']):1;	//默认从第一页开始查询
		$size=!empty($_REQUEST['size'])?intval($_REQUEST['size']):100;  //默认每次查询100条
		
		$pageStart=($page-1)*$size;
		$limit=" limit $pageStart,$size";
		$list=$db->select("select id,start_time,end_time,userid,username,userip,user_country,user_province,terminal,catname,catname_top,contentid,title,tags,url,site_type,remark,creatime from #table_user_behavior $where $limit");
		$total=$db->select("select count(id) as pageSize from #table_user_behavior $where");
		$totalPage=ceil($total[0]['pageSize']/$size);
		if(!empty($list))
		{
			$arr=array('status'=>true,'page'=>$page,'totalPage'=>$totalPage,'data'=>$list);
		}
		else
		{
			$arr=array('status'=>false,'data'=>'no data');
		}

		echo $this->json->encode($arr);

	}
	


}