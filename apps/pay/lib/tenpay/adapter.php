<?php
require app_dir('pay').'lib/tenpay/classes/PayRequestHandler.class.php';
require app_dir('pay').'lib/tenpay/classes/PayResponseHandler.class.php';

class adapter extends pay_base
{
	private $charge = null;
	function __construct($platform, $forminfo)
	{
		// 初始化数据库配置信息
		parent::init_config($platform);
		$this->charge = loader::model('charge', 'pay');
		$this->set_config($forminfo);
	}

	// 设置 接口 所需要的配置信息
	public function set_config($forminfo)
	{
		$chargeinfo = $this->charge->get($forminfo['out_trade_no']);

		$Order = array(
			'bargainor_id' => $this->config['bargainor_id'],	// 商户号
			'subject' => $forminfo['subject'],	// 商品名称
			'key' => $this->config['key'],	// 密钥
			'return_url' => APP_URL.$this->config['return_url'],	// 返回处理地址
			'sp_billno' => $forminfo['out_trade_no'],	// 商家订单号
			'transaction_id' => $this->config['bargainor_id'].date("Ymd").date("His").rand(1000, 9999),	// 财付通交易单号
			'total_fee' => $forminfo['total_fee'] * 100,	// 金额
		);
		$this->config = $Order;
	}

	// 生成的 tenpay 链接
	public function receive()
    {
		return $this->_build_URL($this->config);
    }

	// 配置 tenpay 参数
	private function _build_URL($config)
	{
		/* 创建支付请求对象 */
		$reqHandler = new PayRequestHandler();
		$reqHandler->init();
		$reqHandler->setKey($config['key']);

		//----------------------------------------
		//设置支付参数
		//----------------------------------------
		$reqHandler->setParameter("bargainor_id", $config['bargainor_id']);			//商户号
		$reqHandler->setParameter("sp_billno", $config['sp_billno']);					//商户订单号
		$reqHandler->setParameter("transaction_id", $config['transaction_id']);		//财付通交易单号
		$reqHandler->setParameter("total_fee", $config['total_fee']);					//商品总金额,以分为单位
		$reqHandler->setParameter("return_url", $config['return_url']);				//返回处理地址
		$reqHandler->setParameter("desc", $config['subject']);	//商品名称
		$reqHandler->setParameter("cs", 'utf-8');	//字符集

		//用户ip,测试环境时不要加这个ip参数，正式环境再加此参数
		//$reqHandler->setParameter("spbill_create_ip", $chargeinfo['createip']);		

		$reqUrl = $reqHandler->getRequestURL();

	//	$html = '<button type="button">';
		$html .= '<a href="'.$reqUrl.'" target="_blank" >财付通支付</a>';
	//	$html .='</button>';
		return $html;
	}
}