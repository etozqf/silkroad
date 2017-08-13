<?php
class thirdparty
{
    static function getInstance($config)
    {
        loader::import('lib.thirdparty.thirdparty_api');
        $class = 'thirdparty_'.$config['apitype'];
        $result = loader::import('lib.thirdparty.partner.'.$config['apitype']);
        if(!$result)
        {
            $class = 'thirdparty_default';
            loader::import('lib.thirdparty.partner.default');
        }
        return new $class($config);
    }
}