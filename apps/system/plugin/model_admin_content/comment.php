<?php

class plugin_comment extends object 
{
	private $content, $topic;
	
	public function __construct($content)
	{
		$this->content = $content;
		$this->topic = loader::model('topic', 'comment');
	}

	public function after_add()
	{
        loader::lib('uri', 'system')->content($this->content->contentid);
		$this->_save();
	}

	public function after_edit()
	{
		$this->_save();
	}

	private function _save()
	{
		$description = $this->content->data['description'];
		$contentid = $this->content->contentid;
		$content = table('content', $contentid);
		$topicid = $content['topicid'];
		$content['description'] = $description ? $description : $content['title'];

		// 跳过链接模型
		if ($this->content->data['modelid'] == 3)
		{
			return;
		}

		if(!$topicid && $content['allowcomment'])
		{
			$tid = $this->topic->add(
				$content['title'],
				$content['url'],
				$content['description'],
				$content['thumb'],
				!$content['allowcomment']
			);
			$r = $this->content->set_field('topicid', $tid, "contentid = $contentid");
			if(!$r) $this->topic->delete($tid);
		}
		else
		{
			$this->topic->edit(
				$topicid,
				$content['title'],
				$content['url'],
				$content['description'],
				$content['thumb'],
				!$content['allowcomment']
			);
		}
	}
}