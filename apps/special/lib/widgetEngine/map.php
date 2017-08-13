<?php
class widgetEngine_map extends widgetEngine
{
	public function _addView()
	{
		$this->view->display('widgets/map/form');
	}

	public function _editView($widget)
	{
		$data = decodeData($widget['data']);
        if (isset($data['x']) && isset($data['y']))
        {
            $data['marker'] = array(
                'lng' => $data['x'],
                'lat' => $data['y']
            );
        }
		$this->view->assign('data', json_encode((object) $data));
		$this->view->display('widgets/map/form');
	}

    public function _genData($post, $widget = null)
    {
        return encodeData($post);
    }

    public function _render($widget)
    {
        $data = decodeData($widget['data']);
        $data['center'] = decodeData($data['center']);
        $data['marker'] = decodeData($data['marker']);
        $data['city'] = decodeData($data['city']);
        $data['height'] = intval($data['height']);
        if ($data['height'] < 10)
        {
            $data['height'] = 300;
        }
        $data['width'] = intval($data['width']);
        if ($data['width'] < 10)
        {
            $data['width'] = 300;
        }
        $data['zoom'] = intval($data['zoom']);
        if (!$data['zoom'])
        {
            $data['zoom'] = 12;
        }

        return $this->_genHtml(null, $data);
    }
}