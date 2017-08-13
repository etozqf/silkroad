<?php

class plugin_push_log extends object
{
    protected $_model;

    /**
     * @var model_admin_mobile_push_log
     */
    protected $_log;

    function __construct(&$model)
    {
        $this->_model = $model;
        $this->_log = loader::model('admin/mobile_push_log', 'mobile');
    }

    function after_push()
    {
        $this->_log->insert(array(
            'title' => value($this->_model->data, 'title'),
            'content' => value($this->_model->data, 'message'),
            'contentid' => value($this->_model->data, 'contentid'),
            'modelid' => value($this->_model->data, 'modelid'),
            'devices' => value($this->_model->data, 'platform'),
            'successed' => 1
        ));
    }
}