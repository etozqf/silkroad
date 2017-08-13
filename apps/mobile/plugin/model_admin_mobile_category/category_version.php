<?php

class plugin_category_version extends object
{
    function __construct(&$model) {}

    function after_add()
    {
        $this->_increase_version();
    }

    function after_edit()
    {
        $this->_increase_version();
    }

    function after_delete()
    {
        $this->_increase_version();
    }

    function after_enable()
    {
        $this->_increase_version();
    }

    function after_disable()
    {
        $this->_increase_version();
    }

    protected function _increase_version()
    {
        $setting = new setting();
        $version = $setting->get('mobile', 'category_version');
        $version = intval($version) + 1;
        $setting->set('mobile', 'category_version', $version);
        $setting->cache('mobile');
    }
}