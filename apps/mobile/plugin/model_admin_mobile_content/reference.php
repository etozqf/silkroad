<?php
class plugin_reference extends object
{
    protected $_model;

    function __construct(&$model)
    {
        $this->_model = $model;
    }

    function after_get()
    {
        $referenceid = intval($this->_model->data['referenceid']);
        if ($referenceid) {
            $reference = table('content', $referenceid);
            $this->_model->data['reference'] = $reference ? $reference : null;
        }
    }
}