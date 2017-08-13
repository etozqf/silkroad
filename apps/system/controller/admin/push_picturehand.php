<?php
/*
  +付费站推送免费站
  + 推送数据类型： 组图
  +author:yangya
*/
class controller_admin_push_picturehand extends system_controller_abstract
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
		$this->api_url="?app=picture&controller=picture&action=handadd";
		$this->catid_list=app_config("picture","push_picture"); /*读取推送栏目对应关系*/
		$this->db=factory::db();
		
		
	}

	/*付费站组图推送免费站*/
	// public function cron_picture()
	// {
	// 	$old_catid=array_keys($this->catid_list);

	// 	遍历付费站栏目读取相关联的组图数据
	// 	foreach($old_catid as $value)
	// 	{
	// 		$this->get_catid_picture($value);
	// 	}

	// 	//exit('{"state":true}');

	// }

	/*得到指定要推送的组图*/
	public function get_contentid_picture($contentid)
	{
		$contentid = intval(value($_GET, 'contentid'));
    // $catid = intval(value($_GET, 'catid'));
    // var_dump($contentid);
    if(!$contentid){
    	exit('推送失败');
    }
		$field="c.contentid,catid,title,color,tags,thumb,published,unpublished,allowcomment,sourceid,p.editor,description";	
		$where=" where c.contentid=$contentid and p.contentid=$contentid";
		
		$picture_arr=$this->db->get("select $field from cmstop_content as c, cmstop_picture as p $where");
		
		$arr=$this->picture_gruop($picture_arr);
		
		$res = $this->insert_picture($arr);
		
		$yang = $this->json->decode($res['content']);
		
			if($yang['state']){
				$ress = array("state"=>true);
			}else{
				$ress = array("state"=>false,"error"=>$yang['error']);
			} 
			echo $this->json->encode($ress);
		}	



	/*拼装得到组图里的图片信息*/
	public function picture_gruop($picture_arr)
	{
		
		$field="g.aid,image,note,sort,a.filename,filepath,alias,description,filemime,fileext,filesize,isimage,thumb,created";
		$contentid = $picture_arr['contentid'];
		// return $contentid;
		$arr = $this->db->select("select $field from cmstop_picture_group as g left join cmstop_attachment as a on g.aid=a.aid where g.contentid=$contentid");
		$gourp =array('aid','image','note','picture','sort');
		
		foreach($arr as $key=>$value)
		{
				foreach($value as $k=>$v){
					// var_dump($k);die;
					if(in_array($k,$gourp)) {
						$picture_arr['pictures']["$value[aid]"]["$k"]=$value["$k"];
					}else{
						$picture_arr['attachment']["$value[aid]"]["$k"]=$value["$k"];
					}  	
			}
				
		}

		return $picture_arr;
	}



	/*遍历数组，将每一条文章进行推送*/
	public function insert_picture($val)
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
					// return $val;
					$result = $this->push_picture($val);	
					return $result;	
					
			

	}


	/*将数据通过添加文章接口，推送到免费站*/
	public function push_picture($post)
	{
		ksort($post);
		$sign=md5(http_build_query($post).$this->auth_secret);
		$request_url = $this->gateway.$this->api_url.'&key='.$this->auth_key.'&sign='.$sign;
		// echo '<pre>';
		// print_r($request_url);die;
		$answer=request($request_url,$post);
		return $answer;
		
	}



}
