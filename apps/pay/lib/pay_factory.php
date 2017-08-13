<?php
/**
 * 支付工厂类
 *
 * @author liuyuan
 * @copyright 2010 (c) CmsTop
 * @date 2011/04/27
 * @version $Id$
 * @todo 根据名称后台设置的支付平台拼音作switch判断可能不够通用
 */

class pay_factory
{
	private static $objects;

	public static function factory($platform, $forminfo = NUll)
	{
		loader::import('lib.pay_base', app_dir('pay'));
		import('helper.pinyin');
		$platform['paycode'] = $classname = pinyin::get($platform['name'], 'utf-8');

		if (!isset(self::$objects[$classname]))
		{
			switch($classname)
			{
				case 'zhifubao':
					$adapter = 'alipay';
					break;
				case 'masget':
					$adapter = 'masget';
					break;
				case 'caifutong':
					$adapter = 'tenpay';
					break;
				case 'yinlianzaixian':
					$adapter = 'chinapay';
					break;
				case 'kuaiqianzhifu':
					$adapter = 'bill';
					break;
			}

			loader::import('lib.'.$adapter.'.adapter', app_dir('pay'));
			self::$objects[$classname] = new adapter($platform, $forminfo);
		}
		return self::$objects[$classname];
	}
}