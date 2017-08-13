<?php
/**
 * 国别报告
 * 
 * @author  houxiaowen@cmstop.com
 */
class controller_list extends country_controller_abstract
{
	private $country, $cache, $property;
	public $config;

	function __construct($app)
	{
		parent::__construct($app);
		$this->config = loader::import('config.country');
        $this->country = loader::model('country');
        $this->property = loader::model('admin/property', 'system');
        $this->cache = factory::cache();

	}

	public function index()
	{
		if (!$this->_userid) {
            $this->redirect(WWW_URL);
            exit;
        }
		$data = array(
			'title'    => '我生成报告',
            'pagesize' => 5,
            'subTpl'   => 'index',
			'status'   => 0
		);
		$this->template->assign('jianjie', $jianjie);
		$this->template->assign($data);
    	$this->template->display('country/panel/list.html');
	}

	function recycle()
	{
		if (!$this->_userid) {
            $this->redirect(WWW_URL);
            exit;
        }
		$data = array(
			'title'    => '报告回收站',
			'pagesize' => 5,
			'subTpl'   => 'recycle',
			'status'   => 1
		);
		$this->template->assign($data);
		$this->template->display('country/panel/list.html');
	}

	function page()
	{
		// 强制服务端刷新
		header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");
		$where = array();
		$where['status'] = isset($_GET['status']) ? intval($_GET['status']) : 1;
		$where['uid'] = $this->_userid;
		$where['choren'] = 1;
		$page = isset($_GET['page'])?max(intval($_GET['page']),1):1;
		$pagesize = isset($_GET['pagesize']) ? intval($_GET['pagesize']) : setting('contribution', 'pagesize');
		$pagesize = empty($pagesize) ? 10 : $pagesize;
		$fields = '*';
		$order = '`pubtime` DESC';
		if($this->_userid)
		{
			$data = $this->country->ls($where, $fields, $order, $page, $pagesize);
		}
		array_walk($data['data'], array($this, 'ls_after'));
		echo $this->json->encode($data);
	}

	function del()
	{
		$where['cid'] = intval($_GET['cid']);
		$where['uid'] = $this->_userid;
		$status['status'] = 1;
		$res = $this->country->update($status, $where);		
		$data = array(
			'state' => $res,
		);
		echo $this->json->encode($data);
	}

	function back()
	{
		$where['cid'] = intval($_GET['cid']);
		$where['uid'] = $this->_userid;
		$status['status'] = 0;
		$res = $this->country->update($status, $where);		
		$data = array(
			'state' => $res,
		);
		echo $this->json->encode($data);
	}

	function ls_after(&$data)
	{
		$data['pubtime'] = date('Y-m-d H:i:s',$data['pubtime']);
	}
	

}


