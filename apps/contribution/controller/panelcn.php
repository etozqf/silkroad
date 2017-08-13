<?php
class controller_panelcn extends contribution_controller_abstract
{
	private $pagesize = 5;
	function __construct($app)
	{
		parent::__construct($app);
		if (!$this->_userid)
		{
			$this->redirect(url('member/index/login_cn'));exit;
		}
		$this->space =loader::model('space','space');
		$this->contribution = loader::model('contribution');
		$space_on = loader::model('admin/app','system')->get_field('disabled',"`app`='space'");
		$this->space_exist = $this->space->get_by('userid',$this->userid);
		$this->template->assign('space_exist', $this->space_exist);
		$this->template->assign('space_on', $space_on);
	}

	function index()
	{
		$this->news();
	}

	/*
	**ajax查询各个收藏的总数
	*/
	function ajax_total()
	{
		$db = & factory::db();
		$memberid = empty($_GET['memberid']) ? $this->_userid : intval($_GET['memberid']);
		
		
		
		$total2=$db->select("select count(collectid) total from cmstop_collect where typeid=4 and memberid=$memberid and status=2");
		$states['project'] = $total2[0];

		$total3=$db->select("select count(collectid) total from cmstop_collect where typeid=5 and memberid=$memberid and status=2");
		$states['voide'] = $total3[0];

		$total4=$db->select("select count(collectid) total from cmstop_collect where typeid=6 and memberid=$memberid and status=2");
		$states['law'] = $total4[0];

		$data = $this->json->encode($states);
		$data = (isset($_GET['jsoncallback']))? $_GET['jsoncallback']."($data);": $data;
		echo $data;
	}

	function news()
	{
		$data = array(
			'title' => 'News Collection',
			'subTpl' => 'news',
			'status' => 6
		);
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$memberid = $this->userid ;
		$db = & factory::db();
		$total=$db->select("select count(collectid) total from cmstop_collect where typeid=0 and memberid=$memberid and status=2");
		$states = $total[0];
		
		$offset = ($page-1)*$this->pagesize;
	

		$sql = "select * from cmstop_collect where typeid=0 and memberid=$memberid and status=2 order by addtime desc limit $offset,$this->pagesize";
		$result = $db->select($sql);
		
		$this->template->assign('page', $page);
		$this->template->assign('totalnews', $states);
		$this->template->assign('pagesize', $this->pagesize);
		$this->template->assign($total[0]);
		$this->template->assign('result',$result);
		$this->template->assign($data);
		$this->template->display('cn/contribution/panel/list.html');
	}

	function project()
	{
		$data = array (
			'title' => 'Project Collection',
			'subTpl' => 'project',
			'status' => 3
		);
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$memberid = empty($_GET['memberid']) ? $this->_userid : intval($_GET['memberid']) ;
		
		$db = & factory::db();
		$total=$db->select("select count(collectid) total from cmstop_collect where typeid=4 and memberid=$memberid and status=2");
		$states = $total[0];
		
		$offset = ($page-1)*$this->pagesize;
	

		$sql = "select * from cmstop_collect where typeid=4 and memberid=$memberid and status=2 order by addtime desc limit $offset,$this->pagesize";
		$result = $db->select($sql);
		
		$this->template->assign('page', $page);
		$this->template->assign('totalproject', $states);
		$this->template->assign('pagesize', $this->pagesize);
		$this->template->assign($total[0]);
		$this->template->assign('result',$result);
		$this->template->assign($data);
		$this->template->display('cn/contribution/panel/list.html');
	}
	
	function voide()
	{
		$data = array (
			'title' => 'Voide Collection',
			'subTpl' => 'voide',
			'status' => 3
		);
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$memberid = empty($_GET['memberid']) ? $this->_userid : intval($_GET['memberid']) ;
		
		$db = & factory::db();
		$total=$db->select("select count(collectid) total from cmstop_collect where typeid=5 and memberid=$memberid and status=2");
		$states = $total[0];
		
		$offset = ($page-1)*$this->pagesize;
	

		$sql = "select * from cmstop_collect where typeid=5 and memberid=$memberid and status=2 order by addtime desc limit $offset,$this->pagesize";
		$result = $db->select($sql);
		
		$this->template->assign('page', $page);
		$this->template->assign('totalvoide', $states);
		$this->template->assign('pagesize', $this->pagesize);
		$this->template->assign($total[0]);
		$this->template->assign('result',$result);
		$this->template->assign($data);
		$this->template->display('cn/contribution/panel/list.html');
	}

	function law()
	{
		$data = array (
			'title' => 'Law Collection',
			'subTpl' => 'law',
			'status' => 3
		);
		$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
		$memberid = empty($_GET['memberid']) ? $this->_userid : intval($_GET['memberid']) ;
		
		$db = & factory::db();
		$total=$db->select("select count(collectid) total from cmstop_collect where typeid=7 and memberid=$memberid and status=2");
		$states = $total[0];
		
		$offset = ($page-1)*$this->pagesize;

		$sql = "select * from cmstop_collect where typeid=7 and memberid=$memberid and status=2 order by addtime desc limit $offset,$this->pagesize";
		$result = $db->select($sql);
		
		$this->template->assign('page', $page);
		$this->template->assign('totallaw', $states);
		$this->template->assign('pagesize', $this->pagesize);
		$this->template->assign($total[0]);
		$this->template->assign('result',$result);
		$this->template->assign($data);
		$this->template->display('cn/contribution/panel/list.html');
	}

	
	
	function page()
	{
		// 强制服务端刷新
		header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");
		$where = array();
		$where['status'] = isset($_GET['status']) ? intval($_GET['status']) : 6;
		$where['createdby'] = $this->userid;
		$page = isset($_GET['page'])?max(intval($_GET['page']),1):1;
		$pagesize = isset($_GET['pagesize']) ? intval($_GET['pagesize']) : setting('contribution', 'pagesize');
		$pagesize = empty($pagesize) ? 10 : $pagesize;
		$fields = '*';
		$order = '`created` DESC';
		
		if($where['status'] == 6)
		{
			$data = $this->contribution->get_publish($where, $fields, $order, $page, $pagesize);
		}
		else
		{
			$data = $this->contribution->ls($where, $fields, $order, $page, $pagesize);
		}
		
		$result = array(
			'total'=>$this->contribution->count($where),
			'data'=>$data,
			'num' => $this->contribution->get_person_count($this->userid));
		echo $this->json->encode($result);
	}

	private function _strip_tags($content)
	{
		$allows = '<a><b><br><div><em><font><h1><h2><h3><h4><h5><h6><hr><i><img><li><strong><table><tbody><td><th><thead><tr><u><ul><p>';
		return strip_tags($content, $allows);
	}
}