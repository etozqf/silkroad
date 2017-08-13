<?php
/**
 * 转义字符串中html标签,如果参数为数组则遍历
 *	
 * @param mixed $string 待转换的字符
 * @return mixed
 */
function htmlspecialchars_deep($string)
{
	return is_array($string) ? array_map('htmlspecialchars_deep', $string) : htmlspecialchars($string, ENT_QUOTES);
}

/**
 * 使用反斜线引用字符串,如果参数为数组则遍历
 *
 * @param mixed $string 待转换的字符
 * @return mixed
 */
function addslashes_deep($string)
{
	return is_array($string) ? array_map('addslashes_deep', $string) : addslashes($string);
}

/**
 * 使用反斜线引用字符串,如果参数为数组则深度遍历
 *	
 * @param mixed $string 待转换的字符
 * @return mixed
 */
function new_addslashes($string)
{
	if(!is_array($string)) return addslashes($string);
	foreach($string as $key => $val) $string[$key] = new_addslashes($val);
	return $string;
}

/**
 * 使用反斜线引用字符串
 * 
 * @param mixed $string 待转换的对象
 * @return mixed
 */
function addslashes_deep_obj($obj)
{
    if (is_object($obj))
    {
        foreach ($obj as $key => $val)
        {
            $obj->$key = addslashes_deep($val);
        }
    }
    else
    {
        $obj = addslashes_deep($obj);
    }
    return $obj;
}

/**
 * 去掉字符串中的反斜线
 *
 * @param mixed $string 待转换的字符
 * @return mixed
 */
function stripslashes_deep($string)
{
	return is_array($string) ? array_map('stripslashes_deep', $string) : stripslashes($string);
}

/**
 * 清除js数据中的换行与反斜线
 * 
 * @param string $string 待转换的字符
 * @return string
 */
function js_format($string)
{ 
	return addslashes(str_replace(array("\r", "\n"), array('', ''), $string));
}

/**
 * 将html转换成text
 *	
 * @param string $string 待转换的字符
 * @return string
 */
function text_format($string)
{
	return nl2br(str_replace(' ', '&nbsp;', htmlspecialchars($string)));
}

/**
 * 格式化ID
 *	
 * @param mixed $id
 * @return int
 */
function id_format($id)
{
	if (is_numeric($id)) return $id;
	if (is_array($id)) return array_filter($id, 'is_numeric');
	if (strpos($id, ',') !== false) return preg_match("/^([\d]+,)+$/", $id.',') ? explode(',', $id) : false;
    return false;
}

/**
 * 编码转换
 *
 * @param	string	$_in_charset	输入字符集
 * @param	string	$_out_charset	输出字符集
 * @param	mixed	$str_or_ary		内容
 * @return	mixed
 */
function str_charset($in_charset, $out_charset, $str_or_arr)
{
	$lang = array(&$in_charset, &$out_charset);
	foreach ($lang as &$l)
	{
		switch (strtolower(substr($l, 0, 2)))
		{
			case 'gb': $l = 'gbk';
			break;
			case 'bi': $l = 'big5';
			break;
			case 'ut': $l = 'utf-8';
			break;
		}
	}
		
	if(is_array($str_or_arr))
	{
		foreach($str_or_arr as &$v)
		{
			$v = str_charset($in_charset, $out_charset, $v);
		}
	}
	else
	{
		$str_or_arr = iconv($in_charset, $out_charset, $str_or_arr);
	}
	return $str_or_arr;
}

/**
 * 实现fputcsv内置函数，将行格式化为 CSV 并写入文件指针
 *
 * fputcsv() 将一行（用 fields 数组传递）格式化为 CSV 格式并写入由 handle 指定的文件。返回写入字符串的长度，出错则返回 FALSE。
 * 可选的 delimiter 参数设定字段分界符（只允许一个字符）。默认为逗号：,。
 * 可选的 enclosure 参数设定字段字段环绕符（只允许一个字符）。默认为双引号："。
 *
 * @param resource $fp 存储文件指针
 * @param array $array 数据
 * @param string $delimiter 分界符
 * @param string $enclosure 环绕符
 * @return int
*/
if(!function_exists('fputcsv'))
{
	function fputcsv($fp, $array, $delimiter = ',', $enclosure = '"')
	{
		$data = $enclosure.implode($enclosure.$delimiter.$enclosure, $array).$enclosure."\n";
		return fwrite($fp, $data);
	}
}

/**
 * 产生一个随机字符串
 * 
 * @param int $length	字符串长度
 * @param string $chars	随机字符范围
 * @return string
 */
function random($length, $chars = '0123456789')
{
	$hash = '';
	$max = strlen($chars) - 1;
	for($i = 0; $i < $length; $i++)
	{
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
}

/**
 * 返回文件扩展名
 *
 * @param string $filename 文件路径
 * @return string
 */
function fileext($filename)
{
    if(strpos($filename, '?'))
    {
        $filename = substr($filename, 0, strpos($filename, '?'));
    }
	return pathinfo($filename, PATHINFO_EXTENSION);
}

/**
 * 获取视频代码所使用的播放器
 * -- 返回 video,player,playerurl 组成的数组
 *
 * @param string $video
 * @return array|string
 */
function getVideoPlayer($video='')
{
    if(!$video) return '';
    $r['video'] = $video;
    $fileext = fileext($r['video']);
    if(preg_match('/^\[([a-zA-Z0-9]+)\]([^\[]+)(\[\/\1\])$/i', $r['video'], $matches))
    {
        $r['video'] = $matches[2];
        $r['player'] = $matches[1];
        $r['playerurl'] = '';
        if($r['player'] == 'ctvideo')
        {
            $new_setting = new setting();
            $video_setting = $new_setting->get('video');
            $r['playerurl'] = $video_setting['player'];
            unset($new_setting, $video_setting);
        }
    }
    elseif(!(stripos($r['video'], 'rtmp://') === false))
    {
        $r['player'] = 'rtmp';
    }
    elseif($fileext && strlen($fileext)<7)
    {
        $r['player'] = $fileext;
    }
    else
    {
        $r['player'] = 'flash';
    }
    return $r;
}

/**
 * 将数组转换为字符串
 *
 * @param mixed $array  待转化数组
 * @param string $s		分隔符
 * @return string
 */
function implode_ids($array, $s = ',')
{
	if(empty($array)) return '';
	return is_array($array) ? implode($s, $array) : $array;
}

/**
 * 单词统计
 *
 * @param string $string	待统计文本
 * @param string $charset	字符集
 * @return int
 */
function words_count($string, $charset = 'utf-8')
{
	$string = strip_tags($string);
	$en_count = preg_match_all("/([[:alnum:]]|[[:punct:]])+/", $string, $matches);
	$string = preg_replace("/([[:alnum:]]|[[:space:]]|[[:punct:]])+/", '', $string);
	$zh_count = mb_strlen($string, $charset);
	$count = $en_count + $zh_count;
	return $count;
}

/**
 * 格式化存储单位
 *
 * @param int $size
 * @param bool $point 是否要小数
 * @return string
 */
function size_format($size, $point = true)
{
	$decimals = 0;
	$suffix = '';
	switch (true)
	{
	case $size >= 1073741824:
		$decimals = 2;
		$size = round($size / 1073741824 * 100) / 100;
		$suffix = 'GB';
		break;
	case $size >= 1048576:
		$decimals = 2;
		$size = round($size / 1048576 * 100) / 100;
		$suffix = 'MB';
		break;
	case $size >= 1024:
		$decimals = 2;
		$size = round($size / 1024 * 100) / 100;
		$suffix = 'KB';
		break;
	default:
		$decimals = 0;
		$suffix = 'B';
	}
	return number_format($size, $point ? $decimals : 0) . $suffix;
}

/**
 * 计算指定存储单位字符串的数值
 *
 * @param $size_string 存储单位字符串，如 128KB,1MB,1T,1GB 等
 * @return int 以 byte 计算的容量数值
 */
function size_calculate($size_string)
{
    if (is_int($size_string))
    {
        return $size_string;
    }
    $units = array('K', 'M', 'G', 'T', 'P', 'E');
    if (! preg_match('/^(\d+)([' . implode('', $units) . '])?(B)?$/i', $size_string, $matches))
    {
        return intval($size_string);
    }
    $value = intval($matches[1]);
    if (isset($matches[2]) && $matches[2])
    {
        $index = array_search(strtoupper($matches[2]), $units);
        if ($index !== false)
        {
            return $value * pow(1024, $index + 1);
        }
    }
    return $value;
}

/**
 *	截取字符串
 *	
 * @param string $string	待截取字符串
 * @param int $length		截取长度，每个字符为一个长度，无论中英文
 * @param string $charset	字符字符集
 * @param string $etc		省略符
 * @return string
*/
function str_cutword($string, $length = 80, $charset = "utf-8", $etc = '...')
{
    $start = 0;
    if (! $length) return $string;
    if (function_exists('mb_substr'))
    {
        $slice = mb_substr($string, $start, $length, $charset);
    }
    else
    {
        $re ['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re ['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re ['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re ['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $string, $match);
        $slice = join('', array_slice($match [0], $start, $length));
    }
    if ($slice == $string)
    {
        return $slice;
    }
    return $slice . $etc;
}

/**
 * 友好格式化日期：已过去多久
 *
 * @param int $time 输入时间戳
 * @param string $format 时间格式
 * @param boolon $second 是否精确到秒
 * @return string
 */
function time_format($time, $format = 'Y年n月j日 G:i:s', $second = false)
{
	$diff = TIME - $time;
	if ($diff < 60 && $second)
	{
		return $diff ? $diff.'秒前' : '刚刚';
	}
	$diff = ceil($diff/60);
	if ($diff < 60)
	{
		return $diff  ? $diff.'分钟前' : '刚刚';
	}
	$d = date('Y,n,j', TIME);
	list($year, $month, $day) = explode(',', $d);
	$today = mktime(0, 0, 0, $month, $day, $year);
	$diff = ($time-$today) / 86400;
	switch (true)
	{
		case $diff < -2:
			break;
		case $diff < -1:
			$format = '前天 '.($second ? 'G:i:s' : 'G:i');
			break;
		case $diff < 0:
			$format = '昨天 '.($second ? 'G:i:s' : 'G:i');
			break;
		default:
			$format = '今天 '.($second ? 'G:i:s' : 'G:i');
	}
	return date($format, $time);
}

/**
 * 友好格式化日期：多久之后
 *
 * @param int $time 时间戳
 * @param string $full_format 超出指定天数范围后使用的时间戳
 * @param int $day_max 指定一个天数范围，当剩余天数大于该天数时，返回 $full_format 格式的时间
 * @return string 格式化结果
 */
function time_format_after($time, $full_format = 'Y-m-d H:i:s', $day_max = 30)
{
    $diff = $time - TIME;
    if ($diff == 0) {
        return '现在';
    }
    if ($diff < 0)
    {
        return time_format($time, $full_format, true);
    }
    if ($diff < 60) {
        return $diff . '秒后';
    }
    $minute = ceil($diff / 60);
    if ($minute < 60) {
        return $minute . '分钟后';
    }
    $day = ceil($diff / 86400);
    if ($day_max && $day > $day_max) {
        return date($full_format, $time);
    }
    $time = date('G:i', $time);
    if ($day < 1) {
        return '今天 ' . $time;
    }
    if ($day < 2) {
        return '明天 ' . $time;
    }
    if ($day < 3) {
        return '后天 ' . $time;
    }
    return $day . '天后';
}

/**
 * 友好格式化时时：将转换为时分秒显示
 *
 * @param int $second 秒数
 * @return string
 */
function second_format($second)
 {
	$hour = $minute = 0;
	$str = '';
	if($second > 3600)
	{
		$hour = floor($second / 3600);
		$second = $second % 3600;			
	}
	if($second > 60)
	{
		$minute = floor($second / 60);
		$second = $second % 60;	
	}
	if($hour)
	{
		$str .= $hour ."小时";
	}
	if($minute)
	{
		$str .= $minute ."分";
	}
	if($second)
	{
		$str .= $second ."秒";
	}
	return $str;
 }

/**
 * 截取字符串
 *
 * @param string $string 原始字符串
 * @param int $length 截取长度
 * @param string $dot 省略符
 * @param string $charset 字符集
 * @return string
 */
function str_cut($string, $length, $dot = '...', $charset = 'utf-8')
{
	$strlen = strlen($string);
	if($strlen <= $length) return $string;
	$specialchars = array('&amp;', '&quot;', '&#039;', '&lt;', '&gt;');
	$entities = array('&', '"', "'", '<', '>');
	$string = str_replace($specialchars, $entities, $string);
	$strcut = '';
	if(strtolower($charset) == 'utf-8')
	{
		$n = $tn = $noc = 0;
		while($n < $strlen)
		{
			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t < 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} elseif(bin2hex($string[$n]) >=65281||bin2hex($string[$n])<=65374){
				$tn = 3; $n += 3; $noc += 2;
			} else{
				$n++;
			} 
			if($noc >= $length) break;
		}
		if($noc > $length) $n -= $tn;
		$strcut = substr($string, 0, $n);
	}
	else
	{
		$dotlen = strlen($dot);
		$maxi = $length - $dotlen - 1;
		for($i = 0; $i < $maxi; $i++)
		{
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
	}
	if(strlen($strcut) == $strlen)
		return $string;
	else
		return $strcut.$dot;
}

/**
 * 生成缩略图
 *
 * @param string $img     原始图片
 * @param int $width      缩略图宽
 * @param int $height     缩略图高
 * @param int $is_abs     是否为绝对路径
 * @param null $default   当图片不存在时的默认图片
 * @param mixed $cut      new! 是否采用新的裁剪算法，如果为 int，则该值为新算法中对应的裁剪位置, null为不使用
 * @param int $quality    缩略图质量
 * @return null|string    缩略图地址
 */
function thumb($img, $width, $height, $is_abs = 1, $default = null, $cut = 1, $quality = null)
{
	if(empty($img)) return is_null($default) ? IMG_URL.'images/nopic.gif' : $default;
	if(!extension_loaded('gd')) return $img;
	if (preg_match("/^(".preg_quote(UPLOAD_URL, '/')."|".preg_quote(UPLOAD_PATH, '/').")(.*)$/", $img, $matches)) $img = $matches[2];
	if (strpos($img, '://') || !file_exists(UPLOAD_PATH.$img)) return $img;
    $basename = basename($img);
    $origin_prefix = 'orig_';
    if (strpos($basename, $origin_prefix) === 0)
    {
        $basename = substr($basename, strlen($origin_prefix));
    }
	$newimg = dirname($img).'/thumb_'.$width.'_'.$height.'_'.$basename;
	if(!file_exists(UPLOAD_PATH.$newimg) || filemtime(UPLOAD_PATH.$newimg) < filemtime(UPLOAD_PATH.$img))
	{
		$image = factory::image();
        if (is_null($quality))
        {
            $image->set_thumb($width, $height);
        }
        else
        {
            $image->set_thumb($width, $height, $quality);
        }
        if (! is_null($cut))
        {
            $newimg = $image->thumb_cut(UPLOAD_PATH.$img, UPLOAD_PATH.$newimg, is_int($cut) ? $cut : 0, true) ? $newimg : $img;
        }
        else
        {
            $newimg = $image->thumb(UPLOAD_PATH.$img, UPLOAD_PATH.$newimg) ? $newimg : $img;
        }
	}
	if ($is_abs) $newimg = UPLOAD_URL.$newimg;
	return $newimg;
}

/**
 * 字符编码（对称）
 *
 * @param string $data	待解码内容
 * @param string $key	密钥
 * @return string
 */
function str_encode($data, $key)
{
	return cmstop::encode($data, $key);
}

/**
 * 字符解码（对称）
 *
 * @param string $data	待编码内容
 * @param string $key	密钥
 * @return string
 */
function str_decode($data, $key)
{
	return cmstop::decode($data, $key);
}

/**
 * 数据格式化解码，自动检测是JSON编码还是序列化编码
 *
 * @param string $data	
 * @return string
 */
function decodeData($data)
{
	return is_array($data)
        ? $data
        : ($data[0]=='{' || $data[0]=='[') ? json_decode($data, true) : unserialize($data);
}

/**
 * 数据格式化编码（JSON）
 *
 * @param string $data
 * @return string
 */
function encodeData($data)
{
	return json_encode($data);
}

/**
 * 字符串编码（escape）
 *
 * @param string $str 待编码字符串
 * @param string $charset 字符集
 * @return string
 */
function escape($str, $charset = 'utf-8')
{
	preg_match_all("/[\x80-\xff].|[\x01-\x7f]+/", $str, $r);
	$ar = $r[0];
	foreach($ar as $k=>$v)
	{
		$ar[$k] = ord($v[0]) < 128 ? rawurlencode($v) : '%u'.bin2hex(iconv($charset, 'UCS-2', $v));
	}
	return join('', $ar);
}

/**
 * 字符串解码（unescape）
 *
 * @param string $str 待解码字符串
 * @param string $charset 字符集
 * @return string
 */
function unescape($str, $charset = 'utf-8')
{
	$str = rawurldecode($str);
	$str = preg_replace("/\%u([0-9A-Z]{4})/es", "iconv('UCS-2', '$charset', pack('H4', '$1'))", $str);
    return $str;
}

/**
 * 路径格式化
 *
 * @param string $dir
 * @return string
 */
function format_dir($dir)
{
	return rtrim(str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $dir), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
}

/**
 * url格式化
 *
 * @param string $url
 * @return string
 */
function format_url($url)
{
	return str_replace("\\", "/", $url);
}

/**
 * 向浏览器输出内容,格式化标准HTTP头
 *
 * @param string $data
 * @return strign
 */
function output($data)
{
	$strlen = strlen($data);
	if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && $strlen > 255 && extension_loaded('zlib') && !ini_get('zlib.output_compression') && ini_get('output_handler') != 'ob_gzhandler')
	{
		$data = gzencode($data, 4);
		$strlen = strlen($data);
		header('Content-Encoding: gzip');
		header('Vary: Accept-Encoding');
	}
 	header('X-Powered-By: CMSTOP/1.0.0');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	header('ETag: "'.$strlen.'-'.time().'"');
	header('Accept-Ranges: bytes');
	return $data;
}

/**
 * 获取配置文件一个键的值
 *
 * @param string $file 配置文件
 * @param string $key  配置键
 * @param mixed $default 默认值
 * @return mixed
 */
function config($file, $key = null, $default = null)
{
	return config::get($file, $key, $default);
}

/**
 * 获取app设置
 *
 * @param string $app
 * @param string $var 变量名,默认返回全部
 * @return 
 */
function setting($app, $var = null)
{
	return setting::get($app, $var);
}

/**
 * 查询数据库并缓存
 *
 * @param string $table	表名
 * @param string $id	按主键查询
 * @param string $field	查询字段
 * @return array
 */
function table($table, $id = null, $field = null)
{
    static $_staticCache;
    if (isset($_staticCache[$table])) {
       if (is_null($id))
           return $_staticCache[$table];
       return is_null($field) ? $_staticCache[$table][$id] : (isset($_staticCache[$table][$id][$field]) ? $_staticCache[$table][$id][$field] : false);
    }

	$cache = factory::cache();
	
	if ($_cache = $cache->get('table_'.$table))
	{
        $_staticCache[$table] = $_cache;
		if (is_null($id)) return $_cache;
		return is_null($field) ? $_cache[$id] : (isset($_cache[$id][$field]) ? $_cache[$id][$field] : false);
	}
	else 
	{
		if (is_null($id))
		{
			static $result;
			if (!isset($result[$table]))
			{
				$array = array();
				$db = factory::db();
				$primary = $db->get_primary('#table_'.$table);
				$fields = is_null($field) ? '*' : (strpos($field, $primary) === false ? $primary.','.$field : $field);
				
				$data = $db->select("SELECT $fields FROM `#table_$table` ORDER BY `$primary`");
				if (is_array($data))
				{
					foreach ($data as $k=>$v)
					{
						if (!isset($v[$primary])) break;
						$key = $v[$primary];
						$array[$key] = $v;
					}
				}
				$result[$table] = $array;
			}
			return $result[$table];
		}
		else 
		{
			static $row;
			$key = $table.'_'.$id;
			if (!isset($row[$key]))
			{
				$db = factory::db();
				$primary = $db->get_primary('#table_'.$table);
				$row[$key] = $db->get("SELECT * FROM `#table_$table` WHERE `$primary`=?", array($id));
			}
			return (is_null($field) && !isset($row[$key][$field])) ? $row[$key] : $row[$key][$field];
		}
	}
}

/**
 * 模拟原db_cache将整表写入缓存
 *
 * @param string $table 表名
 * @param fields 字段名
 * @return boolean
 */
function table_cache($table = null, $fields="*", $recursive=false)
{
	$db		= factory::db();
	$cache	= factory::cache();
	
	if (is_null($table))
	{
		if (!($rst = $db->select('SELECT `tablename`, `allfields` FROM #table_cache'))
            || !is_array($rst))
        {
            return false;
        }
		foreach ($rst as $item)
		{
			table_cache($item['tablename'], $item['allfields'], true);
		}

        // 清空servers缓存
        clear_cache_servers();

		$cache->set('cmstop_cache', 1);
		return true;
	}
	if (!$primary = $db->get_primary('#table_'.$table))
	{
		return false;
	}
	$data	= array();
	$rst	= $db->select("SELECT $fields FROM #table_$table ORDER BY `$primary`");
	foreach ($rst as $k=>$v)
	{
		$data[$v[$primary]] = $v;
	}

	$result = $cache->set("table_$table", $data);

    // 清空servers缓存
    if (!$recursive) clear_cache_servers();

    return $result;
}

/**
 * 清除 cache_servers 中的缓存
 *
 * @param null $key 要清除的 key，不提供则清除所有 key
 */
function clear_cache_servers($key = null)
{
    // 前台和计划任务不执行清空操作
    if (!defined('IN_ADMIN') || defined('INTERNAL')) {
        return;
    }

    $cache_servers = config('cache_servers');
    if (!empty($cache_servers)) {
        import('cache.cache');
        foreach ($cache_servers as $server) {
            $cache = new cache($server);
            if ($key === null) {
                $cache->clear();
            } else {
                $cache->rm($key);
            }

        }
    }
}

/**
 * 生成控制器URL
 *
 * @param string $aca
 * @param string $params
 * @param bool $is_full  是否为绝对路径
 * @return 
 */
function url($aca, $params = null, $is_full = false)
{
	$router = factory::router();
	return $router->url($aca, $params, $is_full);
}

/**
 * 生成带参数的URL
 *
 * @param string $url 基础url
 * @param array $query 参数
 * @param bool $mode 是否转义
 * @return string
 */
function url_query($url, $query = array(), $mode = false)
{
	if ($query)
	{
		$data = parse_url($url);
		if (!$data) return false;
		if (isset($data['query']))
		{
			parse_str($data['query'], $q);
			$query = array_merge($q, $query);
		}
		$data['query'] = http_build_query($query);
		$url = http_build_url($data, $mode);
	}
	return $url;
}

/**
 * 根据数组创建URL
 *
 * @param array $data
 * @param bool $mode 是否转义
 * @return string
 */
function http_build_url($data, $mode = false)
{
	if (!is_array($data)) return false;
	$url = isset($data['scheme']) ? $data['scheme'].'://' : '';
	if (isset($data['user'])) $url .= $data['user'];
	if (isset($data['pass'])) $url .= ':'.$data['pass'];
	if (isset($data['user'])) $url .= '@';
	if (isset($data['host'])) $url .= $data['host'];
	if (isset($data['port'])) $url .= ':'.$data['port'];
	if (isset($data['path'])) $url .= $data['path'];
	if (isset($data['query'])) $url .= '?'.($mode ? str_replace('&', '&amp;', $data['query']) : $data['query']);
	if (isset($data['fragment'])) $url .= '#'.$data['fragment'];
	return $url;
}

/**
 * 分页函数
 *
 * @param int $total 总条目
 * @param int $page	当前页码
 * @param int $pagesize 每页条数
 * @param int $offset 页码显示数量控制（n*2+1）
 * @param string $url 基础URL
 * @param bool $mode 是否转义
 * @param string $tag 页码标签
 * @return string
 */
function pages($total, $page = 1, $pagesize = 20, $offset = 2, $url = null, $mode = false, $tag='li', $class="row",  $prev="上一页", $next="下一页")
{
	if($total <= $pagesize) return '';
	$page = max(intval($page), 1);
	$pages = ceil($total/$pagesize);
	$page = min($pages, $page);
	$prepage = max($page-1, 1);
	$nextpage = min($page+1, $pages);
	$from = max($page - $offset, 2);
	if ($pages - $page - $offset < 1) $from = max($pages - $offset*2 - 1, 2);
	$to = min($page + $offset, $pages-1);
	if ($page - $offset < 2) $to = min($offset*2+2, $pages-1);
	$more = 1;
	if ($pages <= ($offset*2+5))
	{
		$from = 2;
		$to = $pages - 1;
		$more = 0;
	}
    $p_start_tag = $tag ? '<'.$tag.'>' : '';
    $p_end_tag = $tag ? '</'.$tag.'>' : '';
	$str = '';
    if ($page <= 1)
    {
        $str .= $p_start_tag.'<a href="javascript:void(0);" class="prev disable">'.$prev.'</a>'.$p_end_tag;
    }
    else
    {
        $str .= $p_start_tag.'<a href="'.pages_url($url, $prepage, $mode).'" class="prev">'.$prev.'</a>'.$p_end_tag;
    }
	$str .= $page == 1 ? $p_start_tag.'<a href="javascript:void(0);" class="'.$class.'">1</a>'.$p_end_tag : $p_start_tag.'<a class="number" href="'.pages_url($url, 1, $mode).'">1'.($from > 2 && $more ? ' ...' : '').'</a>'.$p_end_tag;
	if ($to >= $from)
	{
		for($i = $from; $i <= $to; $i++)
		{
			$str .= $i == $page ? $p_start_tag.'<a href="javascript:void(0);" class="number red">'.$i.'</a>'.$p_end_tag : $p_start_tag.'<a class="number" href="'.pages_url($url, $i, $mode).'">'.$i.'</a>'.$p_end_tag;
		}
	}
	$str .= $page == $pages ? $p_start_tag.'<a href="javascript:void(0);" class="'.$class.'">'.$pages.'</a>'.$p_end_tag : $p_start_tag.'<a href="'.pages_url($url, $pages, $mode).'">'.($to < $pages-1 && $more ? '... ' : '').$pages.'</a>'.$p_end_tag;
    if ($page >= $pages)
    {
        $str .= $p_start_tag.'<a href="javascript:void(0);" class="next disable">'.$next.'</a>'.$p_end_tag;
    }
    else
    {
        $str .= $p_start_tag.'<a href="'.pages_url($url, $nextpage, $mode).'" class="next">'.$next.'</a>'.$p_end_tag;
    }
	return $str;
}

/**
 * 专栏分页函数
 *
 * @param int $total 总条目
 * @param int $page	当前页码
 * @param int $pagesize 每页条数
 * @param int $offset 页码显示数量控制（n*2+1）
 * @param string $url 基础URL
 * @param bool $mode 是否转义
 * @param string $tag 页码标签
 * @return string
 */
function space_pages($total, $page = 1, $pagesize = 20, $offset = 2, $url = null, $mode = false, $tag='li', $class="number red",  $prev="上一页", $next="下一页")
{
	if($total <= $pagesize) return '';
	$page = max(intval($page), 1);
	$pages = ceil($total/$pagesize);
	$page = min($pages, $page);
	$prepage = max($page-1, 1);
	$nextpage = min($page+1, $pages);
	$from = max($page - $offset, 2);
	if ($pages - $page - $offset < 1) $from = max($pages - $offset*2 - 1, 2);
	$to = min($page + $offset, $pages-1);
	if ($page - $offset < 2) $to = min($offset*2+2, $pages-1);
	$more = 1;
	if ($pages <= ($offset*2+5))
	{
		$from = 2;
		$to = $pages - 1;
		$more = 0;
	}
    $p_start_tag = $tag ? '<'.$tag.'>' : '';
    $p_end_tag = $tag ? '</'.$tag.'>' : '';
	$str = '';
    if ($page <= 1)
    {
        $str .= $p_start_tag.'<a href="javascript:void(0);" class="prev disable">'.$prev.'</a>'.$p_end_tag;
    }
    else
    {
        $str .= $p_start_tag.'<a href="'.pages_url($url, $prepage, $mode).'" class="prev">'.$prev.'</a>'.$p_end_tag;
    }
	$str .= $page == 1 ? $p_start_tag.'<a href="javascript:void(0);" class="'.$class.'">1</a>'.$p_end_tag : $p_start_tag.'<a class="number" href="'.pages_url($url, 1, $mode).'" '.($from > 2 && $more && $more ? 'style="width:65px;"' : '').'>1'.($from > 2 && $more ? ' ...' : '').'</a>'.$p_end_tag;
	if ($to >= $from)
	{
		for($i = $from; $i <= $to; $i++)
		{
			$str .= $i == $page ? $p_start_tag.'<a href="javascript:void(0);" class="'.$class.'">'.$i.'</a>'.$p_end_tag : $p_start_tag.'<a class="number" href="'.pages_url($url, $i, $mode).'">'.$i.'</a>'.$p_end_tag;
		}
	}
	$str .= $page == $pages ? $p_start_tag.'<a href="javascript:void(0);" class="'.$class.'">'.$pages.'</a>'.$p_end_tag : $p_start_tag.'<a href="'.pages_url($url, $pages, $mode).'" '.($to < $pages-1 && $more ? 'style="width:65px;"' : '').' style="width:65px;">'.'末页'.'</a>'.$p_end_tag;
    if ($page >= $pages)
    {
        $str .= $p_start_tag.'<a href="javascript:void(0);" class="next disable">'.$next.'</a>'.$p_end_tag;
    }
    else
    {
        $str .= $p_start_tag.'<a href="'.pages_url($url, $nextpage, $mode).'" class="next">'.$next.'</a>'.$p_end_tag;
    }
	return $str;
}

/**
 * 分页函数
 *
 * @param int $total 总条目
 * @param int $page	当前页码
 * @param int $pagesize 每页条数
 * @param int $offset 页码显示数量控制（n*2+1）
 * @param string $url 基础URL
 * @param bool $mode 是否转义
 * @param string $tag 页码标签
 * @return string
 */
function pages_infolist($total, $page = 1, $pagesize = 20, $offset = 2, $url = null, $mode = false, $tag='li', $class="number",  $prev="Previous Page", $next="Next Page")
{
	$p_start_tag = $tag ? '<'.$tag.'>' : '';
    $p_end_tag = $tag ? '</'.$tag.'>' : '';
	if($total <= $pagesize) 
	{
		 $str .= $p_start_tag.'<a href="javascript:void(0);"  class="pre disable">'.$prev.'</a>'.$p_end_tag;
		 $str .= $p_start_tag.'<a href="javascript:void(0);"  class="number red">1</a>'.$p_end_tag;
		 $str .= $p_start_tag.'<a href="javascript:void(0);"  class="next disable" style="background:#ebebeb;">'.$next.'</a>'.$p_end_tag;
		 return $str;
		 exit;	
	}
	$page = max(intval($page), 1);
	$pages = ceil($total/$pagesize);
	$page = min($pages, $page);
	$prepage = max($page-1, 1);
	$nextpage = min($page+1, $pages);
	$from = max($page - $offset, 2);
	if ($pages - $page - $offset < 1) $from = max($pages - $offset*2 - 1, 2);
	$to = min($page + $offset, $pages-1);
	if ($page - $offset < 2) $to = min($offset*2+2, $pages-1);
	$more = 1;
	if ($pages <= ($offset*2+5))
	{
		$from = 2;
		$to = $pages - 1;
		$more = 0;
	}
    
	$str = '';
    if ($page <= 1)
    {
        $str .= $p_start_tag.'<a href="javascript:void(0);"  class="pre disable">'.$prev.'</a>'.$p_end_tag;
    }
    else
    {
        $str .= $p_start_tag.'<a href="'.pages_url($url, $prepage, $mode).'"  class="pre" style="background:#fff;border:1px solid #ebebeb;">'.$prev.'</a>'.$p_end_tag;
    }
	$str .= $page == 1 ? $p_start_tag.'<a  href="javascript:void(0);" class="number red">1</a>'.$p_end_tag : $p_start_tag.'<a   class="number" href="'.pages_url($url, 1, $mode).'">1</a>'.$p_end_tag;
	if ($to >= $from)
	{
		for($i = $from; $i <= $to; $i++)
		{
			$str .= $i == $page ? $p_start_tag.'<a  href="javascript:void(0);" class="number red">'.$i.'</a>'.$p_end_tag : $p_start_tag.'<a  class="number"  href="'.pages_url($url, $i, $mode).'">'.$i.'</a>'.$p_end_tag;
		}
	}
	$str .= $page == $pages ? $p_start_tag.'<a href="javascript:void(0);" class="number red" >'.$pages.'</a>'.$p_end_tag : $p_start_tag.'<a  class="number" href="'.pages_url($url, $pages, $mode).'">'.($to < $pages-1 && $more ? '... ' : '').$pages.'</a>'.$p_end_tag;
    if ($page >= $pages)
    {
        $str .= $p_start_tag.'<a  href="javascript:void(0);" class="next disable" style="background:#ebebeb;">'.$next.'</a>'.$p_end_tag;
    }
    else
    {
        $str .= $p_start_tag.'<a  href="'.pages_url($url, $nextpage, $mode).'" class="next">'.$next.'</a>'.$p_end_tag;
    }
	return $str;
}

/**
 * 分页函数
 *
 * @param int $total 总条目
 * @param int $page	当前页码
 * @param int $pagesize 每页条数
 * @param int $offset 页码显示数量控制（n*2+1）
 * @param string $url 基础URL
 * @param bool $mode 是否转义
 * @param string $tag 页码标签
 * @return string
 */
function pages_case($total, $page = 1, $pagesize = 1, $offset = 2, $url = null, $mode = false, $tag='li', $class="number",  $prev="Previous Page", $next="Next Page")
{
	$p_start_tag = $tag ? '<'.$tag.'>' : '';
    $p_end_tag = $tag ? '</'.$tag.'>' : '';
	if($total <= $pagesize) 
	{
		 $str .= $p_start_tag.'<a href="javascript:void(0);"  class="pre disable">'.$prev.'</a>'.$p_end_tag;
		 $str .= $p_start_tag.'<a href="javascript:void(0);"  class="number red">1</a>'.$p_end_tag;
		 $str .= $p_start_tag.'<a href="javascript:void(0);"  class="next disable" style="background:#ebebeb;">'.$next.'</a>'.$p_end_tag;
		 return $str;
		 exit;	
	}
	$page = max(intval($page), 1);
	$pages = ceil($total/$pagesize);
	$page = min($pages, $page);
	$prepage = max($page-1, 1);
	$nextpage = min($page+1, $pages);
	$from = max($page - $offset, 2);
	if ($pages - $page - $offset < 1) $from = max($pages - $offset*2 - 1, 2);
	$to = min($page + $offset, $pages-1);
	if ($page - $offset < 2) $to = min($offset*2+2, $pages-1);
	$more = 1;
	if ($pages <= ($offset*2+5))
	{
		$from = 2;
		$to = $pages - 1;
		$more = 0;
	}
    
	$str = '';
    if ($page <= 1)
    {
        $str .= $p_start_tag.'<a href="javascript:void(0);"  class="pre disable">'.$prev.'</a>'.$p_end_tag;
    }
    else
    {
        $str .= $p_start_tag.'<a href="'.pages_url($url, $prepage, $mode).'"  class="pre" style="background:#fff;border:1px solid #ebebeb;">'.$prev.'</a>'.$p_end_tag;
    }
	$str .= $page == 1 ? $p_start_tag.'<a  href="javascript:void(0);" class="number red">1</a>'.$p_end_tag : $p_start_tag.'<a   class="number" href="'.pages_url($url, 1, $mode).'">1</a>'.$p_end_tag;
	if ($to >= $from)
	{
		for($i = $from; $i <= $to; $i++)
		{
			$str .= $i == $page ? $p_start_tag.'<a  href="javascript:void(0);" class="number red">'.$i.'</a>'.$p_end_tag : $p_start_tag.'<a  class="number"  href="'.pages_url($url, $i, $mode).'">'.$i.'</a>'.$p_end_tag;
		}
	}
	$str .= $page == $pages ? $p_start_tag.'<a href="javascript:void(0);" class="number red" >'.$pages.'</a>'.$p_end_tag : $p_start_tag.'<a  class="number" href="'.pages_url($url, $pages, $mode).'">'.($to < $pages-1 && $more ? '... ' : '').$pages.'</a>'.$p_end_tag;
    if ($page >= $pages)
    {
        $str .= $p_start_tag.'<a  href="javascript:void(0);" class="next disable" style="background:#ebebeb;">'.$next.'</a>'.$p_end_tag;
    }
    else
    {
        $str .= $p_start_tag.'<a  href="'.pages_url($url, $nextpage, $mode).'" class="next">'.$next.'</a>'.$p_end_tag;
    }
	return $str;
}



/**
 * 生成分页URL
 *
 * @param string $url 基础URL
 * @param int $page 分页页码
 * @param boolean $mode 是否转义
 * @return string
 */
function pages_url($url, $page, $mode = false)
{
    if (!$url) $url = remove_xss(URL);
    if (strpos($url, '$page') === false) {
        return url_query($url, array('page'=>$page), $mode);
    }
    return preg_replace('#{?\$page}?[\b]?#', $page, $url);
}

/**
 * SQL: 获得某个时
 间之后条件语句
 *
 * @param string $field
 * @param string $mintime 时间戳
 * @return string
 */
function where_mintime($field, $mintime)
{
	if (!$mintime) return '';
	$mintime = trim($mintime);
	if (!is_numeric($mintime))
	{
		if (strlen($mintime) == 10) $mintime .= ' 00:00:00';
		$mintime = strtotime($mintime);
	}
	$where = "$field>=$mintime";
	return $where;
}

/**
 * SQL: 获得某个时间之前条件语句
 *
 * @param string $field
 * @param string $maxtime 时间戳
 * @return string
 */
function where_maxtime($field, $maxtime)
{
	if (!$maxtime) return '';
	$maxtime = trim($maxtime);
	if (!is_numeric($maxtime))
	{
		if (strlen($maxtime) == 10) $maxtime .= ' 23:59:59';
		$maxtime = strtotime($maxtime);
	}
	$where = "$field<=$maxtime";
	return $where;
}

/**
 * SQL: 获得键字查询语句
 *
 * @param string $field
 * @param string $keywords
 * @return string
 */
function where_keywords($field, $keywords)
{
	$keywords = trim($keywords);
	if ($keywords === '') return '';
	$keywords = preg_replace('/\s+/', '%', addcslashes($keywords, '%_'));
	$where = "$field LIKE '%$keywords%'";
	return $where;
}

/**
 * SQL: 获得栏目ID查询语句
 *
 * @param string $field
 * @param string $catid
 * @return string
 */
function where_catid($field, $catid)
{
    if (!is_array($catid))
    {
        $catid = trim($catid);
        if (!($catid = array_filter(array_map('intval', explode(',', $catid)))))
        {
            return '';
        }
        $catid = (array) $catid;
    }
    $result = $catid;
    foreach ($catid as $id)
    {
        $childids = table('category', $id, 'childids');
        if ($childids) $result[] = $childids;
    }
    $result = implode(',', $result);
    return "$field IN ($result)";
}

/**
 * 写入文件
 *
 * @param string $file 文件名
 * @param string $data 文件内容
 * @param boolean $append 是否追加写入
 * @return int
 */
function write_file($file, $data, $append = false)
{
	$dir = dirname($file);
	if (!is_dir($dir)) folder::create($dir);

    $result = false;
    // 加入缓存控制
    if(file_exists($file))
    {
        $_key = "write_file_{$file}_{$append}";
        $_sign = md5($data);
        $_cache = factory::cache();
        if($_cache->get($_key) == $_sign) return strlen($data);
        $_cache->set($_key, $_sign, 0);
    }
    // 缓存控制结束
    if ($fp = @fopen($file, $append ? 'ab' : 'wb'))
    {
        $result = @fwrite($fp, $data);
        @fclose($fp);
        @chmod($file, 0777);
    }
	return $result;
}

/**
 * 读取缓存
 *
 * @param string $file 文件名
 * @param string $path 文件路径，默认为CACHE_PATH
 * @param boolean $iscachevar 是否启用缓存
 * @return array
 */
function cache_read($file, $path = null, $iscachevar = 0)
{
	if(!$path) $path = CACHE_PATH;
	$cachefile = $path.$file;
	if($iscachevar)
	{
		global $TEMP;
		$key = 'cache_'.substr($file, 0, -4);
		return isset($TEMP[$key]) ? $TEMP[$key] : $TEMP[$key] = @include $cachefile;
	}
	return @include $cachefile;
}

/**
 * 写入缓存
 *
 * @param string $file 文件名
 * @param array $array 缓存内容
 * @param string $path 文件路径，默认CACHE_PATH
 * @return int
 */
function cache_write($file, $array, $path = null)
{
	if(!is_array($array)) return false;
	$array = "<?php\nreturn ".var_export($array, true).";";
	$cachefile = ($path ? $path : CACHE_PATH).$file;
	$strlen = write_file($cachefile, $array);
	return $strlen;
}

/**
 * 删除缓存
 *
 * @param string $file
 * @param string $path
 * @return boolean
 */
function cache_delete($file, $path = '')
{
	$cachefile = ($path ? $path : CACHE_PATH).$file;
	return @unlink($cachefile);
}

/**
 * 写入php错误日志
 *
 * @param string $errno 错误编号
 * @param string $errmsg 错误信息
 * @param string $filename 错误文件
 * @param string $linenum 错误行数
 * @param mixed $vars 错误参数
 */
function php_error_log($errno, $errmsg, $filename, $linenum, $vars)
{
	$filename = str_replace(ROOT_PATH, '.', $filename);
	$filename = str_replace("\\", '/', $filename);
	if(!defined('E_STRICT')) define('E_STRICT', 2048);
	$dt = date('Y-m-d H:i:s');
	$errortype = array (
	E_ERROR           => 'Error',
	E_WARNING         => 'Warning',
	E_PARSE           => 'Parsing Error',
	E_NOTICE          => 'Notice',
	E_CORE_ERROR      => 'Core Error',
	E_CORE_WARNING    => 'Core Warning',
	E_COMPILE_ERROR   => 'Compile Error',
	E_COMPILE_WARNING => 'Compile Warning',
	E_USER_ERROR      => 'User Error',
	E_USER_WARNING    => 'User Warning',
	E_USER_NOTICE     => 'User Notice',
	E_STRICT          => 'Runtime Notice'
	);
	$user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);
	$err = "<errorentry>\n";
	$err .= "\t<datetime>" . $dt . "</datetime>\n";
	$err .= "\t<errornum>" . $errno . "</errornum>\n";
	$err .= "\t<errortype>" . $errortype[$errno] . "</errortype>\n";
	$err .= "\t<errormsg>" . $errmsg . "</errormsg>\n";
	$err .= "\t<scriptname>" . $filename . "</scriptname>\n";
	$err .= "\t<scriptlinenum>" . $linenum . "</scriptlinenum>\n";
	if (in_array($errno, $user_errors))
	{
		$err .= "\t<vartrace>" . wddx_serialize_value($vars, "Variables") . "</vartrace>\n";
	}
	$err .= "</errorentry>\n\n";
	$logfile = ROOT_PATH.'data/logs/'.date('Y-m-d').'.xml';
    if(!is_dir(ROOT_PATH.'data/logs/'))
    {
        @mkdir(ROOT_PATH.'data/logs/');
    }
	@error_log($err, 3, $logfile);
	@chmod($logfile, 0777);
}

/**
 * 利用curl模拟浏览器发送请求
 *
 * @param string $url 请求的URL
 * @param array|string $post post数据
 * @param int $timeout 执行超时时间
 * @param boolean $sendcookie 是否发送当前cookie
 * @param array $options 可选的CURL参数
 * @return array
 */
function request($url, $post = null, $timeout = 40, $sendcookie = true, $options = array())
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] ? $_SERVER['HTTP_USER_AGENT'] : 'cmstopinternalloginuseragent');
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 35);
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout ? $timeout : 40);
	if ($sendcookie) {
		$cookie = '';
		foreach ($_COOKIE as $key=>$val)
		{
			$cookie .= rawurlencode($key).'='.rawurlencode($val).';';
		}
		curl_setopt($ch, CURLOPT_COOKIE , $cookie);
	}
	if ($post)
	{
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($post) ? http_build_query($post) : $post);
	}
	
    if (!ini_get('safe_mode') && ini_get('open_basedir') == '')
    {
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1 );
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

	foreach ($options as $key=>$value)
	{
		curl_setopt($ch, $key, $value);
	}
    
	$ret = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$content_length = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    if (!$content_length) $content_length = curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
    $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
	curl_close($ch);
	return array(
        'httpcode'=>$httpcode,
        'content_length'=>$content_length,
        'content_type'=>$content_type,
        'content'=>$ret
    );
}

/**
 * 获取APP应用目录
 *
 * @param string $app
 * @return string
 */
function app_dir($app)
{
    return ROOT_PATH.'apps'.DS.$app.DS;
}

/**
 * 利用firephp输出调试到header
 *
 * @param mixed $message 要输出的信息
 * @param bool $showtime 是否显示执行时间
 */
function console($message, $showtime = false)
{
	/** @var $fb FirePHP */
	static $fb = null;
    static $lasttime = CMSTOP_START_TIME;

	$thistime = microtime(true);
	$usedtime = $thistime - $lasttime;
	$lasttime = $thistime;

	$label = $showtime ? sprintf("%09.5fs", $usedtime) : null;

	if (strstr($_SERVER['HTTP_USER_AGENT'], ' Firefox/')) {
		if (is_null($fb)) {
			import('helper.firephp');
			$fb = FirePHP::getInstance(true);
		}
		$fb->info($message, $label);
	} elseif (strstr($_SERVER['HTTP_USER_AGENT'], ' Chrome/')) {
		if (!class_exists('ChromePHP', false)) {
			import('helper.chromephp');
		}
		ChromePHP::log($label, $message);
	}
}

/**
 * 从数组中读取指定键值的值
 *
 * @param array $array 要读取的数组
 * @param string $key 要读取的键
 * @param null $default 键不存在时指定默认值
 * @return mixed|null 键存在时返回值，不存在时返回 NULL
 */
function value($array, $key, $default = NULL)
{
    return isset($array[$key]) ? $array[$key] : $default;
}

/**
 * 仅执行第一次匹配替换
 * @param string $search 查找的字符串
 * @param string $replace 执行替换的字符串
 * @param string $subject 原字符串
 * @return string
 */
function str_replace_once($search, $replace, $subject)
{
	$pos = strpos($subject, $search);
	if ($pos === false)
	{
		return $subject;
	}
	return substr_replace($subject, $replace, $pos, strlen($search));
}

/**
 * 移除内容开始的 BOM 信息
 *
 * @param string $content 要移除 BOM 头的内容
 * @return string 被移除 BOM 头后的内容
 */
function remove_bom($content)
{
    if ($content && substr($content, 0, 3) == chr(239).chr(187).chr(191))
    {
        $content = substr($content, 3);
    }
    return $content;
}

/**
 * 计算$url的绝对地址
 *
 * @param $url
 * @param $baseurl
 *
 * @return string
 */
function absoluteurl($url, $baseurl)
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

/**
 * 以自然方式截取字符串
 *
 * TODO 效率太低，需要更好的方法
 *
 * @param $string
 * @param $length
 * @param string $dot
 * @return mixed|string
 */
function str_natcut($string, $length, $dot = '...')
{
    if (!$string || !$length) return $string;
    $string = htmlspecialchars_decode($string);
    $string = preg_replace('/\s{2,}/', ' ', $string);
    $maxlen = min($length, mb_strlen($string, 'UTF-8'));
    $chars = preg_split('/([\xF0-\xF7][\x80-\xBF]{3}|[\xE0-\xEF][\x80-\xBF]{2}|[\xC0-\xDF][\x80-\xBF]|[\x00-\x7F])/m', $string, $maxlen + 1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    $index = 0;
    $length = .0;
    $total = count($chars);
    $result = array();
    while ($length < $maxlen && $index < $total)
    {
        $char = $chars[$index];
        if (preg_match('/[\xE0-\xEF][\x80-\xBF]{2}/', $char))
        {
            if (($maxlen - $length) < 1) break;
            $length += 1;
        }
        else
        {
            $length += 0.5;
        }
        $result[] = $char;
        $index++;
    }
    $result = implode('', $result);
    unset($chars, $char);
    return htmlspecialchars($result) . ($result == $string ? '' : $dot);
}
function str_natcutword($string, $length, $dot = '...')
{
    return str_natcut($string, $length, $dot);
}

/**
 * 以自然的方式计算字符串长度
 *
 * 一个汉字计算一个长度，两个英文计算一个长度
 *
 * @param $string
 * @return float|int
 */
function str_natcount($string)
{
    $length = 0;
    if (!$string) return $length;
    $string = preg_replace('/[\xE0-\xEF][\x80-\xBF]{2}/m', '__', $string);
    return ceil(mb_strlen($string, 'UTF-8') / 2);
}

/**
 * 将上传文件的路径转成绝对路径
 *
 * @param $url
 * @param string $default
 * @return string
 */
function abs_uploadurl($url, $default = '')
{
    if(!$url) return $default;
    if(strpos($url, '://') === false)
    {
        return UPLOAD_URL . $url;
    }
    return $url;
}

/**
 * 去掉上传文件的绝对路径前缀
 *
 * @param $url
 * @param string $prefix
 * @return string
 */
function trim_uploadurl($url, $prefix = UPLOAD_URL)
{
    if (empty($url)) {
        return $url;
    }

    if (strpos($url, $prefix) !== false) {
        $url = substr($url, strlen($prefix));
    }

    return $url;
}

function script_template($html)
{
    return $html ? str_replace(array('&', '</textarea'), array('&amp;', '&lt;/textarea'), $html) : $html;
}

/**
 * 用于escape编码的解码
 *
 * @param $str
 * @param string $charset
 * @return string
 */
function urldecode_escape($str, $charset='UTF-8')
{
    $str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($str));
    return html_entity_decode($str, null, $charset);
}

/**
 * 根据ip返回位置
 *
 * @param string $ip IP字符串
 * @return string
 */
function iptolocation($ip)
{
    static $iplocation = null;
	if ($iplocation == null)
	{
		import('helper.iplocation');
		$iplocation = new iplocation();
	}
	return $iplocation->get($ip);
}

/**
 * 检测并将 GBK 编码的文本转换为 UTF-8 编码
 *
 * @param string $string 要转换的文本
 * @return mixed 转换之后的文本
 */
function gbk_to_utf8($string)
{
    $encoded = json_encode($string);
    if ($encoded == 'null' && $string != 'null')
    {
        return str_charset('gbk', 'utf-8', $string);
    }
    return $string;
}

/**
 * 将秒数转换为时间字符串
 *
 * 如：
 * 10 将转换为 00:10，
 * 120 将转换为 02:00，
 * 3601 将转换为 01:00:01
 *
 * @param int $second 秒数
 * @return string 时间字符串
 */
function second_to_time($second)
{
    $second = intval($second);
    if (!$second) return '';

    $hours = floor($second / 3600);
    $hours = $hours ? str_pad($hours, 2, '0', STR_PAD_LEFT) : 0;
    $second = $second % 3600;
    $minutes = floor($second / 60);
    $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
    $seconds = $second % 60;
    $seconds = str_pad($seconds, 2, '0', STR_PAD_LEFT);

    return implode(':', $hours ? compact('hours', 'minutes', 'seconds') : compact('minutes', 'seconds'));
}

/**
 * 为内容中的关键词添加链接
 *
 * @param string $content 内容
 * @param string $keyword 关键词
 * @param string $link 链接
 * @param bool $once 是否只替换一次
 * @return string 替换后的结果
 */
function keyword_add_link($content, $keyword, $link, $once = true)
{
    if (stripos($content, $keyword) === false)
    {
        return $content;
    }

    $result = array();
    $pos_open = 0;
    $pos_close = 0;
    $replaced = false;

    $is_alnum = ctype_alnum($keyword);

    $parts = preg_split(
        '#(<[^>]+\/?>)#ms', $content, -1,
        PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE
    );
    foreach ($parts as $part)
    {
        if (($once && $replaced)
            || $part[0][0] == '<' && $part[0][strlen($part[0]) - 1] == '>'
            || stripos($part[0], $keyword) === false)
        {
            $result[] = $part[0];
            if (!$once || !$replaced)
            {
                if (strtolower(substr($part[0], 0, 3)) == '<a ')
                {
                    $pos_open = $part[1];
                }
                elseif (strtolower($part[0]) == '</a>')
                {
                    $pos_close = $part[1];
                }
            }
            continue;
        }
        if ($pos_open && $pos_open > $pos_close)
        {
            $result[] = $part[0];
            continue;
        }
        if ($is_alnum)
        {
        	$result[] = preg_replace(
	            '/([^a-zA-Z0-9])('.preg_quote($keyword).')([^a-zA-Z0-9])/i',
	            '\1<a href="'.$link.'" target="_blank">\2</a>\3',
	            $part[0],
	            $once ? 1 : -1
	        );
	        if ($part[0] == $result[count($result)-1])
	        {
	        	continue;
	        }
        }
        else
        {
	        $result[] = preg_replace(
	            '/('.preg_quote($keyword).')/i',
	            '<a href="'.$link.'" target="_blank">\1</a>',
	            $part[0],
	            $once ? 1 : -1
	        );
	    }
        $replaced = true;
    }
    return implode('', $result);
}

/**
 * 递归合并两个数组，array_merge_recursive 的覆盖版
 *
 * 如果输入的数组中有相同的字符串键名，则这些值会被合并到一个数组中去，这将递归下去，
 * 因此如果一个值本身是一个数组，本函数将按照相应的条目把它合并为另一个数组。
 * 然而，如果数组具有相同的数组键名，后一个值将 **会覆盖** 原来的值，而不是附加到后面。
 *
 * @param $arr1
 * @param $arr2
 * @return array
 * @from http://php.net/manual/en/function.array-merge-recursive.php#42663
 */
function array_merge_recursive_replace($arr1, $arr2)
{
    if (!is_array($arr1) || !is_array($arr2)) {
        return $arr2;
    }
    foreach ($arr2 as $key => $value) {
        $arr1[$key] = array_merge_recursive_replace($arr1[$key], $value);
    }
    return $arr1;
}

/**
 * 二维数组按指定字段索引
 *
 * @param $array
 * @param $key
 * @return array
 */
function sort_array($array, $key)
{
	$result = array();
	foreach ($array as $item)
	{
		if (empty($item[$key]))
		{
			continue;
		}
		$result[$item[$key]] = $item;
	}
	return $result;
}

/**
 * 将指定字符串转化为 32 位随机字符
 *
 * @param $input
 * @return string
 */
function str_short($input)
{
    $base32 = array(
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
        'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p',
        'q', 'r', 's', 't', 'u', 'v', 'w', 'x',
        'y', 'z', '0', '1', '2', '3', '4', '5'
    );

    $hex = substr(md5($input), 8, 16);
    $hexLen = strlen($hex);
    $subHexLen = $hexLen / 8;
    $output = array();

    for ($i = 0; $i < $subHexLen; $i++) {
        $subHex = substr($hex, $i * 8, 8);
        $int = 0x3FFFFFFF & (1 * ('0x' . $subHex));
        $out = '';

        for ($j = 0; $j < 6; $j++) {
            $val = 0x0000001F & $int;
            $out .= $base32[$val];
            $int = $int >> 5;
        }

        $output[] = $out;
    }

    return implode('', $output);
}

/**
 * 将指定字符串转化为 32 位随机字符
 *
 * @param string $color
 * @param string $input type
 * @return string|array
 */
/**
 * 十六进制颜色代码和3原色代码互转
 *
 * @param string $color
 * @param string $input_type
 * @return array|string
 */
function format_color($color, $input_type = 'hex')
{
	if (($input_type == 'hex') && (strlen($color) == 7)) {
		$ret = array();
		$ret[] = hexdec(substr($color, 1, 2));
		$ret[] = hexdec(substr($color, 3, 2));
		$ret[] = hexdec(substr($color, 5, 2));
		return $ret;
	} elseif (($input_type == 'dec') && is_array($color)) {
		$ret = '#';
		$ret .= dechex($color[0]);
		$ret .= dechex($color[1]);
		$ret .= dechex($color[2]);
		return $ret;
	}
	return '';
}

/**
 * 后台发布稿件的同时调用东软接口把contentid发送过去
 *
 * @param string $rule 权限预留
 * @param string $siteId int 0-丝路数据库，1-丝路资讯站
 * @param int $modelid 内容模型
 * @param string $cayName 栏目名称{新闻，案例，智讯，视讯，国别报告，项目}
 * @param int $contentid 要发送的内容id
 * @return array|int|string
 */
function intface($rule,$siteId,$modelid,$catName,$contentid,$status)
{
	$url = "http://192.168.110.110/silkroad/rest/api/$rule/$siteId/$modelid/$catName/$contentid/$status";
	console($url);
	$jsonData = json_encode(array());
	$headers = array(
		'app_key: xhsl',
    'app_secret: 57ce586290087fb9a1ea856f',
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonData)
    );
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
  curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $result = curl_exec($ch);
  curl_close($ch);
  $data = json_decode($result,true);
	if(!$data['code']==0){
  	echo "<script>alert('接口异常,请联系系统管理员')</script>";
  	file_put_contents('/var/log/cmstop_contentid.log',$contentid.'#',FILE_APPEND);
  }
}
