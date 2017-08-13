<?php
loader::import('lib.chinapay.class.chinapay_service', app_dir('pay'));
loader::import('lib.chinapay.class.chinapay_notify', app_dir('pay'));

class adapter extends pay_base
{
	function __construct($platform, $forminfo)
	{
		// 初始化数据库配置信息
		parent::init_config($platform);
		$this->set_config($forminfo);
	}

	// 设置 银联在线接口 所需要的配置信息
	public function set_config($forminfo)
	{
		//接口配置信息
		$config = array(
			'CuryId' 		=> '156',					//货币代码，3位，境内商户固定为156，表示人民币
			'Version' 		=> '20070129',				//接口版本号，境内支付为 20070129
			'GateId' 		=> '',						//支付网关号，4位，上线时建议留空
			'OrdId' 		=> $forminfo['out_trade_no'],						//订单号，**16位长度**
			'TransDate' 	=> date('Ymd',time()),								//订单日期
			'TransAmt' 		=> sprintf("%012d", $forminfo['total_fee']*100),	//订单金额，定长12位，以分为单位，不足左补0
			'TransType' 	=> '0001',					//交易类型，0001 表示支付交易，0002 表示退款交易，现无退款功能
			'Priv1' 		=> '',					//备注，最长60位，交易成功后会原样返回，可用于额外的订单跟踪等
			//页面返回地址，最长80位，当用户完成支付后，银行页面会自动跳转到该页面，并POST订单结果信息
			'PageRetUrl' 	=> APP_URL.'?app=pay&controller=account&action=chinapay',	
			//后台返回地址，最长80位，当用户完成支付后，银联服务器会POST订单结果信息到该页面
			'BgRetUrl' 		=> APP_URL.'?app=pay&controller=account&action=chinapay'
		);
		// 合并银联在线数据库配置信息、接口配置信息、购买商品信息
		$this->config = array_merge($this->config, $config, $forminfo);
	}

	// 返回银联在线 生成的 form表单
	public function receive()
    {
		$chinapay = new chinapay_service($this->config);
		return $chinapay->build_form();
    }

	// 验证是否为银联在线返回及交易是否成功
	public function notify()
	{
		$chinapay = new chinapay_notify($this->config);
		return $chinapay->notify_verify();
	}
}