<?php
class controller_index extends guestbook_controller_abstract
{
	private $guestbook, $pagesize, $guestbook_type;

	function __construct($app)
	{
		parent::__construct($app);
		$this->guestbook = loader::model('guestbook');
		$this->pagesize = empty($this->setting['pagesize']) ? 15 : $this->setting['pagesize'];
	}
	
		function index()
	{
        $typeid = isset($_GET['typeid']) ? intval($_GET['typeid']) : 0;
        $page = max((isset($_GET['page']) ? intval($_GET['page']) : 1), 1);
        $pagesize = isset($_GET['pagesize']) ? intval($_GET['pagesize']) : 0;
        if(empty($pagesize)) $pagesize = $this->pagesize;

        if ($this->system['pagecached'])
        {
            $keyid = md5('pagecached_guestbook_index_index_' .$typeid.'_'.$page.'_'.$pagesize);
            cmstop::cache_start($this->system['pagecachettl'], $keyid);
        }

		$type = $this->guestbook->count_type($this->setting['repliedshow']);
		$setting = $this->json->encode($this->setting['option']);
		
		$where = null;
		if($this->setting['repliedshow'])
		{
			$where[] = "reply != ''";
		}
		if($typeid)
		{
			$where[] = "`typeid`=".$typeid;
		}
		
		$where = implode(' AND ', $where);
		
		$fields = '*';
		$order = '`gid` DESC';
		$data  = $this->guestbook->ls($where, $fields, $order, $page, $pagesize);
        if(empty($data))
        {
            $total = 0;
        }
        else
        {
            $total = $this->guestbook->count($where);
        }
		$multipage = pages($total, $page, $pagesize, 2, null, null, '', 'current', '&lt;', '&gt;');
		
		$this->template->assign('typeid', $typeid);
		$this->template->assign('type', $type);
		$this->template->assign('data', $data);
		$this->template->assign('multipage', $multipage);
		$this->template->assign('total', $total);
		$this->template->assign('setting', $setting);
		$this->template->assign('guestbookname', $this->setting['guestbookname']);
		$this->template->assign('showmanage', $this->setting['showmanage']);
		$this->template->display('guestbook/index.html');
		

        if ($this->system['pagecached']) cmstop::cache_end();
	}

	function index_newcn()
	{
        $typeid = isset($_GET['typeid']) ? intval($_GET['typeid']) : 0;
        $page = max((isset($_GET['page']) ? intval($_GET['page']) : 1), 1);
        $pagesize = isset($_GET['pagesize']) ? intval($_GET['pagesize']) : 0;
        if(empty($pagesize)) $pagesize = $this->pagesize;

        if ($this->system['pagecached'])
        {
            $keyid = md5('pagecached_guestbook_index_index_' .$typeid.'_'.$page.'_'.$pagesize);
            cmstop::cache_start($this->system['pagecachettl'], $keyid);
        }

		$type = $this->guestbook->count_type($this->setting['repliedshow']);
		$setting = $this->json->encode($this->setting['option']);
		
		$where = null;
		if($this->setting['repliedshow'])
		{
			$where[] = "reply != ''";
		}
		if($typeid)
		{
			$where[] = "`typeid`=".$typeid;
		}
		
		$where = implode(' AND ', $where);
		
		$fields = '*';
		$order = '`gid` DESC';
		$data  = $this->guestbook->ls($where, $fields, $order, $page, $pagesize);
        if(empty($data))
        {
            $total = 0;
        }
        else
        {
            $total = $this->guestbook->count($where);
        }
		$multipage = pages($total, $page, $pagesize, 2, null, null, '', 'current', '&lt;', '&gt;');
		
		$this->template->assign('typeid', $typeid);
		$this->template->assign('type', $type);
		$this->template->assign('data', $data);
		$this->template->assign('multipage', $multipage);
		$this->template->assign('total', $total);
		$this->template->assign('setting', $setting);
		$this->template->assign('guestbookname', $this->setting['guestbookname']);
		$this->template->assign('showmanage', $this->setting['showmanage']);
		$this->template->display('newcn/page/feedback.html');

        if ($this->system['pagecached']) cmstop::cache_end();
	}

	function index2()
	{
		$setting = $this->json->encode($this->setting['option']);
		$this->template->display('newcn/page/contact-us.html');
	}

	function add()
	{
		if($this->is_post())
		{
			//FEEDBACK     subject(标题),  comments（内容）,  username,   phone_number,   E-mail,   seccode,
			//contact-us   First Name,Last Name,Email Address(E-mail),Phone Number(phone_number),Country/Region（国家）,Contact Message(comments)（内容）
			$_POST['title'] = isset($_POST['subject']) ? $_POST['subject']:'contact-us';
			$_POST['content'] = isset($_POST['comments']) ? $_POST['comments']:null;
			$_POST['username'] = isset($_POST['username']) ? $_POST['username']:((isset($_POST['first-name']) && isset($_POST['last-name'])) ? ($_POST['first-name'].$_POST['last-name']):null);
			$_POST['mobile'] = isset($_POST['phone_number']) ? $_POST['phone_number']:null;
			$_POST['E-mail'] = isset($_POST['E-mail']) ? $_POST['E-mail'] : null;
			$_POST['address'] = isset($_POST['address']) ? $_POST['address'] : null;
			$_POST['typeid'] = isset($_POST['typeid']) ? $_POST['typeid'] : 1;
			if(!$this->_submit_check())
			{
				$result = array('state' => false, 'message' => $this->error);
			}
			else
			{
				$data = $_POST;
				$data['typeid'] = intval($data['typeid']);
				if($gid = $this->guestbook->add($data))
				{
					$result = array('state' => true, 'message' => '留言成功');
				}
				else
				{
					$result = array('state' => false, 'message' => $this->guestbook->error());
				}
			}
			if(isset($_POST['first-name']))
			{
				$this->showmessage($result['message'], url('guestbook/index/index2/'), 3000, $result['state']);
			}
			else
			{
				$this->showmessage($result['message'], url('guestbook/index/index/'), 3000, $result['state']);
			}
		}
		else
		{
			$this->index();
		}
	}
	

	/*咨询里的留言提交
     + 接收的参数:
     +标题、类型、内容、留言人、留言时间
	*/
	function zixun_guest()
	{
		$typeid=intval($_GET['typeid']);	/*类型*/
		$dd['typeid']=$typeid;

		/*留言标题*/
		if($typeid==1)
		{
			$title="记者观察咨询留言";
		}
		else if($typeid==2)
		{
			$title="推荐专家咨询留言";
		}
		else if($typeid==3)
		{
			$title="研究机构咨询留言";
		}

		$dd['title']=$title;

		$dd['addtime']=time();	/*留言时间*/
		$dd['content']=addslashes_deep($_GET['content']);
		$dd['userid']=$this->userid;
		$dd['username']=table("member",$this->_userid,'username');
        $dd['ip']=$_GET['ip'];
		$db=factory::db();
		$result=$db->insert("insert into #table_guestbook (typeid,title,addtime,content,userid,username,ip) values(:typeid,:title,:addtime,:content,:userid,:username,:ip)",$dd);

		if(!empty($result))
		{
			$arr=array('state'=>true,'info'=>'提交成功');
		}
		else
		{
			$arr=array('state'=>false,'info'=>'提交失败');
		}
		
		exit(json_encode($arr));
	}


	function seccode()
	{
		import('helper.seccode');
		$seccode = new seccode();
		$return = $seccode->valid()
				? array('state' => true, 'message' => '正确')
				: array('state' => false, 'error' => '验证码不正确');
		echo $this->json->encode($return);
	}
	//提交
	function _submit_check()
	{
		//FEEDBACK     subject(标题),  comments（内容）,  username,   phone_number,   e-mail,   seccode,
		//contact-us   First Name,Last Name,Email Address,Phone Number,Country/Region（国家）,Contact Message（内容）
		$set = $this->setting;
		if(empty($_POST['username']))
		{
			$this->error = '姓名为空';
			return false;
		}
		if(empty($_POST['title']))
		{
			$this->error = '标题为空';
			return false;
		}
		if(empty($_POST['content']))
		{
			$this->error = '内容为空';
			return false;
		}
		if(mb_strlen($_POST['content'],'utf-8') > $set['replymax'])
		{
			$this->error = '留言超过最大限制字数';
			return false;
		}
		if(!isset($_POST['first-name']))
		{
			if($set['iscode'])
			{
				import('helper.seccode');
				$seccode = new seccode();
				if(!$seccode->valid())
				{
					$this->error = '验证码不正确';
					return false;
				}
			}
		}
		
		if($set['option']['gender'] && $set['option']['isgender'] && empty($_POST['gender']))
		{
			$this->error = '性别为空';
			return false;
		}
		if($set['option']['email'] && $set['option']['isemail'] && empty($_POST['email']))
		{
			$this->error = 'E-mail为空';
			return false;
		}
		if($set['option']['address'] && $set['option']['isaddress'] && empty($_POST['address']))
		{
			$this->error = '地址为空';
			return false;
		}
		if($set['option']['mobile'] && $set['option']['ismobile'] && empty($_POST['mobile']))
		{
			$this->error = '手机为空';
			return false;
		}
		if($set['option']['telephone'] && $set['option']['istelephone'] && empty($_POST['telephone']))
		{
			$this->error = '电话为空';
			return false;
		}
		if($set['option']['qq'] && $set['option']['isqq'] && empty($_POST['qq']))
		{
			$this->error = 'QQ为空';
			return false;
		}
		if($set['option']['msn'] && $set['option']['ismsn'] && empty($_POST['msn']))
		{
			$this->error = 'MSN为空';
			return false;
		}
		if($set['option']['homepage'] && $set['option']['ishomepage'] && empty($_POST['homepage']))
		{
			$this->error = '个人主页为空';
			return false;
		}
		$_POST = htmlspecialchars_deep($_POST);
		return true;
	}
}