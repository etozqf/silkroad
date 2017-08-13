<?php
class LoginEngine_sina_weibo
{
	public $data;
	private $access_token_url = 'https://api.weibo.com/oauth2/access_token';
	private $api_url = 'https://api.weibo.com/2/';
	private $identity = 'sina_weibo';
	public static function getInstance($engine)
	{
		if (isset(self::$_instance[$engine]))
		{
			return self::$_instance[$engine];
		}
		$class = "LoginEngine_$engine";
		if (!class_exists($class, false))
		{
			throw new Exception("Widget Engine '$engine' not exists.");
		}
		return self::$_instance[$engine] = new $class();
	}

	public function authorize_url($auth_key, $redirect_url)
	{
		$_SESSION['consumer_key'] = $auth_key['client_id'];
		$_SESSION['consumer_secret'] = $auth_key['client_secret'];
		$url = url('system/auth/authorize', array(
			'platform'	=> $this->identity,
			'next'		=> urlencode($redirect_url.'&type='.$this->identity)
		));
		return $url;
	}

	public function exec($param, $auth_key)
	{
		$result['state'] = true;
		$result['data'] = array(
			'type'			=> $this->identity,
			'username'		=> $param['nickname'],
			'authkey'		=> $param['userid'],
			'access_token'	=> $param['access_token'],
			'expires'		=> $param['expires_in']
		);
		return $result;
	}

	public function post($topic, $context, $access_token, $auth_key, $params = null)
	{
		$topic = loader::model('topic', 'comment')->get($topic);
		$title = value($topic, 'title');
		$url = value($topic, 'url');
		$text = '我评论了:【'.$title.'】__ct_url__ '.$context;
		$text = str_cut($text, 277);
		$text = str_replace('__ct_url__', $url, $text);
		$params['consumer_key'] = $auth_key['client_id'];
		$params['consumer_secret'] = $auth_key['client_secret'];
		$params['api_url'] = $this->api_url;
		$this->oauth2 = new oauth2($params);
		$result = $this->oauth2->request('post', $params['api_url'].'statuses/update.json', array(
			'status' => $text,
			'access_token' => $access_token
		));
	}
}