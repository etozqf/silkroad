<?php
class activityField_photo extends activityField
{
    function _design($field = null)
    {
        $this->view->assign('field', $field);
        $this->view->display('field/type/photo/design');
    }

    function _edit($field, $data)
    {
        $this->view->assign('field', $field);
        $this->view->assign('data', $data);
        $this->view->display('field/type/photo/edit');
    }

    function _render($field)
    {
        $this->template->assign('field', $field);
        $this->template->display(self::getTemplate($field['type']));
    }

    function _validate($field, $value, $is_required = false)
    {
        if ($value) return true;

        $upload = $_FILES[$field['fieldid']];
        if (!is_array($upload) || $upload['error'])
        {
            if ($is_required) {
                self::$error = $field['label'].'上传失败';
                return false;
            }
            return true;
        }

        $sizelimit = intval($field['options']['sizelimit']);
        if ($sizelimit && $upload['size'] > $sizelimit * 1024 * 1024)
        {
            self::$error = $field['label'].'文件大小不能超过'.$sizelimit.'MB';
            return false;
        }

        $ext = pathinfo($upload['tmp_name'], PATHINFO_EXTENSION);
        if (!$ext)
        {
            $info = getimagesize($upload['tmp_name']);
            if (!$info || !in_array($info[2], array(IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_JPEG)))
            {
                self::$error = $field['label'].'文件格式不正确';
                return false;
            }
        }

        return true;
    }

    function _save($field, $value)
    {
        if ($value) return $value;

        $upload = $_FILES[$field['fieldid']];
        if (!is_array($upload) || $upload['error']) return null;

        $sizelimit = intval($field['options']['sizelimit']);
        $ext = 'jpg|jpeg|gif|png';

        $attachment = loader::model('admin/attachment', 'system');
        if ($file = $attachment->upload($field['fieldid'], true, null, $ext, $sizelimit ? $sizelimit * 1024 : null)) {
            return $file;
        }
        self::$error = $attachment->error;
        return false;
    }

    function _renderTemplate($field)
    {
        $tips = htmlspecialchars('<img width="200" src="{'.$field['fieldid'].'}" />');
        return '<a  class="photo" target="_blank" tips="'.$tips.'" href="{'.$field['fieldid'].'}">查看图片</a>';
    }

    function _output($field, $value)
    {
        return $value ? UPLOAD_URL.$value : IMG_URL.'images/nopic.gif';
    }
}