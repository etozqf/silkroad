<?php
function mobile_model()
{
    return app_config('mobile', 'model');
}

function mobile_status()
{
    return app_config('mobile', 'status');
}

function mobile_category($priv = false)
{
    static $caches = array();
    $priv = intval($priv);
    if (isset($caches[$priv])) {
        return $caches[$priv];
    }
    $categorys = loader::model('admin/mobile_category', 'mobile')->select(array('disabled' => 0));
    loader::lib('mobile_priv', 'mobile');
    if ($categorys) {
        foreach ($categorys as $index => &$category) {
            if ($priv && !mobile_priv::category($category['catid'])) unset($categorys[$index]);
        }
    }
    return $caches[$priv] = $categorys;
}

function mobile_classify($modelid, $priv = false)
{
    static $caches = array();
    $priv = intval($priv);
    if (isset($caches[$priv])) {
        return $caches[$priv];
    }
    $categorys = loader::model('admin/mobile_classify', 'mobile')->select(array('disabled' => 0, 'modelid' => $modelid));
    return $caches[$priv] = $categorys;
}

function mobile_category_ids($priv)
{
    $categorys = mobile_category($priv);
    if (count($categorys)) {
        $ids = array();
        foreach ($categorys as &$category) {
            $ids[] = $category['catid'];
        }
        return $ids;
    }
    return array();
}

function mobile_category_ids_front()
{
    $cache = factory::cache();
    if ($catids = $cache->get('category_ids_front')) {
        return $catids;
    } else {
        $categorys = loader::model('admin/mobile_category', 'mobile')->select(array('disabled' => 0));
        if (count($categorys)) {
            $ids = array();
            foreach ($categorys as &$category) {
                $ids[] = $category['catid'];
            }
            $cache->set('category_ids_front', $ids);
            return $ids;
        }
    }
}

function mobile_updown($field, &$model, $where = '')
{
    $id = intval(value($_REQUEST, $field));
    $old_index = intval(value($_REQUEST, 'old'));
    $now_index = intval(value($_REQUEST, 'now'));

    if ($old_index != $now_index) {
        $db = factory::db();
        $db->beginTransaction();
        try {
            $diff = $old_index - $now_index;
            if ($diff > 0) {
                // 旧的大于新的，向上移动
                $func = 'set_inc';
                $ids = $model->gets_field($field, "`sort` >= $now_index AND `sort` < $old_index".($where ? ' AND '.$where : ''));
            } else {
                // 旧的小于新的，向下移动
                $func = 'set_dec';
                $ids = $model->gets_field($field, "`sort` > $old_index AND `sort` <= $now_index".($where ? ' AND '.$where : ''));
            }

            foreach ($ids as $range_id) {
                if (!$model->{$func}('sort', $range_id, 1)) {
                    throw new Exception($model->error());
                }
            }

            if (!$model->update(array('sort' => $now_index), $id)) {
                throw new Exception($model->error());
            }

            $db->commit();

            return true;
        } catch (Exception $ex) {
            $db->rollBack();
        }
    }

    return false;
}

function tag_mobile_content(array $opts = array())
{
    $options = array('status' => 6);

    if (!empty($opts['catid'])) $options['catid'] = $opts['catid'];
    if (!empty($opts['modelid'])) $options['modelid'] = $opts['modelid'];
    if (isset($opts['inslider'])) $options['inslider'] = intval($opts['inslider']);
    if (isset($opts['thumb'])) $options['thumb'] = intval($opts['thumb']);

    $published = isset($opts['published']) ? $opts['published'] : '';
    if (!empty($published)) {
        if (strpos($published, ',') === false) {
            if (is_numeric($published) && strlen($published) < 4) {
                $published = strtotime("-$published day");
                $options['published_min'] = $published;
            } else {
                $options['published_min'] = $published;
                $options['published_max'] = $published;
            }
        } elseif (preg_match("/^\s*([\d]{4}\-[\d]{1,2}\-[\d]{1,2})?\s*\,\s*([\d]{4}\-[\d]{1,2}\-[\d]{1,2})?\s*$/", $published, $m)) {
            if ($m[1]) $options['published_min'] = $m[1];
            if ($m[2]) $options['published_max'] = $m[2];
        }
    }

    $where = isset($opts['where']) ? $opts['where'] : '';
    if (!empty($where)) $options['where'] = $where;

    $pagesize = !empty($opts['size']) ? intval($opts['size']) : 0;
    $page = isset($opts['page']) ? intval($opts['page']) : 1;

    return loader::model('mobile_content', 'mobile')->search($options, $page, $pagesize);
}

function tag_mobile_slider(array $opts = array())
{
    $options = array('status' => 6);

    if (empty($opts['catid'])) return false;
    $options['catid'] = $opts['catid'];
    $options['inslider'] = 1;
    $size = !empty($opts['size']) ? intval($opts['size']) : null;

    $data = loader::model('mobile_content', 'mobile')->search($options, 0, $size);
    if (!empty($data)) {
        $contentids = array();
        foreach ($data['data'] as &$row) {
            $contentids[] = $row['contentid'];
        }
        if (!empty($contentids)) {
            $thumbs = array();
            $sliders = loader::model('admin/mobile_slider', 'mobile')->select("`catid` = {$options['catid']} AND `contentid` IN (".implode_ids($contentids).")", "`contentid`, `thumb`");
            foreach ($sliders as $slider) {
                $thumbs[$slider['contentid']] = $slider['thumb'];
            }
            foreach ($data['data'] as &$row) {
                $row['thumb_slider'] =  $thumbs[$row['contentid']];
            }
        }
    }

    return $data;
}

function mobile_addon($contentid = 0)
{
    $uuid = uniqid('mod-addon-');
    $url_prefix = ADMIN_URL . 'apps/mobile/';
    $data = <<<EOF
<div class="mod-addon" id="{$uuid}"></div>
<link rel="stylesheet" type="text/css" href="{$url_prefix}css/addon.css" />
<script type="text/javascript" src="{$url_prefix}js/addon.js"></script>
<script type="text/javascript">$(function() { MobileAddon.render('#{$uuid}', {$contentid}); });</script>
EOF;
    return $data;
}

/**
 * 判断浏览器头
 * -- 如果是手机，返回相应的系统
 */
function mobile_agent($agent='')
{
    $mobile_agent = app_config('mobile', 'config.agent');
    if(!$agent) $agent = $_SERVER['HTTP_USER_AGENT'];
    foreach($mobile_agent as $k=>$t)
    {
        foreach($t as $r)
        {
            if(!(stripos($agent, $r) === false))
            {
                return $k;
            }
        }
    }
    return false;
}

function mobile_length_check($string, $length)
{
    return str_natcount($string) <= $length;
}

function mobile_url($controller_action, $args = '')
{
    list($controller, $action) = explode('/', $controller_action, 2);
    $url = MOBILE_URL . ($controller ? ($controller . '/') : '');
    if ($controller && !empty($action)) {
        $url .= $action;
    }
    return $url . ($args ? ('?' . $args) : '');
}

function mobile_content_ad($type = 'mobile')
{
    return loader::model('mobile_ad', 'mobile')->render_content($type);
}