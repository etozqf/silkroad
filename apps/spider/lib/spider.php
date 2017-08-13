<?php
/**
 * @version	$Id: spider.php 10242 2015-03-09 06:52:41Z yanghengfei $
 */
import("helper.simple_html_dom");
class spider
{
	protected $_scheme = '';
	protected $_baseuri = '';
	protected $_hosturi = '';
	protected $_charset = 'utf-8';
	
	function __construct()
	{
		$this->_charset = strtoupper(config('config','charset'));
	}

    /**
     * 将原有规则的 nextPage / listNextPage 等转换为 start / end 结构
     *
     * @static
     * @param string $next_page_rule 原有规则的 nextPage
     * @return array 转换结果
     */
    static function nextPageToRange($next_page_rule)
    {
        $result = array('', '');
        if (!$next_page_rule)
        {
            return $result;
        }
        $next_page_rule_range = preg_split('/\(.+\)/', trim($next_page_rule, '\/\\'), 2, PREG_SPLIT_NO_EMPTY);
        if (is_array($next_page_rule_range))
        {
            $result = array($next_page_rule_range[0], $next_page_rule_range[1]);
        }
        return $result;
    }

	function getList($url, $rule, $charset)
	{
		return $rule['listType'] == 1
			? $this->_getXMLList($url, $rule, $charset)
			: $this->_getHTMLList($url, $rule, $charset);
	}
	protected function _getXMLList($url, $rule)
	{
		import('helper.xml');
	    $data = array();
		$aelement = file_get_xmlarray($url, 'rss');
		if (! $aelement)
		{
			return $data;
		}
	    $aelement = $aelement['channel']['item'];
	    foreach ($aelement as $item)
		{
            if (($item['title'] = strip_tags($item['title'])))
            {
                $data[] = array(
                    'title'=>$item['title'],
                    'url'=>$item['link']
                );
            }
		}
		return $data;
	}
	protected function _getHTMLList($url, $rule, $charset)
	{
		$content = $this->_fileGetContents($url, $charset);
		$this->_initUri($url, $content);
		if (($nextPage = trim($rule['listNextPage']))
			&& preg_match('#^/.+/$#', $nextPage))
		{
			$rule['listNextPage'] = $nextPage.'sU';
		}
		elseif (($nextPageStart = trim($rule['listNextPageStart']))
            && ($nextPageEnd = trim($rule['listNextPageEnd'])))
        {
            $rule['listNextPage'] = $this->_rangeToPattern($nextPageStart, $nextPageEnd, '([^>\b\s\'"]+)');
        }
        else
        {
			unset($rule['listNextPage']);
		}
        unset($rule['listNextPageStart']);
        unset($rule['listNextPageEnd']);

		$rule['urlPattern'] = preg_quote($rule['urlPattern'], '#');
		$rule['urlPattern'] = '#^'.str_replace('\(\*\)','.*?', $rule['urlPattern']).'$#is';
		$rule['listLimitLength'] = intval($rule['listLimitLength']);
		if (!$rule['listLimitLength'])
		{
			unset($rule['listLimitLength']);
		}
		$data = array();
        $urls = array();
		$this->_getAllList($content, $rule, $charset, $data, $urls);
        unset($urls);
		$return = array();
		foreach ($data as $href => $title)
		{
            $return[] = array(
                'url'=>$href,
                'title'=>strip_tags($title)
            );
		}
		return $return;
	}
	protected function _getAllList($content, $rule, $charset, &$data, &$urls, $t = 0)
	{
		$start = $rule['listStart'];
		$end = $rule['listEnd'];
		if (!empty($start) && !empty($end))
		{
			$content = $this->_cut($content, $start, $end);
		}
		$dom = str_get_html($content);
		foreach ($dom->find('a') as $a)
		{
			if (isset($rule['listLimitLength'])
				 && count($data) >= $rule['listLimitLength'])
			{
				return;
			}
			$href = trim($a->href);
			if (!$href 
				|| preg_match('/^javascript:/i', $href) 
				|| !($href = $this->_fullUrl($href)))
			{
				continue;
			}
			if (preg_match($rule['urlPattern'], $href))
			{
				$text = $a->innertext;
				if (trim($text) == '') continue;
				if (!isset($data[$href]) || strlen($text) > strlen($data[$href]))
				{
					$data[$href] = $text;
				}
			}
		}
		if (isset($rule['listLimitLength'])
			 && count($data) >= $rule['listLimitLength'])
		{
			return;
		}
		
		if (!isset($rule['listNextPage']) || !preg_match($rule['listNextPage'], $content, $m))
		{
			return;
		}
		if (isset($m['url'])
			 || preg_match('/href\s*=\s*(["\'])?(?P<url>[^>"\']+)\1/Ui', $m[0], $m))
		{
			$href = trim($m['url']);
			if (!$href || preg_match('/^javascript:/i', $href)) return;
			$href = $this->_fullUrl($href);
		}
		else
		{
			return;
		}
        if (in_array($href, $urls))
        {
            return;
        }
        $urls[] = $href;
		$content = $this->_fileGetContents($href, $charset);
		if ($content)
		{
			$this->_getAllList($content, $rule, $charset, $data, $urls, 1);
		}
	}
	function getDetails($url, $rule, $charset, $refresh = false)
	{
		$guid = md5($url);
		$cachefile = "spider/$guid.php";
		if (is_array($data = cache_read($cachefile)) && !$refresh)
		{
			return $data;
		}
		$content = $this->_fileGetContents($url, $charset);
		if (! $content)
		{
			return array();
		}
		$this->_initUri($url, $content);
		
		$start = $rule['rangeStart'];
		$end = $rule['rangeEnd'];
		// 缩小范围
		if (!empty($start) && !empty($end))
		{
			$content = $this->_cut($content, $start, $end);
		}
		$data = array();

		foreach (array('title','author','pubdate','source', 'tag', 'description') as $field)
		{
			$start = $rule[$field.'Start'];
			$end = $rule[$field.'End'];
			if (empty($start) || empty($end))
			{
				$data[$field] = '';
			}
			else
			{
				$data[$field] = trim(strip_tags($this->_cut(
					$content,
					$start,
					$end
				)));
			}
		}
		$data['pubdate'] = str_replace(array('年','月','日'),array('-','-',' '),$data['pubdate']);
		$data['pubdate'] = strtotime($data['pubdate']);
		if (!$data['pubdate'] || $data['pubdate'] < 946684800)
		{
			$data['pubdate'] = TIME;
		}
		$data['pubdate'] = date('Y-m-d H:i:s', $data['pubdate']);
		$data['author'] = str_natcut($data['author'], 20, 'utf-8', '');
		$data['link'] = $url;
		$data['content'] = $this->_getContent($content, $rule, $charset);
		$data['content'] = $this->_parse_content($data['content']);
		$data['saveremoteimage'] = $rule['saveRemoteImg'];
		cache_write($cachefile, $data);
		return $data;
	}
	
	protected function _getContent($content, $rule, $charset)
	{
		
		$start = $rule['contentStart'];
		$end = $rule['contentEnd'];
		if (empty($start) || empty($end))
		{
			return '';
		}
		if (($nextPage = trim($rule['nextPage']))
			&& preg_match('#^/.+/$#', $nextPage))
		{
			$rule['nextPage'] = $nextPage.'sU';
		}
        elseif (($nextPageStart = trim($rule['nextPageStart']))
            && ($nextPageEnd = trim($rule['nextPageEnd'])))
        {
            $rule['nextPage'] = $this->_rangeToPattern($nextPageStart, $nextPageEnd, '([^>\b\s\'"]+)');
        }
        else
		{
			unset($rule['nextPage']);
		}
        unset($rule['nextPageStart']);
        unset($rule['nextPageEnd']);

		$body = array();
        $urls = array(); // 避免重复或无限递归获取某一链接
		$this->_getAllContent($content, $rule, $charset, $body, $urls);
        unset($urls);
		return count($body) > 1
			? ('<p class="mcePageBreak">&nbsp;</p>'.implode('<p class="mcePageBreak">&nbsp;</p>', $body))
			: $body[0];
	}
	
	protected function _getAllContent($content, $rule, $charset, &$body, &$urls)
	{
		$c = $this->_cut($content, $rule['contentStart'], $rule['contentEnd']);
		$c = $this->_stripContent($c, $rule['allowTags']);
		$c = $this->_replaceContent($c, (array)$rule['replacement']);
		$body[] = $c;
		if (!isset($rule['nextPage']) || !preg_match($rule['nextPage'], $content, $m))
		{
			return;
		}
		if (isset($m['url'])
			 || preg_match('/href\s*=\s*(["\'])?(?P<url>[^>"\']+)\1/i', $m[0], $m))
		{
			$href = trim($m['url']);
			if (!$href || preg_match('/^javascript:/i', $href)) return;
			$href = $this->_fullUrl($href);
		}
		else
		{
			return;
		}
        if (in_array($href, $urls))
        {
            return;
        }
        $urls[] = $href;
		$content = $this->_fileGetContents($href, $charset);
		if (! $content)
		{
			return;
		}
		$start = $rule['rangeStart'];
		$end = $rule['rangeEnd'];
		if (!empty($start) && !empty($end))
		{
			$content = $this->_cut($content, $start, $end);
		}
		$this->_getAllContent($content, $rule, $charset, $body, $urls);
	}
	
	public function _replaceContent($content, $replacement)
	{
		if (is_array($replacement))
		{
			foreach ($replacement['source'] as $i=>$s)
			{
				$s = trim($s);
				if (preg_match('#^/.+/$#', $s))
				{
					$s = $s.'sU';
				}
				else
				{
					$s = '/'.str_replace('\(\*\)','(.*?)',preg_quote($s)).'/sU';
				}
				$t = $replacement['target'][$i];
				$content = preg_replace($s, $t, $content);
			}
		}
		$content = preg_replace(
			'#(<img\s+[^>]*src\s*=\s*(["\'])?)([^"\']+)(\2.*[/]?>)#Uise',
			'stripcslashes("\1".$this->_fullUrl(\'\3\')."\4")',
			$content
		);
		return $content; 
	}
	
	protected function _stripContent($content, $allowTags)
	{
		$content = preg_replace('/<(script|style)[^>]*>.*<\/\1>/Uis', '', $content);
		$allowTags = array_unique(array_filter(array_map('trim', explode(',', $allowTags))));
		if ($allowTags)
		{
			$allowTags = '<'.implode('><', $allowTags).'>';
		}
		else
		{
			$allowTags = null;
		}
		return strip_tags($content, $allowTags);
	}
	
	protected function _cut($content, $startTag, $endTag)
	{
		if (preg_match('#^/.+/$#i', trim($startTag)))
		{
			if (!preg_match(trim($startTag).'sU', $content, $m, PREG_OFFSET_CAPTURE))
			{
				return '';
			}
			$spos = $m[0][1] + strlen($m[0][0]);
		}
		else
		{
			$startTag = str_replace("\r\n", "\n", $startTag);
			$spos = strpos($content, $startTag);
			if ($spos === false)
			{
				return '';
			}
			$spos = $spos + strlen($startTag);
		}
		if (preg_match('#^/.+/$#i', trim($endTag)))
		{
			if (!preg_match(trim($endTag).'sU', $content, $m, PREG_OFFSET_CAPTURE))
			{
				return '';
			}
			$epos = $m[0][1];
		}
		else
		{
			$endTag = str_replace("\r\n", "\n", $endTag);
			$epos = strpos($content, $endTag, $spos);
			if ($epos === false)
			{
				return '';
			}
		}
		return substr($content, $spos, $epos-$spos);
	}
	
	protected function _fileGetContents($url, $charset)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$content = curl_exec($ch);
		curl_close($ch);

		if ($this->_charset != strtoupper($charset))
		{
			$content = mb_convert_encoding($content, $this->_charset, $charset);
		}
		
		return str_replace("\r\n", "\n", $content);
	}
	
	protected function _initUri($url, $content)
	{
		if (preg_match('/<base\s+[^>]*href\s*=\s*(["\'])?([^>"\']+)\1[^>]*[\/]?>/', $content, $m))
		{
			$url = $m[2];
		}
		$info = parse_url($url);
		$_pass = empty($info['pass']) ? '' : ":{$info['pass']}";
		$_auth = empty($info['user']) ? '' : "{$info['user']}{$_pass}@";
		$_port = empty($info['port']) ? '' : ":{$info['port']}";
		
		$this->_scheme = $info['scheme'];
		$this->_hosturi = $_auth.$info['host'].$_port;
		$path = explode('/', $info['path']);
		array_pop($path);
		$path = implode('/', $path);
		$this->_baseuri = rtrim($this->_hosturi.$path, '/');
	}
	
	protected function _fullUrl($url)
	{
		if (preg_match('#^(?:http|https|ftp|mms|rtsp|thunder|emule|ed2k)://#', $url))
		{
			return $url;
		}
		
		$url = trim($url);
		if ($url == '')
		{
			return '';
		}
	    $pos = strpos($url, "#");
	    if ($pos > 0)
		{
	    	$url = substr($url, 0, $pos);
	    }
	    if ($url[0] == '/')
		{
	        $url = $this->_hosturi . $url;
	    }
		else
		{
	    	$url = $this->_baseuri .($url[0] == '?' ? '' : '/'). $url;
	    }
	    $parts = explode('/', $url);
	    $okparts = array();
	    while (($part = array_shift($parts)) !== NULL)
		{
			$part = trim($part);
            if ($part == '.' || $part === '')
			{
				continue;
			}
			if ($part == '..')
			{
				if (count($okparts) > 1)
				{
					array_pop($okparts);
				}
                continue;
			}
			$okparts[] = $part;
		}
		return $this->_scheme .'://'. implode('/', $okparts);
	}

    protected function _rangeToPattern($start, $end, $match)
    {
        return '#'
            . str_replace('\(\*\)', '.*', preg_quote($start))
            . $match
            . str_replace('\(\*\)', '.*', preg_quote($end))
            . '#sU';
    }

    private function _parse_content($content)
    {
    	if (preg_match_all('#src="*(.*\.jpg?|.*\.png?)"*#', $content, $imginfo))
    	{
    		$newImgurl = array();
    		foreach ($imginfo[1] as $img)
    		{
    			$img = trim($img, '"');
    			$newImgurl[] = $this->_fullUrl($img);
    		}
    		$content = str_replace($imginfo[1], $newImgurl, $content);
    	}
        $content = preg_replace_callback('#<\s*([\/]?)\s*([\w]+)[^>]*>#im', array($this, '_parse_tag'), $content);
        $content = preg_replace('#<a\s[^>]*>(.*?)<\/a>#im', "$1", $content);
        $content = preg_replace('#(?:<p[^>]*>)?(<img[^>]+>)(?:<\/p>)?#im', "<p style='text-align:center;text-indent: 0px;'>$1</p>", $content);
        $content = preg_replace_callback('#<p>(\s|&nbsp;|　)*(.*?)<\/p>#im', array($this, '_parse_space'), $content);
        return $content;
    }

    private function _parse_tag($matches)
    {
        list($with_tag, $is_close, $tag) = $matches;
        $tag = strtolower($tag);
        $allow_tags = array('p', 'a', 'img', 'br', 'b', 'strong');
        if (in_array($tag, $allow_tags))
        {
            if ($is_close != '')
            {
                return $with_tag;
            }
            switch ($tag) {
                case 'p':
                    return '<p>';
                case 'br':
                    return '</p><p>';
                default:
                    return $with_tag;
            }
        }
    }

    private function _parse_space($matches)
    {
        $string = $matches[2];
        return ($string == '') ? '' : '<p>'.$string.'</p>';
    }
}