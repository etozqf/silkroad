<?php
class plugin_reference extends object
{
    protected $_model;

    function __construct(&$model)
    {
        $this->_model = $model;
    }

    function after_edit()
    {
        $this->_update();
    }

    function after_publish()
    {
        $this->_update();
    }

    function after_unpublish()
    {
        $this->_update();
    }

    function after_restore()
    {
        $this->_update();
    }

    function after_pass()
    {
        $this->_update();
    }

    function after_remove()
    {
        $this->_update();
    }

    function after_move()
    {
        $this->_update();
    }

    protected function _update()
    {
        if (! ($contentid = intval($this->_model->contentid))) return false;
        $db = factory::db();
        if (!($references = $db->select("SELECT `contentid` FROM `#table_link` WHERE `referenceid` = ?", array($contentid)))) return false;
        if (!($data = $db->get("SELECT * FROM `#table_content` WHERE `contentid` = ?", array($contentid)))) return false;
        foreach ($references as &$reference)
        {
            $reference = $reference['contentid'];
        }
        $update = array();
        foreach (array('url', 'status', 'published', 'publishedby', 'unpublished', 'unpublishedby', 'modified', 'modifiedby') as $key)
        {
            $update[] = "`{$key}` = " . (is_numeric($data[$key]) ? $data[$key] : "'{$data[$key]}'");
        }
        return $db->update("UPDATE `#table_content` SET " . implode(', ', $update) . " WHERE `contentid` IN (" . implode_ids($references) . ") AND `status` IN (3, 5, 6)");
    }
}