<?php
class payview_controller_article extends payview_controller_abstract
{
	private $article, $userid, $username;

	function __construct(&$app)
	{
		parent::__construct($app);
		if (!$this->_userid)
		{
			$result = array('state' => false, 'error' => '您还没有登录，请登录！');
			$result = $this->json->encode($result);
			echo $_GET['jsoncallback']."($result);";
			exit;
		}
		$this->userid = $this->_userid;
		$this->username = $this->_username;
		$this->article = loader::model('admin/article', 'article');
		$this->category = loader::model('category', 'system');
		
	}
	
	//显示文章内容
	function show()
	{
		$catid = $_GET['catid'];
		$contentid = $_GET['contentid'];
		if($this->_check_power($catid))
		{
			$r = $this->article->get($contentid, 'content');
			$r['content'] = $this->_transform_filepath($r['content'], $catid);
			$r['content'] = preg_replace('/<p\s*[^>]*>(\s|&nbsp;)*<\/p>/isU', '', $r['content']);
			$result = array('state' => true, 'content' => $r['content']);
			$result = $this->json->encode($result);
			echo $_GET['jsoncallback']."($result);";
		}
		else
		{
			$result = array('state' => false, 'error' => '您没有本文章阅读权限');
			$result = $this->json->encode($result);
			echo $_GET['jsoncallback']."($result);";
		}
		exit;
	}
	
	function printing()
	{
		if ($this->system['pagecached']) cmstop::cache_start($this->system['pagecachettl']);
		
		$catid = $_GET['catid'];
		$contentid = $_GET['contentid'];
		if(!$this->_check_power($catid))
		{
			$result = array('state' => false, 'error' => '您没有本文章阅读权限');
			exit($this->json->encode($result));
		}
		$data = $this->article->get($contentid);
		if($data['modelid']!=1) return $this->showmessage('没有此打印内容！');
		$this->template->assign('pos', $this->category->pos($data['catid']));
		$this->template->assign($data);
		$this->template->display('article/print.html');
		
		if ($this->system['pagecached']) cmstop::cache_end();
	}
	
	function downloadfile()
	{
		$file = $_GET['f'];
		$file = str_replace(' ','+',$file);
		$file = str_decode($file,$this->config['authkey']);
		list($catid, $file) = explode('|', $file);
		if($this->_check_power($catid))
		{
			// 文件的真实地址（支持url,不过不建议用url） 
			$file = UPLOAD_PATH.$file;
			$this->_get_file($file);
		}
		else
		{
			$this->showmessage('您没有本文章附件下载权限！');
		}
	}
	
	
}