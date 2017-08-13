<?php

class plugin_tags extends object 
{
	private $content;
	
	public function __construct($content)
	{
		$this->content = $content;
	}

    public function before_add()
    {
        $this->_filter_tags();
    }

    public function before_edit()
    {
        $this->_filter_tags();
    }

    protected function _filter_tags()
    {
        $tags = $this->content->data['tags'] = trim($this->content->data['tags']);
        if ($tags)
        {
            $tags = explode(' ', $tags);
            $tags = array_filter($tags, 'trim');
            $tags = array_unique($tags);
            if (count($tags) > 5)
            {
                $tags = array_slice($tags, 0, 5);
            }
            $tags = implode(' ', $tags);
            $this->content->data['tags'] = $tags;
        }
    }
	
	public function after_add()
	{
		$this->tags();
	}
	
	public function after_edit()
	{
		$this->tags();
	}
	
	public function after_pass()
	{
		$r = table('content', $this->content->contentid);
		$this->content->data = $r;				
		$this->tags();
	}

	public function after_publish()
	{
		$r = table('content', $this->content->contentid);
		$this->content->data = $r;				
		$this->tags();
	}
	public function after_unpublish()
	{
		$r = table('content', $this->content->contentid);
		$this->content->data = $r;				
		$this->tags();
	}
	public function after_remove()
	{
		$r = table('content', $this->content->contentid);
		$this->content->data = $r;				
		$this->tags();
	}			
	public function after_restore()
	{
		$r = table('content', $this->content->contentid);
		$this->content->data = $r;				
		$this->tags();
	}				
	public function after_get()
	{
		$this->output($this->content->data);
	}
	
	public function after_copy()
	{
		$r = table('content', $this->content->contentid);
		if(intval($r['modelid']) != 1) return ;
		$this->content->data = $r;
		$this->tags();
	}
	
	public function after_reference()
	{
		$r = table('content', $this->content->contentid);
		$this->content->data = $r;
		$this->tags();
	}
	
	private function tags()
	{
		$this->content->data['tags'] = trim($this->content->data['tags']);
		$status = $this->content->data['status'];
		if ($_GET['action'] == 'add' && empty($this->content->data['tags'])) return false;

		if(!$status){
                    $status = table('content', $this->content->contentid,'status');
                }
		if (empty($this->content->data['tags']))
		{
			loader::model('admin/content_tag', 'system')->update($this->content->contentid);
			return;
		}
		if($status == 6){
			$tagids = loader::model('admin/tag', 'system')->update($this->content->data['tags']);
		}
		if($status == 4 || $status == 0){
			$tagids = loader::model('admin/tag', 'system')->dedate($this->content->data['tags']);
		}
		if ($tagids)
		{
			$this->content->data['tags'] = loader::model('admin/content_tag', 'system')->update($this->content->contentid, $tagids);
		}
	}
	
	private function output(& $r)
	{
		if (isset($r['tags']) && $r['tags'])
		{
			$uri = loader::lib('uri', 'system');
			if ($this->content->action == 'show')
			{
				$tags = explode(' ', $r['tags']);
				foreach ($tags as $k=>$tag)
				{
					$tags[$k] = array('tag'=>$tag, 'url'=>$uri->tag($tag));
				}
				$r['keywords'] = $tags;
			}
			elseif ($this->content->action == 'view')
			{
				$html = '';
				$tags = explode(' ', $r['tags']);
				foreach ($tags as $tag)
				{
					$html .= '<a href="'.$uri->tag($tag).'" target="_blank">'.$tag.'</a> ';
				}
				$r['tags_view'] = $html;
			}
		}
	}
}
