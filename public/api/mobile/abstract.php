<?php
class api_mobile_controller_abstract extends controller
{
	protected $app, $cache, $json, $template, $view, $config, $setting, $system, $_userid, $_username, $_groupid, $_roleid, $_departmentid;

    function __construct($app)
    {
        parent::__construct($app);
        $this->config = config::get('config');
        $this->setting = setting::get($app->app);
        $this->system = setting::get('system');
		$this->json = factory::json();
		$this->cache = factory::cache();

        // 验证通过，写入用户数据
        $userinfo = $this->get_userinfo();
        $this->_userid = $userinfo['userid'];
        $this->_username = $userinfo['username'];
        $this->_groupid = $userinfo['groupid'];
        $this->_roleid = $userinfo['roleid'];
        $this->_departmentid = $userinfo['departmentid'];

        // 兼容CT内容生成，输出点东西
        $array = array('_userid'=>$this->_userid, '_username'=>$this->_username, '_groupid'=>$this->_groupid, '_roleid'=>$this->_roleid, '_departmentid'=>$this->_departmentid);

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
     * 获取KEY对应的用户信息
     *
     * @return string
     */
    protected function get_userinfo()
    {
		if(isset($_POST['userauth']) || !$_POST['userauth']) return false;
        return online();
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
        exit($this->json->encode(array('state' => false, 'message' => $info)));
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
            $result['message'] = $message;
        }
        $result = json_encode($result);
        if(empty($_GET['jsoncallback']))
        {
            exit($result);
        }
        else
        {
            exit($_GET['jsoncallback']."($result);");
        }
    }
}