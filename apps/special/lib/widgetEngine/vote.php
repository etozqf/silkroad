<?php
class widgetEngine_vote extends widgetEngine
{
	private $vote;
	public function __construct()
	{
		$this->vote = loader::model('admin/vote','vote');
	}
	public function _render($widget)
	{
		$data = decodeData($widget['data']);
		$contentid = $data['contentid'];
		$r = $this->vote->get($contentid, '*', 'show');
		if (!$r || $r['status'] != 6)
		{
			return false;
		}
		return $this->_genHtml(null, $r);
	}
	public function _addView()
	{
		$this->view->display('widgets/vote/form');
	}
	public function _editView($widget)
	{
		$data = decodeData($widget['data']);
		$vote = $this->vote->get($data['contentid']);
		$this->view->assign('action', 'edit');
		$this->view->assign('data', $data);
		$this->view->assign('vote', $vote);
		$this->view->display('widgets/vote/form');
	}
	public function _genData($post)
	{
		$post['vote']['option'] = $post['option'];
		if ($post['select_type'] == 1) {
			$data = array('contentid' => $post['contentid']);
		} else {
			if(!$post['contentid'])
			{
				$data = array('contentid' => $this->vote->add($post['vote']));
			} else {
				$this->vote->edit($contentid, $post['vote']);
				$data = array('contentid' => $post['contentid']);
			}
		}
		return encodeData($data);
	}
}