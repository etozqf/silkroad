<?php
/**
* a curl interface for getting wechat data
*/
class Wechat
{
	public $type;
	private $username, $password, $token, $cookie, $error;

	function init($username, $password, $type)
	{
		$this->username = $username;
		$this->password = $password;
		$this->type = $type;
		foreach (array('token', 'cookie') as $item) {
			if (empty($this->$item) && !($this->$item = factory::cache()->get("wechat_$item"))) {
				if (!$this->login()) return false;
			}
		}
		return true;
	}

	function login()
	{
		$url = 'https://mp.weixin.qq.com/cgi-bin/login?lang=zh_CN';
		$post = array(
			'username' => $this->username,
			'pwd' => $this->password,
			'imgcode' => '',
			'f' => 'json'
		);
		$postString = array();
		foreach ($post as $k => $p) {
			$postString[] = $k . '=' . $p;
		}
		$postString = implode('&', $postString);
		$response = $this->_request($url, $postString);
		if (empty($response) || ($response['httpcode'] != 200) || !$response['content_length']) {
			$this->error = '网络连接异常';
			return false;
		}
		$data = json_decode($response['content'], true);
		if ($data['ErrCode'] != 0) {
			$this->error = '登陆失败,errCode: '.$data['ErrCode'];
			return false;
		}
		preg_match('#token=(\w*)#', $data['ErrMsg'], $m);
		$this->cookie = $this->_get_cookie($response['header']);
		$this->token = $m[1];
		factory::cache()->set('wechat_cookie', $this->cookie);
		factory::cache()->set('wechat_token', $this->token);
		return true;
	}

	function revoke()
	{
		foreach (array('token', 'cookie') as $item) {
			$this->$item = factory::cache()->rm("wechat_$item");
		}
		return true;
	}

	function getToken()
	{
		$url = "https://mp.weixin.qq.com/cgi-bin/advanced?action=dev&t=advanced/dev&token={$this->token}&lang=zh_CN";
		$data = $this->_request($url, null, $this->cookie);

		if ($this->isTimeout($data)) {
			return $this->login() ? $this->getToken(): false;
		}

		$content = $data['content'];
		if (preg_match('#editOpen:"(\d)"===#', $content, $m)) {
			$editOpen = intval($m[1]);
		}
		if (preg_match('#[^\w]open:"(\d)"===#', $content, $m)) {
			$isOpen = intval($m[1]);
		}
		$devModel = !$editOpen && $isOpen;
		if (preg_match('#class="nickname">(.*?)</a>#', $content, $m)) {
			$name = $m[1];
		}
		$ret = array();
		foreach(array('URL', 'Token', 'AppId', 'AppSecret') as $w) {
			if (preg_match('#{name:"'.$w.'",value:"([^"]*)"#', $content, $m)) {
				$ret[$w] = $m[1];
			}
		}
		return array(
			'devModel' => $devModel,
			'name' => $name,
			'data' => $ret
		);
	}

	function getInfo()
	{
		$url = "https://mp.weixin.qq.com/cgi-bin/home?t=home/index&lang=zh_CN&token={$this->token}";
		$data = $this->_request($url, null, $this->cookie);

		if ($this->isTimeout($data)) {
			return $this->login() ? $this->getInfo(): false;
		}

		$ret = array();
		foreach (array(
			'news' => 'added_message',
			'user' => 'added_fans',
			'users' => 'total_fans extra'
		) as $key => $item) {
			if (preg_match("#{$item}\">(.*)</li>#", $data['content'], $m)) {
				if (preg_match('#<em class="number">(\d+)</em>#', $m[1], $r)) {
					$ret[$key] = $r[1];
				}
			}
		}
		return $ret;
	}

	function isTimeout($data)
	{
		return !$data['header'] || ($data['httpcode'] != 200) || (strpos($data['content'], '登录超时') !== false);
	}

	function error()
	{
		return empty($this->error) ? '' : $this->error;
	}

	private function _request($url, $post = null, $cookie = false)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:25.0) Gecko/20100101 Firefox/25.0');
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 35);
		curl_setopt($ch, CURLOPT_TIMEOUT, 40);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Host: mp.weixin.qq.com',
			'Referer: https://mp.weixin.qq.com/'
		));

		if ($cookie) {
			$cookiestring = '';
			foreach ((array)$cookie as $key=>$val) {
				$cookiestring .= rawurlencode($key).'='.rawurlencode($val).';';
			}
			curl_setopt($ch, CURLOPT_COOKIE , $cookiestring);
		}
		if ($post) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($post) ? http_build_query($post) : $post);
		}
		
		if (!ini_get('safe_mode') && ini_get('open_basedir') == '') {
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1 );
		}

		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		
		$ret = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$content_length = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
		if (!$content_length) $content_length = curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
		$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($ret, 0, $header_size);
		$content = substr($ret, $header_size);
		curl_close($ch);
		return array(
			'httpcode'=>$httpcode,
			'content_length'=>$content_length,
			'content_type'=>$content_type,
			'header'=>$header,
			'content'=>$content,
			'header_size'=>$header_size
		);
	}

	private function _get_cookie($head)
	{
		$head_arr = preg_split('#[\r\n]+#', $head);
		$ret = array();
		foreach ($head_arr as $item) {
			if (!preg_match('#^Set\-Cookie: (.*)$#', $item, $m)) {
				continue;
			}
			if (empty($m[1])) {
				continue;
			}
			foreach (explode(';', $m[1]) as $cookie) {
				if (preg_match('#^(.*?)=(.*?)$#i', $cookie, $ma)) {
					$ret[$ma[1]] = $ma[2];
				}
			}
		}
		return $ret;
	}
}