<?php

class plugin_category extends object
{
    protected $_model;

    protected $_db;

    protected $_model_bind;

    /**
     * @var model_admin_mobile_content_stat
     */
    protected $_model_stat;

    function __construct(&$model)
    {
        $this->_db = factory::db();
        $this->_model = $model;
        $this->_model_bind = loader::model('admin/mobile_content_category', 'mobile');
        $this->_model_stat = loader::model('admin/mobile_content_stat', 'mobile');
    }

    function after_add()
    {
        $this->_update_catid();
    }

    function after_edit()
    {
        $this->_update_catid();
    }

    function after_get()
    {
        $contentid = $this->_model->contentid;
        if ($contentid) {
            $this->_model->data['catids'] = implode(',', $this->_model_bind->gets_field('catid', array(
                'contentid' => $contentid
            )));
        }
    }

    protected function _update_catid()
    {
        $contentid = $this->_model->contentid;
        if (!$contentid) {
            throw new Exception('内容ID不正确');
        }

        $catids = id_format(value($this->_model->data, 'catid'));
        if (!$catids) {
            throw new Exception('请选择频道');
        }

        $catids = (array) $catids;
        $orig_catids = $this->_model_bind->gets_field('catid', array(
            'contentid' => $contentid
        ));
        $priv_catids = mobile_category_ids(true);
        if (count($orig_catids)) {
            // 原来有频道
            $diff_add_catids = array_diff($catids, $orig_catids);
            $diff_removed_catids = array_diff($orig_catids, $catids);
            if (count($diff_removed_catids)) {
                $diff_allow_removed_catids = array_intersect($diff_removed_catids, $priv_catids);
                if (count($diff_allow_removed_catids)) {
                    foreach ($diff_allow_removed_catids as $catid) {
                        $this->_model_bind->delete(array(
                            'contentid' => $contentid,
                            'catid' => $catid
                        ));
                        $this->_update_category($catid);
                    }
                }
            }
            if (count($diff_add_catids)) {
                $diff_allow_add_catids = array_intersect($diff_add_catids, $priv_catids);
                if (count($diff_allow_add_catids)) {
                    foreach ($diff_allow_add_catids as $catid) {
                        $this->_model_bind->insert(array(
                            'contentid' => $contentid,
                            'catid' => $catid
                        ));
                        $this->_update_category($catid);
                    }
                }
            }
        } else {
            // 原来没有频道
            $catids = array_intersect($catids, $priv_catids);
            if (count($catids)) {
                foreach ($catids as $catid) {
                    $this->_model_bind->insert(array(
                        'contentid' => $contentid,
                        'catid' => $catid
                    ));
                    $this->_update_category($catid);
                }
            }
        }
    }

    protected function _update_category($catid)
    {
        // 更新 sorttime
        $this->_db->update("UPDATE `#table_mobile_category` SET `sorttime` = ? WHERE `catid` = ?", array(TIME, $catid));

        // 更新频道统计缓存
        $this->_model_stat->clear_cache($catid);
    }
}