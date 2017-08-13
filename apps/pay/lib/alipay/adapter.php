<?php
loader::import('lib.alipay.class.alipay_function', app_dir('pay'));
loader::import('lib.alipay.class.alipay_notify', app_dir('pay'));
loader::import('lib.alipay.class.alipay_service', app_dir('pay'));

class adapter extends pay_base
{
	function __construct($platform, $forminfo)
	{
		// 初始化数据库配置信息
		parent::init_config($platform);
		$this->set_config($forminfo);
	}

	// 设置 支付宝接口 所需要的配置信息
	public function set_config($forminfo)
	{
		$config = array(
			'service' 		=> 'create_direct_pay_by_user',
			'payment_type' 	=> '1',
			'sign_type' 	=> 'MD5',
			'_input_charset'=> 'utf-8',
			'transport' 	=> 'http',
			'notify_url' 	=>  APP_URL.$this->config['notify_url'],
			'return_url' 	=>  APP_URL.$this->config['return_url'],
			'paymethod' 	=>  'directPay'
		);

		// 合并支付宝接口配置信息、数据库配置信息、购买商品信息 (arg_sort 排序数组)
		$this->config = arg_sort(array_merge($this->config, $config, $forminfo));
		$this->config['sign'] = build_mysign($this->config, $this->config['key']);	// 生成签名
	}

	// 返回支付宝 生成的 form表单
	public function receive()
    {
		$alipay = new alipay_service($this->config, $this->config['key'], $this->config['sign_type']);
		return $alipay->build_form();
    }

	// 验证是否为支付宝返回
	public function notify()
	{
		$alipay = new alipay_notify($this->config['partner'], $this->config['key'], $this->config['sign_type'], $this->config['_input_charset'], $this->config['transport']);
		return $alipay->return_verify();
	}
}