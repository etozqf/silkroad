<?php
/*
  *园区位置管理
  *author:yangya
*/
class controller_admin_position extends system_controller_abstract
{
	private $position, $pagesize = 15;

	public function __construct($app)
	{
		parent::__construct($app);
		$this->db=factory::db();
		$this->position = loader::model('admin/position');

		
	}

	/**
   * 浏览
   */
	public function index()
	{
    $this->view->assign('head', array('title'=>'园区位置管理'));
		$this->view->display("position/index");
	}

	/**
     * 搜索
     */
	function search()
	{
		$this->view->display('position/search');
	}

	/**
   * 列表
   *
   * @aca public
   */
	function page()
	{

		$where = null;
		if (!empty($_GET['name'])) $where = "name like '%{$_GET['name']}%'";
		$fields = '*';
		$order = '`createtime` DESC';
		$page = max((isset($_GET['page']) ? intval($_GET['page']) : 1), 1);
		$size = max((isset($_GET['size']) ? intval($_GET['size']) : $this->pagesize), 1);
		$total = $this->position->count($where);
		$data = $this->position->page($where, $fields, $order, $page, $size);
		foreach($data as $k=>$v){
			$data["$k"]["createtime"] = date('Y-m-d H:i',$v['createtime']);
		}
		echo $this->json->encode(array('total'=>$total,'data'=>$data));
	}

	 /**
     * 添加
     */
	function add()
	{
		if ($this->is_post())
		{
			if ($positionid = $this->position->add($_POST))
			{
				$data = $this->position->get($positionid);
				$data["createtime"] = date('Y-m-d H:i',$data['createtime']);
				$result = array('state'=>true, 'data'=>$data);
			}
			else
			{
				$result = array('state'=>false, 'error'=>$this->position->error());
			}
			echo $this->json->encode($result);
		}
		else
		{
			$this->view->display('position/add');
		}
	}
	
	/**
     * 编辑
     */
	function edit()
	{
		if ($this->is_post())
		{
			$positionid = $_POST['positionid'];
			
			if($this->position->edit($positionid, $_POST) !== false)
			{
				$data = $this->position->get($positionid);
				$data["createtime"] = date('Y-m-d H:i',$data['createtime']);
				$result = array('state'=>true, 'data'=>$data);
			}
			else
			{
				$result = array('state'=>false, 'error'=>$this->position->error());
			}
			
			echo $this->json->encode($result);
		}
		else
		{
			$positionid = $_GET['positionid'];
			$position = $this->position->get($positionid);
			$this->view->assign($position);
			$this->view->display('position/edit');
		}
	}

    /**
     * 删除
     */
	function delete()
	{
		$positionid = $_GET['id'];
		if($positionid)
		{
			$result = $this->position->delete($positionid) ? array('state'=>true) : array('state'=>false, 'error'=>$this->position->error());
		}
		else
		{
			$result = array('state'=>false, 'error'=>'ID不正确');
		}
		echo $this->json->encode($result);
	}

}
