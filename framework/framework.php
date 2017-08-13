<?php
//define('DS', DIRECTORY_SEPARATOR);
define('TIME', time());
define('MICROTIME', microtime(true));
defined('FW_PATH') or define('FW_PATH', dirname(__FILE__).DS);

set_include_path(FW_PATH.PATH_SEPARATOR.get_include_path());

xhprof_start();

require(FW_PATH.'loader.php');

import('core.object');
import('core.exception');
import('core.function');
import('core.config');
import('core.setting');
import('core.log');
import('core.tag');
import('core.observer');

import('core.model');
import('core.view');
import('core.controller');

import('factory');
import('env.request');
import('env.response');
import('filter.input');
import('filter.output');
import('form.form_element');
import('form.element');
import('helper.folder');
import('helper.date');

define('IP', request::get_clientip());
define('URL', request::get_url());

if (function_exists('get_magic_quotes_runtime') && get_magic_quotes_runtime())
{
    set_magic_quotes_runtime(false);
}

function shutdown_function()
{
    $e = error_get_last();
    if(isset($e['type']))
    {
        if(in_array($e['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR)))
        {
            php_error_log($e['type'],$e['message'],$e['file'],$e['line'],NULL);
        }
    }
}

function xhprof_start()
{
    if (extension_loaded('xhprof') && defined('XHPROF_DEBUG') && XHPROF_DEBUG) {
        $default_flags = XHPROF_FLAGS_NO_BUILTINS | XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY;
        $ignore_functions =  array();
        xhprof_enable($default_flags, $ignore_functions);
    }
}

if(defined('LOG_ERROR') && LOG_ERROR)
{
    register_shutdown_function('shutdown_function');
    //set_error_handler('php_error_log');
}

if(function_exists('date_default_timezone_set')) date_default_timezone_set(config('config', 'timezone'));

filter::input();


