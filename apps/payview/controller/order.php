<?php
class payview_controller_order extends payview_controller_abstract
{
	private $category, $pagesize = 15, $userid, $username, $order_url;

	function __construct(&$app)
	{
		parent::__construct($app);
		if (!$this->_userid) $this->showmessage('请登录', '?app=member&controller=index&action=login&referer='.urlencode(URL));
		$this->userid = $this->_userid;
		$this->username = $this->_username;
		$this->payview_order = loader::model('payview_order');
		$this->payview_power = loader::model('admin/payview_power');
		$this->payview_category = loader::model('admin/payview_category');
		$this->order_url = APP_URL.'?app=payview&controller=order&action=showorder';
	}

	function index()
	{
		$this->template->assign('head', array('title'=>'订阅订单'));
		$this->template->assign('pagesize', $this->pagesize);
		$this->template->display("payview/order_list.html");
	}

	function page()
	{
		$page = max((isset($_GET['page']) ? intval($_GET['page']) : 1), 1);
		$size = max((isset($_GET['pagesize']) ? intval($_GET['pagesize']) : $this->pagesize), 1);

		$rwkeyword = trim($_GET['rwkeyword']);
		$where = array('userid='.$this->userid);
	
		
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
				if($data['status']==1)
				{
					$this->payview_power->edit($data);
				}
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
			$head['title'] = "栏目订阅";
			$this->template->assign('head', $head);
			$this->template->assign('setting', $this->setting);
			$this->template->display('payview/order_add.html');
		}
	}
	
	//填写订单
	public function add_form()
	{
		$userid = $this->userid;
		$username = $this->username;
		$pvcid = id_format($_REQUEST['pvcid']);
		$data = $this->payview_category->get($pvcid);
		if(empty($data) || $data['type']!=0 || $data['disabled']!=0)
		{
			$this->showmessage('您订阅的栏目组不存在！');
		}
		$timetype = $data['timetype'];
		$readertime = $this->readertime($timetype);  //订阅期限从明天开始
		$data['starttime'] = $readertime['starttime'];
		$data['endtime'] = $readertime['endtime'];
		
		$title = $this->mk_title($data);
		$this->template->assign('data', $data);
		$this->template->assign('title', $title);
		$this->template->assign('setting', $this->setting);
		$this->template->display('payview/order_add_form.html');
	}

	//订单支付确认
	public function confirm()
	{
		$userid = $this->userid;
		$username = $this->username;
		$pvcid = id_format($_REQUEST['pvcid']);
		$is_invoice = $_REQUEST['is_invoice'];
		$invoice_title = $_REQUEST['invoice_title'];
		$post_name = $_REQUEST['post_name'];
		$post_address = $_REQUEST['post_address'];
		$post_phone = $_REQUEST['post_phone'];
		$post_fees = $this->setting['post_fees'];
		$data = $this->payview_category->get($pvcid);
		$is_invoice = $is_invoice ? $is_invoice : 0;
		if(empty($data) || $data['type']!=0 || $data['disabled']!=0)
		{
			$this->showmessage('您订阅的栏目组不存在！');
		}
		
		$creatorder = 1;		//是否创建订单
		$renew = 0;				//是否续订
		$timetype = $data['timetype'];
		$readertime = $this->readertime($timetype);  //订阅期限从明天开始
		$starttime = $readertime['starttime'];
		$endtime = $readertime['endtime'];
		$title = $this->mk_title($data);
		//var_dump($readertime);exit;
		
		//检查是否已订阅此栏目组
		$old_orderinfo = $orderinfo = $this->payview_order->get_by_pvcid($pvcid);
		/*if($old_orderinfo && $orderinfo['status']!=1)
		{
			//未付款订单
			$update_data = array();
			if($orderinfo['starttime']<$starttime)
			{
				//更新阅读期限
				$update_data['starttime'] = $starttime;
				$update_data['endtime'] = $endtime;
			}
			$update_data['is_invoice'] = $is_invoice;
			$update_data['invoice_title'] = $invoice_title;
			$update_data['post_name'] = $post_name;
			$update_data['post_address'] = $post_address;
			$update_data['post_phone'] = $post_phone;
			$update_data['post_fees'] = $post_fees;
			$this->payview_order->edit($orderinfo['oid'],$update_data);
			$orderinfo = array_merge($orderinfo,$update_data);
			$amount = $orderinfo['payfee'];
			$orderno = $orderinfo['orderno'];
			$creatorder = 0;
		}
		else if($orderinfo['endtime']>TIME)*/
		if($orderinfo['endtime']>TIME && $orderinfo['status']==1)
		{
			//已订阅且未过期，续订，订阅期限从未过期订单的结束时间开始，如果阅读权限时间也订单时间不同，还需要获取权限中的时间进行比较
			$readertime = $this->readertime($timetype, date('Y-m-d',$orderinfo['endtime']+86400));
			$starttime = $readertime['starttime'];
			$endtime = $readertime['endtime'];
			$renew = 1;	
		}
		
		if($creatorder)
		{
			//创建新订单
			$amount = $data['fee'];
			$orderno = $this->mk_tradeno();
			
			$orderinfo = array(
					'orderno' => $orderno,
					'pvcid' => $pvcid,
					'userid' => $userid,
					'username' => $username,
					'type' => 0,
					'payfee' => $amount,
					'starttime' =>$starttime,
					'endtime' =>$endtime,
					'status' => 0,
					'is_invoice' =>$is_invoice,
					'invoice_title' =>$invoice_title,
					'post_name' =>$post_name,
					'post_address' =>$post_address,
					'post_phone' =>$post_phone,
					'post_fees' =>$post_fees,
			);
			$this->payview_order->add($orderinfo);
		}

		
		$is_invoice && $amount += $post_fees;
		loader::import('lib.pay_function', app_dir('pay'));
		$payment_url = $this->order_url.'&orderno='.$orderno;
		$page_ret_url = APP_URL.'?app=payview&controller=order&action=receive';
		$bg_ret_url = '';		//格式：app/controller/action
		$checkvalue = make_verify_info($amount,$title,$payment_url,$orderno);
		$sHtmlText = '
		<form id="pay" action="?app=pay&controller=account&action=buyindex" method="post"> 
		<input type="hidden" name="amount" value="'.$amount.'">
		<input type="hidden" name="title" value="'.$title.'">
		<input type="hidden" name="orderno" value="'.$orderno.'">
		<input type="hidden" name="payment_url" value="'.$payment_url.'">
		<input type="hidden" name="page_ret_url" value="'.$page_ret_url.'">
		<input type="hidden" name="bg_ret_url" value="'.$bg_ret_url.'">
		<input type="hidden" name="checkvalue" value="'.$checkvalue.'">
		<input type="submit" class="zl-ok" value="支付订单确认">
		</form> ';
		
		$this->template->assign('sHtmlText', $sHtmlText);
		$this->template->assign('renew', $renew);
		$this->template->assign('old_orderinfo', $old_orderinfo);
		$this->template->assign('orderinfo', $orderinfo);
		$this->template->assign('data', $data);
		$this->template->assign('title', $title);
		$this->template->display('payview/order_confirm.html');
	}

	//重新支付
	public function repay()
	{
		$orderno = id_format($_REQUEST['orderno']);
		$orderinfo = $this->payview_order->get_by('orderno', $orderno);
		if(empty($orderinfo))
		{
			$this->showmessage('此订单不存在！');
		}
		if($orderinfo['status']==1)
		{
			$this->showmessage('此订单已支付！', $this->order_url.'&orderno='.$orderno);
		}
		if($orderinfo['status']==2)
		{
			$this->showmessage('此订单已关闭！', $this->order_url.'&orderno='.$orderno);
		}
		
		$data = $this->payview_category->get($orderinfo['pvcid']);
		
		
		$timetype = $data['timetype'];
		$readertime = $this->readertime($timetype);
		$starttime = $readertime['starttime'];
		$endtime = $readertime['endtime'];
		$title = $this->mk_title($data);
		$amount = $orderinfo['payfee'];
		$orderno = $orderinfo['orderno'];
		
		$update_data = array();
		if($orderinfo['starttime']<$starttime)
		{
			//更新阅读期限
			$update_data['starttime'] = $starttime;
			$update_data['endtime'] = $endtime;
		}
		if(isset($_REQUEST['is_invoice']))
		{
			$is_invoice = $_REQUEST['is_invoice'];
			$invoice_title = $_REQUEST['invoice_title'];
			$post_name = $_REQUEST['post_name'];
			$post_address = $_REQUEST['post_address'];
			$post_phone = $_REQUEST['post_phone'];
			$update_data['is_invoice'] = $is_invoice;
			$update_data['invoice_title'] = $invoice_title;
			$update_data['post_name'] = $post_name;
			$update_data['post_address'] = $post_address;
			$update_data['post_phone'] = $post_phone;
		}
		$post_fees = $this->setting['post_fees'];
		$update_data['post_fees'] = $post_fees;
		$this->payview_order->edit($orderinfo['oid'],$update_data);
		$orderinfo = array_merge($orderinfo,$update_data);

		
		$orderinfo['is_invoice'] && $amount += $post_fees;
		loader::import('lib.pay_function', app_dir('pay'));
		$payment_url = $this->order_url.'&orderno='.$orderno;
		$page_ret_url = APP_URL.'?app=payview&controller=order&action=receive';
		$bg_ret_url = '';		//格式：app/controller/action
		$checkvalue = make_verify_info($amount,$title,$payment_url,$orderno);
		$sHtmlText = '
		<form id="pay" action="?app=pay&controller=account&action=buyindex" method="post"> 
		<input type="hidden" name="amount" value="'.$amount.'">
		<input type="hidden" name="title" value="'.$title.'">
		<input type="hidden" name="orderno" value="'.$orderno.'">
		<input type="hidden" name="payment_url" value="'.$payment_url.'">
		<input type="hidden" name="page_ret_url" value="'.$page_ret_url.'">
		<input type="hidden" name="bg_ret_url" value="'.$bg_ret_url.'">
		<input type="hidden" name="checkvalue" value="'.$checkvalue.'">
		<input type="submit" class="zl-ok" value="支付订单确认">
		</form> ';
		
		$this->template->assign('sHtmlText', $sHtmlText);
		$this->template->assign('orderinfo', $orderinfo);
		$this->template->assign('data', $data);
		$this->template->assign('title', $title);
		$this->template->display('payview/order_confirm.html');
	}
	
	//接收支付返回结果
	public function receive()
	{
		$orderno = $_GET['orderno'];
		$checkvalue = $_GET['checkvalue'];
		$status = $_GET['status'];
		$payment_url = $this->order_url.'&orderno='.$orderno;
		//$payment_url = $this->cookie->get('payment_url');  //提交支付时的订单查看地址
		//用$orderno取出原订单数据进行校验$checkvalue
		$order_data = $this->payview_order->get_by('orderno', $orderno);
		if(empty($order_data)) $this->showmessage('订单不存在！');
		//栏目组信息
		$data = $this->payview_category->get($order_data['pvcid']);
		$title = $this->mk_title($data);
		$amount = $order_data['is_invoice'] ? $order_data['payfee'] + $order_data['post_fees'] : $order_data['payfee'];
		loader::import('lib.pay_function', app_dir('pay'));
		
		if(!verify_info($amount,$title,$payment_url,$orderno,$checkvalue,$status))
		{
			$this->showmessage('支付验证失败！请重新支付！', $this->order_url.'&orderno='.$orderno, 5000);
		}
		
		if($status==1)
		{
			//支付成功
			$this->payview_order->update(array('status' => 1), array('orderno' => $orderno));
			//添加权限
			$this->payview_power->edit($order_data);
			$this->showmessage('订阅成功', $this->order_url.'&orderno='.$orderno, 3000);
		}
		else
		{
			//支付失败
			$this->payview_order->update(array('status' => 2), array('orderno' => $orderno));
			$this->showmessage('支付失败！', $this->order_url.'&orderno='.$orderno, 5000);
		}
	}
	
	public function showorder()
	{
		$orderno = $_GET['orderno'];
		$data = $this->payview_order->get_by('orderno', $orderno);
		$data = $this->payview_order->output($data);
		if(empty($data)) $this->showmessage('订单不存在！');
		$category_data = $this->payview_category->get($data['pvcid']);
		
		$this->template->assign('category_data', $category_data);
		$this->template->assign('data', $data);
		$this->template->assign('setting', $this->setting);
		$this->template->display('payview/order_show.html');
	}
	
	public function showpower()
	{
		$userid = $this->userid;
		$where = "userid=$userid AND endtime>".TIME;
		$data = $this->payview_power->select($where);
		$this->payview_power->_output($data);print_r($data);
		
		$this->template->assign('data', $data);
		$this->template->display('payview/power_show.html');
	}
	
	/* 
	* 返回订阅起始时间与结束时间
	* @param $months	月数
	* @param $fromtime	开始日期时间戳
	*/
	private function readertime($months, $fromtime = null)
	{
		import('helper.date');
		$fromtime = $fromtime ? $fromtime : TIME;
		$date = new date($fromtime);
		$starttime = date('Y-m-d',$date->timestamp);
		$starttime .= '00:00:00';
		$starttime = strtotime($starttime);	
		$date->add(1);
		$endtime = $date->add($months,'m');
		$endtime = date(mktime(23, 59, 59, $endtime->month, $endtime->day, $endtime->year));
		
		return array('starttime' =>$starttime,'endtime' =>$endtime);
	}

	// 生成订单标题
	public function mk_title($data)
	{
		$title = $data['title'].'('.$data['timetype'].'个月)';
		return $title;
	}

	// 生成订单号
	public function mk_tradeno()
	{
		mt_srand(microtime(TRUE) * 1000000);
		return date('YmdHis').str_pad(mt_rand( 1, 99999), 5, '0', STR_PAD_LEFT);
	}
}