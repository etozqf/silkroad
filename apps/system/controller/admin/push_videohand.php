<?php
/*
  +付费站推送免费站
  +类型：视频
  +author:kanzhi
*/
class controller_admin_push_videohand extends system_controller_abstract
{
	public function __construct($app)
	{
		parent::__construct($app);
		$this->gateway="http://api.silkroad.news.cn/";
		$this->auth_key="f766b428cace0dddbd52050e3cc56a6f";
		// $this->auth_key="a0f5f2e8da9519d7b66420ed5f3fab80";
		$this->auth_secret="206cf2d4a060dc1c80278498da051d74";
		// $this->auth_secret="d85a118665aafca845d1b5e81ae5d9e1";
		$this->api_url="?app=video&controller=video&action=handadd";
		$this->catid_list=config('push'); /*读取推送栏目对应关系*/
		$this->db=factory::db();
		
		
	}

	/*得到指定的推送视频*/
	public function get_contentid_video()
	{
    $contentid = intval(value($_GET, 'contentid'));
		
		if(!$contentid){
    	exit('推送失败');
    }
    $field="c.contentid,catid,title,color,tags,subtitle,video,sourceid,v.editor,playtime,description,thumb,published,allowcomment";
		$where=" where c.contentid=$contentid and v.contentid=$contentid";;

	
		$arr=$this->db->select("select $field from cmstop_content as c, cmstop_video as v $where");
		// echo '<pre>';print_r($arr);
		$res = $this->insert_video($arr);
		$yang = $this->json->decode($res['content']);
		// print_r($yang);die;
			if($yang['state']){
				$ress = array("state"=>true);
			}else{
				$ress = array("state"=>false,"error"=>$yang['error']);
			} 
			echo $this->json->encode($ress);

	}


	/*遍历数组，将该条视频进行推送*/
	public function insert_video($arr)
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
			// echo '<pre>';print_r($val);die;
			$result=$this->push_video($val);	
			return $result;	
			}

	}

	/*将数据通过添加文章接口，推送到免费站*/
	public function push_video($post)
	{
		ksort($post);



		$sign=md5(http_build_query($post).$this->auth_secret);
		$request_url = $this->gateway.$this->api_url.'&key='.$this->auth_key.'&sign='.$sign;
		// echo '<pre>';print_r($post);die;
		
		$answer=request($request_url,$post);
		// print_r($answer);
		return $answer;


	}




}
