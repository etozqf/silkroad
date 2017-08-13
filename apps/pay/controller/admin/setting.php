<?php

class controller_admin_setting extends pay_controller_abstract
{
	function __construct(& $app)
	{
		parent::__construct($app);
		if (!license('system')) $this->showmessage('请联系 <a href="http://www.cmstop.com/" target="_blank">CmsTop</a> 官方购买授权');
	}
	
	public function index()
	{
		if ($this->is_post())
		{
			$setting = new setting();
			$result = $setting->set_array($this->app->app, $_POST['setting']) ? array('state'=>true,'message'=>'保存成功') : array('state'=>false,'error'=>'保存失败');
			echo $this->json->encode($result);
		}
		else
		{
			$head = array('title'=>'财务管理设置');

			$this->view->assign('head', $head);
			$this->view->assign('setting', $this->setting);
			$this->view->display('setting');
		}
	}
}