<?php

class plugin_mobile extends object
{
    protected $model;

    protected $db;

    public function __construct(&$model)
    {
        $this->model = $model;
        $this->db = factory::db();
    }

    function after_edit()
    {
        $this->_update_status();
    }

    function after_delete()
    {
        $referenceid = intval($this->model->contentid);
        if (!$referenceid) return;

        $mobile_contents = $this->db->select("SELECT `contentid`, `modelid` FROM `#table_mobile_content` WHERE `referenceid` = ?", $referenceid);
        if (!empty($mobile_contents)) {
            foreach ($mobile_contents as $mobile_content) {
                $contentid = $mobile_content['contentid'];
                switch (intval($mobile_content['modelid'])) {
                    case 2: // picture
                    case 7: // activity
                    case 8: // vote
                    case 9: // survey
                        $this->db->delete("DELETE FROM `#table_mobile_content` WHERE `contentid` = ?", array($contentid));
                        break;
                    default: // 弱引用
                        $this->db->update("UPDATE `#table_mobile_content` SET `referenceid` = NULL WHERE `contentid` = ?", array($contentid));
                        break;
                }
            }
        }
    }

    function after_remove()
    {
        $this->_update_status(0);
    }

    function after_restore()
    {
        $this->_update_status();
    }

    function after_publish()
    {
        $this->_update_status(6);
    }

    function after_unpublish()
    {
        $this->_update_status(0);
    }

    protected function _update_status($status = null)
    {
        $referenceid = $this->model->contentid;
        if (is_null($status)) {
            $status = table('content', $referenceid, 'status');
            if ($status != 6) {
                $status = 0;
            }
        }
        $this->db->update("UPDATE `#table_mobile_content` SET `status` = ? WHERE `referenceid` = ?", array($status, $referenceid));
    }
}