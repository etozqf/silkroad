<?php
class addonEngine_survey extends addonEngine
{
    function _addView()
    {
        $this->view->display('engine/survey/form');
    }

    function _editView($addon)
    {
        $this->view->assign($addon);
        $this->view->assign('edit', true);
        $this->view->display('engine/survey/form');
    }

    function _format($post)
    {
        $post['catid_html'] = $this->renderCategory($post['contentid']);
        return $this->json->encode($post);
    }

    function _export($addon)
    {
        $data = decodeData($addon['data']);

        $survey = loader::model('admin/survey', 'survey');
        $content = $survey->get(intval($data['contentid']), '*', 'show');
        if (! $content || $content['status'] != 6)
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
           $data['uuid'] = uniqid('survey-');
           return $this->_genHtml(null, $data);
        }
        return '';
    }
}