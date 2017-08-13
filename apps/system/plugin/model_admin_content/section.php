<?php

class plugin_section extends object 
{
	private $content;
	
	public function __construct($content)
	{
		$this->content = $content;
	}

	public function after_remove()
	{
		$this->delete();
	}
	public function after_unpublish()
	{
		$this->delete();
	}

	private function delete()
	{
		$contentid = $this->content->contentid;
		$sr = loader::model('admin/section_recommend', 'page');
		$su = loader::model('admin/section_url','page');
		if ($contentid)
		{
			if (($recommend = $sr->gets_by_contentid($contentid, '*')))
            {
                $sectionids = array();
                foreach ($recommend as $row)
                {
                    $sr->deleteItem($row['recommendid'], $row);
                    $sectionids[] = $row['sectionid'];
                }
                if (!empty($sectionids))
                {
                    $su->delete(array_unique($sectionids));
                }
			}
		}
	}
}