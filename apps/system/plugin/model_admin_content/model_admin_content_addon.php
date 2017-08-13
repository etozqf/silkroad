<?php
class plugin_model_admin_content_addon
{
    protected $_content;
    protected $_addon;
    protected $_content_addon;

    function __construct(&$content)
    {
        $this->_content = $content;
        $this->_addon = loader::model('admin/addon', 'addon');
        $this->_content_addon = loader::model('admin/content_addon', 'addon');

        loader::import('lib.addonEngine', app_dir('addon'));
    }

    function after_add()
    {
        $contentid = intval($this->_content->contentid);
        $addons = value($_POST, 'addons');
        if (!$contentid || !$addons || !($addons = json_decode($addons, true))) {
            return;
        }
        // 重要，避免后续模型的循环处理
        unset($_POST['addons']);

        foreach ($addons as $engine => $post) {
            // 使得挂件可以处理前端 POST 过来的数据
            $addon_data = addonEngine::genData($engine, $post);
            if (!$addon_data) continue;

            $addon_contentid = intval(value($addon_data, 'contentid'));

            if (!$addon_contentid || !($addonid = $this->_get_content_addonid($addon_contentid))) {
                $addonid = $this->_create_addon($engine, $addon_contentid ? $addon_contentid : null, $addon_data);
            }
            $this->_bind_addon_to_content($addonid, $contentid, $engine, $addon_data);
        }

        // 以当前内容为单位，发布该内容下的挂件
        $this->_publish($contentid);
    }

    function after_edit()
    {
        $contentid = intval($this->_content->contentid);
        if (!$contentid) return;

        // 没有传递挂件参数，说明该模型下没有使用挂件
        // 注意：清空某个模型的挂件后，该参数仍会传递，只不过传递的是 '[]'
        $addons = value($_POST, 'addons');
        if (!$addons) return;
        // 重要，避免后续模型的循环处理
        unset($_POST['addons']);

        // 获取该内容原有的挂件 ID
        $old_addonids = $this->_content_addon->gets_field('addonid', array(
            'contentid' => $contentid
        ));

        $new_addonids = array();
        foreach (json_decode($addons, true) as $engine => $post) {
            $addon_data = addonEngine::genData($engine, $post);
            if (!$addon_data) continue;

            $addonid = intval(value($addon_data, 'addonid'));
            $addon_contentid = intval(value($addon_data, 'contentid'));

            if ($addonid && ($old_addon = $this->_addon->get($addonid))) {
                if ($old_addon['contentid']) {
                    if ($addon_contentid) {
                        if ($old_addon['contentid'] == $addon_contentid) {
                            $this->_update_addon($addonid, $engine, $addon_contentid, $addon_data);
                            $this->_update_content_addon($contentid, $addonid, $addon_data);
                        } else {
                            if (!($addonid = $this->_get_content_addonid($addon_contentid))) {
                                $addonid = $this->_create_addon($engine, $addon_contentid, $addon_data);
                            }
                            $this->_bind_addon_to_content($addonid, $contentid, $engine, $addon_data);
                        }
                    } else {
                        $addonid = $this->_create_addon($engine, null, $addon_data);
                        $this->_bind_addon_to_content($addonid, $contentid, $engine, $addon_data);
                    }
                } else {
                    if ($addon_contentid) {
                        if (($new_addonid = $this->_get_content_addonid($addon_contentid))) {
                            $this->_addon->delete($addonid);
                            $this->_bind_addon_to_content($new_addonid, $contentid, $engine, $addon_data);
                            $addonid = $new_addonid;
                        } else {
                            $this->_update_addon($addonid, $engine, $addon_contentid, $addon_data);
                            $this->_update_content_addon($contentid, $addonid, $addon_data);
                        }
                    } else {
                        $this->_update_addon($addonid, $engine, null, $addon_data);
                        $this->_update_content_addon($contentid, $addonid, $addon_data);
                    }
                }
            } else {
                if ($addon_contentid) {
                    if (!($addonid = $this->_get_content_addonid($addon_contentid))) {
                        $addonid = $this->_create_addon($engine, $addon_contentid, $addon_data);
                    }
                } else {
                    $addonid = $this->_create_addon($engine, null, $addon_data);
                }
                $this->_bind_addon_to_content($addonid, $contentid, $engine, $addon_data);
            }

            $new_addonids[] = $addonid;
        }

        // 删除无效的 addon 关联
        $delete_addonids = array_diff($old_addonids, $new_addonids);
        foreach ($delete_addonids as $delete_addonid) {
            $this->_content_addon->delete(array(
                'contentid' => $contentid,
                'addonid' => $delete_addonid
            ));
        }

        // 以当前内容为单位，发布该内容下的挂件
        $this->_publish($contentid);
    }

    protected function _update_addon($addonid, $engine, $contentid, &$addon_data)
    {
        $this->_addon->update(array(
            'engine' => $engine,
            'contentid' => $contentid ? $contentid : null,
            'data' => addonEngine::encodeData($addon_data['data'])
        ), $addonid);
    }

    protected function _update_content_addon($contentid, $addonid, &$addon_data)
    {
        $this->_content_addon->update(array(
            'place' => $addon_data['place']
        ), array(
            'contentid' => $contentid,
            'addonid' => $addonid
        ));
    }

    protected function _create_addon($engine, $contentid, &$addon_data)
    {
        return $this->_addon->insert(array(
            'engine' => $engine,
            'contentid' => $contentid,
            'data' => addonEngine::encodeData($addon_data['data'])
        ));
    }

    protected function _bind_addon_to_content($addonid, $contentid, $engine, &$addon_data)
    {
        $this->_content_addon->insert(array(
            'contentid' => $contentid,
            'engine' => $engine,
            'addonid' => $addonid,
            'place' => $addon_data['place']
        ));
    }

    protected function _get_content_addonid($contentid)
    {
        return $this->_addon->get_field('addonid', array('contentid' => $contentid));
    }

    function after_copy()
    {
        $contentid = intval($this->_content->contentid);
        if (!$contentid) return;

        $content_addons = $this->_content_addon->select(array(
            'contentid' => $contentid
        ));

        if (!$content_addons) return;

        foreach ($content_addons as $content_addon) {
            $content_addon['contentid'] = $contentid;
            $this->_content_addon->insert($content_addon);
        }

        $this->_publish($contentid);
    }

    function after_pass()
    {
        $this->_publish($this->_content->contentid);
    }

    function after_publish()
    {
        $this->_publish($this->_content->contentid);
    }

    protected function _publish($contentid)
    {
        $contentid = intval($contentid);
        if (!$contentid || table('content', $contentid, 'status') != 6) {
            return;
        }

        $content_addons = $this->_content_addon->select(array(
            'contentid' => $contentid
        ));
        if (!$content_addons) return;

        request(ADMIN_URL . '?app=addon&controller=addon&action=publish&contentid=' . $contentid);
    }

    protected function _publish_addon($addonid)
    {
        request(ADMIN_URL . '?app=addon&controller=addon&action=publishAddon&addonid=' . $addonid);
    }
}