<?php
class addonEngine_special extends addonEngine
{
    function _addView()
    {
        $this->view->display('engine/special/form');
    }

    function _editView($addon)
    {
        $this->view->assign($addon);
        $this->view->assign('edit', true);
        $this->view->display('engine/special/form');
    }

    function _format($post)
    {
        $post['catid_html'] = $this->renderCategory($post['contentid']);
        return self::encodeData($post);
    }

    function _export($addon)
    {
        $data = decodeData($addon['data']);
        $content = loader::model('admin/special', 'special')->get(intval($data['contentid']), '*', 'show');
        if (! $content || $content['status'] != 6)
        {
            return false;
        }
        $page = loader::model('admin/special_page', 'special')->get(intval($data['pageid']));
        return array(
            'addon' => $data,
            'content' => $content,
            'page' => $page
        );
    }

    function _render($addon)
    {
        if ($data = $this->_export($addon))
        {
            $data['uuid'] = uniqid('special-');
            return $this->_genHtml(null, $data);
        }
        return '';
    }
}