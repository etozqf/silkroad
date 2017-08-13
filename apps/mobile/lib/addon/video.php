<?php
class mobileAddon_video extends mobileAddon
{
    function _addView()
    {
        $this->view->display('addon/video/form');
    }

    function _editView($addon)
    {
        $this->view->assign($addon);
        $this->view->assign('edit', true);
        $this->view->display('addon/video/form');
    }

    function _format($post)
    {
        $post['catid_html'] = $this->renderMobileCategory($post['contentid']);
        return $this->json->encode($post);
    }

    function _export($addon)
    {
        if (isset($addon['content'])) return $addon;

        $data = decodeData($addon['data']);

        if (!($contentid = intval(value($data, 'contentid')))
            || !($content = loader::model('admin/mobile_video', 'mobile')->get($contentid))
            || $content['status'] != 6) {
            return false;
        }

        $video_info = loader::model('mobile_content')->get_video_info($contentid, $content);
        $content = array_merge($content, $video_info);

        return array(
            'content' => $content,
            'addon' => $data
        );
    }

    function _render($device, $addon)
    {
        if (($data = $this->_export($addon))) {
            return $this->_genHTML($addon['engine'], $device, $data);
        }
        return '';
    }
}