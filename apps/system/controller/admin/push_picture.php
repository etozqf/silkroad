<?php
/*
  +付费站推送免费站
  + 推送数据类型： 组图
  +author:kanzhi
*/
class controller_admin_push_picture extends system_controller_abstract
{
	public $gateway,$auth_key,$auth_secret,$api_url,$catid_list,$pagesize;

	public function __construct($app)
	{
		parent::__construct($app);
		$this->gateway="http://api.silkroad.news.cn/";
		$this->auth_key="f766b428cace0dddbd52050e3cc56a6f";
		// $this->auth_key="a0f5f2e8da9519d7b66420ed5f3fab80";//本地
		$this->auth_secret="206cf2d4a060dc1c80278498da051d74";
		// $this->auth_secret="d85a118665aafca845d1b5e81ae5d9e1";//本地
		$this->api_url="?app=picture&controller=picture&action=add";
		$this->catid_list=app_config("picture","push_picture"); /*读取推送栏目对应关系*/
		$this->db=factory::db();
		$this->pagesize=100;
		$this->start_time=strtotime("00:00:00");	//每天的开始时间
		$this->end_time=strtotime("23:59:59");		//每天的结束时间
	}

	/*付费站文章推送免费站*/
	public function cron_picture()
	{
		$old_catid=array_keys($this->catid_list);

		/*遍历付费站栏目读取相关联的文章数据*/
		foreach($old_catid as $value)
		{
			$this->get_catid_picture($value);
		}

		exit('{"state":true}');

	}

	/*得到指定栏目中的所有符合条件的文章*/
	private function get_catid_picture($catid)
	{
		$field="c.contentid,catid,title,subtitle,color,sourceid,tags,editor,status,description,thumb,published,unpublished,allowcomment";
		$where=" where catid=$catid and status=6 and weight>=80 and modelid=2 and published > $this->start_time and published <=$this->end_time order by published desc";
		/*统计符合查询的条件数量*/
		$total=$this->db->select("select count(contentid) as contentid from #table_content as c $where");

		$count=ceil($total[0]['contentid']/$this->pagesize);

		/*如果该栏目下的数量小于1，则返回false*/
		if($count<1){
			return false;
		}

		for($i=1;$i<=$count;$i++)
		{   

			$page=$i-1;
			$start=$page*$this->pagesize;
			$limit=" limit $start".",".$this->pagesize;
			$picture_arr=$this->db->select("select $field from #table_content as c inner join #table_picture as p on c.contentid=p.contentid $where $limit");

			$arr=$this->picture_gruop($picture_arr);
			// echo '<pre>';print_r($arr);die;
			$this->insert_picture($arr);
		}	

	}

	/*拼装得到组图里的图片信息*/
	public function picture_gruop($picture_arr)
	{
		
		$field="aid,image,note,pictureid,sort";
		$pictures=array();
		foreach($picture_arr as $key=>&$value)
		{
			$value['pictures']=$this->db->select("select $field from cmstop_picture_group where contentid={$value['contentid']}");		
		}

		return $picture_arr;
	}


	/*遍历数组，将每一条文章进行推送*/
	public function insert_picture($arr)
	{
			foreach($arr as $val)
			{
					$val['catid']=$this->catid_list[$val['catid']];	
					$val['weight']=20;
					$val['saveremoteimage']=1;	//下载远程图片本地化
					$val['publishedby']=1;
					$val['published']=date("Y-m-d H:i",$val['published']);
					$val['status']=6;
					$sourceid = $val['sourceid'];
					$source=$this->db->get("select name from cmstop_source where sourceid=$sourceid");
					$val['source']=$source['name'];
					unset($val['sourceid']);
					if(!empty($val['thumb']))
					{
						$val['thumb']=UPLOAD_URL.$val['thumb'];
					}
					foreach($val['pictures'] as $k=>&$v)
					{
						$pattern = "/([0-9]+\/)+[\d]+\.[a-z]+/i";
						preg_match($pattern,$v['image'],$arr);   //正则匹配，获取图片目录地址  eg:2016/0425/1234.jpg
            $v['image']=UPLOAD_URL.$arr[0];    //得到付费站站组图的绝对路径。
						
					}
					// echo '<pre>';print_r($val);die;
					
					$result=$this->push_picture($val);	
					
			}

	}


	/*将数据通过添加文章接口，推送到免费站*/
	public function push_picture($post)
	{
		ksort($post);
		$sign=md5(http_build_query($post).$this->auth_secret);
		$request_url = $this->gateway.$this->api_url.'&key='.$this->auth_key.'&sign='.$sign;
		
		$answer=request($request_url,$post);
	
		echo '<pre>';print_r($answer);
	}



}
