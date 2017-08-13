<?php

class filter
{
	private static $_allowtags = 'p|br|b|strong|hr|a|img|object|param|form|input|label|dl|dt|dd|div|font|span',
	               $_allowattrs = 'id|class|align|valign|src|border|href|target|width|height|title|alt|name|action|method|value|type|style',
	               $_disallowattrvals = 'expression|javascript:|behaviour:|vbscript:|mocha:|livescript:';
	
	function __construct($allowtags = null, $allowattrs = null, $disallowattrvals = null)
	{
		if ($allowtags) self::$_allowtags = $allowtags;
		if ($allowattrs) self::$_allowattrs = $allowattrs;
		if ($disallowattrvals) self::$_disallowattrvals = $disallowattrvals;
	}
	
	static function input($cleanxss = 1)
	{
        if (get_magic_quotes_gpc())
        {
           $_POST = stripslashes_deep($_POST);
           $_GET = stripslashes_deep($_GET);
           $_COOKIE = stripslashes_deep($_COOKIE);
           $_REQUEST = stripslashes_deep($_REQUEST);
        }
        if (!defined('IN_ADMIN') && !defined('IN_API') && $cleanxss)
        {
        	$_POST = self::xss($_POST);
        	$_GET = self::xss($_GET);
        	$_COOKIE = self::xss($_COOKIE);
        	$_REQUEST = self::xss($_REQUEST);
        }
	}
	
	static function xss($string)
	{
		if (is_array($string))
		{
			$cookieConfig = config('cookie');
			foreach ($string as $key => & $value) {
				if (in_array($key, array($cookieConfig['prefix'].'auth', 'userauth'))) {
					continue;
				}
				$value = self::xss($value);
			}
		}
		else 
		{
			if (strlen($string) > 20)
			{
				$string = self::_strip_tags($string);
			}
		}
		return $string;
	}
	
	static function _strip_tags($string)
	{
		$string = str_replace('+/', '+', $string);
		return preg_replace_callback("|(<)(/?)(\w+)([^>]*)(>)|", array('self', '_strip_attrs'), $string);
	}
	
	static function _strip_attrs($matches)
	{
		if (preg_match("/^(".self::$_allowtags.")$/", $matches[3]))
		{
			if ($matches[4])
			{
				preg_match_all("/\s(".self::$_allowattrs.")\s*=\s*(['\"]?)(.*?)\\2/i", $matches[4], $m, PREG_SET_ORDER);
				$matches[4] = '';
				foreach ($m as $k=>$v)
				{
					if (!preg_match("/(".self::$_disallowattrvals.")/", $v[3]))
					{
						$matches[4] .= $v[0];
					}
				}
			}
		}
		else 
		{
			$matches[1] = '&lt;';
			$matches[5] = '&gt;';
		}
		unset($matches[0]);
		return implode('', $matches);
	}
}