<?php
define('SETTING_PATH', CACHE_PATH . 'setting' . DS);

class setting extends object
{
    private $db, $table;
    private static $objInstance;

    function __construct()
    {
        $this->db = factory::db();
        $this->table = '#table_setting'; //在db类的dbh()方法中被替换成['prefix']，即前缀cmstop
    }

    static function getInstance()
    {
        if (!self::$objInstance) {
            self::$objInstance = new setting();
        }
        return self::$objInstance;
    }

    static function get($app, $var = null)
    {
        static $settings;
        $cache = factory::cache();
        if (!isset($settings[$app])) {
            if (($settings[$app] = $cache->get('setting_' . $app)) === false) {
                $settings[$app] = self::getInstance()->cache($app);
            }
        }
        return is_null($var) ? $settings[$app] : (isset($settings[$app][$var]) ? $settings[$app][$var] : null);
    }

    function set($app, $var, $value)
    {
        $value = json_encode($value);
        $db = $this->db->prepare("REPLACE INTO `$this->table`(`app`, `var`, `value`) VALUES(?,?,?)");
        return $db->execute(array($app, $var, $value));
    }

    function set_array($app, $data)
    {
        if (!is_array($data)) return false;
        foreach ($data as $key => $value) {
            $this->set($app, $key, $value);
        }
        $this->cache($app);
        return true;
    }

    function cache($app = null, $recursive = false)
    {
        if (is_null($app)) {
            $arrapp = table('app');
            $apps = array_keys($arrapp);
            $rs = array_fill(0, count($apps), true);

            // 清空servers缓存
            clear_cache_servers();

            return array_map(array($this, 'cache'), $apps, $rs);
        } else {
            $setting = array();
            $data = $this->db->select("SELECT `var`,`value` FROM `$this->table` WHERE `app`=?", array($app));
            foreach ($data as $r) {
                $setting[$r['var']] = json_decode($r['value'], true);
            }
            $cache = factory::cache();
            $cache->set('setting_' . $app, $setting);

            // 清空servers缓存
            if (!$recursive) clear_cache_servers();

            return $setting;
        }
    }
}