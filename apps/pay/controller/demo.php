<?php
/**
 * 财务管理--购物消费测试
 *
 * @author zhouqingfeng
 * @copyright 2010 (c) CmsTop
 * @date 2011/06/06
 * @version $Id$
 */
loader::import('lib.pay_function', app_dir('pay'));
class controller_demo extends pay_controller_abstract
{

	function __construct(&$app) 
	{
		parent::__construct($app);
		$this->cookie = factory::cookie();
		
	}

	// 提交订单付费
	public function index()
	{
	
		// 测试数据
		$amount = $_POST['amount'] ? $_POST['amount'] : 0.01;
		$title = $_POST['title'] ? $_POST['title'] : '《编程思想》';
		$orderno = $_POST['orderno'] ? $_POST['orderno'] : 24;
	
		if($_POST['amount'])
		{
			$payment_url = APP_URL.'?app=pay&controller=demo&action=index';
			$page_ret_url = APP_URL.'?app=pay&controller=demo&action=show';
			$bg_ret_url = 'pay/demo/receive';
			$checkvalue = make_verify_info($amount,$title,$payment_url,$orderno);
			$formdata = '
			<form id="pay" action="?app=pay&controller=account&action=buyindex" method="post"> 
			<input type="hidden" name="amount" value="'.$amount.'">  <br >
			<input type="hidden" name="title" value="'.$title.'">  <br >
			<input type="hidden" name="orderno" value="'.$orderno.'">  <br >
			<input type="hidden" name="payment_url" value="'.$payment_url.'">  <br >
			<input type="hidden" name="page_ret_url" value="'.$page_ret_url.'">  <br >
			<input type="hidden" name="bg_ret_url" value="'.$bg_ret_url.'"> <br >
			<input type="hidden" name="checkvalue" value="'.$checkvalue.'">
			</form> 
			<script>document.getElementById("pay").submit();</script>';
		}
		else
		{
			$formdata = '提交订单付费
			<form id="pay" action="?app=pay&controller=demo&action=index" method="post"> 
			金额：<input type="text" name="amount" value="'.$amount.'">  <br >
			名称：<input type="text" name="title" value="'.$title.'">  <br >
			订单号：<input type="text" name="orderno" value="'.$orderno.'">  <br >
			<input type="submit" value="我要支付订单">  
			</form> 
			';
		}
		echo $formdata;
	}

	// 显示交易结果
	public function show()
	{
		$orderno = $_GET['orderno'];
		$checkvalue = $_GET['checkvalue'];
		$status = $_GET['status'];
		//$payment_url = $this->cookie->get('payment_url');  //提交支付时的订单查看地址
		//用$orderno取出原订单数据进行校验$checkvalue
		//$order_data = $this->order->get_by('orderno',$orderno);
		//if(!verify_info($amount,$title,$payment_url,$orderno,$checkvalue,$status)) $this->showmessage('支付信息不正确，支付失败！');
		if($status==1)
		{
			$data = $orderno.'|'.$checkvalue.'|'.$status;
			
			$this->showmessage('支付成功，此处显示订单支付后的详细信息');
		
		}
		else
		{
			$this->showmessage('支付失败！请重试！');
		}
	}

	// 接收交易结果并处理，后台处理，用户不可见
	public function receive()
	{
		$data = $_GET['orderno'].'|'.$_GET['checkvalue'].'|'.$_GET['status'];

		echo '1';
	}

	 
	
}