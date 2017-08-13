<?php
class LoginEngine_qzone
{
	public $data;
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
		return 'https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id='.$auth_key['client_id'].'&redirect_uri='.urlencode($redirect_url.'&type=qzone&state='.$_SESSION['third_login']);
	}

	public function exec($param, $auth_key)
	{
		$this->data['type'] = 'qzone';
		// 获得auth_token (get jsonp)
		$url = 'https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&client_id='.$auth_key['client_id'].'&client_secret='.$auth_key['client_secret'].'&code='.$param['code'].'&state='.$_SESSION['third_login'].'&redirect_uri='.APP_URL;
		if (!$query = $this->_request($url))
		{
			return array('state'=>false, 'error'=>'获取auth_token失败');
		}

		// 获得openid
		$url = 'https://graph.qq.com/oauth2.0/me?'.$query;
		$html = $this->_request($url);
		preg_match_all('/\{(?:"client_id"\:"(\w*)".*?"openid":"(\w*)")\}/', $html, $match);
		if ($match)
		{
			$appid = $match[1][0];
			$openid = $match[2][0];
		}
		else
		{
			return array('state'=>false, 'error'=>'获取openid失败');
		}
		$this->data['authkey'] = $openid;

		// 获得用户数据
		$url = 'https://graph.qq.com/user/get_user_info?'.$query.'&oauth_consumer_key='.$appid.'&openid='.$openid;
		$html = $this->_request($url);
		$result = json_decode($html, 1);
		if ($result)
		{
			$this->data['username'] = $result['nickname'];
			return array('state'=>true, 'data'=>$this->data);
		}
	}

	private function _request($url)
	{
		$user_agent = 'Mozilla/4.0+(compatible;+MSIE+6.0;+Windows+NT+5.1;+SV1)';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		//返回结果
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		return curl_exec($ch);
	}
}