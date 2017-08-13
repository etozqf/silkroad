<?php
/**
* 搜狐畅言
*/
class Changyan
{

	public static $instance;
	public static function getInstance()
	{
		if (empty(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	// 搜狐畅言提供CmsTop的标识, 因加密问题用私有变量的方式写在这里
	private $user, $password, $token, $error;
	private $client_id = "caqYcMHwX";
	private $key = "FA035EACB63674644319E9B7ED476CBB";

	private $errorString = array(
		'user name exist' => '用户名已存在',
		'request failed' => '接口访问失败'
	);

	/**
	 * 注册
	 * 
	 * @param {string} user
	 * @param {string} password
	 * @return {boolean}
	 */
	function register($user, $password) 
	{
		$this->user = $user;
		$this->password = $password;

		$url = 'http://changyan.sohu.com/admin/api/open/reg';
		$post = array(
			'client_id' => $this->client_id,
			'user' => $user,
			'password' => $password,
			'sign' => $this->_sign()
		);
		$response = request($url, $post, 30, false);
		$content = json_decode(value($response, 'content', ''), 1);
		if (empty($content)) {
			$this->error = 'request failed';
			return false;
		}
		if ($content['status'] != '0') {
			if ($content['msg'] === 'user name exist') {
				return $this->login($user, $password);
			}
			$this->error = $content['msg'];
			return false;
		}
		$this->token = array(
			'value' => $content['token'],
			'expired' => TIME + 60
		);
		return true;
	}

	/**
	 * 自动注册
	 * @param  {string} $isvName 站点名称	
	 * @param  {string} $url 站点地址
	 * @return {mixed}
	 */
	function autoRegister($isvName, $url)
	{
		$url = 'http://changyan.sohu.com/admin/api/open/auto-reg';
		$signString = $this->client_id . '_' . TIME . '000';
		$post = array(
			'client_id' => $this->client_id,
			'isv_name' => $isvName,
			'url' => $url,
			'sign' => $this->_sign($signString)
		);
		$response = request($url, $post, 30, false);
		$content = json_decode(value($response, 'content', ''), 1);
		if (empty($content) || ($content['status'] != '0')) {
			$this->error = 'request failed';
			return false;
		}
		$this->token = array(
			'value' => $content['token'],
			'expired' => TIME + 60
		);
		$this->password = substr($content['user'], 0, 6);
		$this->user = $content['user'];
		return array(
			'user' => $this->user,
			'password' => $this->password
		);
	}

	/**
	 * 登陆
	 * 
	 * @param {string} user
	 * @param {string} password
	 * @return {boolean}
	 */
	function login($user, $password)
	{
		$this->user = $user;
		$this->password = $password;

		$url = 'http://changyan.sohu.com/admin/api/open/validate';
		$post = array(
			'client_id' => $this->client_id,
			'user' => $user,
			'password' => $password,
			'sign' => $this->_sign()
		);
		$response = request($url, $post, 30, false);
		$content = json_decode(value($response, 'content', ''), 1);
		if (empty($content)) {
			$this->error = 'request failed';
			return false;
		}
		if ($content['status'] != '0') {
			$this->error = $content['msg'];
			return false;
		}
		$this->token = array(
			'value' => $content['token'],
			'expired' => TIME + 60
		);
		return true;
	}

	/**
	 * 获取代码
	 * 
	 * @param {boolean} is_mobile
	 * @return {string} code
	 */
	function code($is_mobile = false)
	{
		$url = 'http://changyan.sohu.com/admin/api/open/get-code';
		$get = array(
			'client_id' => $this->client_id,
			'user' => $this->user,
			'sign' => $this->_sign(),
			'is_mobile' => $is_mobile
		);
		$response = request($url . '?' .http_build_query($get), null, 30, false);
		$content = json_decode(value($response, 'content', ''), 1);
		if (empty($content)) {
			$this->error = 'request failed';
			return false;
		}
		if ($content['status'] != '0') {
			$this->error = $content['msg'];
			return false;
		}
		return $content['code'];
	}

	/**
	 * 同步登陆
	 *
	 * @return {string} url
	 */
	function sso()
	{
		$url = 'http://changyan.sohu.com/admin/api/open/set-cookie';
		$get = array(
			'token' => $this->_getToken(),
			'client_id' => $this->client_id
		);
		return $url . '?' . http_build_query($get);
	}

	/**
	 * 返回错误信息
	 * 
	 * @return {string} error
	 */
	function error()
	{
		return empty($this->errorString[$this->error]) ? $this->error : $this->errorString[$this->error];
	}

	/**
	 * 签名
	 *
	 * @return {string} sign
	 */
	private function _sign($string = '')
	{
		$string = empty($string) ? $this->user . '_' . TIME . '000' : $string;
		$key = '';
		$ret = '';

		for ($i=0, $l = strlen($this->key)-1; $i < $l; $i+=2) {
			$key .= chr(hexdec($this->key[$i].$this->key[$i+1]));
		}
		$blocksize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
		$pad = $blocksize - (strlen($string) % $blocksize);
		$pkcString = $string . str_repeat(chr($pad), $pad);

		$encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $pkcString, MCRYPT_MODE_ECB);
		for ($i=0; $i<strlen($encrypted); $i++){
			$ord = ord($encrypted[$i]);
			$hexCode = dechex($ord);
			$ret .= substr('0'.$hexCode, -2);
		}
		return $ret;
	}

	/**
	 * 获取 token
	 * 
	 * @return {string} token
	 */
	private function _getToken()
	{
		if (empty($this->token) || $this->token['expired'] < TIME) {
			$this->login($this->user, $this->password);
		}
		return $this->token['value'];
	}
}