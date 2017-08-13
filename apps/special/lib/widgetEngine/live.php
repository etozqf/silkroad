<?php
class widgetEngine_live extends widgetEngine
{
	public function _render($widget)
	{
		$data = decodeData($widget['data']);

        $data['video'] = $data['src'];
        $data['width'] = isset($data['width']) && $data['width'] ? $data['width'] : 560;
        $data['height'] = isset($data['height']) && $data['height'] ? $data['height'] : 420;

        $player = getVideoPlayer($data['video']);
        if($player['player'] == 'flash') $player['player'] = 'rtmp';
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
		$this->view->display('widgets/live/form');
	}
	
	public function _editView($widget)
	{
		$this->view->assign('data', decodeData($widget['data']));
		$this->view->display('widgets/live/form');
	}
}