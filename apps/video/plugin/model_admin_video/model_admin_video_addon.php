<?php
class plugin_model_admin_video_addon
{
    protected $_content;
    protected $_addon;

    function __construct(& $content)
    {
        $this->_content = $content;
        $this->_addon = loader::model('admin/addon', 'addon');
    }

    function after_edit()
    {
        $this->_publish();
    }

    function after_publish()
    {
        $this->_publish();
    }

    function after_unpublish()
    {
        $this->_unpublish();
    }

    function after_restore()
    {
        $this->_publish();
    }

    function after_pass()
    {
        $this->_publish();
    }

    function after_remove()
    {
        $this->_unpublish();
    }

    function after_delete()
    {
        $this->_unpublish();
    }

    protected function _validate()
    {
        $contentid = intval($this->_content->contentid
            ? $this->_content->contentid
            : (isset($this->_content->data) && isset($this->_content->data['contentid'])
                ? $this->_content->data['contentid']
                : null
            ));
        // 如果该内容被选取为挂件，则重新发布该内容对应的挂件
        if ($contentid
            && factory::db()->get("SELECT `contentid` FROM `#table_content` WHERE `contentid` = ? AND `status` = 6", array($contentid))
            && ($addonid = $this->_addon->get_field('addonid', array('contentid' => $contentid)))
        )
        {
            return $addonid;
        }
        return false;
    }

    protected function _publish()
    {
        if (($addonid = $this->_validate()))
        {
            request(ADMIN_URL . '?app=addon&controller=addon&action=publishAddon&addonid=' . $addonid);
        }
    }

    protected function _unpublish()
    {
        if (($addonid = $this->_validate()))
        {
            request(ADMIN_URL . '?app=addon&controller=addon&action=unpublishAddon&addonid=' . $addonid);
        }
    }
}