<?php
/**
 * 活动字段抽象类
 *
 * 用途：
 * - 后台设置视图
 * - 前台表单视图
 * - 入库验证
 * - 前端 JS 验证
 * - 生成入库数据
 */
abstract class activityField
{
    protected static $ENV = array();

    protected static $instance = array();

    protected static $error = null;

    function __get($key)
    {
        return self::$ENV[$key];
    }

    static function setEnv(array $env)
    {
        self::$ENV = $env;
        if (empty(self::$ENV['template'])) {
            self::$ENV['template'] = factory::template('activity');
        }
        if (empty(self::$ENV['view'])) {
            self::$ENV['view'] = factory::view('activity');
        }
    }

    /**
     * 根据字段类型获取实例
     *
     * @param string $type 字段类型
     * @return activityField 对应实例
     * @throws Exception
     */
    final static function getInstance($type)
    {
        if (isset(self::$instance[$type]))
        {
            return self::$instance[$type];
        }
        if (!preg_match("/^[0-9a-z_]+$/i", $type)) {
            throw new Exception("activity field '$type' mismatch.");
        }
        loader::import("lib.field.type.$type", app_dir('activity'));
        $class = "activityField_$type";
        if (!class_exists($class, false))
        {
            throw new Exception("activity field '$type' not exists.");
        }
        return self::$instance[$type] = new $class();
    }

    final static function getAvailableTypes()
    {
        static $types = null;
        if (is_null($types))
        {
            $types = @include app_dir('activity').'lib/field/type.php';
        }
        return $types;
    }

    final static function getAvailableRules()
    {
        static $rules = null;
        if (is_null($rules))
        {
            $rules = @include app_dir('activity').'lib/field/rule.php';
        }
        return $rules;
    }

    final static function error()
    {
        return self::$error;
    }

    // ===== helper =====

    final static function parseOptions($options, $only_value = false)
    {
        if ($options)
        {
            $options = array_filter(explode("\n", $options), 'strlen');
            foreach ($options as &$option)
            {
                if (strpos($option, '|'))
                {
                    $option = explode('|', $option, 2);
                    if ($only_value) $option = $option[1];
                }
            }
            return $options;
        }
        return array();
    }

    final static function parseRules(&$fields, $for_html = false)
    {
        foreach ($fields as &$field)
        {
            if (!empty($field['options']['rule']) && empty($field['options']['regex']))
            {
                $rule = $field['options']['rule'];
                $rules = self::getAvailableRules();
                if (array_key_exists($rule, $rules))
                {
                    $field['options']['regex'] = $for_html && isset($rules[$rule]['regex_javascript'])
                        ? $rules[$rule]['regex_javascript']
                        : $rules[$rule]['regex'];
                }
            }
        }
    }

    final static function parseField($field_name)
    {
        $field = table('activity_field', $field_name);
        if (is_array($field))
        {
            $field['options'] = decodeData($field['options']);
            return $field;
        }
        return array();
    }

    final static function genOptions($options)
    {
        foreach ($options as &$option)
        {
            if (is_array($option))
            {
                $option = implode('|', $option);
            }
        }
        return implode("\n", $options);
    }

    final static function genValidateAttributes($field, $multiple = false)
    {
        $name_suffix = $multiple ? '[]' : '';

        $attributes = array(
            'name' => $field['fieldid'].$name_suffix,
            'label' => $field['label'],
        );

        if ($field['need'])
        {
            $attributes['require'] = 1;
        }

        if (is_array($field['options']))
        {
            foreach ($field['options'] as $option => $option_value)
            {
                $option_value = trim($option_value);
                if ($option_value)
                {
                    $attributes[$option] = $option_value;
                }
            }
        }

        $html = array();
        foreach ($attributes as $attr => $value)
        {
            $html[] = "data-validate-{$attr}=\"".htmlspecialchars($value)."\"";
        }
        return count($html) ? ' ' . implode(' ', $html) : '';
    }

    final static function getTemplate($type)
    {
        $base_path = ROOT_PATH.'template/'.config('template', 'name').'/';
        $prefix = 'activity/field/';
        if (is_file($base_path.$prefix.$type.'.html'))
        {
            return $prefix.$type.'.html';
        }
        return $prefix.'type/'.$type.'.html';
    }

    final static function getTypeName($type)
    {
        $types = self::getAvailableTypes();
        return array_key_exists($type, $types) ? $types[$type] : '';
    }

    // ===== // helper =====

    final static function design($type, $field = null)
    {
        if (!is_null($field) && is_array($field['options']['option']))
        {
            $field['options']['option'] = self::genOptions($field['options']['option']);
        }
        return self::getInstance($type)->_design($field);
    }

    final static function edit($type, $field, $value)
    {
        return self::getInstance($type)->_edit($field, $value);
    }

    final static function render($type, $field)
    {
        return self::getInstance($type)->_render($field);
    }

    final static function validate($type, $field, $value, $is_required = false)
    {
        self::$error = null;
        return self::getInstance($type)->_validate($field, $value, $is_required);
    }

    final static function save($type, $field, $value)
    {
        self::$error = null;
        return self::getInstance($type)->_save($field, $value);
    }

    final static function renderTemplate($type, $field)
    {
        return self::getInstance($type)->_renderTemplate($field);
    }

    final static function output($type, $field, $value)
    {
        return self::getInstance($type)->_output($field, $value);
    }

    abstract function _design($field = null);
    abstract function _edit($field, $value);
    abstract function _render($field);
    abstract function _validate($field, $value, $is_required = false);

    function _save($field, $value)
    {
        return $value;
    }

    function _renderTemplate($field)
    {
        return '{'.$field['fieldid'].'}';
    }

    function _output($field, $value)
    {
        return $value;
    }
}