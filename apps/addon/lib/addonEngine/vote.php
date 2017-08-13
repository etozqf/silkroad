<?php
class addonEngine_vote extends addonEngine
{
    function _addView()
    {
        $catid = intval(value($_GET, 'catid'));
        if ($catid)
        {
            $this->view->assign('catid', $catid);
        }

        $this->view->display('engine/vote/form');
    }

    function _editView($addon)
    {
        $this->view->assign($addon);
        $this->view->assign('edit', true);
        $this->view->display('engine/vote/form');
    }

    function _format($post, $encode = true)
    {
        $post['catid_html'] = $this->renderCategory($post['contentid']);
        return $encode ? $this->json->encode($post) : $post;
    }

    function _genData($post, $addon = null)
    {
        $data = value($post, 'data');
        if (! $data || ! is_array($data)) return false;

        if (value($data, 'tabs') === 0)
        {
            $data['modelid'] = 8;
            $data['allowcomment'] = 1;
            $data['status'] = 6;
            $data['mininterval'] = 24;
            $response = request(ADMIN_URL . '?app=vote&controller=vote&action=add', $data);
            if (! $response || $response['httpcode'] != 200)
            {
                return false;
            }
            $response = json_decode($response['content'], true);
            if (! ($contentid = intval($response['contentid'])))
            {
                return false;
            }
            $post['contentid'] = $contentid;
            $post['data'] = $this->_format(array(
                'tabs' => 1,
                'contentid' => $contentid,
                'thumb' => '',
                'url' => $response['url'],
                'title' => $data['title'],
                'date' => date('Y-m-d H:i', TIME),
                'catid' => $data['catid']
            ), false);
            unset($post['addonid']);
        }
        return $post;
    }

    function _export($addon)
    {
        $data = decodeData($addon['data']);

        $vote = loader::model('admin/vote', 'vote');
        $content = $vote->get(intval($data['contentid']), '*', 'show');
        if (! $content || $content['status'] != 6)
        {
            return false;
        }

        $content['addon'] = $data;
        return $content;
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