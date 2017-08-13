<?php
/**
 * 个人专栏
 *
 * @aca whole 个人专栏
 */
class controller_admin_index extends space_controller_abstract
{
	private $space, $pagesize = 15;
	
	public function __construct($app)
	{
		parent::__construct($app);
		$this->space = loader::model('space');
		$this->db=factory::db();
	}

    /**
     * 专栏管理
     */
	public function index()
	{
		$head = array('title'=>'专栏管理');
		$this->view->assign('head', $head);
		$this->view->assign('statuss', $this->space->statuss);
		$this->view->assign('types', $this->space->types);

		$this->view->display('index');
	}

    /**
     * 列表
     */
	public function page()
	{
		$where = null;
		$fields = '*';
		$order = isset($_GET['orderby']) ? str_replace('|', ' ', $_GET['orderby']) : '`sort` DESC';
		
		if (isset($_GET['keywords']) && $_GET['keywords']) $where[] = where_keywords('author', $_GET['keywords']);
		if (is_numeric($_GET['status']))
		{
			$status = intval($_GET['status']);
			$where[] = ($status == 3)?'`status`>=3':'`status`='.$status;
		}
		if ($where) $where = implode(' AND ', $where);
		$page = max((isset($_GET['page']) ? intval($_GET['page']) : 1), 1);
		$size = max((isset($_GET['pagesize']) ? intval($_GET['pagesize']) : $this->pagesize), 1);
		
		$data = $this->space->page($where, $fields, $order, $page, $size);
		$total = $this->space->count($where);
		
		$result = array('data'=>$data, 'total'=>$total);
		for($i=0;$i<count($result['data']);$i++)
		{
			
			if(isset($result['data'][$i]['typeid']))
			{
				$tid=$result['data'][$i]['typeid'];
				$typeid=$result['data'][$i]['typeid']-1;
				$typename=$this->space->types["$typeid"];
				$result['data'][$i]['typeid']=$typename;
				$result['data'][$i]['tid']=$tid;
			}
		}
		echo $this->json->encode($result);
	}

    /**
     * 搜索
     */
	public function search()
	{
		$this->view->display('search');
	}

    /**
     * 添加
     */
	public function add()
	{
		if ($this->is_post())
		{

			if($this->space->add($_POST))
			{
				$result = array('state' =>true,'message' => '添加成功');
			}
			else
			{
				$result = array('state' =>false,'error' => $this->space->error());
			}
			echo $this->json->encode($result);
		}
		else
		{
			$head = array('title'=>'添加专栏');


			$sub_type_list=$this->db->select("select * from #table_space_sub_type where status=1 order by sort asc");
			$this->view->assign("sub_type_list",$sub_type_list);
			$this->view->assign('statuss', $this->space->statuss);
			$this->view->assign('types', $this->space->types);
			$this->view->assign('head', $head);
			$this->view->display('add');
		}
	}

    /**
     * 编辑
     */
	public function edit()
	{
		$spaceid = intval($_GET['spaceid']);
		if ($this->is_post())
		{
			if($this->space->edit($_POST,$spaceid))
			{
				$data = $this->space->get($spaceid);
				$data['typeid'] = $this->space->types[($data['typeid']-1)];
				$result = array('state' =>true,'message' => '修改成功','data' => $data);
			}
			else
			{
				$result = array('state' =>false,'error' => $this->space->error());
			}

			echo $this->json->encode($result);
		}
		else
		{
			$sub_type_list=$this->db->select("select * from #table_space_sub_type where status=1 order by sort asc");
			$space = $this->space->get($spaceid);
			
			$typeid=$space['typeid'];	//专栏类型
			$types=$this->space->types;
			$space['typeid']= $this->space->types[($space['typeid']-1)];

			$head = array('title'=>'编辑专栏');
			$this->view->assign('statuss', $this->space->statuss);
			$this->view->assign('sub_type_list',$sub_type_list);	//得到所有的子分类
			$this->view->assign('sub_type',$space['sub_type']);	//子分类,只和机构研究类别进行关联。
			$this->view->assign('head', $head);
			$this->view->assign($space);
			$this->view->assign('typename',$typename);
			$this->view->assign('types',$types);
			$this->view->assign('typeid',$typeid);
			$this->view->display('edit');
		}
	}

    /**
     * 专栏面板
     */
	public function panel()
	{
		$spaceid = intval($_GET['spaceid']);
		$space = $this->space->get($spaceid);
		$head = array('title'=>$space['name'].'_专栏面板');
		$this->view->assign('statuss', $this->space->statuss);
		$this->view->assign('head', $head);
		$this->view->assign($space);
		$this->view->display('panel');
	}

    /**
     * 删除
     */
	public function delete()
	{
		if(empty($_POST['spaceid']))
		{
			$result = array('state'=>false, 'error'=>'用户ID为空');
		}
		else
		{
			$where = "`spaceid` IN ({$_POST['spaceid']})";
			if($this->space->delete($where))
			{
				$result = array('state'=>true, 'message'=>'删除成功');
			}
			else
			{
				$result = array('state'=>false, 'error'=>'发生错误');
			}
		}
		echo $this->json->encode($result);
	}

    /**
     * 专栏地址验证
     *
     * @aca public
     */
	public function validate()
	{
		$url = $_GET['url'];
		$userid = intval($_GET['userid']);
		$r = $this->space->get("`url`='".$url."'");
		if(!$r || $r['userid'] == $userid)
		{
			$return = array('state' => true, 'info' => '可以使用');
		}
		else
		{
			$return =  array('state' => false, 'error' => '已经注册');
		}
		echo $this->json->encode($return);
	}

    /**
     * 管理作者检查
     *
     * @aca public
     */
	function author_check()
	{
		$author = $_GET['author'];
		$r = $this->space->get_by('author',$author);
		if($r)
		{
			$result = array('state' => false,'error' => '已经存在');
		}
		else
		{
			$result = array('state' => true,'info' => '可以使用');
		}
		echo $this->json->encode($result);
	}

    /**
     * 别名检查
     *
     * @aca public
     */
	function alias_check()
	{
		$alias = $_GET['alias'];
		$r = $this->space->get_by('alias',$alias);
		if($r)
		{
			$result = array('state' => false,'error' => '已经存在');
		}
		else
		{
			$result = array('state' => true,'info' => '可以使用');
		}
		echo $this->json->encode($result);
	}

    /**
     * 启用禁用
     */
	function status()
	{
		$spaceid = $_POST['spaceid'];
		$status = intval($_POST['status']);
		$result = $this->space->status($spaceid,$status);
		echo $this->json->encode($result);
	}
	
    /**
     * 搜索建议
     *
     * @aca public
     */
	function suggest()
	{
		$q = $_REQUEST['q'];
		$where = '';
		if (trim($q) != '')
		{
			$q = str_replace('_', '\_', addcslashes($q, '%_'));
			$where = "`author` LIKE '%$q%'";
			if (preg_match("/^[\w]+$/", $q))
			{
				// 字母和数字(也搜索initial字段)
				$where .= " OR `initial` LIKE '$q%'";
			}
		}
		$data = $this->space->select($where, 'author', '`sort` DESC', 30);
		foreach ($data as & $r)
		{
			$r['text'] = $r['author'];
		}
		echo $this->json->encode($data);
	}
	
	/**
     * 关联用户搜索建议
     *
     * @aca public
     */
	function username()
	{
		$q = $_REQUEST['q'];
		$where = '';
		if (trim($q) != '')
		{
			$q = str_replace('_', '\_', addcslashes($q, '%_'));
			$where = "`username` LIKE '%$q%'";
		}
		
		$data = $this->space->username($where);
		foreach ($data as & $r)
		{
			$r['text'] = $r['username'];
		}
		echo $this->json->encode($data);
	}

	/*
	 * 子分类管理列表
	 *
	*/
	function type()
	{
			$this->view->display("sub_type_index");		
	}

	/*子分类管理列表,服务器端处理*/
	function space_sub_type()
	{
			$data=$this->db->select("select sid,name,sort,status from #table_space_sub_type");
			foreach($data as &$value)
			{
				if($value['status']==1){
					$value['status']="正常";
				}
				else
				{
					$value['status']="禁用";
				}
			}
			$result = array('data'=>$data);
			
			echo $this->json->encode($result);
	}


	/*添加咨询类别子分类*/
	function add_sub_type()
	{
		$this->view->display("add_sub_type");	
	}


	/*处理添加操作*/
	function check_add_sub_type()
	{
			/*在PDO的插入方式中,如果直接使用表单，在占位符这块需要使用 :变量 这种形式*/
			$name=$_POST['name'];

			/*插入前先执行查询，看子分类是否已经存在*/
			$sql1="select sid,name from #table_space_sub_type where name='{$name}'";

			$result1=$this->db->select($sql1);

			if($result1)
			{
				$result=array('state' =>false,'message' => '该子分类已经存在');
			}
			else
			{
			
					$sql2="insert into #table_space_sub_type (name,sort,status) values(:name,:sort,:status)";
					$jieguo=$this->db->insert($sql2,$_POST);
					
					if($jieguo)
					{
						$result = array('state' =>true,'message' => '添加子分类成功');
					}
					else
					{
						$result = array('state' =>false,'message' => '添加子分类失败');
					}
				
			}

			echo $this->json->encode($result);
	}

	/*编辑子分类*/
	function edit_sub_type()
	{
		$sid=$_GET['sid'];
		$sql="select * from #table_space_sub_type where sid={$sid}";
		$list=$this->db->get($sql);
		$this->view->assign("list",$list);
		$this->view->display("edit_sub_type");
	}

	/*处理编辑操作*/
	function check_edit_sub_type()
	{
			$model=loader::model("space_sub_type");
			$data=array('name'=>$_POST['name'],'sort'=>$_POST['sort'],'status'=>$_POST['status']);
			$where="sid=".$_POST['sid'];
			$jieguo=$model->update($data,$where);
			
			if($jieguo)
			{
				$result = array('state' =>true,'message' => '编辑子分类成功');
			}
			else
			{
				$result = array('state' =>false,'message' => '编辑子分类失败');
			}
			echo $this->json->encode($result);
	}


	function delete_sub_type()
	{
		$sid=$_POST['sid'];
		$sql="delete from #table_space_sub_type where sid=".$sid;	
		$result=$this->db->delete($sql);
		if($result){
			$data=array('state'=>true);
		}else{
			$data=array('state'=>false);
		}

		echo $this->json->encode($data);
	}





}