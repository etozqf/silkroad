<?php

class plugin_property extends object 
{
	private $content, $content_property;
	
	public function __construct($content)
	{
		$this->content = $content;
		$this->content_property = loader::model('admin/content_property', 'system');
	}
	
	public function after_add()
	{
		$this->property();
	}
	
	public function before_edit()
	{
		//$proids = $this->content_property->ls_proid($this->content->contentid);
		//$this->content->data['proids'] = implode(',', $proids);
	}
	
	public function after_edit()
	{
		$this->property();
	}
	
	public function after_get()
	{
		$proids = $this->content_property->ls_proid($this->content->contentid);
		$this->content->data['proids'] = implode(',', $proids);
	}
	
	private function property()
	{
		$proids = trim($_POST['proids']);
		if ($_GET['action'] == 'add' && empty($proids)) return false;
		
		$this->content_property->update($this->content->contentid, $proids);
	}
}