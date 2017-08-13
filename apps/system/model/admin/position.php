<?php
class model_admin_position extends model
{
	function __construct()
	{
		parent::__construct();
		$this->_table = $this->db->options['prefix'].'position_yq';
		$this->_primary = 'positionid';
		$this->_fields = array('positionid', 'point', 'name', 'value', 'url', 'createtime','province');
		$this->_readonly = array('positionid');
		$this->_create_autofill = array();
		$this->_update_autofill = array();
		$this->_filters_input = array();
		$this->_filters_output = array();
		$this->_validators = array(
            'name' => array(
                'not_empty'=>array('园区名称不能为空'),
			    'max_length' =>array(255, '园区名称不得超过255字节')
		    ),
		);
	}

	function add($data)
	{
		$data = $this->filter_array($data, array('positionid', 'point', 'name', 'value', 'url', 'createtime', 'province'));
		$data['createtime'] = time();
		$data['value'] = table('yq_category',$data['value'],'value');
		return $this->insert($data);
	}

	function edit($positionid, $data)
	{
		
		$data = $this->filter_array($data, array('point', 'name', 'value', 'url', 'createtime', 'province'));
		$data['value'] = table('yq_category',$data['value'],'value');
		
		return parent::update($data, "positionid=$positionid");
	}

	function delete($positionid)
	{
		$result = parent::delete(implode_ids($positionid));
		return $result;
	}

	function ls()
	{
		return $this->select();
	}

	
	
    
}