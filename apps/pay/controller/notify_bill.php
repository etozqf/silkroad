<?php
/**
 * 快钱通知页面
 *
 * @author zhouqingfeng
 * @copyright 2010 (c) CmsTop
 * @date 2012/06/04
 * @version $Id$
 */

class controller_notify_bill extends pay_controller_abstract
{	
	var $url;	//提示后自动跳转地址
	public function __construct(&$app) 
	{
		parent::__construct($app);
		$this->cookie = factory::cookie();
		$this->url = $this->cookie->get('url');
		$this->url = $this->url ? $this->url : '?app=pay&controller=charge&action=index';
	}

	public function success()
	{
		//消费支付不提示直接进入余额扣除的地址
		if($this->cookie->get('buy_entrance')=='1')
		{
			header("Location: ".$this->url);
		}
		else
		{
			$this->showmessage('恭喜您，交易成功！',$this->url);
		}
	}

	public function failed()
	{		
		$this->showmessage('交易失败，请重试！',$this->url);
	}

	public function error()
	{
		$this->showmessage('验证签名错误，请重试！',$this->url);
	}
}