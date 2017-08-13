<?php

class plugin_field extends object 
{
	private $content, $db;

	public function __construct($content)
	{
		$this->content = $content;
		$this->db = factory::db();
	}

	public function after_add()
	{
		$this->_save();
	}

	public function after_edit()
	{
		$this->_save();
	}

	public function after_delete()
	{
		$contentid = $this->content->contentid;
		$sql = "DELETE FROM #table_content_meta WHERE contentid = ?";
		$this->db->delete($sql, array($contentid));
	}

	private function _save()
	{
		$contentid = $this->content->contentid;
		$setting = $_POST['field'];
		if(!empty($setting)) {
			$data = serialize($setting);
		}

		$sql = "REPLACE INTO #table_content_meta SET contentid = ?, data = ?";
		$this->db->update($sql, array($contentid, $data));
	}
}