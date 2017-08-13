<?php
// 编辑后保存版本
class plugin_content_version extends object 
{
	private $activity;
	
	public function __construct($activity)
	{
		$this->activity = $activity;
	}

	public function after_edit()
	{
		$data = $this->activity->data;
		$title = $data['title'];
		$contentid = $data['contentid'];
		if($contentid && $title)
		{
			$content_version = loader::model('admin/content_version', 'system');
			$content_version->add($contentid, $title, serialize($data));
		}
	}
}