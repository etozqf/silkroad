<?php
class plugin_log extends object
{
    protected $_model;

    function __construct(&$model)
    {
        $this->_model = $model;
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
            loader::model('admin/mobile_content_log', 'mobile')->add(array(
                'contentid' => $contentid,
                'action' => $action,
                'title' => $content['title'],
                'url' => $content['url']
            ));
        }
    }
}