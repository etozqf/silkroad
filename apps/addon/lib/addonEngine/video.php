<?php
class addonEngine_video extends addonEngine
{
    function _addView()
    {
        $this->view->display('engine/video/form');
    }

    function _editView($addon)
    {
        $this->view->assign($addon);
        $this->view->assign('edit', true);
        $this->view->display('engine/video/form');
    }

    function _format($post)
    {
        $post['catid_html'] = $this->renderCategory($post['contentid']);
        return $this->json->encode($post);
    }

    function _export($addon)
    {
        $data = decodeData($addon['data']);

        if (($contentid = intval(value($data, 'contentid'))))
        {
            if (! ($content = loader::model('admin/video', 'video')->get($contentid))
                || $content['status'] != 6)
            {
                return false;
            }
            $video = value($content, 'video');
        }
        else
        {
            $video = value($data, 'video');
        }

        if (!$video || !($player = getVideoPlayer($video)))
        {
            return false;
        }

        $data['autostart'] = 'false';
        $data = array_merge($data, $player);

        return array(
            'content' => isset($content) ? $content : null,
            'addon' => $data
        );
    }

    function _render($addon)
    {
        if ($data = $this->_export($addon))
        {
            $this->template->assign($data['addon']);
            $data['video'] = $this->template->fetch('video/player/' . $data['addon']['player'] . '.html');
            return $this->_genHTML(null, $data);
        }
        return '';


    }
}