<?php
class addonEngine_activity extends addonEngine
{
    function _addView()
    {
        $this->view->display('engine/activity/form');
    }

    function _editView($addon)
    {
        $this->view->assign($addon);
        $this->view->assign('edit', true);
        $this->view->display('engine/activity/form');
    }

    function _format($post)
    {
        $post['catid_html'] = $this->renderCategory($post['contentid']);
        return $this->json->encode($post);
    }

    function _export($addon)
    {
        $data = decodeData($addon['data']);
        $contentid = intval($data['contentid']);
        if (! $contentid
            || ! ($content = loader::model('admin/activity', 'activity')->get($contentid))
            || $content['status'] != 6)
        {
            return false;
        }
        return array(
            'addon' => $data,
            'content' => $content
        );
    }

    function _render($addon)
    {
        if ($data = $this->_export($addon))
        {
            return $this->_genHTML(null, $data);
        }
        return '';
    }
}