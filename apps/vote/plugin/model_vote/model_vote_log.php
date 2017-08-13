<?php

class plugin_model_vote_log extends object
{
	private $vote, $log, $log_data;
	
	public function __construct($vote)
	{
		$this->vote = $vote;
		$this->log = loader::model('vote_log','vote');
		$this->log_data = loader::model('log_data','vote');
	}

	public function after_vote()
	{
		$vote_log_index = (array)factory::datacache()->get('vote_log_index');

		$tempid = $this->vote->contentid.'-'.(string)ip2long(IP);
		$timeout = max(1800, intval($this->vote->data['mininterval']) * 3600);
		$data = array(
			'created' => TIME,
			'createdby' => $this->vote->_userid,
			'ip' => IP,
			'contentid' => $this->vote->contentid,
			'optionid' => $this->vote->optionid
		);

		if (array_search($tempid, $vote_log_index) === false) {
			$vote_log_index[] = $tempid;
			factory::datacache()->set('vote_log_index', array_filter($vote_log_index));
			$data = array($data);
		} else {
			$old_data = factory::datacache()->get("vote_log_$tempid");
			$old_data[] = $data;
			$data = $old_data;
		}
		factory::datacache()->set("vote_log_$tempid", $data, $timeout);
	}
}