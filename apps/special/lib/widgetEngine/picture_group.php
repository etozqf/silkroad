<?php
class widgetEngine_picture_group extends widgetEngine
{
	public function _render($widget)
	{
        $data = decodeData($widget['data']);

        $picture_group = loader::model('admin/picture', 'picture');
        $picture = $picture_group->get(intval($data['contentid']), '*', 'show');
        if (! $picture || $picture['status'] != 6)
        {
            return false;
        }

        return $this->_genHtml($data['template'], array(
            'data' => $data,
            'uuid' => uniqid('GALLERY'),
            'picture' => $picture
        ));
	}
	public function _addView()
	{
		$this->view->display('widgets/picture_group/form');
	}
	public function _editView($widget)
	{
        $data = decodeData($widget['data']);
        $picture_group = loader::model('admin/picture', 'picture');
        $picture = $picture_group->get(intval($data['contentid']), '*', 'show');
		$this->view->assign('data', $data);
        $this->view->assign('picture', $picture);
		$this->view->display('widgets/picture_group/form');
	}
}
