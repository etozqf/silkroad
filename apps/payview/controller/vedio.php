<?php
class payview_controller_vedio extends payview_controller_abstract
{
	private $vedio, $userid, $username;

	function __construct(&$app)
	{
		parent::__construct($app);
		$this->userid = $this->_userid;
		$this->username = $this->_username;
		
	}
	
	function player()
	{
		//{$playerurl}?w=620&h=470&id={$video}
		$id = $_GET['id'];
		$w = $_GET['w'];
		$h = $_GET['h'];
		$id = str_decode($id,$this->config['authkey']);
		list($catid, $video) = explode('|', $id);//exit($video);
		//视频播放器地址
		$new_setting = new setting();
		$video_setting = $new_setting->get('video');
		$playerurl = $video_setting['player'];
		
		$playerurl .= '?w='.$w.'&h='.$h.'&id='.$video;
		$response = request($playerurl);
		$content = $response['content'];
			
		if($this->_check_power($catid))
		{
			//$content = $this->get_deal_vedio($content,$catid);
		}
		else
		{
			$search = '/poster\s*=\s*["\']([^"\']+)["\']/im';
			preg_match($search, $content, $result);
			$src = $result[1];
			$content = '
				$("#nopower").show();
				$("#vedio_img").attr("src","'.$src.'");
			';
			
		}
		exit($content);
	}
	
	protected function get_deal_vedio($val,$catid)
	{
		$search = '/<source\s+[^>]*src\s*=\s*["\']([^"\']+)["\'][^>]*[\/]?>/im';
		preg_match_all($search, $val, $result);
		if(empty($result[0])){
			return $val;
		}
		$newsrc = '';
		$upload_path = APP_URL.url('payview/vedio/get_vedio_file').'&f=';
		foreach($result[0] as $key=>$oneimg)
		{   
			$oldsrc = $result[1][$key];
			//$val = str_replace($oldsrc, $newsrc, $val);
			$newsrc = $catid . '|' . $oldsrc;
			$newsrc = str_replace('&','&amp;', $upload_path.str_encode($newsrc,$this->config['authkey']));
			if(!empty($newsrc)){
				$val = str_replace($oldsrc, $newsrc, $val);
				$newsrc = '';
			}
		}     
		return $val;
	}
	
	function get_vedio_file()
	{
		$file = $_GET['f'];
		$file = str_replace(' ','+',$file);
		$file = str_decode($file,$this->config['authkey']);echo $file;
		list($catid, $file) = explode('|', $file);
		if($this->_check_power($catid))
		{
			// 文件的真实地址（支持url,不过不建议用url） 
			//$file = UPLOAD_PATH.$file;
			$this->_get_file($file);
		}
		else
		{
			$this->showmessage('您没有本文章附件下载权限！');
		}
	}
	
	
}