<?php

class plugin_model_admin_eventlive_stat extends object
{
    private $_model, $_stat;

    public function __construct(&$model)
    {
        $this->_model = $model;
        $this->_stat = loader::model('admin/eventlive_stat', 'eventlive');
    }

    public function after_add()
    {
        $id = $this->_model->data['id'];
        if (empty($id)) {
            return;
        }

        $this->_stat->insert(array(
            'liveid' => $id
        ));
    }

    public function after_get()
    {
        $id = $this->_model->data['id'];
        if (empty($id)) {
            return;
        }

        $stat = $this->_stat->get($id);
        if (!$stat) {
            return;
        }

        unset($stat['liveid']);
        $this->_model->data = array_merge($this->_model->data, $stat);
    }
}