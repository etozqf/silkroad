<?php
class plugin_sohu extends object
{
	private $m;
	
	public function __construct($model)
	{
		$this->m = $model;
	}

	public function after_logout()
	{
		if (!setting('cloud', 'sohu_changyan')) {
			return;
		}
		$this->m->synclogout .= '<script src="http://changyan.sohu.com/api/2/logout"></script>';
	}
}