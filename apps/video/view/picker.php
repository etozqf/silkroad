<?php $this->display('header', 'system'); ?>

<link rel="stylesheet" href="apps/video/css/picker.css" />

<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/list/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.list.js"></script>

<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/tipsy/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.tipsy.js"></script>

<script type="text/javascript" src="<?=IMG_URL?>js/lib/json2.js"></script>

<div class="picker-panel">
    <div class="operation_area">
        <form action="" id="form-query">
            <input type="hidden" name="mobile" value="<?=$mobile?>">
            <input style="padding: 2px; vertical-align: middle;" id="keyword" type="text" value="<?=$keywords?>" placeholder="搜索标题" size="30" name="keyword">
            <input id="catid" name="catid" width="150"
                   url="?app=system&controller=category&action=cate&catid=%s"
                   initUrl="?app=system&controller=category&action=name&catid=%s"
                   paramVal="catid"
                   paramTxt="name"
                   multiple="1"
                   value=""
                   alt="选择栏目" />
            <input type="submit" class="button_style_1" style="margin-right: 0;" value="搜索" />
        </form>
    </div>
    <div class="picker-panel-left">
        <div class="picker-panel-title">待选(<span data-role="loaded">0</span>/<span data-role="total">0</span>)</div>
        <div class="picker-content-list"></div>
        <div id="message">暂无内容</div>
    </div>
</div>

<div class="btn_area">
    <button type="button" class="button_style_1">确定</button>
</div>

<script type="text/javascript" src="apps/video/js/picker.js"></script>
<script type="text/javascript">VideoPicker.init(<?=$multiple?>)</script>