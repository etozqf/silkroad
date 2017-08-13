<?php
class plugin_update_slider_sorttime extends object
{
    protected $_model;

    protected $_db;

    function __construct(&$model)
    {
        $this->_model = $model;
        $this->_db = factory::db();
    }

    function after_add()
    {
        $this->_update();
    }

    function after_edit()
    {
        $this->_update();
    }

    function after_delete()
    {
        $this->_update();
    }

    protected function _update()
    {
        $catid = intval($this->_model->catid);

        // 更新模型 sorttime 标志
        $this->_db->update("REPLACE INTO `#table_setting` (`app`, `var`, `value`) VALUES (?, ?, ?)", array(
            'mobile', 'sorttime_special_slider', TIME
        ));

        // 更新频道 sorttime 标志
        $this->_db->update("REPLACE INTO `#table_setting` (`app`, `var`, `value`) VALUES (?, ?, ?)", array(
            'mobile', 'sorttime_category_slider_' . $catid, TIME
        ));

        $setting = new setting();
        $setting->cache('mobile');
    }
}