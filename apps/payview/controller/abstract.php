<?php

abstract class payview_controller_abstract extends controller
{
	protected $app, $json, $template, $view, $config, $setting, $system, $_userid, $_username, $_groupid, $_roleid;

    function __construct(& $app)
    {
        parent::__construct();

		$this->app = $app;
		$this->_userid = & $app->userid;
		$this->_username = & $app->username;
		$this->_groupid = & $app->groupid;
		$this->_roleid = & $app->roleid;

		$this->config = config::get('config');
		$this->setting = setting::get($app->app);
		$this->system = setting::get('system');
		
		$this->payview_power = loader::model('admin/payview_power');

		$this->json = & factory::json();
		$array = array('_userid'=>$this->_userid, '_username'=>$this->_username, '_groupid'=>$this->_groupid, '_roleid'=>$this->_roleid);
		
		
		if ($app->client === 'admin')
		{
			$this->view = & factory::view($app->app);
		    $this->view->assign('CONFIG',  $this->config);
			$this->view->assign('SETTING',  $this->setting);
			$this->view->assign('SYSTEM',  $this->system);
			$this->view->assign($array);
		}

		$this->template = & factory::template($app->app);
		$this->template->assign('CONFIG',  $this->config);
		$this->template->assign('SETTING',  $this->setting);
		$this->template->assign('SYSTEM',  $this->system);
		$this->template->assign($array);
    }
    
    public function execute()
    {
    	if ($this->action_exists($this->app->action))
    	{
    		$response = call_user_func_array(array($this, $this->app->action), $this->app->args);
    	}
    	else 
    	{
    		$this->_action_not_defined($this->app->action);
    	}
        return $response;
    }
    
    protected function _action_not_defined($action)
    {
    	$this->showmessage("<font color='red'>$action</font> 动作不存在");
    }
	
	//检测阅读权限
	protected function _check_power($catid)
	{
		$catid = $catid ? $catid : intval($_GET['catid']);
		if(!$catid) return false;
		$userid = $this->_userid;
		$data = $this->payview_power->get_power_cache($userid);
		$now = TIME;
		foreach($data as $categorys)
		{
			if(in_array($catid,$categorys['power_catids']) && $categorys['starttime']<$now && $categorys['endtime']>$now)
			{
				return true;
			}
		}
		return false;
	}
	
	//读取文件
	protected function _get_file($file)
	{
		if (file_exists($file)) { 
			header('Content-Description: File Transfer'); 
			header('Content-Type: application/octet-stream'); 
			header('Content-Disposition: attachment; filename='.basename($file)); 
			header('Content-Transfer-Encoding: binary'); 
			header('Expires: 0'); 
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0'); 
			header('Pragma: public'); 
			header('Content-Length: ' . filesize($file)); 
			ob_clean(); 
			flush(); 
			readfile($file); 
			exit; 
		} 
		else
		{
			$this->showmessage('文件不存在！');
		}
	}
	
	//转换附件地址
	protected function _transform_filepath($content, $catid)
	{
		$upload_path = APP_URL.url('payview/'.$this->app->controller.'/downloadfile').'&f=';
		$search = '/href\s*=\s*["\']('.str_replace('/','\\/',UPLOAD_URL).'[^"\']+)["\']/im';
		preg_match_all($search, $content, $result);
		if(!empty($result[0])){
			$newsrc = '';
			foreach($result[0] as $key=>$oneimg)
			{   
				$oldsrc = $result[1][$key];
				$newsrc = $catid . '|' . str_replace(UPLOAD_URL,'',$oldsrc);
				$newsrc = $upload_path.str_encode($newsrc,$this->config['authkey']);
				if(!empty($newsrc)){
					$content = str_replace($oldsrc, $newsrc, $content);
					$newsrc = '';
				}
			}
		}
		return $content;
	}
}