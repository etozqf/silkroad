<?php
class mobileAddon_picture extends mobileAddon
{
    function _addView()
    {
        $this->view->display('addon/picture/form');
    }

    function _editView($addon)
    {
        $this->view->assign($addon);
        $this->view->assign('edit', true);
        $this->view->display('addon/picture/form');
    }

    function _format($post)
    {
        $post['catid_html'] = $this->renderMobileCategory($post['contentid']);
        return $this->json->encode($post);
    }

    function _export($addon)
    {
        if (isset($addon['picture'])) return $addon;

        $data = decodeData($addon['data']);

        $picture = loader::model('admin/mobile_picture', 'mobile')->get(intval($data['contentid']), '*');
        if (! $picture || $picture['status'] != 6)
        {
            return false;
        }

        return array(
            'data' => $data,
            'picture' => $picture
        );
    }

    function _render($device, $addon)
    {
        if ($data = $this->_export($addon))
        {
            return $this->_genHTML($addon['engine'], $device, $data);
        }
        return '';
    }
}