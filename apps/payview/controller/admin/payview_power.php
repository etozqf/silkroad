<?php
class controller_admin_payview_power extends payview_controller_abstract
{
	private $category, $pagesize = 15;

	function __construct(&$app)
	{
		parent::__construct($app);
		$this->payview_power = loader::model('admin/payview_power');
	}

	function index()
	{
		$this->pagesize = $this->setting['pagesize'] ? $this->setting['pagesize'] : $this->pagesize;
		$this->view->assign('head', array('title'=>'用户订阅权限管理'));
		$this->view->assign('pagesize', $this->pagesize);
		$this->view->display("power_index");
	}

	function page()
	{
		$page = max((isset($_GET['page']) ? intval($_GET['page']) : 1), 1);
		$size = max((isset($_GET['pagesize']) ? intval($_GET['pagesize']) : $this->pagesize), 1);
		$starttime = $_GET['published'];
		$endtime = $_GET['unpublished'];
		$type = $_GET['type'];
		$status = $_GET['status'];

		$rwkeyword = trim($_GET['rwkeyword']);
		$where = array();
		// where 条件
		if (isset($rwkeyword) && $rwkeyword)
		{
			$where[] = where_keywords('username', $rwkeyword);
			unset($rwkeyword);
		}
		
		$starttime && $where[] = where_mintime('`endtime`', $starttime);
		$endtime && $where[] = where_maxtime('`endtime`', $endtime);
		
		$order = isset($_GET['orderby']) ? str_replace('|', ' ', $_GET['orderby']) : '`userid` DESC,endtime DESC';
		$page = max((isset($_GET['page']) ? intval($_GET['page']) : 1), 1);
		$size = max((isset($_GET['pagesize']) ? intval($_GET['pagesize']) : $this->pagesize), 1);
		$data = $this->payview_power->ls($where, '*', $order, $page, $size);
		$total = $this->payview_power->total();
		echo $this->json->encode(array('data' =>$data, 'total' => $total));
	}
	
	
	public function edit()
	{
		if($this->is_post())
		{
			if($this->payview_power->edit($_POST))
			{
				$data = $this->payview_power->get($_POST['oid']);
				$result = array('state' => true, 'data' => $data);
			}
			else 
			{
				$result = array('state' => false, 'error' => $this->payview_power->error);
			}
			exit($this->json->encode($result));
		}
		else 
		{
			$oid = id_format($_REQUEST['oid']);
			$data = $this->payview_power->get($oid);
			if(empty($data))
			{
				exit($this->json->encode($result = array('state' => false, 'error' => '无此数据')));
			}
			$head['title'] = "编辑订阅权限";
			$payview_category = table('payview_category');
			$this->view->assign('data',$data);
			$this->view->assign('head', $head);
			$this->view->assign('payview_category', $payview_category);
			$this->view->assign('setting', $this->setting);
			$this->view->display('power_edit');
		}
	}
	
	public function delete()
	{
		$oid = $_REQUEST['oid'];
		if($this->payview_power->delete($oid))
		{
			$result = array('state' => true, 'data' => '删除成功');
		}
		else 
		{
			$result = array('state' => false, 'error' => $this->payview_power->error);
		}
		exit($this->json->encode($result));
	}
	//重载权限数据
	public function reload()
	{
		set_time_limit(600);
		$this->payview_power->reload();
		$result = array('state' => true, 'data' => '重载成功');
		exit($this->json->encode($result));
	}
	//清空权限缓存
	public function clear_cache()
	{
		$this->payview_power->clear_cache();
		$result = array('state' => true, 'data' => '清空权限缓存成功');
		exit($this->json->encode($result));
	}
	
	//清理过期权限
	public function clear_past()
	{
		if($this->payview_power->clear_past())
		{
			$result = array('state' => true, 'data' => '清理成功');
		}
		else 
		{
			$result = array('state' => false, 'error' => $this->payview_power->error);
		}
		exit($this->json->encode($result));
	}
}