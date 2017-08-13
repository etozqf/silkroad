<?php
/**
 *	Description : 获取推荐阅读相关数据
 *
 *	@author      Hmy
 *	@datetime    2017/3/28 19:57
 *	@copyright   Beijing CmsTop Technology Co.,Ltd.
 */

class controller_recread extends system_controller_abstract
{
	private $content;

	function __construct($app)
	{
		parent::__construct($app);
	}

	function index()
	{
		$dataBase = $_GET['dataBase'];
		$userName = $_GET['userName'];
		$lang = $_GET['lang'];
		$url = 'http://192.168.110.110/silkroad/rest/api/rule/'.$dataBase.'/'.$userName.'/'.$lang.'/recommend';
		$jsonData = json_encode(array());
        $headers = array(
            'app_key: xhsl',
            'app_secret: 57ce586290087fb9a1ea856f',
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData),
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $result = curl_exec($ch);
        curl_close($ch);
        //$data = json_decode($result, true);
        $data = $_GET['callback']."($result)";
        echo $data;
	}
	
}