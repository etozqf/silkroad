<?php
class addonEngine_picture_group extends addonEngine
{
    function _addView()
    {
        $this->view->display('engine/picture_group/form');
    }

    function _editView($addon)
    {
        $this->view->assign($addon);
        $this->view->assign('edit', true);
        $this->view->display('engine/picture_group/form');
    }

    function _format($post)
    {
        $post['catid_html'] = $this->renderCategory($post['contentid']);
        return $this->json->encode($post);
    }

    function _export($addon)
    {
        $data = decodeData($addon['data']);

        $picture_group = loader::model('admin/picture', 'picture');
        $picture = $picture_group->get(intval($data['contentid']), '*', 'show');
        if (! $picture || $picture['status'] != 6)
        {
            return false;
        }

        return array(
            'data' => $data,
            'picture' => $picture
        );
    }

    function _render($addon)
    {
        if ($data = $this->_export($addon))
        {
            $data['uuid'] = uniqid('GALLERY');
            return $this->_genHTML(null, $data);
        }
        return '';
    }
}