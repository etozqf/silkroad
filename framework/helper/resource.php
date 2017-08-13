<?php
abstract class resource
{
	protected static $_package = array();
	protected static $_needs = array();
	protected static $_macros = array();
	protected static $_path = array();
	
	public static function init()
	{
		self::$_package = include (ROOT_PATH.'resources/depends.db');
		self::$_needs = array();
		self::$_macros = array(
			'{IMG_URL}'=>IMG_URL,
			'{ADMIN_URL}'=>ADMIN_URL
		);
		self::$_path = array(
			IMG_URL => IMG_PATH,
			ADMIN_URL => PUBLIC_PATH.'admin/'
		);
	}
	protected static function _depends(array $depends)
	{
		foreach ($depends as $key)
		{
			if (isset(self::$_package[$key]))
			{
				$val = self::$_package[$key];
				if ($val['depends'])
				{
					self::_depends((array) $val['depends']);
				}
				$base = isset($val['base']) ? (rtrim($val['base'], '/') . '/') : '';
				foreach ((array) $val['resource'] as $v)
				{
					self::$_needs[] = self::url($v, $base);
				}
			}
			else
			{
				self::$_needs[] = self::url($key);
			}
		}
	}
	public static function url($url, $baseurl = '')
	{
		$url = trim(strtr($url, self::$_macros));
		return self::absUrl($url, $baseurl);
	}
	public static function absUrl($url, $baseurl = '')
	{
		$url = trim($url);
		if ($url == '' || $url == '#') {
			return $url;
		}

		$url = explode('?', $url, 2);
		$query = isset($url[1]) ? "?$url[1]" : '';
		$url = $url[0];

		if ($baseurl && !preg_match('#^(?:[a-z]{3,10}):#i', $url)) {
			$uri = parse_url($baseurl);
			$pass = isset($uri['pass']) ? ":{$uri['pass']}" : '';
			$user = isset($uri['user']) ? "{$uri['user']}{$pass}@" : '';
			$host = $uri['host'];
			$scheme = $uri['scheme'];
			if (isset($uri['port']) && !(($scheme == 'http' && $uri['port'] == 80) || ($scheme == 'https' && $uri['port'] == 443)))
			{
				$host .= ':' . $uri['port'];
			}
			$httphost = "{$scheme}://{$user}{$host}";
			if ($url[0] == '/') {
				$url = $httphost . $url;
			} else {
				$path = explode('/', $uri['path']);
				if (strlen($url)) {
					$path[count($path)-1] = $url;
				}
				$url = $httphost . implode('/', $path);
			}
		}

		while (strpos($url, './') !== false) {
			$old = $url;
			$url = preg_replace('#([^/]+/)(\.{2}/)#e', '"$1"=="$2"?"$1$2":""', $url);
			$url = str_replace('/./', '/', $url);
			if ($url == $old) break;
		}
		return $url . $query;
	}
	public static function relPath($file, $relative)
	{
		if (preg_match('~^(/|[a-z]:[/\\\\])~i', $file)) {
			return realpath($file);
		}
		if (is_file($relative)) {
			$relative = dirname($relative);
		}
		return realpath($relative .'/'. $file);
	}
    public static function setPackage(array $package)
    {
        self::$_package = $package;
    }
	public static function setMacro($key, $val)
	{
		self::$_macros['{'.$key.'}'] = $val;
	}
	public static function setPath($url, $dir)
	{
		self::$_path[$url] = $dir;
	}
	public static function depends($items)
	{
		self::_depends((array) $items);
		return self::$_needs = array_unique(self::$_needs);
	}
	public static function needs($items)
	{
		return self::depends($items);
	}
	public static function import($name, $data = null)
	{
		if (is_array($name))
		{
			self::$_package = array_merge(self::$_package, $name);
		}
		else
		{
			self::$_package[$name] = $data;
		}
	}
	public static function toHtml($pos = null, $needs = null)
	{
		$needs = array_unique(is_array($needs) ? $needs : self::$_needs);
		$text = array(
			'js'=>array(),
			'css'=>array()
		);
		$tag = array(
			'js'=>array(),
			'css'=>array()
		);
		
		foreach ($needs as $item)
		{
			$ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));
			$file = '';
			$baseurl = '';
			if ($item[0]=='@')
			{
				$item = substr($item, 1);
			}
			elseif (preg_match('|^http[s]?\://|i', $item))
			{
				foreach (self::$_path as $url=>$path)
				{
					if (0 === stripos($item, $url))
					{
						$file = $path.substr($item, strlen($url));
						$baseurl = substr($item, 0, strrpos($item, '/')).'/';
						break;
					}
				}
			}
			else
			{
				$file = PUBLIC_PATH.'admin/'.$item;
				$baseurl = ADMIN_URL.$item;
				$baseurl = substr($baseurl, 0, strrpos($baseurl, '/')).'/';
			}
			if ($file)
			{
				if ($r = call_user_func(array(self, $ext.'Text'), $file, $baseurl))
				{
					$text[$ext][] = $r;
				}
			}
			else
			{
				$tag[$ext][] = call_user_func(array(self, $ext.'Tag'), $item);
			}
		}
		foreach ($text as $t=>$a)
		{
			if (!empty($a))
			{
				$url = self::toOne($a, $pos, $t);
				$tag[$t][] = call_user_func(array(self, $t.'Tag'), $url);
			}
		}
		return implode("\n", $tag['css'])."\n".implode("\n", $tag['js']);
	}
	public static function toOne($data, $psn = null, $type = 'js')
	{
		$dir = CACHE_PATH.'static';
		$baseurl = '?app=system&controller=admin&action=res&hash=';
		if (!empty($psn))
		{
			if (is_array($psn))
			{
				$dir = $psn['path'];
				$baseurl = $psn['url'].'/';
			}
			elseif ($psn = loader::model('admin/psn', 'system')->parse($psn))
			{
				$dir = $psn['path'];
				$baseurl = $psn['url'].'/';
			}
		}
		$data = call_user_func(array(self, $type.'Pack'), $data);
		$hash = md5($data).'.'.$type;
		write_file($dir.'/'.$hash, $data);
		return $baseurl.$hash;
	}
	public static function cssText($file, $baseurl)
	{
		if (!is_file($file)) return null;
		$css = file_get_contents($file);
		if (!$css) return null;
		if (substr($css, 0, 3) == chr(239).chr(187).chr(191)) {
			$css = substr($css, 3);
		}
		if (!$baseurl) return $css;
		$css = preg_replace(
			'/((?:url\s*\()\s*["\']?)([^\)"\']+)(["\' \)])/Uise',
			'stripslashes("\1").self::absUrl(stripslashes("\2"), $baseurl).stripslashes("\3")',
			$css
		);
		return preg_replace('~@include "(.+?)";~e', 'self::cssText(self::relPath("\1", $file), $baseurl)', $css);
	}
	public static function jsText($file)
	{
		if (!$file || !is_file($file)) return null;
		$css = file_get_contents($file);
		if (!$css) return null;
		if (substr($css, 0, 3) == chr(239).chr(187).chr(191)) {
			$css = substr($css, 3);
		}
		return preg_replace('~include\("(.+?)"\)~e', 'self::jsText(self::relPath("\1", $file))', $css);
	}
    public static function jsPack($data)
	{
		return is_array($data) ? implode("\n;", $data) : $data;
	}
    public static function cssPack($data)
	{
		$data = is_array($data) ? implode("\n", $data) : $data;
		$data = preg_replace('#/\*.*\*/|\n|\r#Us', '', $data);
		$data = preg_replace('/\s+/', ' ', $data);
		return str_replace('}', "}\n", $data);
	}
	protected static function cssTag($url)
	{
		return '<link rel="stylesheet" type="text/css" href="'.$url.'" />';
	}
	protected static function jsTag($url)
	{
		return '<script type="text/javascript" src="'.$url.'"></script>';
	}
}