<?php
/**
 * 区块字段设置界面
 *
 * @param $fields 修改时要传递之前设置的值
 * @return string
 */
function section_fields($fields = null)
{
    section_fields_format($fields);
    $view = factory::view('page');
    $view->assign($fields);
    return $view->fetch('section/fields', 'page');
}

/**
 * 区块字段使用界面（即应用到表单时的界面）
 *
 * @param $fields 字段设置
 * @param $data 编辑时要传递的内容
 * @return string
 */
function section_fields_form($fields = null, $data = null)
{
    section_fields_format($fields);

    static $field_names = array(
        'contentid' => '内容ID',
        'title' => '标题',
        'icon' => '图标',
        'color' => '标题颜色',
        'url' => '链接',
        'subtitle' => '副题',
        'suburl' => '副题链接',
        'thumb' => '缩略图',
        'description' => '描述',
        'time' => '时间',
    );

    $rules = array();
    foreach ($fields['system_fields'] as $field => $config)
    {
        $tips = array();
        $has_min = $has_max = $has_width = $has_height = false;

        if (isset($config['required']) && $config['required'])
        {
            $tips[] = '必选';
            $rules[$field]['rule'][] = array(
                'name' => 'required',
                'event' => 'blur change submit',
                'text' => $field_names[$field].'不能为空'
            );
        }

        if (isset($config['min_length']) && ($config['min_length'] = intval($config['min_length'])))
        {
            $has_min = true;
            $rules[$field]['rule'][] = array(
                'name' => 'natmin',
                'event' => 'change blur submit',
                'args' => $config['min_length'],
                'text' => $field_names[$field].'不能少于'.$config['min_length'].'个字'
            );
        }
        if (isset($config['max_length']) && ($config['max_length'] = intval($config['max_length'])))
        {
            $has_max = true;
            $rules[$field]['rule'][] = array(
                'name' => 'natmax',
                'event' => 'change blur submit',
                'args' => $config['max_length'],
                'text' => $field_names[$field].'不能多于'.$config['max_length'].'个字'
            );
        }
        if ($has_min && $has_max)
        {
            $tips[] = '长度在 '.$config['min_length'].'~'.$config['max_length'].' 之间';
        }
        elseif ($has_min)
        {
            $tips[] = '长度大于 '.$config['min_length'].' 个字';
        }
        elseif ($has_max)
        {
            $tips[] = '长度小于 '.$config['max_length'].' 个字';
        }

        if (isset($config['width']) && ($config['width'] = intval($config['width'])))
        {
            $has_width = true;
        }
        if (isset($config['height']) && ($config['height'] = intval($config['height'])))
        {
            $has_height = true;
        }
        $limit = isset($config['limit']) && $config['limit'] ? true : false;
        if ($has_width && $has_height)
        {
            $tips[] = '图片宽高比 '.$config['width'].'x'.$config['height'];
            $limit && ($rules[$field]['rule'][] = array(
                'name' => 'img_ratio',
                'event' => 'submit',
                'args' => $config['width'].'x'.$config['height'],
                'text' => $field_names[$field].'图片宽高比必须等于'.$config['width'].'x'.$config['height']
            ));
        }
        elseif ($has_width)
        {
            $tips[] = '图片宽度 '.$config['width'];
            $limit && ($rules[$field]['rule'][] = array(
                'name' => 'img_width',
                'event' => 'submit',
                'args' => $config['width'],
                'text' => $field_names[$field].'图片宽度必须等于'.$config['width']
            ));
        }
        elseif ($has_height)
        {
            $tips[] = '图片高度 '.$config['height'];
            $limit && ($rules[$field]['rule'][] = array(
                'name' => 'img_height',
                'event' => 'submit',
                'args' => $config['height'],
                'text' => $field_names[$field].'图片高度必须等于'.$config['height']
            ));
        }
        if (!empty($tips))
        {
            $rules[$field]['tips'] = implode('，', $tips);
        }
    }

    $view = factory::view('page');
    $view->assign($fields);
    $view->assign('rules', json_encode((object) $rules));
    $view->assign('data', $data);
    return $view->fetch('section/fields_form', 'page');
}

/**
 * 格式化区块字段设置
 *
 * @param $fields 要被格式化的字段
 */
function section_fields_format(&$fields)
{
    if (is_null($fields) || !is_array($fields))
    {
        $fields = array();
    }
    if (!isset($fields['system_fields']) || !is_array($fields['system_fields']))
    {
        // 兼容旧版本数据
        $fields['system_fields'] = array(
            'contentid' => array(
                'checked' => '1',
                'func' => 'intval'
            ),
            'title' => array(
                'checked' => '1',
                'min_length' => '',
                'max_length' => '',
                'required' => '1',
            ),
            'icon' => array(
                'checked' => '1',
                'func' => 'section_fields_icon_filter'
            ),
            'iconsrc' => array(
                'checked' => '1',
                'func' => 'section_fields_iconsrc_filter'
            ),
            'color' => array(
                'checked' => '1',
            ),
            'url' => array(
                'checked' => '1',
            ),
            'subtitle' => array(
                'checked' => '1',
                'min_length' => '',
                'max_length' => '',
            ),
            'suburl' => array(
                'checked' => '1',
            ),
            'thumb' => array(
                'checked' => '1',
                'required' => '0',
                'width' => 0,
                'height' => 0,
                'limit' => 0
            ),
            'description' => array(
                'checked' => '1',
                'min_length' => '',
                'max_length' => '',
            ),
            'time' => array(
                'checked' => 'time',
                'func' => 'section_fields_strtotime'
            ),
        );
    }
    if (!isset($fields['custom_fields']) || !is_array($fields['custom_fields']))
    {
        $fields['custom_fields'] = array();
    }
}

/**
 * 验证给定的字段设置是否合法
 *
 * 比如是否使用了系统保留字段名，一些简单的规则。
 *
 * @param $fields 要验证的字段设置
 * @return array|string
 */
function section_fields_validate($fields = null)
{
    static $reserve_names = array(
        'recommendid', 'sectionid', 'contentid', 'title', 'icon', 'iconsrc', 'url', 'color', 'subtitle', 'suburl',
        'thumb', 'description', 'time', 'row', 'col', 'app', 'controller', 'action'
    );

    section_fields_format($fields);
    $system_fields =& $fields['system_fields'];
    $custom_fields =& $fields['custom_fields'];

    if (empty($system_fields))
    {
        return '字段设置不能为空';
    }
    if (empty($system_fields['title']) || !$system_fields['title']['checked'])
    {
        return '标题字段必选';
    }
    if (empty($system_fields['url']) || !$system_fields['url']['checked'])
    {
        return '链接字段必选';
    }

    if (is_array($custom_fields))
    {
        foreach ($custom_fields['name'] as $index => $name)
        {
            if (!($name = trim($name)))
            {
                unset($custom_fields['name'][$index]);
                unset($custom_fields['text'][$index]);
                continue;
            }
            if (!preg_match('/^[a-z][0-9a-z_]*/i', $name))
            {
                return '请使用字母开头的字段标识';
            }
            if (in_array($name, $reserve_names))
            {
                return htmlspecialchars($name) . ' 为保留字段，请更换一个';
            }
        }
    }

    return $fields;
}

/**
 * 根据给定的字段规则验证用户提交的数据是否符合要求
 *
 * @param $data 要验证的数据
 * @param $fields 给定的字段规则
 * @return array|string
 */
function section_fields_validate_data($data, $fields = null)
{
    static $field_names = array(
        'contentid' => '内容ID',
        'title' => '标题',
        'icon' => '图标',
        'color' => '标题颜色',
        'url' => '链接',
        'subtitle' => '副题',
        'suburl' => '副题链接',
        'thumb' => '缩略图',
        'description' => '描述',
        'time' => '时间',
    );

    section_fields_format($fields);
    $system_fields =& $fields['system_fields'];
    $custom_fields =& $fields['custom_fields'];

    $keys = array_keys($system_fields);
    if (is_array($custom_fields['name']))
    {
        $keys = array_merge(array_values($custom_fields['name']), $keys);
    }
    foreach ($data as $key => $value)
    {
        if (!in_array($key, $keys))
        {
            unset($data[$key]);
        }
    }

    foreach ($system_fields as $field => $rule)
    {
        if (!$rule['checked'])
        {
            unset($data[$field]);
            continue;
        }

        $value = $data[$field];
        $field_name = $field_names[$field];

        if (isset($rule['required']) && $rule['required'] && !$value)
        {
            return $field_name . '不能为空';
        }
        $strlen = str_natcount($value);
        if (isset($rule['min_length']) && ($min_length = intval($rule['min_length'])) && $strlen < $min_length)
        {
            return $field_name . '长度不能少于' . $min_length . '个字符';
        }
        if (isset($rule['max_length']) && ($max_length = intval($rule['max_length'])) && $strlen > $max_length)
        {
            return $field_name . '长度不能多于' . $max_length . '个字符';
        }
        if (isset($data[$field]) && isset($rule['func']) && $rule['func'] && function_exists($rule['func']))
        {
            $data[$field] = call_user_func($rule['func'], $data[$field]);
        }
    }

    return $data;
}

function section_fields_strtotime($value)
{
    if (!$value || is_numeric($value)) return $value;
    return strtotime($value);
}

function section_fields_icon_filter($value)
{
    return $value == 'blank' ? '' : $value;
}

function section_fields_iconsrc_filter($value)
{
    return $value == IMG_URL.'icon/blank.png' ? '' : $value;
}

