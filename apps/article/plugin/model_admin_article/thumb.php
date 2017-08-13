<?php

class plugin_thumb extends object 
{
	private $article;
	
	public function __construct($article)
	{
		$this->article = $article;
	}
	
	public function after_add()
	{
		$this->thumb();
	}

	public function after_edit()
	{
		$this->thumb();
	}

	private function thumb()
	{
		if (!empty($this->article->options['thumb']) && empty($this->article->data['thumb'])) {
			if (preg_match('#http:\/\/[^>\'"]*\.(jpg|jpeg|png)#m', $this->article->data['content'], $m)) {
				$this->article->edit($this->article->contentid, array('thumb'=>str_replace(UPLOAD_URL, '', $m[0])));
			}
		}
	}
}