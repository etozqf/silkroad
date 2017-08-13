<?php
class payview_controller_category extends payview_controller_abstract
{
	private $category, $pagesize = 15;

	function __construct(&$app)
	{
		parent::__construct($app);
		if (!$this->_userid) $this->showmessage('请登录', '?app=member&controller=index&action=login');
		$this->userid = $this->_userid;
		$this->username = $this->_username;
		$this->payview_category = loader::model('admin/payview_category');
	}

	function page()
	{
		$rwkeyword = trim($_GET['rwkeyword']);
		// where 条件
		$where = 'disabled=0 AND type=0';
		if (isset($rwkeyword) && $rwkeyword)
		{
			$where .= ' AND '.where_keywords('title', $rwkeyword);
		}
		$order = isset($_GET['orderby']) ? str_replace('|', ' ', $_GET['orderby']) : '`pvcid` DESC';
		$page = max((isset($_GET['page']) ? intval($_GET['page']) : 1), 1);
		$size = max((isset($_GET['pagesize']) ? intval($_GET['pagesize']) : $this->pagesize), 1);
		$data = $this->payview_category->ls($where, '*', $order, $page, $size);
		$total = $this->payview_category->count($where);
		echo $this->json->encode(array('data' =>$data, 'total' => $total));
	}
	
}