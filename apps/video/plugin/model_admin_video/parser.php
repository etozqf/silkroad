<?php

class plugin_parser extends object 
{
	private $video, $category, $uri, $template, $json;
	
	public function __construct($video)
	{
		$this->video = $video;
	}

	public function before_add()
	{
		$this->video_parser();
	}

	public function before_edit()
	{
		$this->video_parser();
	}

	private function video_parser()
	{
		$setting = setting('video', 'third_video_tag');
		if (!is_array($setting)) {
			if (!$setting = json_decode($setting, 1)) {
				return;
			}
		}
		foreach($setting as $key => $item) {
			if (strpos($this->video->data['video'], $item['match']['feature']) !== false) {
				if (preg_match($item['match']['catch'], $this->video->data['video'], $m)) {
					$this->video->data['video'] = "[$key]$m[1][/$key]";
				}
			}
		}
	}
}