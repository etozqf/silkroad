<?php
abstract class addonEngine
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
     * @return addonEngine
     * @throws Exception
     */
    static function getInstance($engine)
    {
        if (isset(self::$_instances[$engine]))
        {
            return self::$_instances[$engine];
        }

        loader::import("lib.addonEngine.$engine", app_dir('addon'));
        $class = "addonEngine_$engine";
        if (! class_exists($class, false))
        {
            throw new Exception("Addon Engine '$engine' not exists.");
        }
        return self::$_instances[$engine] = new $class();
    }

    static function render($engine, $addon)
    {
        return self::getInstance($engine)->_render($addon);
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

    static final function encodeData($data)
    {
        return json_encode((object) $data);
    }

    static function dispath($engine, $action, $post)
    {
        return self::getInstance($engine)->{$action}($post);
    }

    protected function _genHTML($template, $data)
    {
        if (! $template)
        {
            $addon = strtolower(substr(get_class($this), 12));
            $dir = ROOT_PATH.'templates'.DS.TEMPLATE.DS.'addon/engine'.DS;
            $file = $dir.$addon.'.html';
        }
        else
        {
            $dir = CACHE_PATH;
            $file = 'addon/' . md5($template) . '.php';
            $sfile = $dir . $file;
            if (! is_file($sfile))
            {
                write_file($sfile, $template);
            }
        }

        $orig_dir = $this->template->dir;
        $orig_compile_dir = $this->template->compile_dir;
        $this->template->set_dir($dir);
        $this->template->compile_dir = CACHE_PATH.'templates/addon/';
        $html = $this->template->assign($data)->fetch($file);
        $this->template->set_dir($orig_dir);
        $this->template->compile_dir = $orig_compile_dir;
        return $html;
    }

    abstract function _addView();
    abstract function _editView($addon);
    abstract function _render($addon);

    function _genData($post, $addon = null)
    {
        return $post;
    }

    function _export($addon)
    {
        return $addon;
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

    static final function renderResource($contentid = null)
    {
        $resources = array(
            '<script type="text/javascript" src="'. IMG_URL . 'js/fet.js"></script>',
            '<script type="text/javascript" src="'. IMG_URL . 'js/repos.js"></script>',
            '<script type="text/javascript">fet.setAlias({IMG_URL: "'.IMG_URL.'"})</script>'
        );
        return implode(PHP_EOL, $resources) . PHP_EOL;
    }

    static final function renderPlace($contentid, $place)
    {
        $contentid = isset($contentid) ? intval($contentid) : null;
        $place = isset($place) && ctype_alnum($place) ? $place : null;

        if (! $contentid || ! $place)
        {
            return '';
        }

        $addons = factory::db()->select("SELECT ca.`engine`, ca.`addonid`
            FROM `#table_content_addon` ca
            LEFT JOIN `#table_addon_engine` e
            ON ca.`engine` = e.`name`
            WHERE ca.`contentid` = ? AND ca.`place` = ?
            ORDER BY e.`sort` ASC, e.`engineid` ASC",
            array($contentid, $place));
        $html = array();
        if (is_array($addons))
        {
            foreach ($addons as $addon)
            {
                $html[] = '<!-- addon ' . $addon['engine'] . ' ' . $addon['addonid'] . ' start -->';
                $html[] = '<!--#include virtual="' . self::genAddonPath($addon['addonid']) . '"-->';
                $html[] = '<!-- addon ' . $addon['engine'] . ' ' . $addon['addonid'] . ' end -->';
            }
        }
        return implode(PHP_EOL, $html);
    }

    static final function getAddons($contentid)
    {
        $contentid = isset($contentid) ? intval($contentid) : null;
        if (!$contentid) return false;

        $addons = factory::db()->select("SELECT ca.`engine`, ca.`addonid`, ca.`place`, a.`data`
            FROM `#table_content_addon` ca
            LEFT JOIN `#table_addon` a ON ca.`addonid` = a.`addonid`
            LEFT JOIN `#table_addon_engine` e ON ca.`engine` = e.`name`
            WHERE ca.`contentid` = ?
            ORDER BY e.`sort` ASC, e.`engineid` ASC",
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

    static final function genAddonPath($addonid)
    {
        $dirname = $addonid > 99 ? (substr($addonid, 0, 3) . '/' . $addonid) : $addonid;
        return '/section/addon/' . $dirname . '.html';
    }
}