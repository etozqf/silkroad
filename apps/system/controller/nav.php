<?php
class controller_nav extends system_controller_abstract
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
		
		$this->template->display('system/nav.html');
		
		if ($this->system['pagecached']) cmstop::cache_end();
	}
	
}