<?php
class plugin_pv extends object 
{
	private $content, $cache;
	
	public function __construct($content)
	{
		$this->content = $content;
		$this->cache = factory::datacache();
	}
	
	public function after_get()
	{
		$contentid = $this->content->contentid;
		$pv = $this->cache->get('pv_'.$contentid);
		if ($pv !== false)
		{
			$this->content->data['pv'] = $pv[0];
		}
	}
	
	public function after_ls()
	{
		$pv = $this->cache->get('pv');
		foreach ($this->content->data as & $v)
		{
            $pv = $this->cache->get('pv_'. $v['contentid']);
			if($pv !== false)
			{
				$v['pv'] = $pv[0];
			}
		}
	}
}