<?php
/*
  +付费站推送免费站
  +推送付费站  东软数据频道下 TBT通报(356)和SPS通报(357) 
  +推送至4500站   TBT通报(223)和SPS通报(224)
  +推送数据时，要求将状态为3(待审)的数据推送，这点要注意
  +author:kanzhi
*/
class controller_admin_dongruan extends system_controller_abstract
{
	public $gateway,$auth_key,$auth_secret,$api_url,$catid_list,$pagesize;

	public function __construct($app)
	{
		parent::__construct($app);
		$this->gateway="http://api.silkroad.news.cn/";
		$this->auth_key="f766b428cace0dddbd52050e3cc56a6f";
		$this->auth_secret="206cf2d4a060dc1c80278498da051d74";
		$this->api_url="?app=article&controller=article&action=add";
		$this->catid_list=config('2017push','dongruan'); /*读取推送栏目对应关系*/
		$this->db=factory::db();
		$this->pagesize=100;
		$this->start_time=strtotime("00:00:00");	//每天的开始时间
		$this->end_time=strtotime("23:59:59");		//每天的结束时间
	}

	/*推送数据*/
	public function push()
	{
		/*从配置文件中得到要推送的栏目,然后根据栏目去查询数据*/
		foreach($this->catid_list as $key=>$value)
		{
			$this->get_catid_article($key,$value);			
		}
		
		exit('{"state":true}');
	}
	

	/*得到指定栏目中的所有符合条件的文章*/
	private function get_catid_article($catid,$free_catid)
	{

		/*统计指定栏目下,符合栏目要求的待审状态数据的数量*/
		$total=$this->db->select("select count(contentid) as contentid from #table_content where catid=$catid and status=3 and published >".$this->start_time." and published<".$this->end_time);
		
		$count=ceil($total[0]['contentid']/$this->pagesize);

		/*如果该栏目下的数量小于1，则返回false*/
		if($count<1){
			return false;
		}
		
		/*进行表关联匹配*/
		$sql="select c.*,a.* from #table_content as c inner join #table_article as a where c.contentid=a.contentid and catid=$catid and status=3 and published >".$this->start_time." and published<".$this->end_time;
		
		for($i=1;$i<=$count;$i++)
		{   

			$page=$i-1;
			$start=$page*$this->pagesize;
			$limit=" limit $start".",".$this->pagesize;
			$arr=$this->db->select($sql.$limit);
			
			$this->insert_article($arr,$free_catid);
		}	

	}

	
	/*遍历数组，将每一页文章进行推送*/
	public function insert_article($arr,$free_catid)
	{
			foreach($arr as $val)
			{
					$val['catid']=$free_catid;
					$val['saveremoteimage']=1;
					$val['publishedby']=1;
					$val['published']=date("Y-m-d H:i",$val['published']);
					$val['status']=3;
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
	public function push_article($post)
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
				$sql="update cmstop_content set markid=1 and contentid=".$post['contentid'];
				$this->db->update($sql);
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



}
