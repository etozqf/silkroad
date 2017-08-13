<?php
define('CMSTOP_START_TIME', microtime(true));
define('RUN_CMSTOP', true);
define('IN_ADMIN', 1);
require '../../cmstop.php';

$config=config("2017push","zx");
$db=factory::db();
foreach($config as $key=>$value){
	/*得到4537里指定栏目的数据*/
	$dbList=getCategoryList($key,$value);
}


function getCategoryList($catid,$free_catid)
{
	$pagesize=200;	//每次查询数量
	global $db;
	/*记录总条数*/
	$total=$db->select("select count(contentid) as count from #table_content where catid=$catid and status=6");
		
	if(empty($total)) return false;

	$count=ceil($total[0]['count']/$pagesize);
	
	if($count<1){
		return false;
	}

	/*分批次查询请求，每次执行pagesize条数*/

	for($i=1;$i<=$count;$i++)
	{
		$page=$i-1;
		$pageStart=$page*$pagesize;
		$limit=" limit $pageStart".",".$pagesize;
		$list=$db->select("select c.*,a.* from #table_content as c inner join #table_article as a on c.contentid=a.contentid and catid=$catid $limit");

		/*遍历数组,进行逐条插入4500数据表*/
		getDetail($list,$free_catid);

		
	}

}

/*进行数组遍历，并逐条插入4500表*/
function getDetail($list,$catid){
	global $db;
	foreach($list as $key=>$val)
	{
			$val['catid']=$catid;
			$val['saveremoteimage']=1;
			$val['publishedby']=1;
			$val['published']=date("Y-m-d H:i",$val['published']);
			$val['status']=6;
			$sourceid = $val['sourceid'];
			$source=$db->get("select name from cmstop_source where sourceid=$sourceid");
			$val['source']=$source['name'];	
			unset($val['sourceid']);
			if(!empty($val['thumb']))
			{
				$val['thumb']=UPLOAD_URL.$val['thumb'];
			}

			$result=push_article($val);
	}
}

	/*将数据通过添加文章接口，推送到免费站*/
	function push_article($post)
	{
		$gateway="http://api.silkroad.news.cn/";
		$auth_key="f766b428cace0dddbd52050e3cc56a6f";
		$auth_secret="206cf2d4a060dc1c80278498da051d74";
		$api_url="?app=article&controller=article&action=zx_add";
		ksort($post);
		$sign=md5(http_build_query($post).$auth_secret);
		$request_url = $gateway.$api_url.'&key='.$auth_key.'&sign='.$sign;
		
		$answer=request($request_url,$post);	
		if($answer['httpcode']==200)
		{
			$content=json_decode($answer['content'],true);
			if($content['state']){
				echo $content['contentid']."success"."<br/>";
			}else{
				echo $content['error']."<br/>";
			}
		}
		else
		{
			echo $answer['httpcode']."error"."<br/>";
		}

		
	}
