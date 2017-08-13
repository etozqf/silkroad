<?php
class controller_behavior extends system_controller_abstract
{
	function __construct($app)
	{
		parent::__construct($app);
	}

	public function record()
	{
		$db=factory::db();
		$auth = $_REQUEST['auth'];
		// $authSQL = "select password from cmstop_member where userid = $_REQUEST [userid]";
		// $authResult = $db->select($authSQL);
		$start_time = !empty($_REQUEST['start_time']) ? intval($_REQUEST['start_time']) : '';
		$end_time = !empty($_REQUEST['end_time']) ? intval($_REQUEST['end_time']) : '';
		$userid = !empty($_REQUEST['userid']) ? intval($_REQUEST['userid']) : '';
		$username = !empty($_REQUEST['username']) ? strval($_REQUEST['username']) : '';
		$userip = !empty($_REQUEST['userip']) ? $_REQUEST['userip'] : '';
		$user_country = !empty($_REQUEST['user_country']) ? $_REQUEST['user_country'] : '';
		$user_province = !empty($_REQUEST['user_province']) ? $_REQUEST['user_province'] : '';
		$terminal = !empty($_REQUEST['terminal']) ? intval($_REQUEST['terminal']) : '';
		$catname = !empty($_REQUEST['catname']) ? strval($_REQUEST['catname']) : '';
		$catname_top = !empty($_REQUEST['catname_top']) ? strval($_REQUEST['catname_top']) : '';
		$contentid = !empty($_REQUEST['contentid']) ? intval($_REQUEST['contentid']): '';
		$title = !empty($_REQUEST['title']) ? strval($_REQUEST['title']) : '';
		$tags = !empty($_REQUEST['tags']) ? strval($_REQUEST['tags']) : '';
		$url = !empty($_REQUEST['url']) ? $_REQUEST['url'] : '';
		$site_type = !empty($_REQUEST['site_type']) ? intval($_REQUEST['site_type']) : '';
			
		if (!empty($auth) && !empty($userid) && !empty($userip) && !empty($user_country) && !empty($user_province) && !empty($url)){
			if (empty($catname) && empty($catname_top) && empty($contentid)) {
				$data=array('state'=>false,'message'=>'error');
			}
			else
			{
				$remark = '';
				$creatime = time();
				
				$SQL = "insert into cmstop_user_behavior(start_time,end_time,userid,username,userip,user_country,user_province,terminal,catname,catname_top,contentid,title,tags,url,site_type,remark,creatime) values('".$start_time."','".$end_time."','".$userid."','".$username."','".$userip."','".$user_country."','".$user_province."','".$terminal."','".$catname."','".$catname_top."','".$contentid."','".$title."','".$tags."','".$url."','".$site_type."','".$remark."','".$creatime."')";
				
				$result=$db->insert($SQL);
			}
			if(!empty($result))
			{
				$data=array('state'=>true,'message'=>'success');
			}
			else
			{
				$data=array('state'=>false,'message'=>'error');
			}
		}
		else
		{
			$data=array('state'=>false,'message'=>'Illegal operation');
		}
		
		echo $_REQUEST['callback']."(".$this->json->encode($data).")";

	}

	public function ceshi()
	{
		$data=array('state'=>true,'message'=>'4');
		echo $_REQUEST ['callback']."(".$this->json->encode($data).")";
	}




}