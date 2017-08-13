<?php

class plugin_source extends object 
{
	private $content;
	
	public function __construct($content)
	{
		$this->content = $content;
	}
	
	public function before_add()
	{
		$this->sourceid();
	}
	
	public function before_edit()
	{
		$this->sourceid();
	}

	public function before_ls()
	{
		$where	= $this->content->where;
		if (isset($where['source']) && $where['source'])
		{
			$source = loader::model('admin/source', 'system')->get_by('name', $where['source'], 'sourceid');
			if ($source) $where['sourceid'] = $source['sourceid'];
			$this->content->where	= $where;
		}
	}
	
	public function after_ls()
	{
		array_walk($this->content->data, array($this, 'output'));
	}
	
	public function after_get()
	{
		$this->output($this->content->data);
	}
	
	private function sourceid()
	{
		if (!empty($this->content->data['source']))
		{
			$this->content->data['sourceid'] = loader::model('admin/source', 'system')->sourceid($this->content->data['source']);
		} elseif(isset($this->content->data['source']) && empty($this->content->data['source'])) {
            $this->content->data['sourceid'] = 0;
        }
	}
	
	private function output(& $r)
	{
		$r['source_url'] = '';
		$r['source_name'] = '';
		if (isset($r['sourceid']) && $r['sourceid'])
		{
			$source = table('source', $r['sourceid']);
			$r['source_url'] = $source['url'];
			$r['source_name'] = $r['source'] = $source['name'];
			$r['source_logo'] = $source['logo'];
		}
	}
}