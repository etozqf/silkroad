<?php

class plugin_square_version extends object
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

    function after_update_data()
    {
        $this->_increase_version();
    }

    protected function _increase_version()
    {
        $setting = new setting();
        $version = $setting->get('mobile', 'square_version');
        $version = intval($version) + 1;
        $setting->set('mobile', 'square_version', $version);
        $setting->cache('mobile');
    }
}