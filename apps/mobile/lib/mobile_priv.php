<?php

class mobile_priv extends object
{
    static $priv, $category, $userid, $roleid, $cache;

    static function init($userid, $roleid)
    {
        self::$userid = $userid;
        self::$roleid = $roleid;
        if ($roleid == 1) return true;
        if (!self::$cache) {
            self::$cache = factory::cache();
        }
        self::$priv = self::$cache->get('mobile_priv_' . $userid);
        if (!self::$priv) {
            self::$priv = self::cache($userid, $roleid);
        }
        return true;
    }

    static function cache($userid, $roleid)
    {
        if (!self::$cache) {
            self::$cache = factory::cache();
        }

        self::$userid = $userid;
        self::$roleid = $roleid;

        if ($roleid == 1) {
            return true;
        }

        $priv = array(
            'catid' => array()
        );
        $db = factory::db();
        $data = $db->select("SELECT `catid` FROM `#table_mobile_category_priv` WHERE `userid` = ?", array($userid));
        foreach ($data as $r) {
            $priv['catid'][] = intval($r['catid']);
        }

        self::$cache->set('mobile_priv_' . $userid, $priv);
        return $priv;
    }

    static function category($catid)
    {
        if (self::$roleid == 1) return true;
        if (is_null($catid)) {
            return !empty(self::$priv['catid']);
        }
        if (is_null(self::$category)) self::$category = table('mobile_category');
        return in_array($catid, self::$priv['catid']);
    }

    static function button($action = null, $modelid = 0, $controller = null)
    {
        $mobile_models = mobile_model();
        if (!$modelid) {
            foreach ($mobile_models as $_modelid => $_model) {
                if (priv::aca('mobile', (is_null($controller) ? $_model['alias'] : $controller), $action)) {
                    return true;
                }
            }
            return false;
        } else {
            $modelalias = $mobile_models[$modelid]['alias'];
            return priv::aca('mobile', (is_null($controller) ? $modelalias : $controller), $action);
        }
    }
}