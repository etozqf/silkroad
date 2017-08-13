<?php

class plugin_model_admin_vote_option extends object
{
	private $vote, $option;
	
	public function __construct($vote)
	{
		$this->vote = $vote;
		$this->option = loader::model('admin/option','vote');
	}
	
	public function before_add()
	{
		$this->total();
	}
	
	public function after_add()
	{
		if (!isset($this->vote->data['option']) || empty($this->vote->data['option'])) return false;
		
		foreach ($this->vote->data['option'] as $k=>$r)
		{
			$r['contentid'] = $this->vote->contentid;
			$this->option->add($r);
		}
	}
	
	public function before_edit()
	{
		$this->total();
	}
	
	public function after_edit()
	{
		if (!isset($this->vote->data['option']) || empty($this->vote->data['option'])) return false;
	
		$optionid = array();
		foreach ($this->vote->data['option'] as $k=>$r)
		{		
			if ($r['optionid'])
			{
				$this->option->edit($r['optionid'], $r);
				$optionid[] = $r['optionid'];
			}
			else 
			{
				$r['contentid'] = $this->vote->contentid;
				$optionid[] = $this->option->add($r);
			}
		}
		$this->option->delete_by($this->vote->contentid, $optionid);
	}
	
	public function after_get()
	{
		$option = array();
		foreach((array)$this->option->ls($this->vote->contentid) as $item) {
			$option[$item['optionid']] = $item;
		}
		$total = $this->vote->data['total'];
		$cache = factory::datacache()->get('vote_option_adder');
		if (!empty($cache[$this->vote->contentid])) {
			foreach ($cache[$this->vote->contentid] as $id => $item) {
				$total += $item - intval($option[$id]['db_votes']);
			}
		}
		$this->vote->data['total'] = $total;
		foreach ($option as $k=>$r)
		{
			$option[$k]['percent'] = $this->vote->data['total'] ? round($r['votes']/$this->vote->data['total']*100, 2) : 0;
		}
		$this->vote->data['option'] = $option;
	}
	
	private function total()
	{
		if (!isset($this->vote->data['option']) || empty($this->vote->data['option'])) return false;
		
		$this->vote->data['total'] = 0;
		foreach ($this->vote->data['option'] as $k=>$r)
		{
			$this->vote->data['total'] += $r['votes'];
		}
	}
}