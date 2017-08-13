<?php
class api_controller_abstract extends controller
{
	protected $app, $cache, $json, $template, $view, $config, $setting, $system, $_userid, $_username, $_groupid, $_roleid, $_departmentid;
    protected $parameters, $auth_key, $auth_sign, $auth_secret;

    function __construct($app)
    {
        parent::__construct($app);
        $this->config = config::get('config');
        $this->setting = setting::get($app->app);
        $this->system = setting::get('system');
	$this->json = factory::json();
	$this->cache = factory::cache();
		
	// 先验证授权信息
        $this->auth_key = $this->get_key();
        if(!$this->auth_key)
        {
            $this->showmessage('KEY获取失败');
        }
        $this->auth_sign = $this->get_sign();
        //if(!$this->auth_sign)
        //{
          //  $this->showmessage('SIGN签名获取失败');
        //}
        $this->auth_secret = $this->get_secret($this->auth_key);
        if(!$this->auth_secret)
        {
            $this->showmessage('授权用户不存在');
        }
        $this->parameters = $this->get_parameter();
       // if(!$this->verify_sign($this->parameters, $this->auth_secret, $this->auth_sign))
        //{
          //  $this->showmessage('授权或数据验证失败');
        //}

        // 验证通过，写入用户数据
        $userinfo = $this->get_userinfo($this->auth_key);
        $this->_userid = $userinfo['userid'];
        $this->_username = $userinfo['username'];
        $this->_groupid = $userinfo['groupid'];
        $this->_roleid = $userinfo['roleid'];
        $this->_departmentid = $userinfo['departmentid'];

        // 如果app为NULL，表示只是实例一个类，无需权限验证
        if ($app === NULL) return true;

		// 验证方法权限
        require_once CMSTOP_PATH.'apps/system/lib/priv.php';
        priv::init($this->_userid, $this->_roleid);
        if (!priv::openaca($this->app->app, $this->app->controller, $this->app->action))
        {
            $this->showmessage('您没有此操作权限[API]['.$this->app->app.'/'. $this->app->controller.'/'. $this->app->action.']！');
        }

        // 兼容CT内容生成，输出点东西
        $array = array('_userid'=>$this->_userid, '_username'=>$this->_username, '_groupid'=>$this->_groupid, '_roleid'=>$this->_roleid, '_departmentid'=>$this->_departmentid);
        if ($app->client === 'api')
        {
            $this->view = factory::view($app->app);
            $this->view->assign('CONFIG', $this->config);
            $this->view->assign('SETTING', $this->setting);
            $this->view->assign('SYSTEM', $this->system);
            $this->view->assign($array);
        }

        $this->template = factory::template($app->app);
        $this->template->assign('CONFIG', $this->config);
        $this->template->assign('SETTING', $this->setting);
        $this->template->assign('SYSTEM', $this->system);
        $this->template->assign($array);
    }

    /**
     * 兼容model中的online获取
     */
    function online()
    {
        return array(
            'userid'=>$this->_userid,
            'username'=>$this->_username,
            'groupid'=>$this->_groupid,
            'roleid'=>$this->_roleid,
            'departmentid'=>$this->_departmentid
        );
    }

    /**
     * 获取接口参数
     *
     * @return array
     */
    protected function get_parameter()
    {
        $_private_params = array('app','controller','action','key','sign');
        $params = array();
        foreach($_GET as $key=>$param)
        {
            if(in_array($key,$_private_params)) continue;
            if(is_numeric($param))
            {
                $param = (int)$param;
            }
            $params[$key] = $param;
        }
        foreach($_POST as $key=>$param)
        {
            if(in_array($key,$_private_params)) continue;
            if(is_numeric($param))
            {
                $param = (int)$param;
            }
            $params[$key] = $param;
        }
        return $params;
    }

    /**
     * 获取KEY 密钥/用户标示
     *
     * @return string
     */
    protected function get_key()
    {
        $key = isset($_GET['key']) ? $_GET['key'] : '';
        if(!$key)
        {
            $key = isset($_POST['key']) ? $_POST['key'] : '';
        }
        return $key;
    }

    /**
     * 获取KEY对应的用户信息
     *
     * @param $key 密钥/用户标示
     * @return string
     */
    protected function get_userinfo($key)
    {
        $_key = 'api_userinfo_' .$key;
        $userinfo = $this->cache->get($_key);
        if(!$userinfo)
        {
            $openauth = loader::model('admin/openauth', 'system');
            $userinfo = $openauth->get("auth_key='{$key}' AND disabled=0");
            if(!$userinfo)
            {
                return false;
            }
            $user_model = loader::model('admin/admin', 'system');
            $a = $user_model->isadminuser($userinfo['userid']);
            if (!$a)
            {
                return false;
            }
            $userinfo = array_merge($userinfo, $a);
            $user_model = loader::model('member', 'member');
            $a = $user_model->get($userinfo['userid'], 'groupid');
            if (!$a)
            {
                return false;
            }
            $userinfo = array_merge($userinfo, $a);
            $this->cache->set($_key,$userinfo);
        }
        return $userinfo;
    }

    /**
     * 获取KEY的私钥
     *
     * @param $key 密钥/用户标示
     * @return string
     */
    protected function get_secret($key)
    {
        $userinfo = $this->get_userinfo($key);
        return $userinfo['auth_secret'];
    }

    /**
     * 获取数据签名
     *
     * @return string
     */
    protected function get_sign()
    {
        $sign = isset($_GET['sign']) ? $_GET['sign'] : '';
        if(!$sign)
        {
            $sign = isset($_POST['sign']) ? $_POST['sign'] : '';
        }
        return $sign;
    }

    /**
     * 生成数据签名
     *
     * @param $data 数据，数组
     * @param $secret 私钥
     * @return string 签名
     */
    protected function make_sign($data, $secret)
    {
        ksort($data);
        $sign = md5(http_build_query($data) . $secret);
        return $sign;
    }

    /**
     * 验证数据签名
     *
     * @param $data 数据，数组
     * @param $secret 私钥
     * @param $sign 签名
     * @return bool
     */
    protected function verify_sign($data, $secret, $sign)
    {
        ksort($data);
        $_sign = md5(http_build_query($data) . $secret);
        return ($_sign == $sign) ? true : false;
    }

    /**
     * 输出json 并halt
     *
     * @param boolean $state 状态
     * @param string $info 信息
     */
    protected function encode($state, $info='')
    {
        if($state) exit($this->json->encode(array('state' => true, 'message' => $info)));
        exit($this->json->encode(array('state' => false, 'error' => $info)));
    }

    public function execute()
    {
        $response = '';
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

    /**
     * 输出信息
     *
     * @param string $message 信息
     * @param bool $success 状态
     */
    public function showmessage($message='', $success=false)
    {
        $result = array();
        $result['state'] =$success;
        if($success)
        {
            $result['message'] = $message;
        }
        else
        {
            $result['error'] = $message;
        }
        exit(json_encode($result));
    }
}
