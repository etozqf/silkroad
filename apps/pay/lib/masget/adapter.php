<?php
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
		$chargeinfo = $this->charge->get($forminfo['out_trade_no']);	// 通过订单号获取交易信息
		$OrderInfo = array(
			'transType' => '01',	// 交易类型 01 消费 04 退货
			'merId'	=> $this->config['merid'],	// 商户代码 商户自己申请的
			'orderNumber' => $forminfo['out_trade_no'],	// 订单号
			'orderAmount' => $forminfo['total_fee'] * 100,	// 金额
			'orderTime' => date('YmdHis', $chargeinfo['created']),	//　订单创建时间
			'customerIp' => $chargeinfo['createip'],	// 客户IP
			'merURL'	=> urlencode(APP_URL.$this->config['return_url'])	//付款后跳转页面
		);
		$this->config['OrderInfo'] = $this->_build_post($OrderInfo);	// 加密业务数据
		$this->config['Md5'] = md5($this->config['merid']);			// 生成签名
	}

	// 生成的 form表单
	public function receive()
    {
		return $this->_build_form($this->config);
    }

	// 生成表单
	private function _build_form($config)
	{
		$html = "<form id='masgetsubmit' name='masgetsubmit' action='".$config['server_url']."' method='POST' target='_blank'>";
        $html .="<input type='hidden' name='OrderInfo' value='".$config['OrderInfo']."'/>";
        $html .="<input type='hidden' name='Md5' value='".$config['Md5']."'/>";
		$html .="<input type='submit' value='确认付款'>";
		$html .="</form>";
		return $html;
	}

	// 数据加密
	private function _build_post($post)
	{
		$arr = array();
		if(!is_array($post)) return false;
		foreach($post as $k => $v)
		{
			$arr[] = $k.'='.$v;
		}
		return base64_encode(implode('&', $arr));
	}
}