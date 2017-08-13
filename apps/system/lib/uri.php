<?php

/**
 * 路径处理类
 *
 * 各路径中，支持不同的变量设置
 *
 * 栏目首页URL规则：{parentdir}/{alias}/index.shtml
 *  -- {parentdir}变量即栏目根目录，由发布点、是否顶级栏目及上级栏目英文名决定。
 *      若是顶级栏目，即频道，那么{parentdir}是发布点地址。
 *      若非顶级栏目，即子栏目，那么{parentdir}由发布点地址及上级栏目英文名组成。依次类推。{alias}变量代表栏目英文名。
 *
 * 列表页URL规则：{parentdir}/{alias}/{model}{page}.shtml
 *  -- {parentdir}变量及{alias}变量见栏目首页URL规则所讲。{model}变量按照内容模型生成列表，现已弃用。{page}即列表分页。
 *
 * 内容页URL规则：{year}/{month}{day}/{contentid}{page}.shtml
 *  -- {year}变量、{month}变量、{day}变量分别代表内容发布年、月、日，{contentid}变量为内容id，{page}变量代表内容页分页
 */
class uri
{
    private $db;

    function __construct()
    {
        $this->db = factory::db();
    }

    function category($catid, $modelid = null, $page = null, $r = array())
    {
        if (empty($r)) {
            $r = table('category', $catid);
            if (!$r) return false;
        }
        $r['parentdir'] = '';
        if ($r['parentids']) {
            $parentdir = array();
            $category = table('category');
            $parentids = explode(',', $r['parentids']);
            foreach ($parentids as $pid) {
                $parentdir[] = $category[$pid]['alias'];
            }
            $r['parentdir'] = implode('/', $parentdir);
        }

        if (is_null($modelid)) {
            $r['model'] = '';
        } else {
            $r['modelid'] = $modelid;
            $r['model'] = table('model', $modelid, 'alias');
        }
        if (is_null($page)) {
            $urlrule = $r['urlrule_index'];
        } else {
            $r['page'] = $page;
            $urlrule = $r['urlrule_list'];
        }
        $url = $r['path'] . '/' . $urlrule;

        if (!(strpos($url, '{$') === false)) {
            $url = str_replace('{$', '{', $url);
        }
        preg_match_all('/\{([a-z]+)\}/', $url, $vars);
        if (isset($vars[1]) && count($vars[1])) {
            foreach ($vars[1] as $var) {
                if (isset($r[$var])) {
                    $url = str_replace('{' . $var . '}', $r[$var], $url);
                } elseif (isset($$var)) {
                    $url = str_replace('{' . $var . '}', $$var, $url);
                }
            }
        }

        $result = $this->psn($url);
        if (empty($page) && $r['url'] != $result['url']) {
            $this->db->exec("UPDATE `#table_category` SET `url`='{$result['url']}' WHERE `catid`=$catid");
        }
        return $result;
    }

    function content($contentid, $page = null, $r = array())
    {
        if (empty($r)) {
            $r = $this->db->get("SELECT * FROM #table_content WHERE contentid=?", array($contentid));
            if (!$r) return false;
        }
        $catid = $r['catid'];
        $published = $r['published'];
        $c = table('category', $catid);
        $url = $c['path'] . '/' . $c['urlrule_show'];
        $year = date('Y', $published);
        $month = date('m', $published);
        $day = date('d', $published);
        $page = (!is_null($page) && $page > 1) ? '_' . $page : '';
        $alias = $c['alias'];
        $parentdir = '';
        if ($c['parentids']) {
            $parentdir = array();
            $category = table('category');
            $parentids = explode(',', $c['parentids']);
            foreach ($parentids as $pid) {
                $parentdir[] = $category[$pid]['alias'];
            }
            $parentdir = implode('/', $parentdir);
        }

        if (!(strpos($url, '{$') === false)) {
            $url = str_replace('{$', '{', $url);
        }
        preg_match_all('/\{([a-z]+)\}/', $url, $vars);
        if (isset($vars[1]) && count($vars[1])) {
            foreach ($vars[1] as $var) {
                if (isset($$var)) {
                    $url = str_replace('{' . $var . '}', $$var, $url);
                }
            }
        }

        $result = $this->psn($url);
        if (empty($page) && $r['url'] != $result['url'] && $r['modelid'] != 3) {
            $this->db->exec("UPDATE `#table_content` SET `url`='{$result['url']}' WHERE `contentid`=$contentid");
            if (!empty($r['topicid'])) {
                $this->db->exec("UPDATE `#table_comment_topic` SET `url`='{$result['url']}' WHERE `topicid`={$r['topicid']}");
            }
        }
        return $result;
    }

    function page($url)
    {
        return $this->psn($url);
    }

    function tag($name)
    {
        return APP_URL . 'tags.php?tag=' . urlencode($name);
    }

    function psn($url)
    {
        if (!preg_match('|^{psn:(\d+)}(.*)$|i', $url, $m)) {
            return false;
        }
        $psnid = $m[1];
        if (!($pos = table('psn', $psnid))) {
            return false;
        }

        $psn = array(
            "{PSN:{$psnid}}"
        );
        $url = array(
            rtrim($pos['url'], '/')
        );
        $path = array(
            rtrim(WWW_PATH, '/'),
            rtrim(str_replace('\\', '/', $pos['path']), '/')
        );
        if (!$path[1]) {
            unset($path[1]);
        }
        $m[2] = preg_replace('|/+|', '/', trim(str_replace('\\', '/', $m[2]), '/'));

        // TODO: 这里曾经遇到过PSN名重复的问题.后来fixed的代码有问题被移除又不明白一开始遇到的问题条件是什么了

        if (strlen($m[2])) {
            $psn[] = $m[2];
            $url[] = preg_replace('|index\.[a-z]+$|i', '', $m[2]);
            $path[] = $m[2];
        }
        return array(
            'psn' => implode('/', $psn),
            'url' => implode('/', $url),
            'path' => implode('/', $path)
        );
    }
}
