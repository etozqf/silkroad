<?php
/**
 * 财务管理--消费记录
 *
 * @author liuyuan
 * @copyright 2010 (c) CmsTop
 * @date 2011/04/26
 * @version $Id$
 */

class controller_payment extends pay_controller_abstract
{
	private $payment, $userid, $pagesize = 15;

	function __construct(&$app) 
	{
		parent::__construct($app);
		if (!$this->_userid) $this->showmessage('请登录', '?app=member&controller=index&action=login');
		$this->payment = loader::model('payment');
		$this->userid = $this->_userid;
	}

	// 消费记录
	public function index()
	{
		$this->template->assign('pagesize', $this->pagesize);
		$this->template->display('pay/payment.html');
	}

	// 获取消费记录数据
	public function page()
	{
		//默认按消费时间排序
		$order = '`created` DESC';

		$page = max((isset($_GET['page']) ? intval($_GET['page']) : 1), 1);
		$size = max((isset($_GET['pagesize']) ? intval($_GET['pagesize']) : $this->pagesize), 1);
		$where = '`createdby` = "'.$this->userid.'"';

		$data = $this->payment->page($where, $order, $page, $size);
		$total = $this->payment->count($where);

		$result = array('total'=>$total, 'data'=>$data);
		echo $this->json->encode($result);
	}
	
}