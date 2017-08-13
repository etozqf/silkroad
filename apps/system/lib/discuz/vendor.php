<?php
define('DISCUZ_VENDOR_PATH', dirname(__FILE__) . '/version/');

abstract class discuz_vendor
{
    public $prefix;

    public $table_forum;

    public $table_thread;

    public $table_post;

    public $table_member;

    protected $_error;

    protected $_db;

    function connect(array $options)
    {
        $this->_db = factory::db($options);

        if (! $this->_db || ! $this->_db->version())
        {
            throw new Exception('无法连接到指定的 Discuz! 数据库');
        }

        return $this;
    }

    abstract function member_query($fields = '*', $options = array(), $page = 1, $pagesize = null);

    abstract function member_count();

    abstract function forum_query($fields = '*', $options = array());

    abstract function has_table_forum();

    abstract function has_table_thread();

    function error()
    {
        return $this->_error;
    }

    final static function instance($version, array $args = array())
    {
        static $instances = array();

        if (isset($instances[$version]))
        {
            return $instances[$version];
        }

        $filename = DISCUZ_VENDOR_PATH . $version . '.php';
        $classname = 'discuz_' . $version;

        if (! ctype_alnum($version) || ! is_file($filename))
        {
            throw new Exception('暂不支持的 Discuz! 版本');
        }

        require_once $filename;

        if (! class_exists($classname))
        {
            throw new Exception('Discuz! 版本不合法');
        }

        $instances[$version] = new $classname($args);
        return $instances[$version];
    }

    final static function versions()
    {
        static $result = null;

        if (! is_null($result))
        {
            return $result;
        }

        $result = array();
        foreach (glob(DISCUZ_VENDOR_PATH . '*.php') as $filename)
        {
            $version = strtolower(basename($filename, '.php'));
            $result[$version] = strtoupper($version);
        }
        return $result;
    }
}