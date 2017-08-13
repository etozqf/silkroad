<?php
class Chinacache_v4
{
	function __construct()
	{
		
	}

	function run($para)
	{
		if (!$para['user'] || !$para['pswd'])
		{
			return array('state'=>0, 'error'=>'缺少参数');
		}

		$url	= "https://r.chinacache.com/content/refresh";

		$path	= $_SERVER['path'];
		$path	= str_replace('@', '%0D%0A', $path);
		unset($_SERVER['path']);

		$task = array(
			'urls'		=> $path
		);
		$params = array(
			'username'	=> $para['user'],
			'password'	=> $para['pswd'],
			'task'		=> json_encode($task)
		);

		$req = request($url, $params, 40, false);
		switch ($req['httpcode']) {
			case '400':
				$ret = array('state'=>0, 'error'=>'参数不正确');
				break;
			case '401':
				$ret = array('state'=>0, 'error'=>'用户名密码错误');
				break;
			case '403':
				$ret = array('state'=>0, 'error'=>'用户被禁用');
				break;
			case '500':
				$ret = array('state'=>0, 'error'=>'ChinaCache服务器错误');
				break;
			case '200':
				if ($json = json_decode($req['content']))
				{
					if ($json['status'] != 'FAIL')
					{
						$ret = array('state'=>1);
					}
					else
					{
						$ret = array('state'=>0, 'error'=>'CDN更新失败');
					}
					break;
				}
			default:
				$ret = array('state'=>0, 'error'=>'未知错误');
				break;
		}
		return $ret;
	}
}
