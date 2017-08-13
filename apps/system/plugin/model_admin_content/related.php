<?php

class plugin_related extends object 
{
	private $content;
	
	public function __construct($content)
	{
		$this->content = $content;
	}
	
	public function after_add()
	{
		$this->related();
	}
	
	public function after_edit()
	{
		$this->related();
	}
	
	public function after_get()
	{
        $related = loader::model('admin/related', 'system');
        $this->content->data['relateds'] = $related->ls($this->content->contentid);
	}
	
	private function related()
	{
		loader::model('admin/related', 'system')->update($this->content->contentid, $this->content->data['related']);
	}
	
	private function get()
	{

	}
}