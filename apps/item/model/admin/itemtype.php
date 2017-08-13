<?php

class model_admin_itemtype extends model
{
	private $itemtype;

	private $tree;
	function  __construct()
	{
		parent::__construct();
		
		$this->_table = $this->db->options['prefix'].'item_type';
		$this->_primary = 'itemtypeid';
		$this->_fields = array('itemtypeid','contentid', 'type','typeid');
		$this->readonly = array('itemtypeid');
		$this->_create_autofill = array();
		$this->_update_autofill = array();
		$this->_filters_input = array();
		$this->_filters_output = array();
		$this->_validators = array();

		//查询到表的数据数组形式返回,下标为每一条数据的id值
		$this->itemtype = table('item_type');
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

	function ls($where = null, $fields = '*',$order='typeid asc')
	{
		$this->where = $where;
		$this->fields = $fields;
		$this->data = $this->select($this->where, $this->fields,$order);
		return $this->data;
	}

	/*
	*添加一条新的数据
	*
	*@param $data 添加的数据,数组形式
	*/
	function add($data)
	{
		$data = $this->filter_array($data,array('itemtypeid','contentid', 'type','typeid'));
		$result = $this->insert($data);
		return $result;
	}

	/*
	*编辑分类的信息
	*/
	function edit($id, $data)
	{
		$data['contentid'] = $id;
		$data = $this->filter_array($data, array('contentid','type','typeid'));
		$result = $this->update($data,$data['contentid']);
		return $result;
	}

	/*
	*删除分类信息
	*/
	function del($id)
	{
		$result = $this->delete($id);
		return $result;
	}
}