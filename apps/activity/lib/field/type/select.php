<?php
class activityField_select extends activityField
{
    function _design($field = null)
    {
        $this->view->assign('field', $field);
        $this->view->display('field/type/select/design');
    }

    function _edit($field, $data)
    {
        $this->view->assign('field', $field);
        $this->view->assign('data', $data);
        $this->view->display('field/type/select/edit');
    }

    function _render($field)
    {
        $field['options']['option'] = self::parseOptions($field['options']['option']);
        $this->template->assign('field', $field);
        $this->template->display(self::getTemplate($field['type']));
    }

    function _validate($field, $value, $is_required = false)
    {
		if (!isset($value) || $value === '') {
            if ($is_required) {
                self::$error = $field['label'].'不能为空';
                return false;
            }
            return true;
        }

        $limit = intval($field['options']['limit']);
        $option = self::parseOptions($field['options']['option'], true);
        if ($limit > 1)
        {
            if (count($value) > $limit)
            {
                self::$error = $field['label'].'只能选择'.$limit.'项';
                return false;
            }
            if (count(array_diff($value, $option)))
            {
                self::$error = $field['label'].'选项不正确';
                return false;
            }
        }
        else
        {
            if (!in_array($value, $option))
            {
                self::$error = $field['label'].'选项不正确';
                return false;
            }
        }
        return true;
    }

    function _save($field, $value)
    {
        return is_array($value) ? implode(',', $value) : $value;
    }

    function _output($field, $value)
    {
        $limit = intval($field['options']['limit']);
        $option = self::parseOptions($field['options']['option']);
        $option_values = self::parseOptions($field['options']['option'], true);
        if ($limit > 1)
        {
            $return = array();
            foreach (explode(',', $value) as $v)
            {
                if (($index = array_search($v, $option_values)) !== false) $return[] = $option[$index][0];
            }
            return implode(',', $return);
        }
        return (($index = array_search($value, $option_values)) !== false) ? $option[$index][0] : '-';
    }
}