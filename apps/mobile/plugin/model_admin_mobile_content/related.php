<?php
class plugin_related extends object
{
    protected $_model;

    /**
     * @var model_admin_mobile_content_related
     */
    protected $_model_related;

    function __construct(&$model)
    {
        $this->_model = $model;
        $this->_model_related = loader::model('admin/mobile_content_related', 'mobile');
    }

    function before_add()
    {
        $related = json_decode(value($_POST, 'relateds'), true);
        $this->_model->data['related'] = count($related) ? 1 : 0;
    }

    function after_add()
    {
        $this->_update_related();
    }

    function after_edit()
    {
        $this->_update_related();
    }

    function after_get()
    {
        $contentid = $this->_model->contentid;
        if (!$contentid) return;

        $this->_model->data['relateds'] = $this->_model_related->ls_by_contentid($this->_model->contentid);
    }

    protected function _update_related()
    {
        $contentid = $this->_model->contentid;
        if (!$contentid) return;

        $relateds = json_decode(value($_POST, 'relateds'), true);
        $this->_model_related->delete(array('contentid' => $contentid));
        foreach ($relateds as $related) {
            $this->_model_related->insert(array(
                'contentid' => $contentid,
                'related_contentid' => $related['contentid']
            ));
        }
    }
}