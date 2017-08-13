<?php

class plugin_model_vote_option extends object
{
	private $vote, $option;
	
	public function __construct($vote)
	{
		$this->vote = $vote;
		$this->option = loader::model('option','vote');
	}
	
	public function before_vote()
	{
		if (is_array($this->vote->optionid))
		{
            $this->vote->optionid = array_unique($this->vote->optionid);
			$optionid = array();
			foreach ($this->vote->optionid as $id)
			{
				$id = intval($id);
				if ($id) 
				{
					if ($this->option->set_inc('votes', array(
							'optionid' => $id,
							'contentid' => $this->vote->contentid
						)))
					{
						$optionid[] = $id;
					}
				}
			}
			$this->vote->votes = count($optionid);
			$this->vote->optionid = $optionid;
		}
		else 
		{
			$id = intval($this->vote->optionid);
			if ($id) 
			{
				if ($this->option->set_inc('votes', array(
						'optionid' => $id,
						'contentid' => $this->vote->contentid
					)))
				{
					$this->vote->votes = 1;
					$this->vote->optionid = $id;
				}
			}
		}
	}

	public function after_get()
	{
		$option = array();
		$total = 0;
		foreach((array)$this->option->ls($this->vote->contentid) as $item) {
			$option[$item['optionid']] = $item;
		}
		foreach ($option as $k => $r) {
			$total += $r['votes'];
		}
		foreach ($option as $k=>$r)
		{
			$option[$k]['percent'] = $total ? round($r['votes'] / $total * 100, 2) : 0;
		}
		$this->vote->data['option'] = $option;
	}
}
