<?php
class controller_admin_payview_order extends payview_controller_abstract
{
	private $category, $pagesize = 15;

	function __construct(&$app)
	{
		parent::__construct($app);
		$this->payview_order = loader::model('admin/payview_order');
		$this->payview_power = loader::model('admin/payview_power');
	}

	function index()
	{
		$this->pagesize = $this->setting['pagesize'] ? $this->setting['pagesize'] : $this->pagesize;
		$this->view->assign('head', array('title'=>'订阅订单'));
		$this->view->assign('pagesize', $this->pagesize);
		$this->view->display("order_index");
	}

	function page()
	{
		$page = max((isset($_GET['page']) ? intval($_GET['page']) : 1), 1);
		$size = max((isset($_GET['pagesize']) ? intval($_GET['pagesize']) : $this->pagesize), 1);

		$rwkeyword = trim($_GET['rwkeyword']);
		$where = array();
		// where 条件
		if (isset($rwkeyword) && $rwkeyword)
		{
			if(is_numeric($rwkeyword) && '19' == mb_strlen($rwkeyword)) {
				$where[] = where_keywords('orderno', $rwkeyword);
			} elseif(is_string($rwkeyword)) {
				$where[] = where_keywords('username', $rwkeyword);
				unset($rwkeyword);
			}
		}
		
		$starttime = $_GET['published'];
		$endtime = $_GET['unpublished'];
		$type = $_GET['type'];
		$status = $_GET['status'];
		$is_invoice = $_GET['is_invoice'];

		$starttime && $where[] = where_mintime('`endtime`', $starttime);
		$endtime && $where[] = where_maxtime('`endtime`', $endtime);
		$status==1 && $where[] = 'status=1';
		$status==2 && $where[] = 'status!=1';
		$type==1 && $where[] = 'type=1';
		$type==2 && $where[] = 'type!=1';
		$is_invoice==1 && $where[] = 'is_invoice=1';
		
		$order = isset($_GET['orderby']) ? str_replace('|', ' ', $_GET['orderby']) : '`oid` DESC';
		$page = max((isset($_GET['page']) ? intval($_GET['page']) : 1), 1);
		$size = max((isset($_GET['pagesize']) ? intval($_GET['pagesize']) : $this->pagesize), 1);
		$data = $this->payview_order->ls($where, '*', $order, $page, $size);
		$total = $this->payview_order->total();
		echo $this->json->encode(array('total' => $total, 'data' =>$data));
	}
	
	public function add()
	{
		if($this->is_post())
		{
			if(!$_POST['pvcid'])
			{
				exit($this->json->encode(array('state'=>false, 'error' => '请选择订阅栏目')));
			}
			$_POST['orderno'] = $this->mk_tradeno();
			if($oid = $this->payview_order->add($_POST))
			{
				$data = $this->payview_order->get($oid);
				$data['status']==1 && $this->payview_power->edit($data);
				$result = array('state' => true, 'data' => $data);
			}
			else 
			{
				$result = array('state' => false, 'error' => $this->payview_order->error);
			}
			exit($this->json->encode($result));
		}
		else 
		{
			$head['title'] = "添加订阅订单";
			$payview_category = table('payview_category');
			$this->view->assign('head', $head);
			$this->view->assign('payview_category', $payview_category);
			$this->view->assign('setting', $this->setting);
			$this->view->display('order_add');
		}
	}
	
	public function edit()
	{
		$oid = id_format($_REQUEST['oid']);
		if($this->is_post())
		{
			if($this->payview_order->edit($oid, $_POST))
			{
				$data = $this->payview_order->get($oid);
				($data['status']==1 || $data['endtime_num']>TIME) ? $this->payview_power->edit($data) : $this->payview_power->delete($data['userid'],$data['pvcid']);
				$result = array('state' => true, 'data' => $data);
			}
			else 
			{
				$result = array('state' => false, 'error' => $this->payview_order->error);
			}
			exit($this->json->encode($result));
		}
		else 
		{
			$data = $this->payview_order->get($oid);
			if(empty($data))
			{
				exit($this->json->encode($result = array('state' => false, 'error' => '无此数据')));
			}
			$head['title'] = "编辑订阅订单";
			$payview_category = table('payview_category');
			$this->view->assign('data',$data);
			$this->view->assign('head', $head);
			$this->view->assign('payview_category', $payview_category);
			$this->view->assign('setting', $this->setting);
			$this->view->display('order_edit');
		}
	}
	
	public function view()
	{
		$oid = id_format($_REQUEST['oid']);
		$data = $this->payview_order->get($oid);
		if(empty($data))
		{
			exit($this->json->encode($result = array('state' => false, 'error' => '无此数据')));
		}
		$head['title'] = "查看订阅订单";
		$payview_category = table('payview_category');
		$this->view->assign('data',$data);
		$this->view->assign('head', $head);
		$this->view->assign('payview_category', $payview_category);
		$this->view->assign('setting', $this->setting);
		$this->view->display('order_show');
	}
	
	public function update_power()
	{
		$oid = id_format($_REQUEST['oid']);
		$data = $this->payview_order->get($oid);console($data);console(TIME);
		($data['status']==1 && $data['endtime_num']>TIME) ? $this->payview_power->edit($data) : $this->payview_power->delete($data['userid'],$data['pvcid']);
		$result = array('state' => true, 'data' => $data);
		exit($this->json->encode($result));
	}
	
	public function delete()
	{
		$oid = $_REQUEST['oid'];
		if($this->payview_order->delete($oid))
		{
			$result = array('state' => true, 'data' => '删除成功');
		}
		else 
		{
			$result = array('state' => false, 'error' => $this->payview_order->error);
		}
		exit($this->json->encode($result));
	}
	
	public function check_username()
	{
		$_GET['username'] = urldecode($_GET['username']);
		$return = $this->payview_order->check_username($_GET['username'])
		? array('state' => true, 'info' => '此用户可用')
		: array('state' => false, 'error' => $this->payview_order->error());
		$data = $this->json->encode($return);
		$data = (isset($_GET['jsoncallback']))? $_GET['jsoncallback']."($data);": $data;
		exit($data);
	}
	

	// 生成订单号
	public function mk_tradeno()
	{
		mt_srand(microtime(TRUE) * 1000000);
		return date('YmdHis').str_pad(mt_rand( 1, 99999), 5, '0', STR_PAD_LEFT);
	}
	
    /**
     * 订单过期关闭
     *
     */
	public function cron()
	{
		@set_time_limit(600);
		$close_time = $this->setting['order_close_time'] ? $this->setting['order_close_time'] : 7;
		$close_time = $close_time < 1 ? 7 : $close_time;
		$close_time = time() - $close_time*86400;
		$this->payview_order->cron_close($close_time);
		exit ('{"state":true}');
	}
}