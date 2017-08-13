<?php
class addonEngine_weibo extends addonEngine
{
    function _addView()
    {
        $this->view->display('engine/weibo/form');
    }

    function _editView($addon)
    {
        $this->view->assign($addon);
        $this->view->assign('edit', true);
        $this->view->display('engine/weibo/form');
    }

    function _export($addon)
    {
        $data = decodeData($addon['data']);
        foreach ($data as &$item)
        {
            if (isset($item['image']))
            {
                $item['image'] = json_decode($item['image'], true);
            }
        }
        return $data;
    }

    function _render($addon)
    {
        return $this->_genHtml(null, array(
            'data' => $this->_export($addon)
        ));
    }
}