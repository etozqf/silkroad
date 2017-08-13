<?php

class plugin_updatecate extends object 
{
	private $content, $catid, $status = null, $contentid;
	
	public function __construct($content)
	{
		$this->content = $content;
		$this->contentid = !empty($this->content->data) && isset($this->content->data['contentid'])
            ? $this->content->data['contentid']
            : $this->content->contentid;
	}
	
	public function after_add()
	{
		$this->iscreated('add');
	}
	
	public function after_publish()
	{
		$this->precatid();
		$this->iscreated('publish');
	}
	
	public function after_unpublish()
	{
		$this->iscreated('remove');
	}
	
	public function before_remove()
	{
		$this->status();
	}
	
	public function after_remove()
	{
		$this->precatid();
		$this->iscreated('remove');
	}
	
	public function after_restore()
	{
		$this->iscreated('publish');
	}
	
	public function after_pass()
	{
        $this->precatid();
		$this->iscreated('publish');
	}
	
	public function before_edit()
	{
		$this->precatid();
	}
	
	public function after_edit()
	{
		$this->iscreated('edit');
	}
	
	public function before_copy()
	{
		$this->precatid();
	}
	
	public function after_copy()
	{
		$this->iscreated('copy');
	}

	public function before_move()
	{
		if($this->status() != 6) return;
	}
	
	public function after_move()
	{
		$this->iscreated('move');
	}
	
	public function after_reference()
	{
		$this->iscreated('reference');
	}
	
	private function iscreated($action = 'other')
	{
		if($action == 'add')
		{
			if($this->content->data['status'] != 6) return;
			$this->changestate($this->content->data['catid']);
		}
		
		if($action == 'publish')
		{
			$this->changestate($this->catid);	
		}
		
		if($action == 'remove')
		{
			$this->changestate($this->catid);	
		}
		
		if($action == 'edit')
		{
			$this->changestate($this->catid);	
			if($this->catid != $this->content->data['catid'])
			{
				$this->changestate($this->content->data['catid']);
			}
		}
		
		if($action == 'copy')
		{
			if($this->catid != $this->content->data['catid'])
			{
				$this->changestate($this->catid);
			}
			$this->changestate($this->content->data['catid']);
		}
		
		if($action == 'move')
		{
			$this->changestate($this->content->catid);
			$this->changestate($this->catid);	
		}
		
		if($action == 'reference')
		{
			$this->changestate($this->content->catid);
		}
	}

	private function changestate($catid, $htmlcreated = 0)
	{
		$db = factory::db();
		$parentids = table('category', $catid, 'parentids');
		if($parentids)
		{
			$catid .= ',' . $parentids;
		}
		$db->exec('UPDATE #table_category SET `htmlcreated`='.$htmlcreated.' WHERE `catid` in ('.$catid.')');
	}
	
	private function precatid()
	{
		$this->catid = $this->content->get_by('contentid', $this->contentid, 'catid');
		$this->catid = $this->catid['catid'];
	}
	
	private function status()
	{
		$this->status = $this->content->get_by('contentid', $this->contentid, 'status');
		if (intval($this->status['status']) != 6) return ;
	}
}