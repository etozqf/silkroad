<?php
/**
 * 自定义属性
 *
 * @aca whole 自定义属性
 */
class controller_admin_property extends system_controller_abstract
{
	private $property;

	function __construct($app)
	{
		parent::__construct($app);
		$this->property = loader::model('admin/property');
	}

    /**
     * 属性设置
     */
	function index()
	{
		$property = table('property');
		
		$current_proid = isset($_REQUEST['proid']) && $_REQUEST['proid'] ? $_REQUEST['proid'] : array_shift(array_keys($property));
		$this->view->assign('current_proid', intval($current_proid));
		
		$this->view->assign('head', array('title'=>'属性设置'));
		$this->view->display('property/index');
	}

    /**
     * 属性列表
     *
     * @aca public
     */
	function cate()
	{
		$proid = intval($_GET['proid']);
		$dsnid = intval($_GET['dsnid']);
		if ($dsnid)
		{
			$dsn = loader::model('admin/dsn','system')->get($dsnid);
			if (!$dsn) {
				exit('[]');
			}
			try {
				$db = factory::db($dsn);
			} catch (PDOException $e) {
				exit('[]');
			}
			$where = $proid ? "parentid=$proid" : "parentid IS NULL";
			$property = $db->select("SELECT * FROM #table_property WHERE $where");
			foreach ($property as &$c)
			{
				$c['hasChildren'] = !is_null($c['childids']);
			}
		} else {
			$property = $this->property->get_child($proid ? $proid : null);
			foreach ($property as $k=>$c)
			{
				$property[$k]['hasChildren'] = !is_null($c['childids']);
				$property[$k]['url'] = $c['proid'];
			}
		}
		exit($this->json->encode(array_values($property)));
	}

    /**
     * 获取属性的父级元素
     */
	function path()
	{
		$proid = intval($_GET['proid']);
		$parentids = table('property', $proid, 'parentids');
		$path = $parentids ? $parentids.','.$proid : $proid;
		$path = explode(',', $path);
		echo $this->json->encode($path);
	}

    /**
     * 刷新
     */
	function reload()
	{
		$property = table('property');
		foreach ($property as $k=>$c)
		{
			$property[$k]['class'] = 'yes hand';
		}
		import('helper.treeview');
		$treeview = new treeview($property);
		echo $treeview->get(null, 'property_tree', '<li><span id="{$proid}" class="{$class}">{$name}</span>{$child}</li>');
	}

    /**
     * 添加
     */
	function add()
	{
		$parentid = $_REQUEST['parentid'];
		
		if ($this->is_post())
		{
			if ($proid = $this->property->add($_POST))
			{
				$cat = $this->property->get($proid,'parentids');
				$parentids = $cat['parentids'];
				$path = $parentids ? $parentids.','.$proid : $proid;
				$path = explode(',', $path);
				$result = array('state'=>true, 'proid'=>$proid, 'path'=>$path);
			}
			else
			{
				$result = array('state'=>false, 'error'=>$this->property->error());
			}
			echo $this->json->encode($result);
		}
		else
		{
			$parentid = intval($_GET['parentid']);
			if ($parentid)
			{
				$r = $this->property->get($parentid);
			}
			else 
			{
				$r = array();
				foreach (table('model') as $modelid=>$m)
				{
					$r['model'][$modelid] = array('name'=>$m['name'], 'show'=>1, 'template'=>$m['template_show']);
				}
				$r['path'] = '{PSN:1}';
				$r['template_index'] = 'system/property.html';
				$r['template_list'] = 'system/list.html';
				$r['template_date'] = 'system/date.html';
				$r['iscreateindex'] = 1;
				$r['urlrule_index'] = '{parentdir}/{alias}/index.shtml';
				$r['urlrule_list'] = '{parentdir}/{alias}/{page}.shtml';
				$r['urlrule_show'] = '{year}/{month}{day}/{contentid}{page}.shtml';
				$r['enablecontribute'] = 1;
			}

			$this->view->assign($r);
			$this->view->display('property/add');
		}
	}

    /**
     * 编辑
     */
	function edit()
	{
		$proid = $_REQUEST['proid'];

		if ($this->is_post())
		{
			if ($this->property->edit($proid, $_POST))
			{
				$result = array('state'=>true, 'proid'=>$proid, 'name'=>$_POST['name']);
			}
			else
			{
				$result = array('state'=>false, 'error'=>$this->property->error());
			}
			echo $this->json->encode($result);
		}
		else
		{
			$r = $this->property->get($proid);
			$r['modelids'] = array_keys($r['model']);
			$this->view->assign($r);
			$this->view->display('property/edit');
		}
	}

	/**
	 *自定义属性关联栏目
	**/
	function relation()
	{
		$proid=$_REQUEST['proid'];

		if($this->is_post())
		{
			$post=array();
			//将接收到的表单数据写入表cmstop_property_category
			//写入前整理数组，并做判断，如果属性记录不存在,就进行添加操作，如果已经有这条记录了，就进行更新操作。
			$proid=intval($_POST['proid']);
			$catid=$_POST['catid'];	
			
			$db=factory::db();
			
			$list=$db->select("select catid,proid from #table_property_category where proid={$proid}");
			if(empty($list))
			{

				//当记录不存在时，执行添加操作	
				foreach($catid as $v)
				{
					$result=$db->insert("insert into #table_property_category(catid,proid) values($v,$proid)"); 
				}
				
			}
			else
			{	
				
				//记录存在时，先删除，在执行添加操作。
				$jieguo=$db->delete("delete from #table_property_category where proid={$proid}");

				/*catid值为空时，代表提交的表单中，关联栏目全部取消，属于与栏目的关联全部都不存在*/
				$isnull=is_null($catid); 

				/*返回数据提示操作成功*/
				if($jieguo && $isnull)
				{
					$result=true;
				}

				if(!empty($jieguo))
				{
					 
					//循环遍历进行更新
					  foreach($catid as $v)
					  {
						$result=$db->insert("insert into #table_property_category(catid,proid) values($v,$proid)"); 
					  }	
					
				}
				
			}

			if(!empty($result)){
				$result=array('state'=>true);
			}else{
				$result=array('state'=>false,'error'=>'关联操作失败');
			}

			echo $this->json->encode($result);
		
		}
		else
		{
			$db=factory::db();
			//读取栏目表数据,首先获得所有一级频道
			$parent_list=$db->select("select catid,name from #table_category where parentids is null");
			
			//对栏目数组进行结构拼装
			$cate_list=$this->get_son_category($parent_list);
			$this->view->assign("cate_list",$cate_list);
			$this->view->display('property/relation');
		}
		
	}

	/*
      *递归操作，得到指定栏目其下的所有的三级子栏目
      * return:Array
	*/
	function get_son_category($parent_list)
	{
		$db=factory::db();
	    foreach($parent_list as $key=>$value)
	    {
	    	$list[$key]['catid']=$value['catid'];
	    	$list[$key]['name']=$value['name'];
	    	$second=$db->select("select catid,name from #table_category where parentid={$value['catid']}");
	    	if(!empty($second))
	    	{
	    			$list[$key]['second']=$second;
			    	if(!empty($list[$key]['second']))
			    	{
			    		foreach($list[$key]['second'] as $kk=>$val)
			    		{
			    				$result=$db->select("select catid,name from #table_category where parentid={$val['catid']}");
			    				if(!empty($result)){
			    					$list[$key]['second'][$kk]['three']=$result;	
			    				}	
			    		}
			    	}
	    			
	    	}
	
	    }
	    return $list;
	}

	

    /**
     * 删除
     */
	function delete()
	{
		$proid = $_REQUEST['proid'];
		$result = $this->property->delete($proid) ? array('state'=>true) : array('state'=>false, 'error'=>$this->property->error());
		echo $this->json->encode($result);
	}

    /**
     * 移动
     */
	function move()
	{
		$proid = $_REQUEST['proid'];
		if ($this->is_post())
		{
            $parentid = $_REQUEST['parentid'];
			$result = $this->property->move($proid, $parentid);
			$result = $result ? array('state'=>true, 'proid'=>$proid) : array('state'=>false, 'error'=>$this->property->error());
			echo $this->json->encode($result);
		}
		else 
		{
			$property = table('property');
			$childids = $property[$proid]['childids'];
			foreach ($property as $k=>$c)
			{
				if ($property[$proid]['parentid'] && $property[$proid]['parentid'] == $c['proid'])
				{
					$property[$k]['radio'] = '';
				}
				else 
				{
					$property[$k]['radio'] = '<input type="radio" name="parentid" value="'.$c['proid'].'" class="radio_style" />';
				}
				if ($k == $proid || strpos(','.$childids.',', ','.$k.',') !== false) unset($property[$k]);
			}
			import('helper.treeview');
			$treeview = new treeview($property);
			$data = $treeview->get(null, 'property_move', '<li><span id="{$proid}"><label>{$radio}{$name}</label></span>{$child}</li>');
			$this->view->assign('data', $data);
			$this->view->display('property/move');
		}
	}

    /**
     * 修复
     */
	function repair()
	{
		$result = $this->property->repair() ? array('state'=>true) : array('state'=>false, 'error'=>$this->property->error());
		echo $this->json->encode($result);
	}

    /**
     * 获取下拉列表中的属性数据
     */
	function dropcate()
	{
		if(!$pricate = $this->privar('regcate'))
		{
			$property = table('property');
			$pricate = array();
			$i = 0;
			$proid = null;
			foreach ($property as $k=>$c)
			{
				$names = '';
				if (is_null($c['childids']))
				{
					$proid = $c['proid'];
					while ($proid = $property[$proid]['parentid']) 
					{
						$names .= $property[$proid]['name'].' > ';
					}
					$pricate[$i++] = array('proid'=>$c['proid'],'name'=>$names.$c['name']);
				}
				if($i == 10) break;
			}
			$this->privar('regcate',$pricate);
		}
		echo $this->json->encode($pricate);
	}

    /**
     * 获取指定属性ID的属性名称
     *
     * @aca public
     */
	function name()
	{
		$proids = array_filter(array_map('intval', explode(',', $_GET['proid'])));
		if ($proids) 
		{
			echo $this->json->encode($this->property->select('proid IN ('.implode_ids($proids).')', 'proid, name'));
		}
	}

    /**
     * 搜索属性
     */
	function search()
	{
		$cats = $this->property->catesearch($_GET['keyword']);
		$property = table('property');
		foreach ($cats as & $c)
		{
			$names = '';
			$proid = $c['proid'];
			while ($proid = $property[$proid]['parentid'])
			{
				$names .= $property[$proid]['name'].' > ';
			}
			$c['name'] = $names.$c['name'];
		}
		echo $this->json->encode($cats);
	}

    /**
     * 更新或存储最近使用的属性
     */
	function note()
	{
		$pricate = $this->privar('regcate');
		$proid = $_GET['proid'];
		$name = $_GET['name'];
		$keyexist = false;
		foreach($pricate as $k=> $v)
		{
			if($proid == $v['proid'])
			{
				$keyexist = true;
				unset($pricate[$k]);
				array_unshift($pricate,$v);
				break;
			}
		}
		if(!$keyexist)
		{
			array_unshift($pricate,array('proid'=>$proid,'name'=>$name));
			array_pop($pricate);
		}
		$this->privar('regcate',$pricate);
	}
}