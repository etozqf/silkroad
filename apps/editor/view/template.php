<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?=$CONFIG['charset']?>" />
    <title>选择模板</title>

    <!--gloabl-->
    <link rel="stylesheet" type="text/css" href="css/admin.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/validator/style.css"/>
    <link rel="stylesheet" type="text/css" href="apps/editor/css/template.css" />
    <script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.js"></script>
    <script type="text/javascript" src="<?=ADMIN_URL?>tiny_mce/tiny_mce_popup.js"></script>
    <script type="text/javascript" src="<?php echo IMG_URL?>js/config.js"></script>
    <script type="text/javascript" src="<?php echo IMG_URL?>js/cmstop.js"></script>
</head>
<body>
    <div class="bk_8"></div>
    <div class="left">
        <ul id="list" class="mar_l_8">
            <li>暂无数据</li>
        </ul>
    </div>
    <div class="right mar_l_8">
        <fieldset id="content">
            <legend>名称</legend>
            <div class="code"></div>
        </fieldset>
    </div>
    <div class="bk_8"></div>
    <div class="foot mceActionPanel">
        <input id="okBtn" type="button" class="button" value="确定" />
        <input id="clearBtn" type="button" class="button" value="取消" />
    </div>

<script type="text/javascript" src="apps/editor/js/template.js"></script>