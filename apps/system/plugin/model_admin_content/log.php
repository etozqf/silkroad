<?php

class plugin_log extends object 
{
	private $content;
	
	public function __construct($content)
	{
		$this->content = $content;
	}
	
	public function __call($method, $args)
	{
		$action = substr($method, 6);
		if ($this->content->contentid && in_array($action, array('add', 'edit', 'delete', 'clear', 'remove', 'restore','restores', 'pass', 'reject', 'reference', 'move', 'islock', 'lock', 'unlock', 'publish', 'unpublish'), true)) 
		{
			loader::model('admin/content_log', 'system')->add($this->content->contentid, $action);
        }
	}
}