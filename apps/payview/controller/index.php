<?php
class payview_controller_index extends payview_controller_abstract
{
	private $user_pvcid;
	
	function __construct(&$app)
	{
		parent::__construct($app);
		if (!$this->_userid) $this->showmessage('请登录', '?app=member&controller=index&action=login&referer='.urlencode(URL));
		$this->userid = $this->_userid;
		$this->payview_category = loader::model('admin/payview_category');
		$this->payview_power = loader::model('admin/payview_power');
		$this->get_user_pvcid();
	}
	
	function index()
	{
		$user_pvcid = implode(',',$this->user_pvcid);
		if(count($this->user_pvcid)==1)
		{
			header("Location: ".url('payview/index/category').'&pvcid='.$user_pvcid);
		}
		$this->template->assign('user_pvcid', $user_pvcid);
		$this->template->assign('setting', $this->setting);
		$this->template->display('payview/index.html');
	}
	
	function category()
	{
		$pvcid = id_format($_REQUEST['pvcid']);
		if(!in_array($pvcid,$this->user_pvcid))
		{
			$this->showmessage('对不起，您尚未订阅本栏目组！<a href="'.url('payview/order/add').'">点击订阅</a>');
		}
		$data = $this->payview_category->get($pvcid);
		if($this->_groupid==1){}
		else if(empty($data) || $data['disabled']!=0)
		{
			$this->showmessage('您访问的栏目组不存在！');
		}
		else if(!in_array($pvcid,$this->user_pvcid))
		{
			$this->showmessage('您没有访问权限本栏目组！');
		}
		
		$this->template->assign('user_pvcid', $this->user_pvcid);
		$this->template->assign('setting', $this->setting);
		$this->template->assign('data', $data);
		$this->template->display('payview/index_category.html');
	}
	
	protected function get_user_pvcid()
	{
		//$this->payview_power->remove_power_cache($this->userid);
		$user_power = $this->payview_power->get_power_cache($this->userid);
		$this->user_pvcid = $user_power[0]['user_pvcid'];
		//if (!$this->user_pvcid) $this->showmessage('您没有访问权限');
		
		if(empty($this->user_pvcid)) 
		{
			header("Location: ".url('payview/order/add'));
		}
	}

}