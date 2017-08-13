<?php
class plugin_stat extends object
{
    protected $_model;

    /**
     * @var model_admin_mobile_content_stat
     */
    protected $_model_stat;

    function __construct(&$model)
    {
        $this->_model = $model;

        $this->_model_stat = loader::model('admin/mobile_content_stat');
    }

    function after_get()
    {
        $contentid = intval($this->_model->contentid);
        if (!$contentid) return;

        $stat = table('mobile_content_stat', $contentid);
        if (!$stat) return;

        $this->_model->data = array_merge($this->_model->data, $stat);
    }

    function after_add()
    {
        $this->_model_stat->clear_cache();
    }

    function after_delete()
    {
        $this->_model_stat->clear_cache();
    }
}