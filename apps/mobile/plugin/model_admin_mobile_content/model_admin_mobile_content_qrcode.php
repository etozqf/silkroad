<?php

class plugin_model_admin_mobile_content_qrcode extends object
{
    private $_model;

    public function __construct(&$model)
    {
        $this->_model = $model;
    }

    public function after_get()
    {
        if (empty($this->_model->data['qrcode'])) {
            return;
        }

        $this->_model->data['qrcode'] = UPLOAD_URL . $this->_model->data['qrcode'];
    }
}