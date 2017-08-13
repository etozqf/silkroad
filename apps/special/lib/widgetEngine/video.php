<?php
class widgetEngine_video extends widgetEngine
{
	public function _render($widget)
	{
		$data = decodeData($widget['data']);

		$data['autostart'] = empty($data['autostart'])?'false':'true';
        $data['width'] = isset($data['width']) && $data['width'] ? $data['width'] : 560;
        $data['height'] = isset($data['height']) && $data['height'] ? $data['height'] : 420;

        $player = getVideoPlayer($data['video']);
        $type = $player['player'];
        $data = array_merge($data, $player);

        $dir = ROOT_PATH.'templates'.DS.TEMPLATE.DS.'video/player/';
        $file = $type.'.html';

        if (!is_file($dir.$file)) return false;

        $orig_dir = $this->template->dir;
        $this->template->set_dir($dir);
        $html = $this->template->assign($data)->fetch($file);
        $this->template->set_dir($orig_dir);
        return $html;
	}
	public function _addView()
	{
		$this->view->display('widgets/video/form');
	}
	public function _genData($post)
	{
		$post['data']['autostart'] = isset($post['data']['autostart']) ? 1 : 0;
		return encodeData($post['data']);
	}
	public function _editView($widget)
	{
		$this->view->assign('data', decodeData($widget['data']));
		$this->view->display('widgets/video/form');
	}
	public function getvideo()
	{
		$contentid = intval($_GET['contentid']);
		$this->video = loader::model('admin/video','video');
		$video = $this->video->get_field('video',$contentid);
		
		$result = $video ? array('state' => true,'data' => $video):array('state' => false,'error' => '不存在的视频');
		echo $this->json->encode($result);
	}
}