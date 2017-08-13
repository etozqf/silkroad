<?php
class controller_index extends contribution_controller_abstract
{
	function __construct($app)
	{
		parent::__construct($app);
		if($this->_userid)
		{
			$url = APP_URL.url('contribution/panel/contribute'); //已登录到管理中心
			$this->redirect($url);
		}
		if(!$this->setting['iscontribute'])
		{
			$this->showmessage('游客投稿未开通');
		}
		$this->contribution = loader::model('contribution');
	}

	function index()
	{
		if($this->is_post())
		{
			if(!$this->validate($_POST))
			{
				$result = array('state' => false,'error' => $this->error());
			}
			$_POST['status'] = 3;
			$contributionid = $this->contribution->add($_POST);
			if($contributionid)
			{
				$result = array('state' => true,'message' => '投递成功');
			}
			else
			{
				$result = array('state' => false,'error' => $this->contribution->error());
			}
			echo $this->json->encode($result);
		}
		else
		{
			$channel = channel();
			$this->template->assign('channel',$channel);
			$this->template->display('contribution/form.html');
		}
	}
	
	function status()
	{
		
	}

	private function validate($post)
	{
		//验证码 catid
		import('helper.seccode');
		$seccode = new seccode();
		if(!$seccode->valid())
		{
			$this->error = '验证码不正确';
			return false;
		}
		if(empty($post['catid']))
		{
			$this->error = '选择投递的栏目';
			return false;
		}
		if(empty($post['title']))
		{
			$this->error = '标题不能为空';
			return false;
		}
		if(empty($post['content']))
		{
			$this->error = '内容不能为空';
			return false;
		}
		//处理
		$allows = '<a><b><br><div><em><font><h1><h2><h3><h4><h5><h6><hr><i><img><li><strong><table><tbody><td><th><thead><tr><u><ul><p>';
		$post['title'] = htmlspecialchars_deep($post['title']);
		$post['description'] = htmlspecialchars_deep($post['description']);
		$post['author'] = htmlspecialchars_deep($post['author']);
		$post['source'] = htmlspecialchars_deep($post['source']);
		$post['email'] = htmlspecialchars_deep($post['email']);
		$post['username'] = htmlspecialchars_deep($post['username']);
		$post['content'] = strip_tags($post['content'], $allows);
		return true;
	}
}