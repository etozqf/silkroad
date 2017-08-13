<?php
class plugin_sorttime extends object
{
    protected $_model;

    protected $_db;

    function __construct(&$model)
    {
        $this->_model = $model;
        $this->_db = factory::db();
    }

    function __call($method, $args)
    {
        $action = substr($method, 6);
        $contentid = intval($this->_model->contentid);
        if ($contentid && in_array($action, array(
            'add', 'edit', 'quickedit', 'remove', 'restore', 'delete',
            'pass', 'publish', 'unpublish', 'stick', 'unstick', 'bumpup',
        ), true)) {
            $content = table('mobile_content', $contentid);

            // 更新模型 sorttime 标志
            $mobile_models = mobile_model();
            $this->_db->update("REPLACE INTO `#table_setting` (`app`, `var`, `value`) VALUES (?, ?, ?)", array(
                'mobile', 'sorttime_'.$mobile_models[$content['modelid']]['alias'], TIME
            ));

            // 更新频道 sorttime 标志
            $rows = $this->_db->select("SELECT `catid` FROM `#table_mobile_content_category` WHERE `contentid` = ?", array($contentid));
            if ($rows) {
                foreach ($rows as $row) {
                    $this->_db->update("UPDATE `#table_mobile_category` SET `sorttime` = ? WHERE `catid` = ?", array(TIME, $row['catid']));
                }
            }

            $setting = new setting();
            $setting->cache('mobile');
        }
    }
}