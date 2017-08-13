<?php

class model_member_front extends model implements SplSubject
{
	private $member_detail;
	public $userid, $m, $synclogout,$ucsynlogin;
    /** @var luosimao $mobile_verification */
    public $mobile_verification;
   

	private $observers = array();
	function __construct()
	{
		parent::__construct();
		// $this->config = loader::import('config.cache_online');
		// $this->cache = factory::cache();
		session_start();
		$this->_table = $this->db->options['prefix'].'member';
		$this->_primary = 'userid';
		$this->_fields = array('userid', 'username', 'password', 'email', 'groupid', 'avatar', 'regip', 'regtime', 'lastloginip', 'lastlogintime', 'logintimes', 'posts', 'comments', 'pageviews', 'credits','salt','amount', 'status','site_type','online_code', 'isfree', 'thirdtype', 'thirdid', 'thirdtoken', 'thirdlastlogin');
		$this->_readonly = array('userid', 'username', 'regip', 'regtime');
		$this->_create_autofill = array('regip'=>IP, 'regtime'=>TIME, 'groupid'=>setting('member', 'groupid'),'salt' => '');
		$this->_update_autofill = array();
		$this->_filters_input = array();
		$this->_filters_output = array();
		$this->_validators = array('username'=>array('not_empty'=>array('用户不能为空')),
									'email'=>array('not_empty'=>array('E-mail不能为空'),
													'email' =>array('E-mail格式不正确')
		)
		);

		$this->member_detail = loader::model('member_detail','member');
		define('UCENTER', setting('member', 'uc'));

        $this->mobile_verification = loader::lib('luosimao', 'member'); // 加载手机验证码类
	}

	function check_matchs($username,$email)
	{
		$where = array('username' => $username,'email' =>$email);
		$data = $this->select($where);
		return empty($data)?false:$data[0];
	}

	function check_username($username)
	{
		$this->username = $username;
		$this->event = 'before_check_username';
		$this->notify();
		if($this->error) {
			return false;
		}
        
		if(empty($username))
		{
			$this->error = '用户名不能为空';
			return false;
		}
		$ban_name = setting('member', 'ban_name');
		$rexp = '/^('.str_replace(array('\\*', '\?', "\r\n", ' '), array('.*', '.?', '|', ''), preg_quote(($ban_name = trim($ban_name)), '/')).')$/i';
		if($ban_name && preg_match($rexp, $username))
		{
			$this->error = '包含禁止的用户名';
			return false;
		}
 
		if($this->exists('username',$username))
		{
			$this->error = '用户名已经存在';
			return false;
		}
        
		return  true;
	}

	function check_username_cn($username)
	{
		$this->username = $username;
		$this->event = 'before_check_username';
		$this->notify();
		if($this->error) {
			return false;
		}
        
		if(empty($username))
		{
			$this->error = 'User name cannot be empty';
			return false;
		}
		$ban_name = setting('member', 'ban_name');
		$rexp = '/^('.str_replace(array('\\*', '\?', "\r\n", ' '), array('.*', '.?', '|', ''), preg_quote(($ban_name = trim($ban_name)), '/')).')$/i';
		if($ban_name && preg_match($rexp, $username))
		{
			$this->error = 'Contain forbidden user name';
			return false;
		}
 
		if($this->exists('username',$username))
		{
			$this->error = 'User name already exists';
			return false;
		}
        
		return  true;
	}

	function check_email($email)
	{
		$this->email = $email;
		$this->event = 'before_check_email';
		$this->notify();
		if($this->error) {
			return false;
		}
		//验证e-mail格式
		if(!preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email))
		{
			$this->error = 'E-mail格式不正确';
			return false;
		}
		if($this->exists('email',$email))
		{
			$this->error = 'E-mail已经存在';
			return false;
		}
		return true;
	}
	/*
	*英文注册邮箱验证
	*/
	function check_email_cn($email)
	{
		$this->email = $email;
		$this->event = 'before_check_email';
		$this->notify();
		if($this->error) {
			return false;
		}
		//验证e-mail格式
		if(!preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email))
		{
			$this->error = 'E-mail format is not correct';
			return false;
		}
		if($this->exists('email',$email))
		{
			$this->error = 'E-mail already exists';
			return false;
		}
		return true;
	}

	function edit($userid, $data)
	{
		return $this->update($data, array('userid' => $userid));
	}

	function password($userid, $password, $last_password, $isMd5 = false)
	{
		//原密码
		$r = $this->get($userid, 'username,password,email,salt');
		if (!$r)
		{
			$this->error = '用户不存在';
			return false;
		}

		if(!$this->check_password($r['password'], $last_password, $r['salt'], $isMd5))
		{
			$this->error = '原密码不正确';
			return false;
		}

		$this->userid = $r['userid'];
		$this->username = $r['username'];
		$this->password = $password;
        $this->salt = $r['salt'];
		$this->last_password = $last_password;
		$this->event = 'after_password';
		$this->notify();
		//UC
		if($this->error)
		{
			return false;
		}
		return $this->set_field('password', self::make_password($password, $r['salt']), "userid=$userid");
	}
	/*重置密码,丝路中国项目中文站新加方法*/
	function resetpassword($userid, $password, $isMd5 = false)
	{
		//原始信息
		$r = $this->get($userid, 'username,password,email,salt');
		

		$this->userid = $r['userid'];
		$this->username = $r['username'];
		$this->password = $password;
        $this->salt = $r['salt'];
		// $this->last_password = $last_password;
		$this->event = 'after_password';
		$this->notify();
		//UC
		
		return $this->set_field('password', self::make_password($password, $r['salt']), "userid=$userid");
	}

	function password_cn($userid, $password, $last_password, $isMd5 = false)
	{
		//原密码
		$r = $this->get($userid, 'username,password,email,salt');
		if (!$r)
		{
			$this->error = 'User does not exist';
			return false;
		}

		if(!$this->check_password($r['password'], $last_password, $r['salt'], $isMd5))
		{
			$this->error = $last_password."The original password is not correct".$r['password']."hehe".$r['salt'];
			return false;
		}

		$this->userid = $r['userid'];
		$this->username = $r['username'];
		$this->password = $password;
        $this->salt = $r['salt'];
		$this->last_password = $last_password;
		$this->event = 'after_password';
		$this->notify();
		//UC
		if($this->error)
		{
			return false;
		}
		return $this->set_field('password', self::make_password($password, $r['salt']), "userid=$userid");
	}

	function email($userid, $password, $email)
	{
		//原密码
		$r = $this->get($this->_userid, 'username,password,email,salt');
		if (!$r)
		{
			$this->error = '用户不存在';
			return false;
		}
		if(!$this->check_password($r['password'], $password, $r['salt']))
		{
			$this->error = '密码不正确';
			return false;
		}

		if($exists = $this->get_by('email',$email))
		{
			if($exists['userid'] != $this->_userid){
				$this->error = 'E-mail已经存在';
				return false;
			}
		}
		$this->error = null;
		$this->userid = $r['userid'];
		$this->username = $r['username'];
		$this->password = $password;
        $this->salt = $r['salt'];
		$this->email = $email;
		$this->event = 'after_email';
		$this->notify();
		if($this->error)
		{
			return false;
		}

		return $this->update(array('email' =>$email),$userid);
	}

	function email_cn($userid, $password, $email)
	{
		//原密码
		$r = $this->get($userid, 'username,password,email,salt');
		if (!$r)
		{
			$this->error = 'User does not exist';
			return false;
		}
		if(!$this->check_password($r['password'], $password, $r['salt']))
		{
			$this->error = 'The original password is not correct';
			return false;
		}

		if($exists = $this->get_by('email',$email))
		{
			if($exists['userid'] != $this->_userid){
				$this->error = 'E-mail already exists';
				return false;
			}
		}
		$this->error = null;
		$this->userid = $r['userid'];
		$this->username = $r['username'];
		$this->password = $password;
        $this->salt = $r['salt'];
		$this->email = $email;
		$this->event = 'after_email';
		$this->notify();
		if($this->error)
		{
			return false;
		}

		return $this->update(array('email' =>$email),$userid);
	}

	function update_detail($data, $where)
	{
		return $this->member_detail->update($data,$where);
	}

	function register($result)
	{
		$this->data = $result;
		$this->event = 'before_register';
		$this->notify();
		if($this->error)
        {
			return false;
		}
		if($this->userid)
		{
			$userid = $this->userid;
		}
		else
		{
			$data = $this->filter_array($result, array('username', 'password', 'email', 'mobile', 'mobileauth','site_type'));
            $data['salt'] = self::make_salt();
			$data['password'] = self::make_password($data['password'], $data['salt']);
			$userid = $this->insert($data);
			
			if ($userid)
			{
				$data['userid'] = $userid;
                $data['mobile'] = $data['mobile'];
                $data['mobileauth'] = $data['mobile'] ? 1 : 0;
                $this->member_detail->add($data);
         // 丝路网新加注册信息代码(修改detail表)
             $name = $result['name'];
             $job = $result['job'];
             $telephone = $result['telephone'];
             $address = $result['address'];
             $qq = $result['qq'];
             $where = "userid=$userid";
          	 $db = & factory::db();
          	 $sql = "update cmstop_member_detail set name='$name',job='$job',telephone='$telephone',qq='$qq',address='$address' where userid=$userid";
          	 $db->update($sql);

          	 
			}
		}
		$this->event = 'after_register';
		$this->notify();
		return $userid ? $userid : false;
	
	}
	/*丝路网英文站注册数据处理方法*/
	function register_cn($data)
	{
		$this->data = $data;
		$this->event = 'before_register';
		$this->notify();
		if($this->error)
        {
			return false;
		}
		if($this->userid)
		{
			$userid = $this->userid;
		}
		else
		{
			$data = $this->filter_array($data, array('username', 'password', 'email', 'mobile', 'mobileauth','site_type'));
            $data['salt'] = self::make_salt();
			$data['password'] = self::make_password($data['password'], $data['salt']);
			$userid = $this->insert($data);
			if ($userid)
			{
				$data['userid'] = $userid;
                $data['mobile'] = $data['mobile'];
                $data['mobileauth'] = $data['mobile'] ? 1 : 0;
                $this->member_detail->add($data);
			}
		}
		$this->event = 'after_register';
		$this->notify();
		return $userid ? $userid : false;
	
	}
	
	public function getProfile($userid, $detail = true)
	{
		$r = $this->get($userid);
        if (! $r) return false;

		$d = array();
		if($detail)
		{
			$d = $this->member_detail->get($userid);
		}
		$this->d = $d && is_array($d) ? array_merge($r, $d) : $r;
		$this->event = 'after_getProfile';
		$this->notify();
		return $this->d;
	}
	
	function login($username, $password, $cookietime = 0, $without_password = false)
	{
		$this->m = $this->get_by('username', $username, 'userid,username,password,groupid,salt,status,site_type');
		$this->error = null;
		$this->ucsynlogin = '';
		$this->username = $username;
		$this->password = $password;
		$this->event = 'before_login';
		$this->notify();
		if($this->error)
		{
			return false;
		}

		if (!$this->m)
		{
			$this->error = "账号或密码错误";
			return false;
		}
		if ($this->m['status'] < 1)
		{
			$this->error = "该用户已被禁用";
			return false;
		}

		$login_log = loader::model('member_login_log', 'member');
		if (!$login_log->valid($username))
		{
			$this->error = '登录失败次数过多，已被系统锁定';
			return false;
		}

		if(!$without_password && !$this->check_password($this->m['password'], $password, $this->m['salt']))
		{
			$this->error = '账号或密码错误';
			return false;
		}

        // 用户组是否可登陆判断
        if($this->m['groupid'] >= 2 && $this->m['groupid'] <= 5)
        {
            switch($this->m['groupid'])
            {
                case 2:
                    $this->error = '您的帐号组是游客，禁止登陆！';
                    break;
                case 3:
                    $this->error = '您的帐号待验证';
                    break;
                case 4:
                    $this->error = '您的帐号未审核';
                    break;
                case 5:
                    $this->error = '您的帐号已禁用';
                    break;
            }
            return false;
        }

        // 用户组中英文站登陆判断 (丝路中国项目新添加此段代码 start)
        if($this->m['site_type']!=1 && $this->m['groupid'] !=1){
   			$this->error = '您的帐号未在中文站注册,禁止登陆!';
   			return false;
       	}
        //(丝路中国项目新添加此段代码 stop)

		$this->update(array('lastloginip'=>IP, 'lastlogintime'=>TIME), "userid=".$this->m['userid']);
		$this->set_inc('logintimes',$this->m['userid']);
		$cookie = factory::cookie();
		$cookie->set('auth', str_encode($this->m['userid']."\t".$this->m['username']."\t".$this->m['groupid']."\t".md5($_SERVER['HTTP_USER_AGENT'])."\t".IP, config('config', 'authkey')), $cookietime);
		$cookie->set('userid', $this->m['userid'], $cookietime);
		$cookie->set('username', $this->m['username'], $cookietime);
		$cookie->set('password', $this->m['password'], $cookietime);
		$cookie->set('groupid', $this->m['groupid'], $cookietime);
		$cookie->set('site_type', $this->m['site_type'], $cookietime);
		$cookie->set('photo', element::member_photo($this->m['userid'], 22, 22));
		$cookie->set('rememberusername', $this->m['username'], TIME + 2592000);
		unset($_SESSION['needseccode']);
		$login_log->add(array('username'=>$username, 'succeed'=>1));

		//查询登录本次登录的时间戳
		// $db = & factory::db();
		
		// $sql = "select time as t from cmstop_member_login_log where username='$username' and succeed=1 order by time desc limit 1";
		// $this->m['lasttime'] = $db->get($sql);
		// //查询缓存查看是否已经登录
		// $org_token = $this->cache->get("silkroad_online_token");
		// $new_token = md5($username.$password.TIME.IP);
		// if($org_token == $new_token){

		// }
		//查询缓存,查看是否已经登录,如果没有登录,则把token写入缓存,如果token已经存在,则更新token
		// $ip = $_SERVER["REMOTE_ADDR"];
		// $_SESSION[$username.$password."silkroad_online_token"] = md5($username.$password.$ip);
		// $this->cache->set($username.$password."silkroad_online_token", md5($username.$password), $this->config['cachetime'] * 60);

		$this->event = 'after_login';
		$this->notify();
		
		return $this->m;
	}

	function login_cn($username, $password, $cookietime = 0, $without_password = false)
	{
		$this->m = $this->get_by('username', $username, 'userid,username,password,groupid,salt,status,site_type');
		$this->error = null;
		$this->ucsynlogin = '';
		$this->username = $username;
		$this->password = $password;
		$this->event = 'before_login';
		$this->notify();
		if($this->error)
		{
			return false;
		}

		if (!$this->m)
		{
			$this->error = "Account or password error";
			return false;
		}
		if ($this->m['status'] < 1)
		{
			$this->error = "The user has been disabled";
			return false;
		}

		$login_log = loader::model('member_login_log', 'member');
		if (!$login_log->valid($username))
		{
			$this->error = 'The number of login failure is too much, has been locked by the system';
			return false;
		}

		if(!$without_password && !$this->check_password($this->m['password'], $password, $this->m['salt']))
		{
			$this->error = 'Account or password error';
			return false;
		}

        // 用户组是否可登陆判断
        if($this->m['groupid'] >= 2 && $this->m['groupid'] <= 5)
        {
            switch($this->m['groupid'])
            {
                case 2:
                    $this->error = 'Your account group is a visitor！';
                    break;
                case 3:
                    $this->error = 'Your account to be verified';
                    break;
                case 4:
                    $this->error = 'Your account is not audited';
                    break;
                case 5:
                    $this->error = 'Your account has been disabled';
                    break;
            }
            return false;
        }
        // 用户组中英文站登陆判断 (丝路中国项目新添加此段代码 start)
       	if($this->m['site_type']!=2 && $this->m['groupid'] !=1){
   			$this->error = 'Your account is not registered in English station, banned logging！';
   			return false;
       	}
        //(丝路中国项目新添加此段代码 stop)

		$this->update(array('lastloginip'=>IP, 'lastlogintime'=>TIME), "userid=".$this->m['userid']);
		$this->set_inc('logintimes',$this->m['userid']);
		$cookie = factory::cookie();
		$cookie->set('auth', str_encode($this->m['userid']."\t".$this->m['username']."\t".$this->m['groupid']."\t".md5($_SERVER['HTTP_USER_AGENT'])."\t".IP, config('config', 'authkey')), $cookietime);
		$cookie->set('userid', $this->m['userid'], $cookietime);
		$cookie->set('username', $this->m['username'], $cookietime);
		$cookie->set('groupid', $this->m['groupid'], $cookietime);
		$cookie->set('site_type', $this->m['site_type'], $cookietime);
		$cookie->set('photo', element::member_photo($this->m['userid'], 22, 22));
		$cookie->set('rememberusername', $this->m['username'], TIME + 2592000);
		unset($_SESSION['needseccode']);
		$login_log->add(array('username'=>$username, 'succeed'=>1));

		$this->event = 'after_login';
		$this->notify();
		
		return $this->m;
	}

    function login_mobile($username, $password)
    {
        $this->m = $this->get_by('username', $username, 'userid,username,email,password,groupid,salt,status');
        $this->error = null;
        $this->ucsynlogin = '';
        $this->username = $username;
        $this->password = $password;
        $this->event = 'before_login';
        $this->notify();
        if($this->error)
        {
            return false;
        }

        if (!$this->m)
        {
            $this->error = "账号或密码错误";
            return false;
        }

        if ($this->m['status'] < 1)
		{
			$this->error = "该用户已被禁用";
			return false;
		}

        $login_log = loader::model('member_login_log', 'member');
        if (!$login_log->valid($username))
        {
            $this->error = '登录失败次数过多，已被系统锁定';
            return false;
        }

        if(!$this->check_password($this->m['password'], $password, $this->m['salt'], true))
        {
            $this->error = '账号或密码错误';
            return false;
        }

        // 用户组是否可登陆判断
        if($this->m['groupid'] >= 2 && $this->m['groupid'] <= 5)
        {
            switch($this->m['groupid'])
            {
                case 2:
                    $this->error = '您的帐号组是游客，禁止登陆！';
                    break;
                case 3:
                    $this->error = '您的帐号待验证';
                    break;
                case 4:
                    $this->error = '您的帐号未审核';
                    break;
                case 5:
                    $this->error = '您的帐号已禁用';
                    break;
            }
            return false;
        }

        $this->update(array('lastloginip'=>IP, 'lastlogintime'=>TIME), "userid=".$this->m['userid']);
        $this->set_inc('logintimes',$this->m['userid']);
        $login_log->add(array('username'=>$username, 'succeed'=>1));
        $this->event = 'after_login';
        $this->notify();
        $this->m['auth'] = str_encode($this->m['userid']."\t".$this->m['username']."\t".$this->m['groupid'], config('config', 'authkey'));
        $isfull = $this->member_detail->isfull($this->m['userid']);
        $isfull['avatar'] = $this->get_photo($this->_userid);
        foreach ($isfull as $key => $value) {
            if(empty($isfull[$key]) || $isfull[$key]=='0000-00-00'){
                $this->m['isfull'] = false;
                $this->m = array_merge($this->m,$isfull);
                return $this->m;
            }
        }
        $this->m['isfull'] = true;
        $this->m = array_merge($this->m,$isfull);
        return $this->m;
    }

    public function login_mobile_by_uid($uid)
    {
        $this->m = $this->get($uid, 'userid,username,email,password,groupid,salt,status');
        $this->error = null;
        $this->ucsynlogin = '';
        $this->username = $this->m['username'];

        if (!$this->m)
        {
            $this->error = "{$this->m['username']} 不存在";
            return false;
        }
        if ($this->m['status'] < 1)
		{
			$this->error = "该用户已被禁用";
			return false;
		}

        $login_log = loader::model('member_login_log', 'member');
        if (!$login_log->valid($this->m['username']))
        {
            $this->error = '登录失败次数过多，已被系统锁定';
            return false;
        }

        // 用户组是否可登陆判断
        if($this->m['groupid'] >= 2 && $this->m['groupid'] <= 5)
        {
            switch($this->m['groupid'])
            {
                case 2:
                    $this->error = '您的帐号组是游客，禁止登陆！';
                    break;
                case 3:
                    $this->error = '您的帐号待验证';
                    break;
                case 4:
                    $this->error = '您的帐号未审核';
                    break;
                case 5:
                    $this->error = '您的帐号已禁用';
                    break;
            }
            return false;
        }

        $this->update(array('lastloginip'=>IP, 'lastlogintime'=>TIME), "userid=".$this->m['userid']);
        $this->set_inc('logintimes',$this->m['userid']);
        $login_log->add(array('username'=>$this->m['username'], 'succeed'=>1));

        $isfull = $this->member_detail->isfull($this->m['userid']);

        $this->event = 'after_login';
        $this->notify();
        $this->m['auth'] = str_encode($this->m['userid']."\t".$this->m['username']."\t".$this->m['groupid'], config('config', 'authkey'));
        $this->m['mobile'] = $isfull['mobile'];
        return array_merge($this->m, $isfull);
    }

	public function otherlogin($arg)
	{
		$this->event = $arg;
		$this->notify();
	}

	public function login_by_uid($uid, $cookietime = 0)
	{
		$this->m = $this->get($uid, 'userid,username,password,groupid,salt,status');
		$this->error = null;
		$this->ucsynlogin = '';
		$this->username = $this->m['username'];

		if (!$this->m)
		{
			$this->error = "{$this->m['username']} 不存在";
			return false;
		}

		if ($this->m['status'] < 1)
		{
			$this->error = "该用户已被禁用";
			return false;
		}

		$login_log = loader::model('member_login_log', 'member');
		if (!$login_log->valid($this->m['username']))
		{
			$this->error = '登录失败次数过多，已被系统锁定';
			return false;
		}

        // 用户组是否可登陆判断
        if($this->m['groupid'] >= 2 && $this->m['groupid'] <= 5)
        {
            switch($this->m['groupid'])
            {
                case 2:
                    $this->error = '您的帐号组是游客，禁止登陆！';
                    break;
                case 3:
                    $this->error = '您的帐号待验证';
                    break;
                case 4:
                    $this->error = '您的帐号未审核';
                    break;
                case 5:
                    $this->error = '您的帐号已禁用';
                    break;
            }
            return false;
        }

		$this->update(array('lastloginip'=>IP, 'lastlogintime'=>TIME), "userid=".$this->m['userid']);
		$this->set_inc('logintimes',$this->m['userid']);
		$cookie = factory::cookie();
		$cookie->set('auth', str_encode($this->m['userid']."\t".$this->m['username']."\t".$this->m['groupid']."\t".md5($_SERVER['HTTP_USER_AGENT'])."\t".IP, config('config', 'authkey')), $cookietime);
		$cookie->set('userid', $this->m['userid'], $cookietime);
		$cookie->set('username', $this->m['username'], $cookietime);
		$cookie->set('rememberusername', $this->m['username'], TIME + 2592000);
		unset($_SESSION['needseccode']);
		$login_log->add(array('username'=>$this->m['username'], 'succeed'=>1));

		$this->event = 'after_login';
		$this->notify();
		
		return $this->m;
	}
	
	function logout()
	{
		$cookie = factory::cookie();
		$username = $cookie->get('username');
		$password = $cookie->get('password');
		//清除session
		// unset($_SESSION[$username.$password."silkroad_online_token"]);

		$cookie->set('auth');
		$cookie->set('userid');
		$cookie->set('username');
		$cookie->set('photo');
		$cookie->set('password');
		// $cookie->set('rememberusername');
		//清除缓存
		// $this->cache->rm($username.$password."silkroad_online_token");

		$this->synclogout = '';
		$this->event = 'after_logout';
		$this->notify();
		return $this->synclogout;
	}
	function logout_cn()
	{
		$cookie = factory::cookie();
		$cookie->set('auth');
		$cookie->set('userid');
		$cookie->set('username');
		$cookie->set('photo');
		// $cookie->set('rememberusername');
		
		$this->synclogout = '';
		$this->event = 'after_logout';
		$this->notify();
		return $this->synclogout;
	}
	
	function set_photo_path($userid)
	{
		$dir = array();
		$userid = sprintf("%09d", $userid);
		$dir[] = substr($userid, 0, 3);
		$dir[] = substr($userid, 3, 2);
		$dir[] = substr($userid, 5, 2);
		$dir = implode('/',$dir);
		$name = substr($userid, -2);
		$return = array($dir, $name);
		return $return;
	}

	function get_photo($userid, $width = 80, $height = 80, $size = 'small')
	{
		$this->userid = $userid;
		$this->size = $size;
		$this->event = 'before_get_photo';
		$this->notify();
		/*if($this->photo)
		{
			return $this->photo;//uc中处理
		}*/

		$dir = array();
		list($path,$filename) = $this->set_photo_path($userid);

		$avatar = 'avatar/'.$path .'/'.$filename.'.jpg';
		
		return is_file(UPLOAD_PATH.$avatar)
			? thumb(UPLOAD_PATH.$avatar, $width, $height, 1, IMG_URL.'images/nohead.jpg')
			: (IMG_URL.'images/nohead.jpg');
	}

	function make_authstr($userid)
	{
		$authstr = md5(random(32));
		$this->member_detail->update(array('authstr'=>$authstr), $userid);
		return $authstr;
	}

	static function online()
	{
		$cookie = factory::cookie();
		$str = $cookie->get('auth');
		return $str ? explode("\t", str_decode($str, config('config', 'authkey'))) : false;
	}

	static function make_password($password, $salt)
	{
		if(strlen($password) == 32) 
		{
			return md5($password.$salt);
		}
		else
		{
			return md5(md5($password).$salt);
		}
	}

    static function make_salt()
    {
        return substr(uniqid(rand()), -6);
    }

	public function check_password($password, $inputpwd, $salt, $isMd5=false)
	{
		if(strlen($inputpwd) == 32) $isMd5 = true; //houbin修改：如果是从数据库取到MD5密码，把isdmd5设成true
		$md5pass = $isMd5 ? md5($inputpwd.$salt) : md5(md5($inputpwd).$salt);
		if($password == $md5pass) return true;
		return false;
	}

	function _after_select(& $data, $multiple)
	{
		if (empty($data)) {
			return $data;
		}
		$group = loader::model('member_group', 'member');
		$groups = $group->groups();
		if ($multiple)
		{
			foreach ($data as $key => $value)
			{
				$data[$key]['group'] = $groups[$value['groupid']];
				$data[$key]['regtime'] = date('Y-m-d H:i:s', $value['regtime']);
				$data[$key]['lastlogintime'] = empty($value['lastlogintime'])?'':date('Y-m-d H:i:s', $value['lastlogintime']);
				unset($data[$key]['password']);
			}
		}
		else
		{
			$data['group'] = $groups[$data['groupid']];
			$data['regtime'] = date('Y-m-d H:i:s', $data['regtime']);
			$data['lastlogintime'] = empty($value['lastlogintime'])?'':date('Y-m-d H:i:s', $data['lastlogintime']);
		}
	}

    function send_message(array $params)
    {
        $data = factory::setting()->get('cloud', 'mobile_verification_info');
        $data['message'] = str_replace('{{code}}', $params['code'] , $data['message']);
        unset($params['code']);
        $data = array_merge($params, $data);

        return $this->mobile_verification->sendMessage($data);
    }
	
	public function attach(SplObserver $observer)
	{
		$this->observers[] = $observer;
	}

	public function detach(SplObserver $observer)
	{
		if($index = array_search($observer, $this->observers, true)) unset($this->observers[$index]);
	}

	public function notify()
	{
		foreach ($this->observers as $observer)
		{
			$observer->update($this);
		}
	}
}
