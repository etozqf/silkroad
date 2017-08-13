<?php
class addonEngine_map extends addonEngine
{
    function _addView()
    {
        $this->view->display('engine/map/form');
    }

    function _editView($addon)
    {
        $this->view->assign($addon);
        $this->view->assign('edit', true);
        $this->view->display('engine/map/form');
    }

    function _format($post)
    {
        $post['catid_html'] = $this->renderCategory($post['contentid']);
        return $this->json->encode($post);
    }

    function _export($addon)
    {
        $data = decodeData($addon['data']);
        $data['center'] = decodeData($data['center']);
        $data['marker'] = decodeData($data['marker']);
        $data['city'] = decodeData($data['city']);
        return $data;
    }

    function _render($addon)
    {
        if ($data = $this->_export($addon))
        {
            return $this->_genHtml(null, $data);
        }
        return '';
    }
}