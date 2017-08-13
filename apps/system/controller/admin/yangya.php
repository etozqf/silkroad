<?php
/*
  + 从4537站点推送数据至4500站
  +使用的栏目配置文件在config/2017push.php中
*/
class controller_admin_yangya extends system_controller_abstract{
	
	public $gateway,$auth_key,$auth_secret,$api_url,$catid_list,$pagesize;

	public function __construct($app)
	{
		parent::__construct($app);
		$this->gateway="http://api.silkroad.news.cn/";
		$this->auth_key="f766b428cace0dddbd52050e3cc56a6f";
		$this->auth_secret="206cf2d4a060dc1c80278498da051d74";
		$this->api_url="?app=article&controller=article&action=yangya_add";
		$this->db=factory::db();
		$this->config=config("2017push",'yangya');	//找到配置文件中,资讯推送对应规则
		$this->pagesize=200;
		$this->start_time=strtotime("00:00:00");	//每天的开始时间
		$this->end_time=strtotime("23:59:59");		//每天的结束时间
	}
	
	/*定时推送*/
    public function cron_push()
    {
    	foreach($this->config as $key=>$value){
			/*得到4537里指定栏目的数据*/
			$dbList=$this->getCategoryList($key,$value);
		}
		exit('{"state":true}');
		
    }


function getCategoryList($catid,$free_catid)
{
	$pagesize=200;	//每次查询数量
	/*记录总条数*/
	$total=$this->db->select("select count(contentid) as count from #table_content where catid=$catid and markid=0 and status=3 and published>".$this->start_time." and published <".$this->end_time);
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
		$list=$this->db->select("select c.*,a.* from #table_content as c inner join #table_article as a on c.contentid=a.contentid and catid=$catid and markid=0 and status=3 and published > ".$this->start_time." and published <".$this->end_time." $limit");
		/*遍历数组,进行逐条插入4500数据表*/
		$this->getDetail($list,$free_catid);
		
	}

}

/*进行数组遍历，并逐条插入4500表*/
function getDetail($list,$catid)
{
	
	foreach($list as $key=>$val)
	{
			$val['catid']=$catid;
			$val['saveremoteimage']=1;
			$val['publishedby']=1;
			$val['published']=date("Y-m-d H:i",$val['published']);
			$val['status']=3;
			$val['markid']=1;	/*记录值,当该值为1时，代表已推送，值为0时代表未推送*/
			$sourceid = $val['sourceid'];
			$source=$this->db->get("select name from cmstop_source where sourceid=$sourceid");
			$val['source']=$source['name'];	
			unset($val['sourceid']);
			if(!empty($val['thumb']))
			{
				$val['thumb']=UPLOAD_URL.$val['thumb'];
			}

			$result=$this->push_article($val);
	}
}


	/*将数据通过添加文章接口，推送到免费站*/
	function push_article($post)
	{
		ksort($post);
		$sign=md5(http_build_query($post).$this->auth_secret);
		$request_url = $this->gateway.$this->api_url.'&key='.$this->auth_key.'&sign='.$sign;
		
		$answer=request($request_url,$post);	
		if($answer['httpcode']==200)
		{
			$content=json_decode($answer['content'],true);
			if($content['state']){
			/*更新数据状态让markid=1,代表该文章已经推送过到免费站了*/
			$sql="update cmstop_content set markid=1 where contentid=".$post['contentid'];
			$this->db->update($sql);
				echo $post['title']."success"."<br/>";
			}else{
				$sql="update cmstop_content set markid=1 where contentid=".$post['contentid'];
				$result=$this->db->update($sql);
				echo $post['title'].":".$content['error']."<br/>";
			}
		}
		else
		{
			echo $answer['httpcode']."error"."<br/>";
		}

		
	}

}

