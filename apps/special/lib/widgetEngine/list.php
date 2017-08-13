<?php
class widgetEngine_list extends widgetEngine
{
	public function _render($widget)
	{
		$data = decodeData($widget['data']);
		$rows = intval($data['rows']);
		$length = intval($data['length']);
		$dateformat = $data['dateformat'] ? $data['dateformat'] : 'Y-m-d H:i:s';
		if ($data['method'] == '1')
		{
			$list = $data['list'];
		}
		elseif ($data['method'] == '2')
		{
			$options = $data['options'];
			$options['fields'] = 'id, title, url, time';
			$options['size'] = $rows;
			$port = loader::model('admin/port', 'system');
			$ret = $port->request($data['port'], array(
				'action' => 'getData'
			), $options);
			if ($ret['httpcode'] == '200') {
				$list = $this->json->decode($ret['content']);
			} else {
				$list = array();
			}
		}
		else
		{
			$placeid = $widget['widgetid'];
			$placeData = loader::model('admin/place_data', 'special');
			$list = $placeData
				->select(
					"placeid=$placeid AND status=1",
					'title, url, time',
					'sort asc, time desc', $rows
				);
			$total = $placeData->count(array(
				'placeid'=>$placeid,
				'status'=>1
			));
		}
		return $this->_genHtml($data['template'], array(
			'data'=>$list,
			'total'=>$total,
			'rows'=>$rows,
			'length'=>$length,
			'dateformat'=>$dateformat
		));
	}
	public function _addView()
	{
		$this->view->display('widgets/list/add');
	}
	public function _genData($post, $widget = null)
	{
		$list = array();
		if (is_array($post['list']))
		{
			foreach ((array) $post['list']['title'] as $i=>$v)
			{
				$list[] = array(
					'title'=>$v,
					'url'=>$post['list']['url'][$i],
					'time'=>strtotime($post['list']['time'][$i])
				);
			}
		}
		if (empty($post['template']) && $widget)
		{
			$data = decodeData($widget['data']);
			$post['template'] = $data['template'];
		}
		return encodeData(array(
			'method'=>$post['method'],
			'list'=>$list,
			'options'=>$post['options'],
			'port'=>$post['port'],
			'rows'=>$post['rows'],
			'length'=>$post['length'],
			'dateformat'=>$post['dateformat'],
			'template'=>$post['template']
		));
	}
	public function _afterPost($widgetid, $post)
	{
        $widgetid = intval($widgetid);
        $place = loader::model('admin/place', 'special');

		if ($post['place'])
		{
			if ($place->get($widgetid))
			{
				$place->update($post['place'], $widgetid);
			}
			else
			{
				$post['place']['placeid'] = $widgetid;
				$post['place']['pageid'] = $_REQUEST['pageid'];
				$place->insert($post['place']);
			}
		}
        else
        {
            $place->delete($widgetid, 1);
        }
	}
	public function _afterCopy($widgetid, $widget)
	{
		$data = decodeData($widget['data']);
		if ($data['method'] == '0')
		{
			$p = loader::model('admin/place', 'special');
			$pd = loader::model('admin/place_data', 'special');
			$place = $p->get($widget['widgetid']);
			$place['placeid'] = $widgetid;
			$place['pageid'] = $_REQUEST['pageid'];
			try {
				$p->insert($place);
			} catch (Exception $e) {}
			foreach ($pd->select("placeid={$widget['widgetid']}") as $d)
			{
				$d['placeid'] = $widgetid;
				try {
					$pd->insert($d);
				} catch (Exception $e) {}
			}
		}
	}
	public function _editView($widget)
	{
		$data = decodeData($widget['data']);
		if ($data['method'] == '0')
		{
			$data['place'] = loader::model('admin/place', 'special')->get($widget['widgetid']);
		}
		foreach ($data['list'] as &$l)
		{
			$l['time'] = $l['time'] ? date('Y-m-d H:i:s', $l['time']) : '';
		}
		$this->view->assign($data);
		$this->view->display('widgets/list/edit');
	}
}