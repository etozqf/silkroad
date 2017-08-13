<?php
/**
 * 支付平台配置信息初始化
 *
 * @author liuyuan
 * @copyright 2010 (c) CmsTop
 * @date 2011/04/27
 * @version $Id$
 * @function set_config 重写该方法初始化相应配置信息，可参考alipay
 * @todo URL （支付成功后服务器跳转地址）目前还不够灵活
 * return_url：页面跳转同步通知，notify_url：服务器异步通知，非本地测试
 */

abstract class pay_base
{
	protected $config = array();
	abstract protected function set_config($forminfo);
	final public function init_config($platform)
	{
		$this->config = unserialize($platform['setting']);
	}
}