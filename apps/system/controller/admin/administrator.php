<?php
/**
 * 管理员
 *
 * @aca 管理员
 */
final class controller_admin_administrator extends system_controller_abstract
{
	private $pagesize = 15;

    /**
     * 管理员
     *
     * @aca 浏览
     */
	function index()
	{
		$this->view->assign('pagesize', $this->pagesize);
		$this->view->assign('head', array('title'=>'管理员'));
		$this->view->display("administrator/index");
	}

    /**
     * 列表
     *
     * @aca 浏览
     */
	function page()
	{
		$departmentid = intval($_REQUEST['departmentid']);
		$roleid = intval($_REQUEST['roleid']);
		$where = array();
		if (!empty($departmentid))
		{
			$where[] = "a.departmentid=$departmentid";
		}
		if (!empty($roleid))
		{
			$where[] = "a.roleid=$roleid";
		}
		if (!empty($_REQUEST['keywords']))
		{
			$keywords = str_replace('*', '%', addcslashes($_REQUEST['keywords'], '%_'));
			$like = '%'.str_replace('_', '\_', $keywords).'%';
			$where[] = "(a.name LIKE '$like' OR m.username LIKE '$like')";
		}
		$order = null;
		if (!empty($_REQUEST['orderby']))
		{
			$order = explode('|', $_REQUEST['orderby']);
			$order = "a.{$order[0]} $order[1]";
		}
		$this->_page(implode(' AND ',$where), $order);
	}

    /**
     * 管理员
     *
     * @aca public
     */
	function mypage()
	{
		if (!$this->_departmentid)
		{
			exit('{total:0,data:[]}');
		}
		$department = table('department', $this->_departmentid);
		$departmentids = $department['childids'] == null 
			? array()
			: array_filter(explode(',', $department['childids']));
		$departmentids[] = $this->_departmentid;
		$where = 'a.departmentid IN ( '.implode_ids($departmentids).' )';
		$order = null;
		if (!empty($_REQUEST['orderby']))
		{
			$order = explode('|', $_REQUEST['orderby']);
			$order = "a.{$order[0]} $order[1]";
		}
		$this->_page($where, $order);
	}
	
	protected function _page($where = null, $order = null)
	{
		$fsql = "FROM #table_admin as a
				LEFT JOIN #table_member as m ON m.userid = a.userid";
		if ($where) {
			$fsql .= " WHERE $where";
		}
		$db = factory::db();
		
		$total = $db->get("SELECT count(*) as total $fsql");
		$total = $total['total'];
		$page = intval($_GET['page']);
		if ($page < 1) {
			$page = 1;
		}
		if (!$order)
		{
			$order = 'a.userid DESC';
		}
		$data = $db->page("SELECT a.*,m.username $fsql ORDER BY $order", $page, $this->pagesize);
		
		$departments = table('department');
		$roles = table('role');
		$log = loader::model('member_login_log', 'member');
		foreach ($data as &$val)
		{
			if ($log_data = $log->get(array('username'=>$val['username'], 'succeed'=>1), 'ip, time', 'time desc'))
			{
				$val['login_time'] = $log_data['time'];
				$val['login_place'] = $log_data['ip'] ? iptolocation($log_data['ip']) : '';
			}
			$val['state'] = ($val['disabled'] == 0) ? '启用' : '<span class="c_red">禁用</span>';
			$val['sex'] = $val['sex'] ? ($val['sex'] == MALE ? '男' : '女') : '保密';
			$val['department'] = $departments[$val['departmentid']]['name'];
			$val['role'] = $roles[$val['roleid']]['name'];
		}

		for ($i=0,$size=count($data);$i<$size;$i++)
		{
			foreach ($data[$i] as &$item)
			{
				$item = is_null($item) ? ' ' : $item;
			}
		}
		exit($this->json->encode(array('total'=>$total,'data'=>$data)));
	}

    /**
     * 添加
     *
     * @aca 添加
     */
	function add()
	{
        /** @var model_admin_admin $admin */
        $admin = loader::model('admin/admin');

		if ($this->is_post())
		{
		    $_POST['groupid'] = 1;// 管理员组
            /** @var model_member $member */
		    $member = loader::model('member','member');
		    // 添加到member表
            $password = $_POST['password'];
		    if ($data = $member->register_for_admin($_POST))
		    {
		        $weight = loader::model('admin/admin_weight');
		        if ($admin->insert($data))
		        {
                    $admin->force_password($data['userid'], $password);
		            $departments = table('department');
		            $roles = table('role');
					$data['username'] = username($data['userid']);
		            $data['state'] = ($data['disabled'] == 0) ? '启用' : '禁用';
        			$data['sex'] = $data['sex'] ? ($data['sex'] == MALE ? '男' : '女') : '保密';
        			$data['department'] = $departments[$data['departmentid']]['name'];
        			$data['role'] = $roles[$data['roleid']]['name'];
        			$admin->setpriv($data['userid'], $_POST['catid'], $_POST['pageid']);
					$weight->add($data['userid'], $_POST['weight']);
        			priv::cache($data['userid'], $data['roleid']);
        			$result = array('state'=>true,'data'=>$data);
        			if ($safetyemail = setting('system','safetyemail'))
        			{
        				$mailcontent = $data['username'].'用户已于'.date('Y-m-d H:i:s', TIME).'被提升为管理员';
        				send_email($safetyemail, $data['username'].'用户已被提升为管理员', $mailcontent);
        			}
		        }
		        else
		        {
		            $result = array('state'=>false,'error'=>$admin->error());
		        }
		    }
		    else
		    {
		        $result = array('state'=>false,'error'=>$member->error());
		    }
			exit($this->json->encode($result));
		}
		else
		{
		    $departmentid = intval($_GET['departmentid']);
		    import('form.form_element');
			$sex_radio = form_element::radio(array(
			     'value'=>1,
			     'name'=>'sex',
			     'options'=>array(1=>'男', 2=>'女')
			));
			$state_radio = form_element::radio(array(
			     'value'=>0,
			     'name'=>'disabled',
			     'options'=>array(0=>'启用',1=>'禁用')
			));
			$this->view->assign('catid',json_encode(array_values($catid)));
			$this->view->assign('sex_radio',$sex_radio);
			$this->view->assign('state_radio',$state_radio);
			$this->view->assign('departmentid',$departmentid);
			$this->view->display('administrator/add');
		}
	}

    /**
     * 添加
     *
     * @aca 添加
     */
	function myadd()
	{
		$departments = table('department');
        $my_department = $departments[$this->_departmentid];
        if (empty($my_department))
        {
            $this->error_out('您没有权限进行此项操作');
        }
        if ($this->_roleid != $my_department['leaderid'])
        {
            $this->error_out('您没有权限进行此项操作');
        }
        
        $departmentid = intval($_REQUEST['departmentid']);
        if (!$departmentid || !isset($departments[$departmentid]))
        {
            $this->error_out('不存在此部门');
        }
        
        $family_departmentids = $my_department['childids'] == null 
			? array()
			: array_filter(explode(',', $my_department['childids']));
	    $family_departmentids[] = $this->_departmentid;
	    if (!in_array($departmentid, $family_departmentids))
	    {
	        $this->error_out('无权限编辑此角色');
	    }
        if ($this->is_post() && $_POST['roleid'] == $this->_roleid)
        {
            $this->error_out('无权限设置本部门多个主管角色');
        }
        $this->add();
	}

    /**
     * 编辑
     *
     * @aca 编辑
     */
	function edit()
	{
        $userid = intval($_REQUEST['userid']);
        /** @var model_admin_admin $admin */
        $admin = loader::model('admin/admin');
		$weight = loader::model('admin/admin_weight');
        if (!$userid)
        {
            $this->error_out('没有可编辑');
        }
		if ($this->is_post())
		{	

			if(empty($_POST['password'])){
				$db=factory::db();
				$ps=$db->get("select password from #table_admin where userid={$userid}");
				$_POST['password']=$ps['password'];
				$mark="kong";
			}else{
				$mark="you";
			}
			


	        if (FALSE !== $admin->update($_POST, $userid))
			{
                /** @var model_member $member */
				$member = loader::model('member', 'member');
				if ($_POST['password'] !== '' & $mark=="you")
				{
					// 同步更新 password
					if (! $admin->force_password($userid, $_POST['password']))
					{
						exit ('{"state":false, "error":"密码修改失败"}');
					}
				}
				// 同步邮箱
				if (! $member->set_field('email', $_POST['email'], $userid))
				{
					exit ('{"state":false, "error":"email修改失败"}');
				}
				$data = $admin->get($userid);
				
				$data['username'] = username($data['userid']);
				$data['state'] = $data['disabled'] ? '禁用' : '启用';
				$data['sex'] = $data['sex'] ? ($data['sex'] == MALE ? '男' : '女') : '保密';
				$data['role'] = '';
				$data['department'] = '';
				if ($data['roleid'])
				{
				    $data['role'] = table('role', $data['roleid'], 'name');
				}
				if ($data['departmentid'])
				{
				    $data['department'] = table('department',$data['departmentid'],'name');
				}
				$admin->setpriv($data['userid'], $_POST['catid'], $_POST['pageid']);
				$weight->edit($data['userid'], $_POST['weight']);
				priv::cache($data['userid'], $data['roleid']);
				$result = array('state'=>true, 'data'=>$data);
			}
			else 
			{
				$result = array('state'=>false, 'error'=>$admin->error());
			}
			exit($this->json->encode($result));
		}
		else
		{
			$cate_priv	= loader::model('admin/category_priv');
			$catid	= $cate_priv->ls_catid($userid);
			$user = $admin->get($userid);
			$member = table('member', $userid);
			$weight = $weight->get($userid);
			$user = array_merge($user, $member);
			$weight && $user['weight'] = $weight['weight'];
			if ($user['birthday'] == '0000-00-00') $user['birthday'] = '';
			import('form.form_element');
			$sex_radio = form_element::radio(array(
			     'value'=>$user['sex'],
			     'name'=>'sex',
			     'options'=>array(1=>'男',2=>'女')
			));
			$state_radio = form_element::radio(array(
			     'value'=>$user['disabled'],
			     'name'=>'disabled',
			     'options'=>array(0=>'启用',1=>'禁用')
			));
			$this->view->assign('sex_radio',$sex_radio);
			$this->view->assign('state_radio',$state_radio);
			$this->view->assign('user',$user);
            $this->view->assign('catid', implode_ids($catid));
		    $this->view->display('administrator/edit');
		}
	}

    /**
     * 编辑
     *
     * @aca 编辑
     */
	function myedit()
	{
		$departments = table('department');
        $my_department = $departments[$this->_departmentid];
        if (empty($my_department))
        {
            $this->error_out('您没有权限进行此项操作');
        }
        if ($this->_roleid != $my_department['leaderid'])
        {
            $this->error_out('您没有权限进行此项操作');
        }
        $users = table('admin');
        $userid = intval($_REQUEST['userid']);
        if (!$userid || !isset($users[$userid]))
        {
            $this->error_out('不存在此人员');
        }
        $user = $users[$userid];
        $departmentid = $user['departmentid'];
        if (!isset($departments[$departmentid]))
        {
            $this->error_out('无部门角色');
        }
        $department = $departments[$departmentid];
        
        $family_departmentids = $my_department['childids'] == null 
			? array()
			: array_filter(explode(',', $my_department['childids']));
	    $family_departmentids[] = $this->_departmentid;
	    if (!in_array($departmentid, $family_departmentids))
	    {
	        $this->error_out('无权限编辑此人员');
	    }
	    $this->edit();
	}

    /**
     * 删除
     *
     * @aca 删除
     */
	function delete()
	{
	    if (!isset($_POST['id']))
        {
            exit('{"state":false,"error":"无对象可删除"}');
        }
        $ids = array_unique(array_filter(array_map('trim',explode(',',$_POST['id']))));
        // 设置member表中groupid = 6
        $member = loader::model('member','member');
        // 删除admin表中记录
        $admin = loader::model('admin/admin');
		// 删除admin_weight表中的记录
        $weight = loader::model('admin/admin_weight');
        $error = '';
        foreach($ids as $userid)
        {
            if (!$member->update(array('groupid'=>6), $userid))
            {
                $error .= $member->error().'<br/>';
                continue;
            }
            if (!$admin->delete($userid))
            {
                $error .= $admin->error().'<br/>';
            }
			if(!$weight->delete($userid))
			{
				$error .= $weight->error().'<br/>';
			}
        }
        $result = $error ? array('state'=>false,'error'=>$error) : array('state'=>true);
        exit($this->json->encode($result));
	}

    /**
     * 删除
     *
     * @aca 删除
     */
	function mydelete()
	{
		$departments = table('department');
        $my_department = $departments[$this->_departmentid];
        if (empty($my_department))
        {
            $this->error_out('您没有权限进行此项操作');
        }
        if ($this->_roleid != $my_department['leaderid'])
        {
            $this->error_out('您没有权限进行此项操作');
        }
        
        if (!isset($_POST['id']))
        {
            $this->error_out("无对象可删除");
        }
        $ids = array_unique(array_filter(array_map('trim',explode(',',$_POST['id']))));
        
        $family_departmentids = $my_department['childids'] == null 
			? array()
			: array_filter(explode(',', $my_department['childids']));
	    $family_departmentids[] = $this->_departmentid;
	    
        $users = table('admin');
        foreach ($ids as $k=>$userid)
        {
            $userid = intval($userid);
            if (!$userid || $userid == $this->_userid 
                 || !($u = $users[$userid])
                 || !in_array($u['departmentid'], $family_departmentids))
            {
                unset($ids[$k]);
            }
        }
        $_POST['id'] = implode(',',$ids);
        $this->delete();
	}

    /**
     * 工作报表
     *
     * @aca 工作报表
     */
    function stat()
    {
		$this->view->assign('head', array('title'=>"工作报表：".username($_GET['userid'])));
		$this->view->display('administrator/stat');
    }

    /**
     * 权限列表
     *
     * @aca public
     */
	function priv()
	{
	    $type = isset($_GET['type']) && in_array($_GET['type'], array('action', 'category', 'page')) ? $_GET['type'] : 'action';
        $userid = intval($_GET['userid']);
        $admin = table('admin', $userid);
        if ($userid)
        {
        	$roleid = $admin['roleid'];
        }
        else
        {
        	$userid = $this->_userid;
        	$roleid = $this->_roleid;
        }
		if ($this->is_ajax())
		{
			priv::init($userid, $roleid);
			
	    	if ($type == 'category')
	    	{
	    		$category = table('category');
	    		foreach ($category as $k=>$c)
	    		{
	    			$category[$k]['allow'] = priv::category($c['catid']) ? 1 : 0;
	    		}
                echo $this->json->encode(array_values($category));
	    	}
	    	elseif ($type == 'page')
	    	{
	    		$title = '页面权限：'.username($userid);
	    		$data = array();
	    		$page = table('page');
	    		foreach ($page as $k=>$c)
	    		{
                    if ($c['status'] != 1) continue;
	    			$data[] = array('pageid'=>$c['pageid'], 'parentid'=>$c['parentid'], 'name'=>$c['name'], 'allow'=>priv::page($c['pageid']) ? 1 : 0);
	    		}
	    		$pageid = array_keys($page);
	    		
	    		$section = table('section');
	    		foreach ($section as &$c)
	    		{
                    if ($c['status'] != 1) continue;
	    			$c['parentid'] = $c['pageid'];
	    			$c['pageid'] = '100000'.$c['sectionid'];
	    			$c['allow'] = priv::section($c['sectionid']) ? 1 : 0;
	    			unset($c['origdata'], $c['data'], $c['template']);
	    			if ($c['parentid'] && in_array($c['parentid'], $pageid)) $data[] = array('pageid'=>$c['pageid'], 'parentid'=>$c['parentid'], 'name'=>$c['name'], 'allow'=>$c['allow']);
	    		}
	    		echo $this->json->encode($data);
	    	}
		}
		else 
		{
	    	$this->view->assign('department', table('department', $admin['departmentid'], 'name'));
	    	$this->view->assign('roleid', $roleid);
	    	$this->view->assign('role', table('role', $roleid, 'name'));
	    	
	    	if ($type == 'action')
	    	{
	    		$title = '操作权限：'.username($userid);
	    	}
	    	elseif ($type == 'category')
	    	{
	    		$title = '栏目权限：'.username($userid);
	    	}
	    	elseif ($type == 'page')
	    	{
	    		$title = '页面权限：'.username($userid);
	    	}
	    	$this->view->assign('head', array('title'=>$title));
	    	$this->view->display('administrator/priv/'.$type);
		}
	}

    /**
     * 栏目列表
     *
     * @aca public
     */
	function catetree()
	{
		$userid = $_GET['userid'];
		$catid = (int) $_GET['catid'];
		$type = $_GET['type'];	// 增加用户的时候列出所有的分类
		$admin = loader::model('admin/admin');
		$cate = loader::model('admin/category', 'system');
		$category = $cate->get_child($catid ? $catid : null);

		if ($userid && ($user = $admin->get($userid)) || $type == 'add')
		{
			!$type && priv::init($userid, $user['roleid']);
			foreach ($category as $k=>$c)
			{
				$category[$k]['hasChildren'] = !is_null($c['childids']);
				$category[$k]['checked'] = $type == 'add' ? 0 : priv::category($c['catid']) ? 1 : 0;
			}
		}

		exit($this->json->encode(array_values($category)));
	}

    /**
     * 页面列表
     *
     * @aca 浏览
     */
	function pagetree()
	{
		$userid = intval($_REQUEST['userid']);
		$admin = loader::model('admin/admin');
		$inited = 0;
		if ($userid && ($user = $admin->get($userid)))
		{
			$inited = 1;
			priv::init($userid, $user['roleid']);
		}
		$data = array();
		$page = loader::model('admin/page','page');
		$pageid = array();
		$pages = $page->select(array('status'=>1));
		foreach ($pages as &$c)
		{
			$d = array('pageid'=>$c['pageid'], 'parentid'=>$c['parentid'], 'name'=>$c['name']);
			if ($inited)
			{
				$d['checked'] = priv::page($c['pageid']) ? 1 : 0;
			}
			$data[] = $d;
			$pageid[] = $c['pageid'];
		}
		
		$section = loader::model('admin/section', 'page')->select(array('status'=>1));
		foreach ($section as &$c)
		{
			if ($c['status'] != 1 || !$c['pageid'] || !in_array($c['pageid'], $pageid))
			{
				continue;
			}
			$d = array(
				'parentid'=>$c['pageid'],
				'pageid'=>'100000'.$c['sectionid'],
				'name'=>$c['name']
			);
			if ($inited)
			{
				$d['checked'] = priv::section($c['sectionid']) ? 1 : 0;
			}
			$data[] = $d;
		}
		echo $this->json->encode($data);
	}

    /**
     * 克隆权限
     *
     * @aca 克隆权限
     */
	function clonepriv()
	{
		$srcuserid = intval($_REQUEST['srcuserid']);
		if ($this->is_post())
		{
			$page_priv = loader::model('admin/page_priv', 'page');
			$section_priv = loader::model('admin/section_priv', 'page');
			if (!($taruserid = intval($_POST['taruserid'])))
			{
				exit ('{"state":false,"error":"无赋值对象"}');
			}
			if ($taruserid == $srcuserid) {
				exit ('{"state":false,"error":"不可克隆给同一对象"}');
			}
			$db = factory::db();
            // 栏目权限
			$rowset = $db->select("SELECT * FROM #table_category_priv WHERE userid=$srcuserid");
			try {
				$db->delete("DELETE FROM #table_category_priv WHERE userid=$taruserid");
			} catch (Exception $e){}
			foreach ($rowset as $r)
			{
				try {
					$db->insert("INSERT INTO #table_category_priv (catid, userid) VALUES(?, ?)", array($r['catid'], $taruserid));
				} catch (Exception $e) {}
			}
            // 页面权限
			$rowset = $page_priv->select(array('userid'=>$srcuserid));
			try {
				$page_priv->delete(array('userid'=>$srcuserid));
			} catch (Exception $e){}
			foreach ($rowset as $r)
			{
				try {
					$page_priv->add($r['pageid'], $taruserid);
				} catch (Exception $e) {}
			}
            // 页面区块权限
            $rowset = $section_priv->select(array('userid'=>$srcuserid));
            try {
            	$section_priv->delete(array('userid'=>$srcuserid));
            } catch (Exception $e){}
            foreach ($rowset as $r)
            {
                try {
                	$section_priv->add($r['sectionid'], $taruserid);
                } catch (Exception $e) {}
            }
            // 管理员默认权重
			$r	= $db->select("SELECT weight FROM #table_admin_weight WHERE userid=$srcuserid LIMIT 1");
			try {
					$db->update("UPDATE #table_admin_weight SET weight=? WHERE userid=$taruserid", array($r[0]['weight']));
				} catch (Exception $e) {}
			$user = $db->get("SELECT roleid,departmentid FROM #table_admin WHERE userid=$srcuserid");
			if ($user)
			{
				try {
					$db->update("UPDATE #table_admin SET roleid=?, departmentid=? WHERE userid=$taruserid", array($user['roleid'],$user['departmentid']));
				} catch (Exception $e) {}
			}
            // 更新权限缓存
			priv::cache($taruserid, $user['roleid']);
			exit ('{"state":true}');
		}
		else
		{
			$this->view->assign('srcuserid', $srcuserid);
			$this->view->display('administrator/clonepriv');
		}
	}

    /**
     * 用户名搜索建议
     *
     * @aca 浏览
     */
	function suggest()
    {
    	$q = $_REQUEST['q'];
		$where = '';
		if (trim($q) != '')
		{
			$where = 'WHERE '.where_keywords('m.username', $q).' OR '.where_keywords('a.name', $q);
		}
    	$db = factory::db();
    	$sql = "SELECT a.userid, m.username FROM #table_admin a
    			LEFT JOIN #table_member m ON m.userid=a.userid $where";
    	if ($rowset = $db->limit($sql, 10))
        {
        	exit ($this->json->encode($rowset));
        }
        else
        {
        	exit ('[]');
        }
    }

    /**
     * 根据用户 ID 建议用户名
     *
     * @aca 浏览
     */
    function name()
    {
    	$userids = array_filter(array_map('intval', explode(',', $_GET['userid'])));
		if ($userids)
		{
    		$db = factory::db();
    		$sql = "SELECT userid, username FROM #table_member WHERE userid IN (".implode_ids($userids).")";
    		if ($rowset = $db->select($sql))
    		{
    			exit ($this->json->encode($rowset));
    		}
		}
		exit ('[]');
    }
	
	protected function error_out($msg)
    {
        if ($this->is_post())
        {
            exit($this->json->encode(array('state'=>false,'error'=>$msg)));
        }
        else
        {
            exit ($msg);
        }
    }
}