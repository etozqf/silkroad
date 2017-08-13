<?php
class controller_admin_payview_category extends payview_controller_abstract
{
	private $category, $pagesize = 15;

	function __construct(&$app)
	{
		parent::__construct($app);
		$this->payview_category = loader::model('admin/payview_category');
		
		import('helper.tree');
		$this->tree = new tree('#table_category', 'catid');
		
		$this->category = table('category');
	}

	function index()
	{
		$this->pagesize = $this->setting['pagesize'] ? $this->setting['pagesize'] : $this->pagesize;
		$this->view->assign('head', array('title'=>'订阅栏目组'));
		$this->view->assign('pagesize', $this->pagesize);
		$this->view->display("category_index");
	}

	function page()
	{
		$rwkeyword = trim($_GET['rwkeyword']);
		// where 条件
		if (isset($rwkeyword) && $rwkeyword)
		{
			$where = where_keywords('title', $rwkeyword);
		}
		$order = isset($_GET['orderby']) ? str_replace('|', ' ', $_GET['orderby']) : '`pvcid` DESC';
		$page = max((isset($_GET['page']) ? intval($_GET['page']) : 1), 1);
		$size = max((isset($_GET['pagesize']) ? intval($_GET['pagesize']) : $this->pagesize), 1);
		$data = $this->payview_category->ls($where, '*', $order, $page, $size);
		$total = $this->payview_category->total();
		echo $this->json->encode(array('data' =>$data, 'total' => $total));
	}
	
	public function add()
	{
		if($this->is_post())
		{
			if(!$_POST['catid'])
			{
				exit($this->json->encode(array('state'=>false, 'error' => '请选择订阅栏目')));
			}
			if($pvcid = $this->payview_category->add($_POST))
			{
				$data = $this->payview_category->get($pvcid);
				$result = array('state' => true, 'data' => $data);
			}
			else 
			{
				$result = array('state' => false, 'error' => $this->payview_category->error);
			}
			exit($this->json->encode($result));
		}
		else 
		{
			$head['title'] = "添加栏目组";
			
			$this->view->assign('head', $head);
			$this->view->assign('setting', $this->setting);
			$this->view->display('category_add');
		}
	}
	
	public function edit()
	{
		$pvcid = id_format($_REQUEST['pvcid']);
		if($this->is_post())
		{
			if($this->payview_category->edit($pvcid, $_POST))
			{
				$data = $this->payview_category->get($pvcid);
				$result = array('state' => true, 'data' => $data);
			}
			else 
			{
				$result = array('state' => false, 'error' => $this->payview_category->error);
			}
			exit($this->json->encode($result));
		}
		else 
		{
			$data = $this->payview_category->get($pvcid);
			if(empty($data))
			{
				exit($this->json->encode($result = array('state' => false, 'error' => '无此数据')));
			}
			$head['title'] = "编辑栏目组";
			$this->view->assign('data',$data);
			$this->view->assign('head', $head);
			$this->view->assign('setting', $this->setting);
			$this->view->display('category_edit');
		}
	}
	
	public function disabled()
	{
		$pvcid = id_format($_REQUEST['pvcid']);
		$disabled = intval($_REQUEST['disabled']);
		if($this->payview_category->disabled($pvcid,$disabled))
		{
			$result = array('state' => true, 'data' => '禁用成功');
		}
		else 
		{
			$result = array('state' => false, 'error' => $this->payview_category->error);
		}
		exit($this->json->encode($result));
	}
	
	public function delete()
	{
		$pvcid = $_REQUEST['pvcid'];
		if($this->payview_category->delete($pvcid))
		{
			$result = array('state' => true, 'data' => '删除成功');
		}
		else 
		{
			$result = array('state' => false, 'error' => $this->payview_category->error);
		}
		exit($this->json->encode($result));
	}

    /**
     * 栏目列表
     *
     * @aca public 浏览
     */
	function cate()
	{
		$catid = intval($_GET['catid']);
		$parentid = intval($_GET['parentid']);
		$category = $this->get_child($catid ? $catid : $parentid);
		foreach ($category as $k=>$c)
		{
			$category[$k]['hasChildren'] = !is_null($c['childids']);
			$category[$k]['url'] = $c['catid'];
			
		}
		exit($this->json->encode(array_values($category)));
	}
	
	function get_child($catid = null)
	{
		return $this->tree->get_child($catid, '`catid`,`parentids`,`childids`,`name`,`path`,`sort`,`url`');
	}
}