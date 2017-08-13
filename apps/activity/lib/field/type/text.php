<?php
class activityField_text extends activityField
{
    function _design($field = null)
    {
        $rules = activityField::getAvailableRules();

        $this->view->assign('field', $field);
        $this->view->assign('rules', $rules);
        $this->view->display('field/type/text/design');
    }

    function _edit($field, $data)
    {
        $this->view->assign('field', $field);
        $this->view->assign('data', $data);
        $this->view->display('field/type/text/edit');
    }

    function _render($field)
    {
        $this->template->assign('field', $field);
        $this->template->display(self::getTemplate($field['type']));
    }

    function _validate($field, $value, $is_required = false)
    {
        if (empty($value)) {
            if ($is_required) {
                self::$error = $field['label'].'不能为空';
                return false;
            }
            return true;
        }

        $limit = intval($field['options']['limit']);
        if ($limit && str_natcount($value) > $limit)
        {
            self::$error = $field['label'].'长度不能超过'.$limit.'字符';
            return false;
        }

        $regex = value($field['options'], 'regex');
        if (!$regex && ($rule = value($field['options'], 'rule')))
        {
            $rules = self::getAvailableRules();
            if (array_key_exists($rule, $rules))
            {
                $regex = $rules[$rule]['regex'];
            }
        }
        if ($regex && !preg_match($regex, $value))
        {
            self::$error = $field['label'].'格式不正确';
            return false;
        }
        return true;
    }

    function _save($field, $value)
    {
        return htmlspecialchars($value);
    }
}