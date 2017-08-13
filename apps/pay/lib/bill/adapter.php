<?php
loader::import('lib.bill.class.bill_service', app_dir('pay'));
//loader::import('lib.bill.class.bill_recieve', app_dir('pay'));

class adapter extends pay_base
{
	function __construct($platform, $forminfo)
	{
		// 初始化数据库配置信息
		parent::init_config($platform);
		$this->set_config($forminfo);
	}

	// 设置 快钱接口 所需要的配置信息
	public function set_config($forminfo)
	{
		//接口配置信息
		//$this->config['pid'] = '1001181534101';
		$config = array(
			'merchantAcctId' 	=> $this->config['pid'],			//人民币网关账号，该账号为11位人民币网关商户编号+01，测试号1001181534101
			'inputCharset' 		=> '1',								//编码方式，1代表 UTF-8; 2 代表 GBK; 3代表 GB2312; 默认为1
			'pageUrl' 			=> '',								//接收支付结果的页面地址，该参数一般置为空即可
			'bgUrl' 			=> APP_URL.'?app=pay&controller=account&action=bill',		//服务器接收支付结果的后台地址，该参数务必填写
			'version' 			=> 'v2.0',				//网关版本，固定值：v2.0,该参数必填
			'language' 			=> '1',					//语言种类，1代表中文显示，2代表英文显示
			'signType' 			=> '4',					//签名类型,该值为4，代表PKI加密方式
			'payerName' 		=> '',					//支付人姓名,可以为空
			'payerContactType' 	=> '',					//支付人联系类型，1 代表电子邮件方式；2 代表手机联系方式。可以为空
			'payerContact' 		=> '',					//支付人联系方式，与payerContactType设置对应，payerContactType为1，则填写邮箱地址；payerContactType为2，则填写手机号码。可以为空。
			'orderId' 			=> $forminfo['out_trade_no'],		//订单号
			'orderAmount' 		=> $forminfo['total_fee']*100,		//订单金额，金额以“分”为单位
			'orderTime' 		=> date("YmdHis"),					//订单提交时间，格式：yyyyMMddHHmmss
			'productName' 		=> $forminfo['subject']?$forminfo['subject']:'',		//商品名称，可以为空
			'productNum' 		=> $forminfo['number']?$forminfo['number']:'',			//商品数量，可以为空	
			'productId' 		=> $forminfo['id']?$forminfo['id']:'',					//商品代码，可以为空
			'productDesc' 		=> $forminfo['body']?$forminfo['body']:'',				//商品描述，可以为空
			'ext1' 				=> '',			//扩展字段1，商户可以传递自己需要的参数，支付完快钱会原值返回，可以为空
			'ext2' 				=> '',			//扩展字段2，商户可以传递自己需要的参数，支付完快钱会原值返回，可以为空
			'payType' 			=> '00',		//支付方式，一般为00，代表所有的支付方式。如果是银行直连商户，该值为10	
			'bankId' 			=> '',			//银行代码，如果payType为00，该值可以为空；如果payType为10，该值必须填写，具体请参考银行列表
			'redoFlag' 			=> '',			//同一订单禁止重复提交标志，实物购物车填1，虚拟产品用0。1代表只能提交一次，0代表在支付不成功情况下可以再提交。可为空
			'pid' 				=> substr($this->config['pid'],0,11),		//快钱合作伙伴的帐户号，即商户编号，可为空		
		);
		// 合并快钱数据库配置信息、接口配置信息、购买商品信息
		$this->config = array_merge($this->config, $config, $forminfo);
	}

	// 返回快钱 生成的 form表单
	public function receive()
    {
		$bill = new bill_service($this->config);
		return $bill->build_form();
    }

	// 验证是否为快钱返回及交易是否成功
	public function notify()
	{
		$bill = new bill_notify($this->config);
		return $bill->notify_verify();
	}
}