<?php

class plugin_place extends object 
{
	private $content;
	
	public function __construct($content)
	{
		$this->content = $content;
	}

	public function after_get()
	{
        $contentid = $this->content->contentid;
        $db = factory::db();
        $this->content->data['places'] = $db->select("SELECT c.`title`, sp.`name` AS `pageName`, p.`name` AS `sectionName`, pd.`placeid`
            FROM `#table_place_data` pd
            LEFT JOIN `#table_place` p ON pd.`placeid` = p.`placeid`
            LEFT JOIN `#table_special_page` sp ON p.`pageid` = sp.`pageid`
            LEFT JOIN `#table_content` c ON sp.`contentid` = c.`contentid`
            WHERE pd.`contentid` = $contentid");
	}

	public function after_delete()
	{
		$contentid = $this->content->contentid;
		$db = factory::db();
		$rowset = $db->select("SELECT placeid FROM #table_place_data WHERE contentid=$contentid");
		loader::model('admin/place_data', 'special')->delete("contentid=$contentid");
		$placeid = array();
		foreach ($rowset  as $r)
		{
			$placeid[] = $r['placeid'];
		}
		if ($placeid)
		{
			request(ADMIN_URL.'?app=special&controller=online&action=pubWidget&widgetid='.implode_ids($placeid));
		}
	}

	public function after_remove()
	{
		$this->after_delete();
	}

	public function after_publish()
	{
		$contentid = $this->content->contentid;
		$db = factory::db();
		$rowset = $db->select("SELECT placeid FROM #table_place_data WHERE contentid=$contentid");
		$placeid = array();
		foreach ($rowset  as $r)
		{
			$placeid[] = $r['placeid'];
		}
		if ($placeid)
		{
			request(ADMIN_URL.'?app=special&controller=online&action=pubWidget&widgetid='.implode_ids($placeid));
		}
	}
}