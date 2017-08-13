<?php
return array(
    'number' => array(
        'label' => '数字',
        'regex' => '/^[+-]?[\d]+(?:\.[\d]+)?$/'
    ),
    'english' => array(
        'label' => '英文字符',
        'regex' => '/^[a-z]+$/i'
    ),
    'chinese' => array(
        'label' => '中文字符',
        'regex' => '/^([\x81-\xfe][\x40-\xfe])+$/',
        'regex_javascript' => '/^[\u4e00-\u9fa5]+$/'
    ),
    'email' => array(
        'label' => 'Email',
        'regex' => '/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/'
    ),
    'telephone' => array(
        'label' => '电话号码',
        'regex' => '/^(86)?(\d{3,4}-)?(\d{7,8})$/'
    ),
    'mobile' => array(
        'label' => '手机号码',
        'regex' => '/^1\d{10}$/'
    ),
    'url' => array(
        'label' => '网址',
        'regex' => '/http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w-\s.\/?%&=]*)?/'
    ),
    'id' => array(
        'label' => '身份证号码',
        'regex' => '/^(?:\d{14}|\d{17})[\dxX]$/'
    ),
    'qq' => array(
        'label' => 'QQ号码',
        'regex' => '/^[1-9]\d{4,20}$/'
    ),
    'zipcode' => array(
        'label' => '邮政编码',
        'regex' => '/^[0-9]\d{5}$/'
    ),
);