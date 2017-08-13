<?php
class model_admin_yqcategory extends model
{
	function __construct()
	{
		parent::__construct();
		$this->_table = $this->db->options['prefix'].'yq_category';
		$this->_primary = 'cateid';
		$this->_fields = array('cateid', 'value', 'color', 'category', 'sort');
		$this->_readonly = array('cateid');
		$this->_create_autofill = array();
		$this->_update_autofill = array();
		$this->_filters_input = array();
		$this->_filters_output = array();
		$this->_validators = array(
            'value' => array(
                'not_empty'=>array('分类名称不能为空'),
			    'max_length' =>array(255, '分类名称不得超过255字节')
		    ),
		);
	}

	function add($data)
	{
		$data = $this->filter_array($data, array('cateid','value','color','category','sort'));
		return $this->insert($data);
	}

	function edit($cateid, $data)
	{
		
		$data = $this->filter_array($data, array('value','color','category','sort'));
		return parent::update($data, "cateid=$cateid");
	}

	function delete($cateid)
	{
		$result = parent::delete(implode_ids($cateid));
		return $result;
	}

	function ls()
	{
		return $this->select();
	}

	
	
    
}