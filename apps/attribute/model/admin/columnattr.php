<?php

class model_admin_columnattr extends model
{
	private $columnattr;

	private $tree;
	function  __construct()
	{
		parent::__construct();
		
		$this->_table = $this->db->options['prefix'].'columnattr';
		$this->_primary = 'columnattrid';
		$this->_fileds = array('columnattrid','parentid','name','alias','sort','description','parentids','childids');
		$this->_readonly = array('columnattrid','parentids','childids');
		$this->_create_autofill = array();
		$this->_update_autofill = array();
		$this->_filters_input = array();
		$this->_filters_output = array();
		$this->_validators = array();

		import('helper.tree');
		$this->tree = new tree('#table_columnattr','columnattrid');

		//查询到表的数据数组形式返回,下标为每一条数据的id值
		$this->columnattr = table('columnattr');
	}


	/*
	*查询数据信息
	*
	*@param $id 查询的表的id值
	*@param $fields 查询的字段,默认为所有字段;
	*@return 查询的数据结果
	*/
	function get($id, $fields = '*')
	{
		return $this->tree->get($id, $fields);
	}

	/*
	*添加一条新的数据
	*
	*@param $data 添加的数据,数组形式
	*/
	function add($data)
	{
		$data = $this->filter_array($data,array('parentid','name','alias','description','parentids','childids','sort'));
		$result = $this->tree->set($data);
		$this->update_cache();
		return $result;
	}

	/*
	*编辑分类的信息
	*/
	function edit($id, $data)
	{
		if (!$this->tree->exists($id))
		{
			$this->error = '属性不存在';
			return false;
		}
		$data['columnattrid'] = $id;
		$data = $this->filter_array($data, array('columnattrid', 'parentid', 'name','alias', 'description', 'parentids', 'childids', 'sort'));
		$result = $this->tree->set($data);
		$this->update_cache();
		return $result;
	}

	/*
	*删除分类信息
	*/
	function delete($id)
	{
		$result = is_numeric($id) ? $this->tree->rm($id) : array_map(array($this->tree, 'rm'), explode(',', $id));
		$this->update_cache();
		return $result;
	}

	/*
	*移动分类
	*/
	function move($id, $parentid)
	{
		if (!$this->tree->exists($id))
		{
			$this->error = '属性不存在';
			return false;
		}
		$result = $this->tree->set(array('columnattrid'=>$id, 'parentid'=>$parentid));
		if (!$result)
		{
			$this->error = '不能移动属性到子属性下';
			return false;
		}
		$this->update_cache();
		return true;
	}

	//分级
	function selectclass($id=null){
		$child = $this->get_child($id);
		foreach ($child as $k => $v) {
			$data[$k]=$v;
			if(!empty($v['childids'])){
				$data[$k]['childdata'] = $this->selectclass($v['columnattrid']);
			}
		}
		return $data;
	}
	/*
	*@$id  要查询parentid为$id 的数据信息
	*/
	function get_child($id = null)
	{
		return $this->tree->get_child($id, '`columnattrid`,`parentids`,`childids`,`name`,`alias`,`sort`');
	}
	
	/*
	*分类修复功能
	*获取 当前表数据的所有ID的集合 在获取 父ID的集合 子ID的集合 
	*并通过循环修改当前表数据的所有parentids childids 字段 数据信息 
	*/
	function repair()
	{
		@set_time_limit(600);
		if(is_array($this->columnattr))
		{
			$data = array();
			$ids = array_keys($this->columnattr);
			foreach($ids as $id)
			{
				if($id == 0) continue;
				$parentids[$id] = $this->get_parentids($id);
			}
			
			foreach($ids as $id)
			{
				if($id == 0) continue;
				$childids = $this->get_childids($id);
				$this->update(array('parentids'=>$parentids[$id], 'childids'=>$childids), $id);
			}
		}
		$this->update_cache();
		return true;
	}
	
	function get_parentids($id, $parentids = '', $n = 1)
	{
		if($n > 5 || !is_array($this->columnattr) || !isset($this->columnattr[$id])) return false;
		$parentid = $this->columnattr[$id]['parentid'];
		if($parentid)
		{
			$parentids = $parentids ? $parentid.','.$parentids : $parentid;
			$parentids = $this->get_parentids($parentid, $parentids, ++$n);
		}
		else
		{
			if (!$parentids) $parentids = null;
			$this->columnattr[$id]['parentids'] = $parentids;
		}
		return $parentids;
	}
	
	function get_childids($id)
	{
		$childids = array();
		if(is_array($this->columnattr))
		{
			foreach($this->columnattr as $id => $cat)
			{
				if($cat['parentid'] && $id != $id)
				{
					$parentids = explode(',', $cat['parentids']);
					if(in_array($id, $parentids)) $childids[] = $id;
				}
			}
		}
		$childids = implode(',', $childids);
		if (!$childids) $childids = null;
		return $childids;
	}

	/*
	*将整张表写入缓存
	*/
	function update_cache()
	{
		table_cache('columnattr');
	}
}