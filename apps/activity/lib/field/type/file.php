<?php
class activityField_file extends activityField
{
    function _design($field = null)
    {
        $this->view->assign('field', $field);
        $this->view->display('field/type/file/design');
    }

    function _edit($field, $data) {
        $this->view->assign('field', $field);
        $this->view->assign('data', $data);
        $this->view->display('field/type/file/edit');
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

        $fileext = value($field['options'], 'fileext');
        if (!$fileext) $fileext = setting('system', 'attachexts');
        if ($fileext)
        {
            $ext = pathinfo($upload['tmp_name'], PATHINFO_EXTENSION);
            if ($ext && !in_array($ext, explode('|', $fileext)))
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
        $fileext = value($field['options'], 'fileext');
        if (!$fileext) $fileext = setting('system', 'attachexts');

        $attachment = loader::model('admin/attachment', 'system');
        if ($file = $attachment->upload($field['fieldid'], true, null, $fileext, $sizelimit ? $sizelimit * 1024 : null)) {
            return array_pop($attachment->aid);
        }
        self::$error = $attachment->error;
        return false;
    }

    function _renderTemplate($field)
    {
        return '<a target="_blank" href="{'.$field['fieldid'].'}">下载</a>';
    }

    function _output($field, $value)
    {
        $value = intval($value);
        if (!$value
            || !($attachment = loader::model('admin/attachment', 'system')->get($value)))
        {
            return '';
        }
        return UPLOAD_URL . $attachment['filepath'] . $attachment['filename'];
    }
}