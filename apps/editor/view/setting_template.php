<?php $this->display('header', 'system');?>
<link rel="stylesheet" type="text/css" href="apps/editor/css/setting_template.css">

<!-- mce -->
<script src="tiny_mce/tiny_mce.js" type="text/javascript"></script>
<script src="tiny_mce/editor.js" type="text/javascript"></script>

<div class="bk_8"></div>
<div class="mar_l_8 setting-panel-editor">
	<div id="editor_title" class="title">新建: <input type="text" id="name" value="" placeholder="模板名称" /></div>
	<div class="bk_8"></div>
	<textarea id="content"></textarea>
	<div class="bk_8"></div>
	<input id="submit" type="button" class="button_style_2" value="保存" />
</div>
<div class="mar_l_8 setting-panel-sider">
    <div id="template_list" class="template_list">
        <div class="title">模板列表<span id="add_template" class="setting-add" title="添加"><input type="button" value="+" class="button_style_1" /></span></div>
        <div class="bk_8"></div>
        <ul>
            <li>暂无数据</li>
        </ul>
    </div>
</div>
<script type="text/javascript" src="apps/editor/js/setting_template.js"></script>