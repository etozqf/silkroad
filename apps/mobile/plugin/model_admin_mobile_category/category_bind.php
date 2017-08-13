<?php

class plugin_category_bind extends object
{
    protected $_model;

    function __construct(&$model)
    {
        $this->_model = $model;
    }

    function after_add()
    {
        $this->_update_bind(true);
    }

    function after_edit()
    {
        $this->_update_bind();
    }

    protected function _update_bind($ignore_empty = false)
    {
        $catid = $this->_model->catid;
        if (!$catid) return;

        $bindids = value($_POST, 'catid_bind');
        if (!$bindids && $ignore_empty) return;

        $bindids = id_format($bindids);
        if (!$bindids && $ignore_empty) return;

        $bindids = (array) $bindids;
        $model_bind = loader::model('admin/mobile_category_bind', 'mobile');
        $model_bind->delete_by('mobile_catid', $catid);
        foreach ($bindids as $bindid) {
            $model_bind->insert(array(
                'mobile_catid' => $catid,
                'catid' => $bindid
            ));
        }
    }
}