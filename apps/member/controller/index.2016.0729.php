<?php
class controller_index extends member_controller_abstract
{
	private $member, $verificationPathswitch;

	function __construct($app)
	{
		parent::__construct($app);
		$session = factory::session();
		$session->start();
		$this->member = loader::model('member_front');
        $this->member_detail = loader::model('member_detail');
        $this->verificationPathswitch = (int)setting::get('cloud', 'mobile_verification');
	}

	function index()
	{
		$this->login();
	}

	function login()
	{
		$referer = (!empty($_REQUEST['url']))?$_REQUEST['url']:request::get_referer(); //没有urlencode
		if(strpos($referer, 'action=logout') > 0)
		{
			$referer = '';
		}

		if ($this->is_post())
		{
			if(!$this->_check_login())
			{
				$this->showmessage($this->error(),url('member/index/login'),3000, false);
			}
			$cookietime = empty($_POST['cookietime']) ? 86400 : intval($_POST['cookietime']);

			//丝路中国项目中文站记住用户名代码 start
			$cookie = factory::cookie();
			$cookie->set('checkbox', $_POST['checkbox'] , $cookietime);
			//丝路中国项目中文站记住用户名代码 stop

			$_POST['username'] = htmlspecialchars($_POST['username']);
			$regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
			$_POST['username'] = preg_replace($regex,"",$_POST['username']);
			$member = $this->member->login($_POST['username'], $_POST['password'], $cookietime);
			if (!$member)
			{
				$_SESSION['needseccode'] = 1;
				$redirect=$_POST['x_url']?APP_URL.(url('member/index/login').'&referer='.urlencode($_POST['x_url'])):url('space/panel/index');
				$this->showmessage($this->member->error(),$redirect,3000, false);
			}
			else
			{
				
				$username = $member['username'];
				$online_code = self::make_str();
				//插入数据库
				$db = & factory::db();
				$userid = $member['userid'];
				$sql = "update cmstop_member set online_code='$online_code' where userid=$userid";
				$db->update($sql);
				// 存入cookie
				$cookie = factory::cookie();
				$cookie->set($username, $online_code, $cookietime);
			}
			$redirect=url('member/index/successlogin');
			$this->redirect($redirect);
		}
		else
		{
			$cookie = factory::cookie();
			$rememberusername = $cookie->get('rememberusername');
			$checked = $cookie->get('checkbox');
			
			if($_SESSION['needseccode'])
			{
				$this->template->assign('needseccode', true);
			}
			else
			{
				$this->template->assign('needseccode', false);
			}
			
			// 此属性在otherlogin插件中被赋予
			$olurl = $this->member->url;
			$this->template->assign('sinaurl', $olurl['sina']); 
			$this->template->assign('oauth_token', $_SESSION['sina']['keys']['oauth_token']);
			$this->template->assign('checked',$checked);
			$this->template->assign('refer', $referer);
			$this->template->assign('rememberusername', $rememberusername);
			$this->template->display('index.html');
		}
	}

	static function make_str()
  {
    return substr(md5(rand()), -6);
  }
	/**
	*中文站登陆成功后跳转的方法
	*/
	public function successlogin(){
		$this->template->display('system/nav.html');
	}



	/**
     * 英文站会员登陆方法
	**/
	function login_cn()
	{
		$referer = (!empty($_REQUEST['url']))?$_REQUEST['url']:request::get_referer(); //没有urlencode
		if(strpos($referer, 'action=logout_cn') > 0)
		{
			$referer = '';
			
		}
		
		if ($this->is_post())
		{	
		
			if(!$this->_check_login_cn())
			{
				$this->showmessage_cn($this->error(),url('member/index/login_cn'),3000, false);
			}
			$cookietime = empty($_POST['cookietime']) ? 86400 : intval($_POST['cookietime']);

			//丝路中国英文站项目记住用户名代码 start
			$cookie = factory::cookie();
			$cookie->set('checkboxcn', $_POST['checkboxcn'] , $cookietime);
			//丝路中国英文站项目记住用户名代码 stop

			$_POST['username'] = htmlspecialchars($_POST['username']);
			$regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
			$_POST['username'] = preg_replace($regex,"",$_POST['username']);
			$member = $this->member->login_cn($_POST['username'], $_POST['password'], $cookietime);
			if (!$member)
			{
				$_SESSION['needseccode'] = 1;
				$redirect=$_POST['x_url']?APP_URL.(url('member/index/login_cn').'&referer='.urlencode($_POST['x_url'])):url('space/panel/index');
				$this->showmessage_cn($this->member->error(),url('member/index/login_cn'),3000, false);
			}
			else
			{
				
				$username = $member['username'];
				$online_code = self::make_str();
				//插入数据库
				$db = & factory::db();
				$userid = $member['userid'];
				$sql = "update cmstop_member set online_code='$online_code' where userid=$userid";
				$db->update($sql);
				// 存入cookie
				$cookie = factory::cookie();
				$cookie->set($username, $online_code, $cookietime);
			}
			$redirect=url('member/index/successlogin_cn');
			$this->redirect($redirect);
		}
		else
		{
			$cookie = factory::cookie();
			$rememberusername = $cookie->get('rememberusername');
			$checkedcn = $cookie->get('checkboxcn');
			
			if($_SESSION['needseccode'])
			{
				$this->template->assign('needseccode', true);
			}
			else
			{
				$this->template->assign('needseccode', false);
			}
			
			// 此属性在otherlogin插件中被赋予
			$olurl = $this->member->url;
			$this->template->assign('sinaurl', $olurl['sina']); 
			$this->template->assign('oauth_token', $_SESSION['sina']['keys']['oauth_token']);
			$this->template->assign('checkedcn',$checkedcn);
			$this->template->assign('refer', $referer);
			$this->template->assign('rememberusername', $rememberusername);
			$this->template->display('cn/index.html');
		}
	}

	/**
	*英文站登陆成功后跳转的方法
	*/
	public function successlogin_cn(){
		
		// $this->template->assign('data', $data);
		$this->template->display('cn/map.html');
	}


	public function otherlogin($arg)
	{	
		// 侯哥留下来的东西,当arg为bindPage时转到plugin/model_member_front/otherlogin中执行bindPage()
		$arg = isset($_GET['arg']) ? $_GET['arg'] : $arg;
		if($arg == 'member')
		{
			$this->member->otherlogin('def_go');
		}
		else
		{
			$this->member->otherlogin($arg);
		}
		
	}

	function logout()
	{
		$refer = URL('member/index/login');
//		$refer = request::get_referer();
		$ucsynlogout = $this->member->logout();

		$url = isset($_GET['url']) ? $_GET['url'] : $refer;
		$this->redirect($url);
	}

	function logout_cn()
	{
		$refer = URL('member/index/login_cn');
//		$refer = request::get_referer();
		$ucsynlogout = $this->member->logout_cn();

		$url = isset($_GET['url']) ? $_GET['url'] : $refer;
		$this->redirect($url);
	}


    /*
     + 中文站注册会员方法
    */
	function register()
	{
        // 是否是注册页面的请求
        $_SESSION['mobile_session_reg'] = true;

		if ($this->_userid)
		{
			$this->redirect(url('member/panel/index'));
		}
		if(!$this->setting['allowreg'])
		{
            $close_reason = trim($this->setting['closereason']);
			$this->showmessage($close_reason ? $close_reason : '已关闭注册!');
		}
		if($this->is_post())
		{
			
			$_SESSION['login_info'] = $_POST;
			if(!$this->_check_register())
			{
				$this->showmessage($this->error, request::get_referer(), 1000, false);
			}
			else
			{

				if($this->member->register($_POST) === false)
				{
					if($_POST['x_url'])
					{
						$redirect=APP_URL.(url('member/index/login').'&referer='.urlencode($_POST['x_url']));
						$this->showmessage($this->member->error(), $redirect, 1000, false);
					}
					else
					{
						$this->showmessage($this->member->error(), request::get_referer(), 1000, false);
					}
					$this->showmessage($this->member->error(), request::get_referer(), 1000, false);
				}
				else
				{
					unset($_SESSION['login_info']);
					/**
					 *   判断是否有xweibo的跳转参数referer进行跳转
					 *   $_POST['x_url'],通过模版隐藏的<input />$_GET获取
					 *   如果是通过xweibo跳转注册，注册完后返回xweibo，并实现与xweibo同步登陆状态
					 *   如果是cmstop注册，注册完成跳转至用户面板，并实现与xweibo同步登陆状态
					 */
					if($_POST['x_url']) {
						$this->member->login($_POST['username'], $_POST['password'], 3600);
						$this->showmessage('注册成功,请返回登录',$_POST['x_url'],3000,true);
					} else {
						$this->showmessage('注册成功,请返回登录',url('space/panel/index'),3000,true);
					}
				}
			}
		}
		else
		{
			// 此属性在otherlogin插件中被赋予
			$olurl = $this->member->url;
			$this->template->assign('sinaurl', $olurl['sina']); 
			$this->template->assign('oauth_token', $_SESSION['sina']['keys']['oauth_token']);
            // 是否需要手机验证码认证
            $this->template->assign('mverification', $this->verificationPathswitch);

			$this->template->assign('login_info',$_SESSION['login_info']);
			$this->template->display('member/register.html');
		}
	}

/*
  + 英文站注册方法
*/
	function register_cn()
	{
        // 是否是注册页面的请求
        $_SESSION['mobile_session_reg'] = true;

		if ($this->_userid)
		{
			$this->redirect(url('member/panel/index'));
		}
		if(!$this->setting['allowreg'])
		{
            $close_reason = trim($this->setting['closereason']);
			$this->showmessage_cn($close_reason ? $close_reason : 'Registration has been closed!');
		}
		if($this->is_post())
		{
			// var_dump($_POST);die;
			$_SESSION['login_info'] = $_POST;
			if(!$this->_check_register_cn())
			{
				$this->showmessage_cn($this->error, request::get_referer(), 1000, false);
			}
			else
			{

				if($this->member->register_cn($_POST) === false)
				{
					if($_POST['x_url'])
					{
						$redirect=APP_URL.(url('member/index/login_cn').'&referer='.urlencode($_POST['x_url']));
						$this->showmessage_cn($this->member->error(), $redirect, 1000, false);
					}
					else
					{
						$this->showmessage_cn($this->member->error(), request::get_referer(), 1000, false);
					}
					$this->showmessage_cn($this->member->error(), request::get_referer(), 1000, false);
				}
				else
				{
					unset($_SESSION['login_info']);
					/**
					 *   判断是否有xweibo的跳转参数referer进行跳转
					 *   $_POST['x_url'],通过模版隐藏的<input />$_GET获取
					 *   如果是通过xweibo跳转注册，注册完后返回xweibo，并实现与xweibo同步登陆状态
					 *   如果是cmstop注册，注册完成跳转至用户面板，并实现与xweibo同步登陆状态
					 */
				
					if($_POST['x_url']) {
						$this->member->login_cn($_POST['username'], $_POST['password'], 3600);
						$this->showmessage_cn('Registration is successful, please return to login',$_POST['x_url'],3000,true);
					} else {
						$this->showmessage_cn('Registration is successful, please return to login',url('member/index/login_cn'),3000,true);
					}
				}
			}
		}
		else
		{
			// 此属性在otherlogin插件中被赋予
			$olurl = $this->member->url;
			$this->template->assign('sinaurl', $olurl['sina']); 
			$this->template->assign('oauth_token', $_SESSION['sina']['keys']['oauth_token']);
            // 是否需要手机验证码认证
            $this->template->assign('mverification', $this->verificationPathswitch);

			$this->template->assign('login_info',$_SESSION['login_info']);
			$this->template->display('member/register.html');
		}
	}
	function ajax_username_check()
	{
		//ajax验证用户名是否可用
		//var_dump($_POST);die;
		// $contentid = intval($_GET['contentid']);
 
    //         $member = loader::model('member');
    //         $data['username'] = $member->validate($_POST);
 			// $data = $this->json->encode($data);
    //         echo $_GET['jsoncallback']."($data);";

	}

	function registerwithtoken()
	{
		$cookie = factory::cookie();
		if (!$thirdtoken = $cookie->get('thirdtoken'))
		{
			$this->showMessage('参数错误');
		}
		else
		{
			$this->template->assign('data', $this->json->decode($thirdtoken));
			$cookie->set('thirdtoken', '', -1);
		}
		if ($_GET['ref'])
		{
			$this->template->assign('ref', $_GET['ref']);
		}
		else
		{
			$this->template->assign('ref', WWW_URL);
		}
		$this->template->display('member/registerwithtoken.html');
	}

	function bind_account()
	{
		if (empty($_POST['interface']) || empty($_POST['authkey']))
		{
			$this->showMessage('参数错误');
		}
		$user_data = $this->member->get(array('username'=>$_POST['username']));
		if (empty($user_data) || !$this->member->check_password($user_data['password'], $_POST['password'], $user_data['salt'])) {
			$this->showMessage('用户不存在');
		}
		$userid = $user_data['userid'];
		$member_api = loader::model('member_api', 'cloud');
		if ($_POST['expires'] < TIME) {
			$_POST['expires'] = $_POST['expires'] + TIME;
		}
		if ($member_api->add($_POST['interface'], (int)$userid, $_POST['authkey'], $_POST['access_token'], $_POST['expires']))
		{
			$this->member->login($_POST['username'], $_POST['password'], 0);
			exit($this->json->encode(array('state'=>true)));
		}
		exit($this->json->encode(array('state'=>false, 'error'=>$member_api->error())));
	}

	function ajaxregister()
	{
		if(!$this->_check_register())
		{
			exit($this->json->encode(array('state'=>false, 'error'=>$this->error)));
		}
		$userid = $this->member->register($_POST);
		if($userid === false)
		{
			exit($this->json->encode(array('state'=>false, 'error'=>$this->error)));
		}
		if (!empty($_POST['interface']) && !empty($_POST['authkey']))
		{
			// 写入第三方信息
			$member_api = loader::model('member_api', 'cloud');
			$member_api->add($_POST['interface'], (int)$userid, $_POST['authkey'], $_POST['access_token'], $_POST['expires']);
		}
		$this->member->login($_POST['username'], $_POST['password'], 0);
		exit($this->json->encode(array('state'=>true)));
	}

	/*
     * 发送邮件
     + 引入smtp类，用来发送邮件
    */
    private function send_email($to,$subject,$message)
    {
    	import('helper.smtp');
        $setting=setting("system","mail");
       //smtp邮件服务器，如果后台未设置，默认使用 smtp.163.com
        $smtpserver=value($setting,'smtp_host');
        //smtp端口号，默认25
        $smtpport=value($setting,"smtp_port");
        //SMTP邮箱账号
        $smtpusermail=value($setting,"smtp_username");
        //SMTP邮箱密码
        $smtppassword=value($setting,"smtp_password");
        //发件人
        $smtpusername=substr($smtpusermail,0,strpos($smtpusermail,'@'));
        //邮件主题
        // $title="丝路网注册邀请码";
        //邮件内容
        // $content="丝路数据库欢迎你前来注册，请将此邀请码".$code."填入到注册页相应位置。";
        
        $mailtype="HTML";
        //默认进行身份验证
        $smtp_auth=true;
    
        $smtp=new smtp($smtpserver,$smtpport,true,$smtpusername,$smtppassword);
        //是否进行debug调试
        $smtp->debug=true;

        //发送邮件
        $result=$smtp->sendmail($to,$smtpusermail,$subject,$message,$mailtype);

        if($result){
            return true;
        }else{
            return false;
        }

    }

	function getpassword()
	{
		if($this->_userid)
		{
			$this->redirect(url('member/panel/index'));
		}

		if($this->is_post())
		{
			if(!$this->_check_getpassword())
			{
				echo $this->json->encode(array('state' => false, 'error' => $this->error));exit;
			}
			$check = $this->member->check_matchs($_POST['username'],$_POST['email']);
			$authstr = $this->member->make_authstr($check['userid']);
			$timestamp = TIME;
			$authkey = config('config', 'authkey');
			$key = md5($timestamp.$authstr.$authkey);
			$check['url'] = APP_URL;
			$check['url'] .= url('member/index/resetpassword',"userid={$check['userid']}&authstr={$authstr}&timestamp={$timestamp}&key={$key}");

			$set = setting('system');
			$mailset = $set['mail'];
			$to = $check['email'];
			$subject = '重置您在'.$set['sitename'].'的密码信息';
			$set['date'] = date("Y-m-d H:i:s",TIME);

			$this->template->assign('check',$check);
			$this->template->assign('set',$set);
			$message = $this->template->fetch('member/sendmail.html');
			// $from = $mailset['from'];

			if(!$this->send_email($to, $subject, $message))
			{
			
				$return = array('state'=>false, 'error'=>'发送邮件失败 请重试');
				echo $this->json->encode($return);
			}
			else
			{
				
				$cookie = factory::cookie();
				$cookie->set('getpassword', 1, $this->setting['locktime'] * 86400);
				$return = array('state'=>true, 'message'=>'发送邮件成功','redirect' =>url('member/index/login'));
				echo $this->json->encode($return);
			}
		}
		else
		{
			$this->template->display('member/getpassword.html');
		}
	}

	function resetpassword()
	{
		$timestamp = $_GET['timestamp'];
		$key = $_GET['key'];
		$authstr = $_GET['authstr'];
		$userid = intval($_GET['userid']);

		$authkey = config('config', 'authkey');
		if ($key != md5($timestamp.$authstr.$authkey))
		{
			$this->showmessage('无效链接',WWW_URL,3000, false);
		}
		elseif (TIME-$timestamp>3600*24*2) //2天。
		{
			$this->showmessage('链接已经过期',WWW_URL,3000, false);
		}

		$r = $this->member->getProfile($userid);
		// console($r);
		if (!$r['authstr'] || $r['authstr'] != $authstr)
		{
			$this->showmessage('无效链接',WWW_URL,3000, false);
		}
		else
		{
			if ($this->is_post())
			{
				if($_POST['password'] != $_POST['password_check'])
				{
					$result = array('state'=>false, 'error'=>'两次输入的密码不一致');
				}
				else
				{
					if ($this->member->resetpassword($userid, $_POST['password'],$r['password'],true))
					{
						$this->member->update_detail(array('authstr'=>''),array('userid' =>$userid));
						$result = array('state'=>true, 'message'=>'密码修改成功');
					}
					else
					{
						$result = array('state'=>false, 'error'=>$this->member->error());
					}
				}
				echo $this->json->encode($result);
			}
			else
			{
				$this->template->assign('url',url('member/index/resetpassword','userid='.$userid.'&authstr='.$authstr.'&timestamp='.$timestamp.'&key='.$key));
				$this->template->display('member/resetpassword.html');
			}
		}
	}

	function getpassword_cn()
	{
		if($this->_userid)
		{
			$this->redirect(url('member/panelcn/index'));
		}

		if($this->is_post())
		{
			if(!$this->_check_getpassword_cn())
			{
				echo $this->json->encode(array('state' => false, 'error' => $this->error));exit;
			}
			$check = $this->member->check_matchs($_POST['username'],$_POST['email']);
			$authstr = $this->member->make_authstr($check['userid']);
			$timestamp = TIME;
			$authkey = config('config', 'authkey');
			$key = md5($timestamp.$authstr.$authkey);
			$check['url'] = APP_URL;
			$check['url'] .= url('member/index/resetpassword_cn',"userid={$check['userid']}&authstr={$authstr}&timestamp={$timestamp}&key={$key}");

			$set = setting('system');
			$mailset = $set['mail'];
			$to = $check['email'];
			$subject = 'Reset your password in'.$set['sitename'];
			$set['date'] = date("Y-m-d H:i:s",TIME);

			$this->template->assign('check',$check);
			$this->template->assign('set',$set);
			$message = $this->template->fetch('cn/member/sendmail.html');
			// $from = $mailset['from'];

			if(!$this->send_email($to, $subject, $message))
			{
			
				$return = array('state'=>false, 'error'=>'Send mail failed please try again');
				echo $this->json->encode($return);
			}
			else
			{
			
				$cookie = factory::cookie();
				$cookie->set('getpassword', 1, $this->setting['locktime'] * 86400);
				$return = array('state'=>true, 'message'=>'Send mail success','redirect' =>url('member/index/login_cn'));
				echo $this->json->encode($return);
			}
		}
		else
		{
			$this->template->display('cn/member/getpassword.html');
		}
	}

	function resetpassword_cn()
	{
		$timestamp = $_GET['timestamp'];
		$key = $_GET['key'];
		$authstr = $_GET['authstr'];
		$userid = intval($_GET['userid']);

		$authkey = config('config', 'authkey');
		if ($key != md5($timestamp.$authstr.$authkey))
		{
			$this->showmessage('Invalid Link',WWW_URL,3000, false);
		}
		elseif (TIME-$timestamp>3600*24*2) //2天。
		{
			$this->showmessage('Link has expired',WWW_URL,3000, false);
		}

		$r = $this->member->getProfile($userid);
		// console($r);
		if (!$r['authstr'] || $r['authstr'] != $authstr)
		{
			$this->showmessage('Invalid Link',WWW_URL,3000, false);
		}
		else
		{
			if ($this->is_post())
			{
				if($_POST['password'] != $_POST['password_check'])
				{
					$result = array('state'=>false, 'error'=>'Two times the password is not consistent');
				}
				else
				{
					if ($this->member->resetpassword($userid, $_POST['password'],$r['password'],true))
					{
						$this->member->update_detail(array('authstr'=>''),array('userid' =>$userid));
						$result = array('state'=>true, 'message'=>'Password modification success');
					}
					else
					{
						$result = array('state'=>false, 'error'=>$this->member->error());
					}
				}
				echo $this->json->encode($result);
			}
			else
			{
				$this->template->assign('url',url('member/index/resetpassword_cn','userid='.$userid.'&authstr='.$authstr.'&timestamp='.$timestamp.'&key='.$key));
				$this->template->display('cn/member/resetpassword.html');
			}
		}
	}

	function validate()
	{
		$return = array();
		switch($_GET['do'])
		{
			case 'email':
				$return = $this->member->check_email($_GET['email'])
				? array('state' => true)
				: array('state' => false, 'error' => $this->member->error());
				break;
			case 'username':
				$_GET['username'] = urldecode($_GET['username']);
				$return = $this->member->check_username($_GET['username'])
				? array('state' => true)
				: array('state' => false, 'error' => $this->member->error());
				break;
			case 'seccode':
				import('helper.seccode');
				$seccode = new seccode();
				$return = $seccode->valid()
				? array('state' => true)
				: array('state' => false, 'error' => '验证码不正确');
				break;
			default:
				$return = array('state' => false, 'error' => '未定义操作');
		}
		$data = $this->json->encode($return);
		$data = (isset($_GET['jsoncallback']))? $_GET['jsoncallback']."($data);": $data;
		echo $data;
	}

    function loginform()
    {
        $cookie = factory::cookie();
		$setting = new setting();
        $rememberusername = $cookie->get('rememberusername');
        if($_SESSION['needseccode'])
        {
            $this->template->assign('needseccode', true);
        }
        else
        {
            $this->template->assign('needseccode', false);
        }

        // 此属性在otherlogin插件中被赋予
        $olurl = $this->member->url;
        $this->template->assign('sinaurl', $olurl['sina']);
        $this->template->assign('oauth_token', $_SESSION['sina']['keys']['oauth_token']);

        $this->template->assign('refer', remove_xss(request::get_referer()));
        $this->template->assign('rememberusername', $rememberusername);
		$thirdlogin = $setting->get('cloud', 'thirdlogin');
		$this->template->assign('thirdlogin', $thirdlogin);
		if ($thirdlogin)
		{
			$api = loader::model('admin/api', 'cloud');
			$thirdlogin_data = $api->select(array('islogin'=>1,'state'=>1), 'apiid,name,icon,interface', 'sort asc');
			$this->template->assign('thirdlogin_data', $thirdlogin_data);
		}

        $html = json_encode($this->template->fetch('member/loginform.html'));
        echo (isset($_GET['jsoncallback']))? $_GET['jsoncallback']."($html);": $html;
    }
    
	function ajaxlogin()
	{
		if($this->_userid)
		{
			$return = array('state'=>true, 'userid' =>$this->_userid, 'username' =>$this->_username, 'message'=>'您已经登录了');
			$data = $this->json->encode($return);
			$data = (isset($_GET['jsoncallback']))? $_GET['jsoncallback']."($data);": $data;
			exit($data);
		}

		if(!isset($_REQUEST['username'])) $_REQUEST['username'] = $_REQUEST['username'];
		if(!isset($_REQUEST['password'])) $_REQUEST['password'] = $_REQUEST['password'];
		$username = $_REQUEST['username'];
		$password = $_REQUEST['password'];

		if(!$this->_check_login())
		{
            $_SESSION['needseccode'] = 1;
			$return = array('state'=>false, 'error'=>$this->error);
			$data = $this->json->encode($return);
			$data = (isset($_GET['jsoncallback']))? $_GET['jsoncallback']."($data);": $data;
			exit($data);
		}

		$cookietime = value($_REQUEST, 'cookietime', 0);

        if ($m = $this->member->login($username, $password, $cookietime)) {
            $return = array(
                'state' => true,
                'userid'=>$m['userid'],
                'username' =>$m['username'],
                'message'=>'登录成功'.$m['ucsynlogin'],
                'rsync' => $m['ucsynlogin']
            );
        } else {
            $_SESSION['needseccode'] = 1;
            $return = array('state' => false, 'error' => $this->member->error());
        }

		$data = $this->json->encode($return);
		$data = (isset($_GET['jsoncallback']))? $_GET['jsoncallback']."($data);": $data;
		echo $data;
	}

	function ajaxlogout()
	{
		if(!$this->_userid)
		{
			$return = array('state'=>false, 'error'=>'您还没有登录');
		}
		else
		{
			$ucsynlogout = $this->member->logout();
			$return = array(
                'state'=>true,
                'message'=>'退出成功'.$ucsynlogout,
                'rsync' => $ucsynlogout
            );
		}
		$data = $this->json->encode($return);
		$data = (isset($_GET['jsoncallback']))? $_GET['jsoncallback']."($data);": $data;
		echo $data;
	}

	function ajaxIsLogin()
	{
		$return = isset($this->_userid) ? array('state'=>TRUE) : array('state'=>FALSE);
		$data = $this->json->encode($return);
		$data = (isset($_GET['jsoncallback']))? $_GET['jsoncallback']."($data);": $data;
		echo $data;
	}

	/**
	 * 解除第三方绑定
	 *
	 * @param type: 第三方绑定接口名
	 * @return 
	 */
	public function unbind()
	{
		if (empty($this->_userid))
		{
			$this->showMessage('请先登录');
		}
		$api = loader::model('api', 'cloud');
		$member_api = loader::model('member_api', 'cloud');
		if (!$data = $api->get(array('interface'=>$_GET['type'])))
		{
			$this->showMessage('类型不存在');
		}
		$apiid = $data['apiid'];
		if ($member_api->delete(array('apiid'=>$apiid, 'userid'=>$this->_userid), 1))
		{
			exit($this->json->encode(array('state'=>true)));
		}
		exit($this->json->encode(array('state'=>false, 'error'=>$this->error())));
	}

	private function _check_login()
	{
		
		if($_SESSION['seccode'])
		{
			import('helper.seccode');
			$seccode = new seccode();
			if (!$seccode->valid())
			{
				$this->error = '验证码不正确';
				return false;
			}
		}
		if(empty($_REQUEST['username']))
		{
			$this->error = '用户名不能为空';
			return false;
		}
		if(empty($_REQUEST['password']))
		{
			$this->error = '密码不能为空';
			return false;
		}
		return true;
	}

	private function _check_login_cn()
	{
		
		if($_SESSION['seccode'])
		{
			import('helper.seccode');
			$seccode = new seccode();
			if (!$seccode->valid())
			{
				$this->error = 'Verification code is not correct';
				return false;
			}
			

		}
		if(empty($_REQUEST['username']))
		{
			$this->error = 'User name cannot be empty';
			return false;
		}
		if(empty($_REQUEST['password']))
		{
			$this->error = 'Password cannot be empty';
			return false;
		}

		return true;
	}

	/*
     + 检测中文网站注册
	*/
	private function _check_register()
	{
		if(!$this->setting['allowreg'])
		{
            $close_reason = trim($this->setting['closereason']);
			$this->error = $close_reason ? $close_reason : '已关闭注册';
			return false;
		}

		if(empty($_POST['invitation_code']))
		{
			$this->error="邀请码不能为空";
			return false;
		}

		/*验证邀请码是否正确*/
		if(!$this->check_invitation_code($_POST['email'],$_POST['invitation_code']))
		{
			$this->error='邀请码错误，重输入正确的邀请码或重新申请';
			return false;
		}

		/*检测验证码*/
		 if ($_SESSION['seccode']!=$_POST['reseccode'])
		 {
	 		$this->error = '验证码不正确';
	 		return false;
		 }

		if(empty($_POST['password']))
		{
			$this->error = '密码不能为空';
			return false;
		}

		if(empty($_POST['email']))
		{
			$this->error = 'E-mail不能为空';
			return false;
		}

		if($_POST['password'] != $_POST['password_check'])
		{
			$this->error = '密码不一致';
			return false;
		}

		if(!$this->member->check_email($_POST['email']))
		{
			$this->error = $this->member->error();
			return false;
		}

		if(!$this->member->check_username(trim($_POST['username'])))
		{
			$this->error = $this->member->error();
			return false;
		}

/*        if($this->verificationPathswitch)
        {
            if($this->member_detail->exists_phone_reg($_POST['mobile']))
            {
                $this->error = '手机号码已存在';
                return false;
            }
            if(empty($_SESSION['mobile_send_code']))
            {
                $this->error = '手机验证码失效';
                return false;
            }

            if (TIME - $_SESSION['mobile_send_time'] > 900)
            {
                unset($_SESSION['mobile_send_code']);
                $this->error = '手机验证码已过期，请从新获取';
                return false;
            }

            if ($_POST['reg_mobile_code'] != $_SESSION['mobile_send_code'])
            {
                $this->error = '手机验证码不符，请从新输入';
                return false;
            } else {
                unset($_SESSION['mobile_send_code']);
            }
        }*/

		return true;
	}

	/*检测邀请码*/
	private function check_invitation_code($email,$code)
	{
		$email=trim($email);
		$code=trim($code);

		if(!isset($_SESSION[$email]['code'])){
			$this->error="邀请码已过期，请重新申请邀请码";
			return false;
		}

		if($_SESSION[$email]['code']!=$code){
			$this->error="邀请码错误，请重输入正确的邀请码或重新申请";
			return false;
		}else{
			/*邀请码正确后，清除该Session*/
			unset($_SESSION[$email]['code']);
			return true;
		}
		
		
	}


	private function _check_register_cn()
	{
		if(!$this->setting['allowreg'])
		{
            $close_reason = trim($this->setting['closereason']);
			$this->error = $close_reason ? $close_reason : 'Registration has been closed ';
			return false;
		}

		if(empty($_POST['code']))
		{
			$this->error="Invitation code can not be empty";
			return false;
		}

		/*验证邀请码是否正确*/
		if(!$this->check_invitation_code_cn($_POST['email'],$_POST['code']))
		{
			$this->error='Invite code error, reenter the correct invitation code or reapply';
			return false;
		}
		// import('helper.seccode');
		// $seccode = new seccode();
		// if (!$seccode->valid())
		// {
		// 	$this->error = '验证码不正确';
		// 	return false;
		// }

		if(empty($_POST['password']))
		{
			$this->error = 'Password can not be empty';
			return false;
		}

		if(empty($_POST['email']))
		{
			$this->error = 'E-mail cannot be empty';
			return false;
		}

		if($_POST['password'] != $_POST['password_check'])
		{
			$this->error = 'Passwords are not consistent';
			return false;
		}

		if(!$this->member->check_email_cn($_POST['email']))
		{
			$this->error = $this->member->error();
			return false;
		}

		if(!$this->member->check_username(trim($_POST['username'])))
		{
			$this->error = $this->member->error();
			return false;
		}

        if($this->verificationPathswitch)
        {
            if($this->member_detail->exists_phone_reg($_POST['mobile']))
            {
                $this->error = 'Cell phone number already exists';
                return false;
            }
            if(empty($_SESSION['mobile_send_code']))
            {
                $this->error = 'Cell phone verification code failure';
                return false;
            }

            if (TIME - $_SESSION['mobile_send_time'] > 900)
            {
                unset($_SESSION['mobile_send_code']);
                $this->error = 'Mobile authentication code has expired, please get the new';
                return false;
            }

            if ($_POST['reg_mobile_code'] != $_SESSION['mobile_send_code'])
            {
                $this->error = 'Phone verification code does not match, please enter the new';
                return false;
            } else {
                unset($_SESSION['mobile_send_code']);
            }
        }

		return true;
	}

	/*英文站检测邀请码*/
	private function check_invitation_code_cn($email,$code)
	{
		$email=trim($email);
		$code=trim($code);

		if(!isset($_SESSION[$email]['code'])){
			$this->error="The invitation code has expired. Please re apply the invitation code.";
			return false;
		}

		if($_SESSION[$email]['code']!=$code){
			$this->error="Invite code error, re enter the correct invitation code or reapply";
			return false;
		}else{
			/*邀请码正确后，清除该Session*/
			unset($_SESSION[$email]['code']);
			return true;
		}
		
		
	}


	private function _check_getpassword()
	{
		if(!isset($_POST['username']) || !isset($_POST['email']))
		{
			$this->error = '用户名和E-mail不能为空';
			return false;
		}

		$check = $this->member->check_matchs($_POST['username'],$_POST['email']);
		if(!$check)
		{
			$this->error = '用户名和E-mail不匹配';
			return false;
		}
		$cookie = factory::cookie();
		// $lock = $cookie->get('getpassword');
		// if(!empty($lock))
		// {
		// 	$this->error = $this->setting['locktime'].'小时只能进行一次此操作';
		// 	return false;
		// }
		return true;
	}

	private function _check_getpassword_cn()
	{
		if(!isset($_POST['username']) || !isset($_POST['email']))
		{
			$this->error = 'User name and E-mail cannot be empty';
			return false;
		}

		$check = $this->member->check_matchs($_POST['username'],$_POST['email']);
		if(!$check)
		{
			$this->error = 'User name and E-mail does not match';
			return false;
		}
		$cookie = factory::cookie();
		$lock = $cookie->get('getpassword');
		// if(!empty($lock))
		// {
			// $this->error = 'This operation can only be carried out once in'.$this->setting['locktime'].' hours';
		// 	return false;
		// }
		return true;
	}

    public function sendmessage ()
    {
        if ($this->verificationPathswitch) {
            if (!$_SESSION['mobile_session_reg']) {
                exit($this->json->encode(array('state' => false, 'error' => '地址错误')));
                return false;
            }
            $params = array();
            if (!($params['mobile'] = $_POST['mobilenumber'])) {
                exit($this->json->encode(array('state' => false, 'error' => '请输入手机号')));
            }
            if (!preg_match("/^1[34578]\d{9}$/", $params['mobile'])) {
                exit($this->json->encode(array('state' => false, 'error' => '请输入正确的电话号码')));
            }

            if (TIME - $_SESSION['mobile_send_time'] > 60) {
                $params['code'] = random(5);
                $ret = $this->member->send_message($params);
                if ($ret) {
                    $_SESSION['mobile_send_time'] = TIME;
                    $_SESSION['mobile_send_code'] = $params['code'];
                    exit($this->json->encode(array('state' => true, 'message' => '验证码已发出')));
                } else {
                    exit($this->json->encode(array('state' => false, 'error' => '手机验证码发送失败，请联系管理员')));
                }
            } else {
                exit($this->json->encode(array('state' => false, 'error' => '频率频繁，请稍后再试')));
            }
        } else {
            exit($this->json->encode(array('state' => false, 'error' => '手机验证码已关闭')));
        }
    }

    /**
		*ajax判断是否是收藏状态
	*/
	function ajax_shifou_collect()
	{
		$db = & factory::db();
		$contentid = $_GET['contentid'];
		$memberid = $_GET['memberid'];
		$sql = "select collectid from cmstop_collect where contentid=$contentid and memberid=$memberid and typeid=4";
		$data = $db->select($sql);
		
		if(!empty($data[0]['collectid']))
			{
				$arr=array('status'=>'success');
			}
			else
			{
				$arr=array('status'=>'error');
			}

			$data = $this->json->encode($arr);
			$data = (isset($_GET['jsoncallback']))? $_GET['jsoncallback']."($data);": $data;
			echo $data;
	}
	/**
		*ajax操作数据库判断是否是项目收藏
	*/

	function ajax_project_collect ()
	{
		$db = & factory::db();
		$contentid = $_GET['contentid'];
		$memberid = $_GET['memberid'] ? $_GET['memberid'] : $this->_userid;
		$typeid = $_GET['typeid'];
		if($typeid==4){
			$sql = "select contentid from cmstop_collect where memberid=$memberid and typeid=4";
		}elseif($typeid==8){
			$sql = "select contentid from cmstop_collect where memberid=$memberid and typeid=8";
		}

		$result = $db->select($sql);
		foreach($result as $k=>$v){
			$data[] = $v['contentid'];
		}
			$data = $this->json->encode($data);
			$data = (isset($_GET['jsoncallback']))? $_GET['jsoncallback']."($data);": $data;
			echo $data;
	}

	/**
		*ajax操作数据库判断是否是文章收藏
	*/

	function ajax_article_collect ()
	{
		$db = & factory::db();
		$memberid = $_GET['memberid'] ? $_GET['memberid'] : $this->_userid;
		$sql = "select contentid from cmstop_collect where memberid=$memberid and typeid=0";
		$result = $db->select($sql);
		foreach($result as $k=>$v){
			$data[] = $v['contentid'];
		}
			$data = $this->json->encode($data);
			$data = (isset($_GET['jsoncallback']))? $_GET['jsoncallback']."($data);": $data;
			echo $data;
	}
	/**
		*ajax操作数据库取消收藏
	*/
    function ajax_not_collect ()
    {
    	$db = & factory::db();
  
		$memberid = $_GET['memberid'] ? $_GET['memberid'] : $this->_userid;
		$typeid = $_GET['typeid'];
		$contentid = $_GET['contentid'];
		if($_GET['spaceid']){
	    	$spaceid = $_GET['spaceid'];
	    	$sql = "delete from cmstop_collect where spaceid=$spaceid and memberid=$memberid";
	    	$data = $db->delete($sql);
   		 }
 		if($typeid==4 || $typeid==8){
 			$sql = "delete from cmstop_collect where contentid=$contentid and memberid=$memberid and typeid=$typeid";
 			$data = $db->delete($sql);
		}
		if($typeid==0){
 			$sql = "delete from cmstop_collect where contentid=$contentid and memberid=$memberid and typeid=0";
 			$data = $db->delete($sql);
		}
			if(!empty($data))
			{
				$arr=array('status'=>'success');
			}
			else
			{
				$arr=array('status'=>'error');
			}

			$data = $this->json->encode($arr);
			$data = (isset($_GET['jsoncallback']))? $_GET['jsoncallback']."($data);": $data;
			echo $data;


    }

	/**
		*ajax操作数据库完成收藏
	*/
    function ajax_collect()
		{
			$db = & factory::db();
			$_GET['addtime'] = time();
		 	$arr = array('memberid','contentid','catid','spaceid','title','url','typeid','status','addtime');
		 	foreach($_GET as $k=>$v){
				if(in_array($k,$arr)){
					$ndata[$k] = $v;
				}
			}
			$keys = implode(',',array_keys($ndata));
			$values = implode(',',array_values($ndata));
			$sql = "insert into cmstop_collect ($keys) values ($values) ";
			$data = $db->insert($sql);
			if(!empty($data))
			{
				$arr=array('status'=>'success');
			}
			else
			{
				$arr=array('status'=>'error');
			}

			$data = $this->json->encode($arr);
			$data = (isset($_GET['jsoncallback']))? $_GET['jsoncallback']."($data);": $data;
			echo $data;
		}

	/**
		*ajax操作数据库删除该条收藏记录
	*/
	function ajax_delete_collect()
	{
		$db = & factory::db();
    	
    	$collectid = $_GET['collectid'];
    	$sql = "delete from cmstop_collect where collectid=$collectid";
    	$data = $db->delete($sql);
    	if(!empty($data))
			{
				$arr=array('status'=>'success');
			}
			else
			{
				$arr=array('status'=>'error');
			}

			$data = $this->json->encode($arr);
			$data = (isset($_GET['jsoncallback']))? $_GET['jsoncallback']."($data);": $data;
			echo $data;
	}

	function thirdPartyLogin() {
		if(!$this->_userid) {
			$this->showmessage('请登陆后再来操作');
		}
		$member = $this->member->get_by('userid', $this->_userid);
		$username = $member['username'];
		if(!$username) {
			$this->showmessage('您的用户名不存在');
		}

		$key = THIRD_PARTY_AUTHKEY;
		$time = ceil(microtime(true)*1000);
		$paramstr = 'uname='.$username.'&ts='.$time;
		$sign = md5($paramstr);

		$secUtil = loader::lib('secUtil', 'member');
		$secUsername = base64_encode($secUtil::dir_encrypt($key, $username));

		$url = THIRD_PARTY_DOMAIN.'/thirdPartyLogin.do?uname='.$secUsername.'&ts='.$time.'&sign='.$sign;
		header("Location:$url");
		exit;
	}

	//分站一键登录
	//authcode=加密字符串
	//字符串：uid=xxx&sitetype=1&token=xxx&time=xxx
	function thirdSiteLogin() {
		$secUtil = loader::lib('secUtil', 'member');
		$authcode = str_replace(' ', '+', urldecode($_REQUEST['authcode']));
		if(!$authcode) {
			$this->showmessage('请求不合法');
		}
		$str = $secUtil::dir_decrypt(THIRD_SITE_AUTHKEY, base64_decode($authcode));
		parse_str($str);
		if(empty($uid) || empty($token) || empty($sitetype)) {
			$this->showmessage('验证失败，参数异常');
		}
		$member = $this->member->get(array('sitetype'=>$sitetype, 'thirdid'=>$uid));
		if(!$member) {
			$this->showmessage('用户不存在');
		}
		if($member['thirdtoken'] != $token) {
			$this->showmessage('验证失败，请重新登录');
		}
		//大于3小时没登录，则过期
		if(TIME - $member['thirdlastlogin'] > 3600 * 3) {
			$this->showmessage('授权已过期，请重新登录');
		}
		if($member['status'] < 1) {
			$this->showmessage('该账户已被禁用');
		}
		if($this->_userid == $member['userid']) {
			if($member['site_type'] == 1) {
				$redirect = url('member/index/successlogin');
			} else {
				$redirect = url('member/index/successlogin_cn');
			}
			$this->redirect($redirect);
		}

		$this->member->update(array('lastloginip'=>IP, 'lastlogintime'=>TIME, 'thirdlastlogin'=>TIME), "userid=".$member['userid']);
		$cookie = factory::cookie();
		$cookie->set('auth', str_encode($member['userid']."\t".$member['username']."\t".$member['groupid']."\t".md5($_SERVER['HTTP_USER_AGENT'])."\t".IP, config('config', 'authkey')), 0);
		$cookie->set('userid', $member['userid'], 0);
		$cookie->set('username', $member['username'], 0);
		$cookie->set('photo', element::member_photo($member['userid'], 22, 22));
		$cookie->set('groupid', $member['groupid'], 0);
		$cookie->set('site_type', $member['site_type'], 0);
		if($member['site_type'] == 1) {
			$redirect = url('member/index/successlogin');
		} else {
			$redirect = url('member/index/successlogin_cn');
		}
		$this->redirect($redirect);
	}

	//authcode=加密字符串
	//字符串：uid=xxx&sitetype=1&token=xxx&time=xxx
	function thirdSiteQuery() {
		$secUtil = loader::lib('secUtil', 'member');
		$authcode = str_replace(' ', '+', urldecode($_POST['authcode'])); //为了安全，post请求
		if(!$authcode) {
			exit(json_encode(array('state'=>0, 'messge'=>'请求不合法')));
		}
		$str = $secUtil::dir_decrypt(THIRD_SITE_AUTHKEY, base64_decode($authcode));
		parse_str($str);
		if(empty($uid) || empty($token) || empty($sitetype)) {
			exit(json_encode(array('state'=>0, 'messge'=>'验证失败，参数异常')));
		}
		$member = $this->member->get(array('sitetype'=>$sitetype, 'thirdid'=>$uid));
		if(!$member) {
			exit(json_encode(array('state'=>0, 'messge'=>'用户不存在')));
		}
		$this->member->update(array('thirdtoken'=>$token, 'thirdlastlogin'=>TIME), "userid=".$member['userid']);
		exit(json_encode(array('state'=>1, 'messge'=>'请求成功')));
	}
	
}
