<?php
/*
  *园区类别管理
  *author:yangya
*/
class controller_admin_yqcategory extends system_controller_abstract
{
	private $pagesize = 15;

	public function __construct($app)
	{
		parent::__construct($app);
		$this->db=factory::db();
		$this->yqcategory = loader::model('admin/yqcategory');

		
	}

	/**
   * 浏览
   */
	public function index()
	{
    $this->view->assign('head', array('title'=>'园区分类管理'));
		$this->view->display("yqcategory/index");
	}

	/**
     * 搜索
     */
	function search()
	{
		$this->view->display('yqcategory/search');
	}

	/**
   * 列表
   *
   * @aca public
   */
	function page()
	{

		$where = null;
		if (!empty($_GET['name'])) $where = "name='{$_GET['name']}'";
		$fields = '*';
		$order = '`sort` DESC';
		$page = max((isset($_GET['page']) ? intval($_GET['page']) : 1), 1);
		$size = max((isset($_GET['size']) ? intval($_GET['size']) : $this->pagesize), 1);
		$total = $this->yqcategory->count($where);
		$data = $this->yqcategory->page($where, $fields, $order, $page, $size);
		foreach($data as $k=>$v){
			$data["$k"]["createtime"] = date('Y-m-d H:i',$v['createtime']);
			$data["$k"]["category"] = $v['category']==1 ? '国内' : '世界';
		}
		echo $this->json->encode(array('total'=>$total[0]['total'],'data'=>$data));
	}

	 /**
     * 添加
     */
	function add()
	{
		if ($this->is_post())
		{
			if ($yqcategoryid = $this->yqcategory->add($_POST))
			{
				$data = $this->yqcategory->get($yqcategoryid);
				$data["createtime"] = date('Y-m-d H:i',$data['createtime']);
				$data["category"] = $data['category']==1 ? '国内' : '世界';
				$result = array('state'=>true, 'data'=>$data);
			}
			else
			{
				$result = array('state'=>false, 'error'=>$this->yqcategory->error());
			}
			echo $this->json->encode($result);
		}
		else
		{
			$this->view->display('yqcategory/add');
		}
	}
	
	/**
     * 编辑
     */
	function edit()
	{
		if ($this->is_post())
		{
			$yqcategoryid = $_POST['cateid'];
			
			if($this->yqcategory->edit($yqcategoryid, $_POST) !== false)
			{
				$data = $this->yqcategory->get($yqcategoryid);
				$data["createtime"] = date('Y-m-d H:i',$data['createtime']);
				$data["category"] = $data['category']==1 ? '国内' : '世界';
				$result = array('state'=>true, 'data'=>$data);
			}
			else
			{
				$result = array('state'=>false, 'error'=>$this->yqcategory->error());
			}
			
			echo $this->json->encode($result);
		}
		else
		{
			$yqcategoryid = $_GET['yqcategoryid'];
			$yqcategory = $this->yqcategory->get($yqcategoryid);
			$this->view->assign($yqcategory);
			$this->view->display('yqcategory/edit');
		}
	}

    /**
     * 删除
     */
	function delete()
	{
		$yqcategoryid = $_GET['id'];
		if($yqcategoryid)
		{
			$result = $this->yqcategory->delete($yqcategoryid) ? array('state'=>true) : array('state'=>false, 'error'=>$this->yqcategory->error());
		}
		else
		{
			$result = array('state'=>false, 'error'=>'ID不正确');
		}
		echo $this->json->encode($result);
	}

}
