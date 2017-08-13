<?php
class plugin_slider extends object
{
    protected $_model;

    function __construct(&$model)
    {
        $this->_model = $model;
    }

    function after_edit()
    {
        $contentid = intval($this->_model->contentid);
        $slider = loader::model('admin/mobile_slider', 'mobile');
        $data = $slider->get($contentid);
        $data['thumb'] = $this->_model->data['thumb_slider'];
        loader::model('admin/mobile_slider', 'mobile')->update($data, $contentid, 1);
    }

    function after_search()
    {
        $contentid = intval($this->_model->contentid);
        if (!$contentid) return;

        $catid = intval($_GET['catid']);
        if (!$catid) return;

        $this->_model->data['inslider'] = loader::model('admin/mobile_slider', 'mobile')->count(array(
            'catid' => $catid,
            'contentid' => $contentid
        )) ? 1 : 0;
    }
}