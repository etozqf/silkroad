<?php
class plugin_addon
{
    protected $_content;
    protected $_addon;
    protected $_content_addon;

    function __construct(&$content)
    {
        $this->_content = $content;
        $this->_addon = loader::model('admin/mobile_addon', 'mobile');
        $this->_content_addon = loader::model('admin/mobile_content_addon', 'mobile');

        loader::import('lib.addon', app_dir('mobile'));
    }

    function after_add()
    {
        // 通知挂件主视频已添加
        $cookie = factory::cookie();
        $cookie_data = $this->_content->data;
        $cookie_data['contentid'] = $this->_content->contentid;
        unset($cookie_data['content']);
        $cookie_data = json_encode($cookie_data);
        $cookie->set('addonvideo', $cookie_data, 60);

        $contentid = intval($this->_content->contentid);
        $addons = value($_POST, 'addons');
        if (!$contentid || !$addons || !($addons = json_decode($addons, true))) {
            return;
        }
        // 重要，避免后续模型的循环处理
        unset($_POST['addons']);

        foreach ($addons as $engine => $post) {
            // 使得挂件可以处理前端 POST 过来的数据
            $addon_data = mobileAddon::genData($engine, $post);
            if (!$addon_data) continue;

            $addon_contentid = intval(value($addon_data, 'contentid'));

            if (!$addon_contentid || !($addonid = $this->_get_content_addonid($addon_contentid))) {
                $addonid = $this->_create_addon($engine, $addon_contentid ? $addon_contentid : null, $addon_data);
            }
            $this->_bind_addon_to_content($addonid, $contentid, $engine, $addon_data);
        }
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
            $addon_data = mobileAddon::genData($engine, $post);
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
    }

    protected function _update_addon($addonid, $engine, $contentid, &$addon_data)
    {
        $this->_addon->update(array(
            'engine' => $engine,
            'contentid' => $contentid ? $contentid : null,
            'data' => mobileAddon::encodeData($addon_data['data'])
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
            'data' => mobileAddon::encodeData($addon_data['data'])
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
    }
}