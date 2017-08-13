<?php
class widgetEngine_weibo extends widgetEngine
{
	public function _render($widget)
	{
		$data = decodeData($widget['data']);
        if (isset($data['code']))
        {
            return $data['code'];
        }
		if (isset($data['items']))
        {
            foreach ($data['items'] as &$item)
            {
                if (isset($item['image']))
                {
                    $item['image'] = json_decode($item['image'], true);
                }
            }
            return $this->_genHtml(null, $data);
        }
        return false;
	}
	public function _addView()
	{
        $this->view->assign('qq_key', setting('system', 'tencent_appkey'));
		$this->view->display('widgets/weibo/form');
	}
	public function _editView($widget)
	{
		$data = decodeData($widget['data']);

        if (! isset($data['method']))
        {
            switch ($data['type'])
            {
                case 'live':
                    $data['method'] = 1;
                    break;
                case 'show':
                    $data['method'] = 2;
                    break;
                default:
                    $data['method'] = 0;
                    break;
            }
        }

        $this->view->assign('qq_key', setting('system', 'tencent_appkey'));
		$this->view->assign('data', $data);
		$this->view->display('widgets/weibo/form');
	}
	public function gettags()
	{
		$tags = urlencode(str_charset('UTF-8','GBK',$_GET['tags']));
		echo $this->json->encode(array('state' => true,'tags' => $tags));
	}
	public function _genData($post)
	{
        if (isset($post['sina'])) $post['data']['sina'] = $post['sina'];
        if (isset($post['qq'])) $post['data']['qq'] = $post['qq'];
		if (isset($post['sohu'])) $post['data']['sohu'] = $post['sohu'];
        if (isset($post['items'])) $post['data']['items'] = array_values($post['items']);
        $post['data']['method'] = $post['method'];
		return encodeData($post['data']);
	}
}