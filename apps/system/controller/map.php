<?php
class controller_map extends system_controller_abstract
{
	private $content;

	function __construct($app)
	{
		parent::__construct($app);
	}

	function index()
	{
		if ($this->system['pagecached'])
		{
			$keyid = md5('pagecached_system_map_index_');
			cmstop::cache_start($this->system['pagecachettl'], $keyid);
		}
		
		$this->template->display('cn/map.html');
		
		if ($this->system['pagecached']) cmstop::cache_end();
	}
	
}