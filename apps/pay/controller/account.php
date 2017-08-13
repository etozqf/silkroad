<?php
/**
 * 财务管理--在线充值
 *
 * @author liuyuan
 * @copyright 2010 (c) CmsTop
 * @date 2011/04/27
 * @version $Id$
 */
loader::import('lib.pay_function', app_dir('pay'));
class controller_account extends pay_controller_abstract
{	
	private $account, $charge, $platform, $userid, $cookie, $tool, $pay_factory;
	public function __construct(&$app)
	{
		parent::__construct($app);
		//判断是否支付接口返回的操作
		$is_notify = in_array($_GET['action'],array('chinapay','bill','alipay','masget','tenpay'));
		if (!$this->_userid && !$is_notify) $this->showmessage('请登录', '?app=member&controller=index&action=login');

		$this->userid 		= $this->_userid;
		$this->cookie 		= factory::cookie();

		$this->charge 		= loader::model('charge');
		$this->account 		= loader::model('account');
		$this->platform 	= loader::model('admin/platform');
		$this->payment 		= loader::model('payment');
	}

	// 在线充值--显示
	// $_GET 传参需要做处理
	public function index()
	{
		$userid = $this->userid;

		// 记录金额、url 跳转页面用
		$_GET['amount'] && $amount = str_decode($_GET['amount'], config('config', 'authkey'));
		$_GET['url'] && $url = str_decode($_GET['url'], config('config', 'authkey'));

		if($amount) $this->cookie->set('amount', $amount);
		if($url) $this->cookie->set('url', $url);

		// 获取支付平台信息 disabled = 0 启用，1 禁用
		$platform = $this->platform->select(array('disabled' => 0) ,"apiid, name, logo, description, url", '`sort` ASC');
		if(!$platform) $this->showmessage('没有可用的支付平台，请联系管理员添加');

		// 获取用户账户信息，没有获取到数据则自动开户
		$account = $this->account->get($userid);
		if(!$account)
		{
			$this->account->add($userid);
			$account = $this->account->get($userid);
		}
		if(!$account) $this->showmessage('系统异常，请联系管理员');

		// 如果有充值金额则直接显示出来
		$this->template->assign('amount', $amount);
		$this->template->assign('platform', $platform);
		$this->template->assign('balance', $account['balance']);
		$this->template->display('pay/account_add.html');
	}
	

	// 在线充值--数据提交
	public function pay()
	{
		header("Content-type: text/html; charset=utf-8");
		$userid = $this->userid;
		$platform = $this->_check_platform($_POST['apiid']);
		if(!$_POST['amount']) $this->showmessage('充值金额不能为空');

		$forminfo = array(
			'out_trade_no' => $this->charge->add($_POST['amount'], $_POST['apiid']),
			'subject' => '在线充值',
			'body' => '',
			'total_fee' => $_POST['amount'],
		);

		// 加载支付接口，将配置信息、购物信息传入
		loader::lib('pay_factory');
		$pay_handle = pay_factory::factory($platform, $forminfo);
		$sHtmlText = $pay_handle->receive();

		// 将需要发送的数据（$sHtmlText）写进HTML
		$this->template->assign('sHtmlText', $sHtmlText);
		$this->template->assign('forminfo', $forminfo);
		$this->template->display('pay/account_confirm.html');
	}

	// 在线充值--重新支付
	public function repay()
	{
		exit;	//不支持重新支付
		header("Content-type: text/html; charset=utf-8");
		$userid = $this->userid;
		$orderno = $_GET['id'];
		if(!is_numeric($orderno)) $this->showmessage('对不起，无此充值记录！');
		
		$cinfo = $this->charge->get($orderno);	// 获取充值记录

		if(!$cinfo || $cinfo['createdby']!=$userid)
		{
			$this->showmessage('对不起，无此充值记录！');
		}
		if($cinfo['status']==1)
		{
			$this->showmessage('此订单已经充值成功！');
		}
		
		$platform = $this->_check_platform($cinfo['apiid']);
		if(!$cinfo['amount']) $this->showmessage('充值金额不能为空');

		$forminfo = array(
			'out_trade_no' => $cinfo['orderno'],
			'subject' => '在线充值',
			'body' => '',
			'total_fee' => $cinfo['amount'],
		);

		// 加载支付接口，将配置信息、购物信息传入
		loader::lib('pay_factory');
		$pay_handle = pay_factory::factory($platform, $forminfo);
		$sHtmlText = $pay_handle->receive();

		// 将需要发送的数据（$sHtmlText）写进HTML
		$this->template->assign('sHtmlText', $sHtmlText);
		$this->template->assign('forminfo', $forminfo);
		$this->template->display('pay/account_confirm.html');
	}

	// 购物消费--显示
	public function buyindex()
	{
		$userid = $this->userid;
		
		// 接收支付数据
		$_POST['amount'] && $amount = $_POST['amount'];						//支付金额
		$_POST['title'] && $title = $_POST['title'];						//订单（商品）名称
		$_POST['payment_url'] && $payment_url = $_POST['payment_url'];		//订单（商品）地址
		$_POST['page_ret_url'] && $page_ret_url = $_POST['page_ret_url'];	//页面返回地址，当用户完成支付后，会自动跳转到该页面
		$_POST['bg_ret_url'] && $bg_ret_url = $_POST['bg_ret_url'];			//后台返回地址，当用户完成支付后，会POST订单结果信息到该页面，格式：app/controller/action
		$_POST['orderno'] && $orderno = $_POST['orderno'];					//订单号
		$_POST['checkvalue'] && $checkvalue = $_POST['checkvalue'];			//订单校验值，可由make_verify_info生成
		$_POST['ext'] && $ext = $_POST['ext'];			//扩展数据，返回给支付页面
		if(!verify_info($amount, $title, $payment_url, $orderno, $checkvalue)) $this->showmessage('您提交的信息错误！请返回重试！', $payment_url);

		// 需要识别此购物消费是否已经支付过
		$order_identify = md5($checkvalue);
		$payment = $this->payment->get_by('order_identify',$order_identify);
		if($payment)
		{
			$status = 1;	//支付状态，1为成功，其他为失败
			$new_checkvalue = make_verify_info($amount, $title, $payment_url, $orderno, $status);
			$post_str = '&orderno='.$orderno.'&checkvalue='.$new_checkvalue.'&status='.$status.'&amount='.$payment['amount'].'&title='.$payment['title'].'&ext='.$ext;
			$this->showmessage('您的订单已经支付成功！', $page_ret_url.$post_str);
		}
				
		// 扣除余额地址url 跳转页面用
		$url = APP_URL.'?app=pay&controller=account&action=buydebit';
		$this->cookie->set('url', $url);
		//进入购买订单消费支付入口，给网银支付后跳转页面调用，如果是消费支付则不显示充值成功提示
		$this->cookie->set('buy_entrance', '1');
		// 记录订单信息
		if($payment_url) $this->cookie->set('payment_url', $payment_url);
		if($amount) $this->cookie->set('amount', $amount);
		if($title) $this->cookie->set('title', $title);
		if($orderno) $this->cookie->set('orderno', $orderno);
		if($checkvalue) $this->cookie->set('checkvalue', $checkvalue);
		if($page_ret_url) $this->cookie->set('page_ret_url', $page_ret_url);
		if($bg_ret_url) $this->cookie->set('bg_ret_url', $bg_ret_url);
		if($ext) $this->cookie->set('ext', $ext);

		// 获取支付平台信息 disabled = 0 启用，1 禁用
		$platform = $this->platform->select(array('disabled' => 0) ,"apiid, name, logo, description, url", '`sort` ASC');
		if(!$platform) $this->showmessage('没有可用的支付平台，请联系管理员添加', $payment_url);

		// 获取用户账户信息，没有获取到数据则自动开户
		$account = $this->account->get($userid);
		if(!$account)
		{
			$this->account->add($userid);
			$account = $this->account->get($userid);
		}
		if(!$account) $this->showmessage('系统异常，请联系管理员', $payment_url);

		// 页面显示
		$this->template->assign('amount', $amount);
		$this->template->assign('payment_url', $payment_url);
		$this->template->assign('title', $title);
		$this->template->assign('platform', $platform);
		$this->template->assign('balance', $account['balance']);
		$this->template->display('pay/account_buy_add.html');
	}
	
	// 购物消费--网银支付
	public function buypay()
	{
		header("Content-type: text/html; charset=utf-8");
		$userid = $this->userid;
		$platform = $this->_check_platform($_POST['apiid']);
		if(!$_POST['amount']) $this->showmessage('支付金额不能为空');
		
		$orderno = $this->charge->get_orderno($_POST['amount'], $_POST['apiid'], 1);
		
		$orderno || $orderno = $this->charge->add($_POST['amount'], $_POST['apiid']);

		$forminfo = array(
			'out_trade_no' => $orderno,
			'subject' => $this->cookie->get('title'),
			'body' => '',
			'total_fee' => $_POST['amount'],
		);

		// 加载支付接口，将配置信息、购物信息传入
		loader::lib('pay_factory');
		$pay_handle = pay_factory::factory($platform, $forminfo);
		$sHtmlText = $pay_handle->receive();

		// 将需要发送的数据（$sHtmlText）写进HTML
		$this->template->assign('sHtmlText', $sHtmlText);
		$this->template->assign('forminfo', $forminfo);
		$this->template->display('pay/account_buy_confirm.html');
	}
	
	// 购物消费--从余额扣除
	public function buydebit()
	{
		$userid = $this->userid;
		
		// 获取用户账户信息，没有获取到数据则自动开户
		$account = $this->account->get($userid);
		if(!$account)
		{
			$this->account->add($userid);
			$account = $this->account->get($userid);
		}
		if(!$account) $this->showmessage('系统异常，请联系管理员');
		
		$amount = $this->cookie->get('amount');
		$title = $this->cookie->get('title');
		$payment_url = $this->cookie->get('payment_url');
		$orderno = $this->cookie->get('orderno');
		$checkvalue = $this->cookie->get('checkvalue');
		$page_ret_url = $this->cookie->get('page_ret_url');
		$bg_ret_url = $this->cookie->get('bg_ret_url');
		$ext = $this->cookie->get('ext');
		
		if(!$amount) $this->showmessage('支付金额不能为空');
		if($account['balance']<$amount) $this->showmessage('您的余额不足，请返回从网上银行支付！', $payment_url);
		
		//post返回数据(orderno,checkvalue,status)给购物订单支付接口调用
		$status = 1;	//支付状态，1为成功，其他为失败
		$new_checkvalue = make_verify_info($amount, $title, $payment_url, $orderno, $status);
		//echo $amount,'---', $title,'---', $payment_url,'---', $orderno,'---', $status,'---',$new_checkvalue;exit;
		$post_str = '&orderno='.$orderno.'&checkvalue='.$new_checkvalue.'&status='.$status.'&amount='.$amount.'&title='.$title.'&ext='.$ext;
		
		// 需要识别此消费是否已经支付过
		$order_identify = md5($checkvalue);
		$payment = $this->payment->get_by('order_identify', $order_identify);
		if($payment) $this->showmessage('您的订单已经支付成功！', $page_ret_url.$post_str);
		
		$data = array(
			'url' => $payment_url,
			'title' => $title,
			'amount' => $amount,
			'order_identify' => $order_identify,
		);
		//记录消费成功，更新帐户余额
		$this->payment->add($data);
		$this->account->debit($userid,$amount);

		if(!empty($bg_ret_url))
		{
			//使用下面的方式
			global $cmstop;
			//页面接收
			$aca = explode('/',$bg_ret_url);
			count($aca)==3 && $response = $cmstop->execute($aca[0],$aca[1],$aca[2],$data);
		}
		//支付成功，请空cookie数据
		$this->cookie->set('buy_entrance', '');
		$this->cookie->set('amount', '');
		$this->cookie->set('title', '');
		$this->cookie->set('orderno', '');
		$this->cookie->set('checkvalue', '');
		$this->cookie->set('page_ret_url', '');
		$this->cookie->set('bg_ret_url', '');
		$this->cookie->set('ext', '');
		$this->cookie->set('url', $payment_url);
		// $this->cookie->set('payment_url', '');
		
		//直接进入用户前台页面
		header("Location: ".$page_ret_url.$post_str);
		
		//$this->showmessage('恭喜您，'.$title.'支付成功！订单号：'.$orderno, $page_ret_url.$post_str);
	}


	// 银联在线 支付成功 处理 chinapay 返回的数据
	public function chinapay()
	{
		//获取交易应答的各项值
		$url = $this->cookie->get('url');
		$orderno = $_POST["orderno"];	// 交易单号
		
		loader::import('lib.chinapay.class.chinapay_notify', app_dir('pay'));
		$chinapay = new chinapay_notify($_POST);

		if($chinapay->notify_verify())
		{
			$cinfo = $this->charge->get($orderno);	// 获取充值记录
			$this->pay_success($cinfo, $cinfo['amount'], $url);
		}
		else
		{
			$this->charge->failed($orderno);
			$this->showmessage($chinapay->msg . '，请重试', $url ? $url : '?app=pay&controller=charge&action=index');
		}
	}

	// 快钱 支付成功 处理 99bill 返回的数据
	public function bill()
	{
		//测试交易单号
		//$_REQUEST["orderId"] = '2012041915233964';
		//获取交易应答的各项值
		//$url = false;
		$url = $this->cookie->get('url');
		$showmessage = false;		//不需要跳转到提示页面
		$orderno = $_REQUEST["orderId"];		// 交易单号
		
		loader::import('lib.bill.class.bill_recieve', app_dir('pay'));
		$bill = new bill_notify($_REQUEST);

		if($bill->notify_verify())
		{
			$cinfo = $this->charge->get($orderno);	// 获取充值记录
			$this->pay_success($cinfo, $cinfo['amount'], $url, $showmessage);
		}
		else
		{
			$this->charge->failed($orderno);
		}
		//支付结果，无论支付成功与否必须为1
		$result = 1;
		//快钱自动跳转到show页面地址，消费者最终看到的就是该页面
		$redirecturl = APP_URL.'?app=pay&controller=notify_bill&action='.$bill->notify;
		//通知快钱已经接收到支付结果
		echo "<result>{$result}</result> <redirecturl>{$redirecturl}</redirecturl>";
	}

	// 支付宝 支付成功成功后 会执行这个方法（页面跳转同步通知）
	public function alipay()
	{
		// is_success 支付宝成功响应了请求
		// trade_status 该笔交易的交易状态

		$url = $this->cookie->get('url');
		$trade_status = $_GET['trade_status'];	// 交易状态
		$out_trade_no = $_GET['out_trade_no'];	// 交易单号
		$amount = $_GET['total_fee'];			// 充值金额

		$cinfo = $this->charge->get($out_trade_no);	// 获取充值记录

		if(isset($_GET['is_success']) && ( $trade_status == 'TRADE_SUCCESS' || $trade_status == 'TRADE_FINISHED'))
		{
			$this->pay_success($cinfo, $amount, $url);
		}
		else
		{
			$this->charge->failed($out_trade_no);
			$this->showmessage('支付失败，请重试', $url ? $url : '?app=pay&controller=charge&action=index');
		}
	}

	// masget 支付成功 处理 masget 返回的数据
	public function masget()
	{
		$url = $this->cookie->get('url');
		$OrderInfo = base64_decode($_POST['OrderInfo']);
		// $OrderInfo = array(
			// 'orderNumber' => '2011060220101108822',
			// 'merId'	=> '999999999',
		// );

		parse_str($OrderInfo, $OrderInfo);
		file_put_contents(CACHE_PATH.'3.txt', print_r($OrderInfo, TRUE));

		$cinfo = $this->charge->get($OrderInfo['orderNumber']);
		$setting = unserialize(array_shift($this->platform->get_by_apiid($cinfo['apiid'], 'setting')));
		$merid = $setting['merid'];

		if($OrderInfo['merId'] == $merid)
		{
			$this->pay_success($cinfo, $cinfo['amount'], $url);
		}
		else
		{
			$this->charge->failed($OrderInfo['orderNumber']);
			$this->showmessage('商户号不正确，请重试', $url ? $url : '?app=pay&controller=charge&action=index');
		}
	}

	// tenpay 支付成功 处理 tenpay 返回的数据
	public function tenpay()
	{
		$url = $this->cookie->get('url');

		$orderNumber 	= $_GET['sp_billno'];			// 订单号
		$bargainor_id 	= $_GET['bargainor_id'];		// 商户号
		$pay_result 	= $_GET['pay_result'];			// 支付结果

		$cinfo = $this->charge->get($orderNumber);
		$setting = unserialize(array_shift($this->platform->get_by_apiid($cinfo['apiid'], 'setting')));

		if($bargainor_id == $setting['bargainor_id'])
		{
			if('0' == $pay_result)
			{
				$this->pay_success($cinfo, $cinfo['amount'], $url);
			}
			else
			{
				$this->charge->failed($orderNumber);
				$this->showmessage('支付失败请重新', $url ? $url : '?app=pay&controller=charge&action=index');
			}
		}
		else
		{
			$this->charge->failed($orderNumber);
			$this->showmessage('商户号不正确，请重新支付', $url ? $url : '?app=pay&controller=charge&action=index');
		}
	}

	/**
	 * 验证成功后对用户数据进行相应的修改
	 *
	 * @param $charge 订单信息
	 * @param $amount 金额
	 * @param $url 支付成功后跳转的url
	 * @param $showmessage 显示显示信息内容
	 */
	private function pay_success($charge, $amount, $url, $showmessage=true)
	{
		$showmessage && !$charge && $this->showmessage('不存在此订单号，请重新支付');
		if($charge['status'] != 1) {	// 如果没有支付成功
			// 资金账户 （cmstop_pay_account） 增加金额
			$this->account->deposit($charge['createdby'], $amount);

			// 充值记录 （cmstop_pay_charge） 修改交易状态
			$this->charge->deposit($charge['orderno']);
			
		}
		//邮件通知用户
		//$this->send_email($charge);

		//消费支付不提示直接进入余额扣除的地址
		if($this->cookie->get('buy_entrance')=='1')
		{
			header("Location: ".$url);
		}
		// 根据url进行页面跳转
		$showmessage && $url && $this->showmessage('支付成功, 正在进入购买页', $url);
		$showmessage && !$url && $this->showmessage('恭喜您，支付成功','?app=pay&controller=charge&action=index');
	}

	/**
	 * 发送邮件通知
	 *
	 * @param $charge 订单信息
	 */
	function send_email($charge)
	{
		//$charge = array();
		//$charge['amount'] = '1200.00';
		//$charge['orderno'] = '23417234618263498';
		loader::import('model.member_front', app_dir('member'));
		$member = new model_member_front();
		$member_data = $member->getProfile($this->_userid,false);
		$charge['username'] = $member_data['username'];

		$sendmail = & factory::sendmail();
		$to = $member_data['email'];
		$subject = $this->setting['emailtitle'];
		$message = $this->setting['emailcontent'];
		//收件用户名 订单号 订单金额
		foreach($charge as $k =>$v)
		{
			$message = str_replace('{'.$k.'}',$v,$message);
		}

		$from = setting('system','mail');
		
		return $sendmail->execute($to, $subject, $message, $from);
	}

	/**
	 * 验证支付平台是否存在
	 *
	 * @param int $platformid 支付平台ID
	 * @return mixed 支付平台相关信息
	 */
	private function _check_platform($platformid)
	{
		if(!empty($platformid))
		{
			$platform = $this->platform->get($platformid);
			return $platform ? $platform : $this->showmessage('该支付平台不存在，请联系管理员添加');
		}
		else
		{
			$this->showmessage('请选择支付平台');
		}
	}
}