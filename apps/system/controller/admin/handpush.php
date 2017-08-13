<?php
/*
  *付费站手动推送免费站
  *author:yangya
*/
class controller_admin_handpush extends system_controller_abstract
{
	public $gateway,$auth_key,$auth_secret,$api_url,$catid_list,$pagesize;

	public function __construct($app)
	{
		parent::__construct($app);
		$this->gateway="http://api.silkroad.news.cn/";
		$this->auth_key="f766b428cace0dddbd52050e3cc56a6f";
		// $this->auth_key="a0f5f2e8da9519d7b66420ed5f3fab80";
		$this->auth_secret="206cf2d4a060dc1c80278498da051d74";
		// $this->auth_secret="d85a118665aafca845d1b5e81ae5d9e1";
		$this->api_url="?app=article&controller=article&action=handadd";
		$this->catid_list=config('push'); /*读取推送栏目对应关系*/
		$this->db=factory::db();
		
	}

	/*得到指定要推送的文章*/
	public function get_contentid_article()
	{
    $contentid = intval(value($_GET, 'contentid'));
    // $catid = intval(value($_GET, 'catid'));
    if(!$contentid){
    	exit('推送失败');
    }
		$field="c.contentid,catid,title,color,tags,thumb,published,unpublished,allowcomment,sourceid,a.subtitle,author,editor,content,description";
		$where=" where c.contentid=$contentid and a.contentid=$contentid";
		$arr=$this->db->select("select $field from cmstop_content as c, cmstop_article as a $where");
		// print_r($arr);die;
			$res = $this->insert_article($arr);
		
			$yang = $this->json->decode($res['content']);
		
			if($yang['state']){
				$ress = array("state"=>true);
			}else{
				$ress = array("state"=>false,"error"=>$yang['error']);
			} 
			echo $this->json->encode($ress);
			

		}


	/*遍历数组，将该条文章进行推送*/
	public function insert_article($arr)
	{

			foreach($arr as $val){
					
					$val['catid']=$this->catid_list[$val['catid']];	
					$val['weight']=20;
					$val['saveremoteimage']=1;
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
				

					$result=$this->push_article($val);
					return $result;	
					
			}

	}


	/*将数据通过添加文章接口，推送到免费站*/
	public function push_article($post)
	{
		ksort($post);
		$sign=md5(http_build_query($post).$this->auth_secret);
		$request_url = $this->gateway.$this->api_url.'&key='.$this->auth_key.'&sign='.$sign;
		
		$answer=request($request_url,$post);
		return $answer;
			
	}



}
