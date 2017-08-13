<?php

class model_space_sub_type extends model implements SplSubject
{
	public $statuss = array(0=>'已禁用',1=>'审核中', 2=>'未批准',3 =>'已开通', 4=>'已推荐');
	public $types   = array(0=>'记者观察',1=>'推荐专家', 2=>'机构研究');
	public $spaceid, $status;
	private $observers = array();
	function __construct()
	{
		parent::__construct();
		$this->_table = $this->db->options['prefix'].'space_sub_type';
		$this->_primary = 'sid';
		$this->_fields = array('sid','name','status','sort');
		$this->_txtfields = array();
		$this->_readonly = array('sid');
		$this->_filters_input = array();
		$this->_filters_output = array();
		$this->_validators = array();
		import('helper.pinyin');
	}
	
	public function attach(SplObserver $observer)
	{
		$this->observers[] = $observer;
	}

	public function detach(SplObserver $observer)
	{
		if($index = array_search($observer, $this->observers, true)) unset($this->observers[$index]);
	}

	public function notify()
	{
		foreach ($this->observers as $observer)
		{
			$observer->update($this);
		}
	}
	
	
}
