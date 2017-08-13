<?php
class plugin_update_content_slider extends object
{
    protected $_model;

    function __construct(&$model)
    {
        $this->_model = $model;
    }

    function after_add()
    {
        $this->_update();
    }

    function after_edit()
    {
        $this->_update();
    }

    protected function _update()
    {
        $contentid = (int)$this->_model->contentid;
        $thumb_slider = value($this->_model->data, 'thumb');
        if ($contentid
            && $thumb_slider
            && ($content = table('mobile_content', $contentid))
        ) {
            factory::db()->update("UPDATE `#table_mobile_content` SET `thumb_slider` = ?
                WHERE `contentid` = ?", array(
                $thumb_slider, $contentid
            ));
        }
    }
}