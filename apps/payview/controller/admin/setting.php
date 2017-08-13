<?php

class controller_admin_setting extends payview_controller_abstract
{
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
			$head = array('title'=>'付费阅读设置');

			$this->view->assign('head', $head);
			$this->view->assign('setting', $this->setting);
			$this->view->display('setting');
		}
	}
}