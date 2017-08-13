<?php

@set_time_limit(0);
/**
 * 索贝vms接口
 */
final class vms
{
	private static $objInstance; 
	public static function getInstance($apiurl, $token)
	{
		$name = md5($apiurl . $token);
		if (empty(self::$objInstance[$name])) {
			self::$objInstance[$name] = new vms($apiurl, $token);
		}
		return self::$objInstance[$name];
	}

	private $apiurl, $token, $hash, $error;

	/**
	 * vms接口
	 * 
	 * @param {string} apiurl
	 * @param {string} token
	 */
	function __construct($apiurl, $token)
	{
		$this->apiurl = $apiurl;
		$this->token = $token;
		$this->hash = md5($this->apiurl.$this->token);
	}

	/**
	 * 获取账号信息
	 * 
	 * @see <API>method=authenticate
	 */
	function getInfo()
	{
		$url = $this->apiurl . '?method=authenticate';
		$ret = $this->_request($url, array(), 30, false);
		if ($ret && $ret['siteNames']) {
			factory::cache()->set('sobeyvms:siteName:'.$this->hash, $ret['siteNames']);
		}
		return $ret;
	}

	/**
	 * 获取栏目列表
	 * 
	 * @see <API>method=getCatalogList
	 * @param {array} params
	 * @return {array} data
	 */
	function getCate($params = array())
	{
		$url = $this->apiurl . '?method=getCatalogList';
		return $this->_request($url, $params, 30, false);
	}

	/**
	 * 获取视频列表
	 * 
	 * @see <API>method=getVideoList
	 * @param {array} params
	 * @return {array} data
	 */
	function getVideoList($params)
	{
		$url = $this->apiurl . '?method=getVideoList';
		return $this->_request($url, $params, 30, false);
	}

	/**
	 * 获取视频
	 * 
	 * @see <API>method=getVideoById
	 * @param {array} params
	 * @return {array} data
	 */
	function getVideoById()
	{

	}

	function error()
	{
		switch ($this->error) {
			case 'NO_PRIVILEGE':
				return '没有权限';
			case 'INVALID_PARAMETER':
				return '参数错误';
			case 'SERVICE_EXPIRED':
				return 'token已过期';
			case 'SERVICE_SUSPEND':
				return '服务已停止';
			case 'REQUEST_FREQUENTLY':
				return '请求过于频繁';
			case 'SERVICE_PROCESS_ERROR':
				return '服务器异常';
			case 'UNRESOLVABLE':
				return '响应解析失败';
		}
		return $this->error;
	}

	/**
	 * 获取hash
	 * 
	 * @return $this->hash
	 */
	function hash()
	{
		return $this->hash;
	}

	private function _request($url, $post = array(), $timeout = 30)
	{
		$post = array_merge(array(
			'partnerToken' => $this->token,
			'dataType' => 'json'
		), $post);
		$rawData = request($url, $post, $timeout, false);
		$data = value($rawData, 'content');
		if (!$data || !($data = json_decode($data, 1))) {
			$this->error = str_natcount($rawData['content']) > 100 ? 'UNRESOLVABLE' : htmlentities($rawData['content']);
			return false;
		}
		if (!empty($data['error'])) {
			$this->error = $data['error'];
			return false;
		}
		return $data;
	}
}