<?php

class model_admin_property extends model
{
	private $tree, $property;

	function __construct()
	{
		parent::__construct();
		$this->_table = $this->db->options['prefix'].'property';
		$this->_primary = 'proid';
		$this->_fields = array('proid', 'parentid', 'name','alias', 'description', 'parentids', 'childids', 'sort', 'disabled', 'ischarge');
		$this->_readonly = array('proid', 'parentids', 'childids');
		$this->_create_autofill = array();
		$this->_update_autofill = array();
		$this->_filters_input = array();
		$this->_filters_output = array();
		$this->_validators = array();

		import('helper.tree');
		$this->tree = new tree('#table_property', 'proid');
		
		$this->property = table('property');
	}

	function get($proid, $fields = '*')
	{
		return $this->tree->get($proid, $fields);
	}

	function add($data)
	{
		$data = $this->filter_array($data, array('parentid', 'name','alias', 'description', 'parentids', 'childids', 'sort', 'disabled', 'ischarge'));
		$result = $this->tree->set($data);
		$this->update_cache();
		return $result;
	}

	function edit($proid, $data)
	{
		if (!$this->tree->exists($proid))
		{
			$this->error = '属性不存在';
			return false;
		}
		$data['proid'] = $proid;
		$data = $this->filter_array($data, array('proid', 'parentid', 'name','alias', 'description', 'parentids', 'childids', 'sort', 'disabled', 'ischarge'));
		$result = $this->tree->set($data);
		$this->update_cache();
		return $result;
	}

	function delete($proid)
	{
		$result = is_numeric($proid) ? $this->tree->rm($proid) : array_map(array($this->tree, 'rm'), explode(',', $proid));
		$this->update_cache();
		return $result;
	}

	function move($proid, $parentid)
	{
		if (!$this->tree->exists($proid))
		{
			$this->error = '属性不存在';
			return false;
		}
		$result = $this->tree->set(array('proid'=>$proid, 'parentid'=>$parentid));
		if (!$result)
		{
			$this->error = '不能移动属性到子属性下';
			return false;
		}
		$this->update_cache();
		return true;
	}

	function ls($where, $order = '`proid` ASC')
	{
		return $this->select($where, '*', $order);
	}

	function propertys()
	{
		$data = $this->select();
		$propertys = array();
		foreach ($data as $value) 
		{
			$propertys[$value['proid']] = $value['name'];
		}
		return $propertys;
	}
	
	function search($name)
	{
		return $this->tree->search($name, '`proid`,`name`');
	}

	function get_pos($proid = null)
	{
		if (is_null($proid)) return false;
		return $this->tree->pos($proid, '`proid`,`parentids`,`name`');
	}

	function get_child($proid = null)
	{
		return $this->tree->get_child($proid, '`proid`,`parentids`,`childids`,`name`,`sort`');
	}
	
	function repair()
	{
		@set_time_limit(600);
		if(is_array($this->property))
		{
			$data = array();
			$proids = array_keys($this->property);
			foreach($proids as $proid)
			{
				if($proid == 0) continue;
				$parentids[$proid] = $this->get_parentids($proid);
			}
			
			foreach($proids as $proid)
			{
				if($proid == 0) continue;
				$childids = $this->get_childids($proid);
				$this->update(array('parentids'=>$parentids[$proid], 'childids'=>$childids), $proid);
			}
		}
		$this->update_cache();
		return true;
	}
	
	function get_parentids($proid, $parentids = '', $n = 1)
	{
		if($n > 5 || !is_array($this->property) || !isset($this->property[$proid])) return false;
		$parentid = $this->property[$proid]['parentid'];
		if($parentid)
		{
			$parentids = $parentids ? $parentid.','.$parentids : $parentid;
			$parentids = $this->get_parentids($parentid, $parentids, ++$n);
		}
		else
		{
			if (!$parentids) $parentids = null;
			$this->property[$proid]['parentids'] = $parentids;
		}
		return $parentids;
	}
	
	function get_childids($proid)
	{
		$childids = array();
		if(is_array($this->property))
		{
			foreach($this->property as $id => $cat)
			{
				if($cat['parentid'] && $id != $proid)
				{
					$parentids = explode(',', $cat['parentids']);
					if(in_array($proid, $parentids)) $childids[] = $id;
				}
			}
		}
		$childids = implode(',', $childids);
		if (!$childids) $childids = null;
		return $childids;
	}

	function update_cache()
	{
		table_cache('property');
	}
	
	function catesearch($name)
	{
		return $this->select("`name` LIKE '%$name%' AND childids IS NULL", 'proid,name', 'proid ASC, sort ASC');
	}
}