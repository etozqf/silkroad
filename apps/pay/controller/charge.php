<?php
/**
 * 财务管理--充值记录
 *
 * @author liuyuan
 * @copyright 2010 (c) CmsTop
 * @date 2011/04/26
 * @version $Id$
 */

class controller_charge extends pay_controller_abstract
{
	private $charge, $userid, $pagesize = 15;

	function __construct(&$app) 
	{
		parent::__construct($app);
		if (!$this->_userid) $this->showmessage('请登录', '?app=member&controller=index&action=login');
		$this->charge = loader::model('charge');
		$this->userid = $this->_userid;
	}

	// 交易记录
	public function index()
	{
		$this->template->assign('pagesize', $this->pagesize);
		$this->template->display('pay/charge.html');
	}

	// 获取交易记录数据
	public function page()
	{
		//默认按交易时间排序
		$order = '`created` DESC';

		$page = max((isset($_GET['page']) ? intval($_GET['page']) : 1), 1);
		$size = max((isset($_GET['pagesize']) ? intval($_GET['pagesize']) : $this->pagesize), 1);
		$where = '`createdby` = "'.$this->userid.'"';

		$data = $this->charge->page($where, $order, $page, $size);
		$total = $this->charge->count($where);

		$result = array('total'=>$total, 'data'=>$data);
		echo $this->json->encode($result);
	}
}