<?php
class payview_controller_power extends payview_controller_abstract
{
	private $userid, $username;

	function __construct(&$app)
	{
		parent::__construct($app);
		if (!$this->_userid) $this->showmessage('请登录', '?app=member&controller=index&action=login');
		$this->userid = $this->_userid;
		$this->username = $this->_username;
		$this->payview_power = loader::model('admin/payview_power');
	}

	function index()
	{
		$userid = $this->userid;
		$data = $this->payview_power->get_power_cache($userid);
		$this->template->assign('data', $data);
		$this->template->display("payview/power_show.html");
	}

	function page()
	{
		// where 条件
		$userid = $this->userid;
		$where = "userid=$userid AND endtime>".TIME;
		$data = $this->payview_power->select($where);
		$this->payview_power->_output($data);
		echo $this->json->encode(array('data' =>$data, 'total' => 0));
	}

}