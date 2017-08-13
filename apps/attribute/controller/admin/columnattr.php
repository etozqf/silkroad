<?php 
/*
*地区属性管理
*
*@book 地区属性管理
*/
class controller_admin_columnattr extends attribute_controller_abstract
{
	private $columnattr;

	function __construct($app)
	{
		parent::__construct($app);
		$this->columnattr = loader::model('admin/columnattr');
	}

	/*
	*地区属性管理
	*
	*@book 分类管理s
	*/
	function index()
	{
		$columnattr = table('columnattr');
		// 获取columnattr表中的第一条数据的id值
		$current_id = isset($_REQUEST['id']) && $_REQUEST['id'] ? $_REQUEST['id'] : array_shift(array_keys($columnattr));
		$this->view->assign('current_id',intval($current_id));
		$this->view->assign('head',array('title'=>'地区属性管理'));
		$this->view->display('columnattr/index');
	}

	/**
     * 遍历分类页面左边列表
     *
     * @aca public
     */
	function cate()
	{
		$id = intval($_GET['id']);
		$columnattr = $this->columnattr->get_child($id ? $id : null);
		foreach ($columnattr as $k=>$c)
		{
			$columnattr[$k]['hasChildren'] = !is_null($c['childids']);
			$columnattr[$k]['url'] = $c['id'];
		}
		exit($this->json->encode(array_values($columnattr)));
	}

	/*
	*添加一个分类
	*/
	function add()
	{
		$parentid = $_REQUEST['parentid'];

		if ($this->is_post())
		{
			if($id = $this->columnattr->add($_POST))
			{
				$cat = $this->columnattr->get($id,'parentids');
				$parentids = $cat['parentids'];
				$path = $parentids ? $parentids.','.$id : $id;
				$path = explode(',', $path);
				$result = array('state'=>true,'id'=>$id,'path'=>$path);
			}
			else
			{
				$result = array('state'=>false,'error'=>$this->columnattr->error());
			}
			echo $this->json->encode($result);
		}
		else
		{
			$parentid = intval($_GET['parentid']);
			if ($parentid)
			{
				// 获取columnattr表中查询到的id为$parentid的数据信息
				$r = $this->columnattr->get($parentid);
			}

			$this->view->assign($r);
			$this->view->display('columnattr/add');
		}
	}

	/**
     * 编辑分类信息
     */
	function edit()
	{
		$id = $_REQUEST['id'];

		if ($this->is_post())
		{
			if ($this->columnattr->edit($id, $_POST))
			{
				$result = array('state'=>true, 'id'=>$id, 'name'=>$_POST['name']);
			}
			else
			{
				$result = array('state'=>false, 'error'=>$this->columnattr->error());
			}
			echo $this->json->encode($result);
		}
		else
		{
			$r = $this->columnattr->get($id);
			$r['modelids'] = array_keys($r['model']);
			$this->view->assign($r);
			$this->view->display('columnattr/edit');
		}
	}

	/**
     * 移动
     */
	function move()
	{
		$id = $_REQUEST['id'];
		if ($this->is_post())
		{

            $parentid = $_REQUEST['parentid'];
			$result = $this->columnattr->move($id, $parentid);
			$result = $result ? array('state'=>true, 'columnattrid'=>$id) : array('state'=>false, 'error'=>$this->columnattr->error());
			echo $this->json->encode($result);
		}
		else 
		{
			$columnattr = table('columnattr');
			$childids = $columnattr[$id]['childids'];
			// 循环当前数据表的每一条数据
			foreach ($columnattr as $k=>$c)
			{
				//若当前的数据中存在父ID且 当前数据的父类也存在
				if ($columnattr[$id]['parentid'] && $columnattr[$id]['parentid'] == $c['columnattrid'])
				{
					$columnattr[$k]['radio'] = '';
				}
				else 
				{
					$columnattr[$k]['radio'] = '<input type="radio" name="parentid" value="'.$c['columnattrid'].'" class="radio_style" />';
				}
				if ($k == $id || strpos(','.$childids.',', ','.$k.',') !== false) unset($columnattr[$k]);
			}

			import('helper.treeview');
			$treeview = new treeview($columnattr);
			$data = $treeview->get(null, 'columnattr_move', '<li><span id="{$columnattrid}"><label>{$radio}{$name}</label></span>{$child}</li>');
			$this->view->assign('data', $data);
			$this->view->display('columnattr/move');
		}
	}

	 /*
     * 删除一个分类
     */
	function delete()
	{
		$id = $_REQUEST['id'];
		$result = $this->columnattr->delete($id) ? array('state'=>true) : array('state'=>false, 'error'=>$this->columnattr->error());
		echo $this->json->encode($result);
	}

	/*
	*分类修复功能
	*/
	function repair()
	{
		$result = $this->columnattr->repair() ? array('state'=>true) : array('state'=>false, 'error'=>$this->columnattr->error());
		echo $this->json->encode($result);
	}


	 /**
     * 获取属性的父级元素
     */
	function path()
	{
		$id = intval($_GET['id']);
		$parentids = table('columnattr', $id, 'parentids');
		$child = table('columnattr',$id);
		$path = $parentids ? $parentids.','.$id : $id;
		$path = explode(',', $path);
		echo $this->json->encode($path);
	}
}