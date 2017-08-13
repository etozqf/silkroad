<?php
abstract class mobileAddon
{
    private static $_ENV = null;
    private static $_instances = array();

    function __get($key)
    {
        return self::$_ENV[$key];
    }

    static function setEnv(array $env)
    {
        self::$_ENV = $env;
    }

    /**
     * @param $engine
     * @return mobileAddon
     * @throws Exception
     */
    static function getInstance($engine)
    {
        if (isset(self::$_instances[$engine]))
        {
            return self::$_instances[$engine];
        }

        loader::import("lib.addon.$engine", app_dir('mobile'));
        $class = "mobileAddon_$engine";
        if (! class_exists($class, false))
        {
            throw new Exception("Mobile Addon '$engine' not exists.");
        }
        return self::$_instances[$engine] = new $class();
    }

    static function addView($engine)
    {
        return self::getInstance($engine)->_addView();
    }

    static function editView($engine, $addon)
    {
        return self::getInstance($engine)->_editView($addon);
    }

    static function genData($engine, $addon)
    {
        return self::getInstance($engine)->_genData($addon);
    }

    static function export($engine, $addon)
    {
        return self::getInstance($engine)->_export($addon);
    }

    static function render($engine, $device, $addon)
    {
        return self::getInstance($engine)->_render($device, $addon);
    }

    static final function encodeData($data)
    {
        return json_encode((object) $data);
    }

    static function dispath($engine, $action, $post)
    {
        return self::getInstance($engine)->{$action}($post);
    }

    abstract function _addView();
    abstract function _editView($addon);
    abstract function _render($device, $addon);

    function _genData($post, $addon = null)
    {
        return $post;
    }

    function _export($addon)
    {
        return $addon;
    }

    protected function _genHTML($engine, $device, $data)
    {
        $dir = ROOT_PATH.'templates'.DS.TEMPLATE.DS.'mobile'.DS.$device.DS.'addon'.DS;
        $file = $dir.$engine.'.html';

        $template = factory::template('mobile');
        $template->savepoint();
        $orig_dir = $template->dir;
        $orig_compile_dir = $template->compile_dir;
        $template->set_dir($dir);
        $template->compile_dir = CACHE_PATH.'templates/mobile/addon/';
        $html = $template->assign($data)->fetch($file);
        $template->set_dir($orig_dir);
        $template->compile_dir = $orig_compile_dir;
        $template->rollback();
        return $html;
    }

    static function renderCategory($contentid)
    {
        $contentid = intval($contentid);
        if (! $contentid) return '';
        $catid = table('content', $contentid, 'catid');
        if (! $catid) return '';
        $category = table('category', $catid);
        $parentids = $category['parentids'];
        $html = '';
        foreach (explode(',', $parentids) as $parentid)
        {
            $parent = table('category', intval($parentid));
            $html .= '<a href="' . $parent['url'] . '" target="_blank" data-catid="' . $parentid . '">' . $parent['name'] . '</a> &gt; ';
        }
        $html .= '<a href="' . $category['url'] . '" target="_blank" data-catid="' . $catid . '">' . $category['name'] . '</a>';
        return $html;
    }

    static function renderMobileCategory($contentid)
    {
        $contentid = intval($contentid);
        if (! $contentid) return '';

        $catids = loader::model('admin/mobile_content_category', 'mobile')->gets_field('catid', array(
            'contentid' => $contentid
        ));
        if (! $catids) return '';

        $html = array();
        foreach ($catids as $catid)
        {
            $parent = table('mobile_category', intval($catid));
            $html[] = '<a data-catid="' . $catid . '">' . $parent['catname'] . '</a>';
        }
        return implode(', ', $html);
    }

    static final function getAddons($contentid)
    {
        $contentid = isset($contentid) ? intval($contentid) : null;
        if (!$contentid) return false;

        $addons = factory::db()->select("SELECT mca.`engine`, mca.`addonid`, mca.`place`, ma.`data`
            FROM `#table_mobile_content_addon` mca
            LEFT JOIN `#table_mobile_addon` ma ON mca.`addonid` = ma.`addonid`
            WHERE mca.`contentid` = ?
            ORDER BY mca.`addonid` DESC",
            array($contentid));
        if (!is_array($addons)) return false;

        $result = array();
        foreach ($addons as $addon)
        {
            if (!is_array($result[$addon['place']])) $result[$addon['place']] = array();
            $export = self::export($addon['engine'], $addon);
            $export['engine'] = $addon['engine'];
            $result[$addon['place']][] = $export;
        }
        return count($result) ? $result : false;
    }
}