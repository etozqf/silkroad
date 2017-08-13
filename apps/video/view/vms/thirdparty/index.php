<?php $this->display('header'); ?>

<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/list/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.list.js"></script>
<!--tablesorter-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/tablesorter/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.tablesorter.js"></script>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>
<!--pagination-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/pagination/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.pagination.js"></script>

<div class="bk_8"></div>
<div class="tag_1" style="margin-bottom:0;">
    <ul class="tag_list" id="pagetab">
        <li><a href="?app=video&controller=vms&action=setting">视频接口配置</a></li>
        <li><a href="?app=video&controller=thirdparty&action=index" class="s_3">第三方接口配置</a></li>
        <li><a href="?app=video&controller=vms&action=setting_video&type=convert">转码参数配置</a></li>
        <li><a href="?app=video&controller=vms&action=setting_video&type=player">播放器参数配置</a></li>
    </ul>
</div>

<div class="table_head operation_area" style="border-bottom:0;background:#F3F9FA;">
    <div class="search_icon f_r">
        <form name="search_f" id="search_f" action="">
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td width="160"><input type="text" name="keywords" id="keywords" class="search_input_text" style="width:145px" value="" placeholder="接口名称" /></td>
                    <td class="t_c" width="45"><input type="button" id="btn_search" value="搜索" class="button_style_1" /></td>
                </tr>
            </table>
        </form>
    </div>
    <div class="f_l">
        <input type="button" name="add" id="btn_add" value="添加" class="button_style_4 f_l" />
    </div>
</div>
<div class="bk_8"></div>

<table width="98%" cellpadding="0" cellspacing="0" id="item_list" class="tablesorter table_list" style="margin-left:6px;">
    <thead>
    <tr>
        <th width="30" class="t_l bdr_3" id="move">排序</th>
        <th width="200">接口名称</th>
        <th>接口地址</th>
        <th class="sorter" width="100"><div>状态</div></th>
        <th width="60">管理操作</th>
    </tr>
    </thead>
    <tbody></tbody>
</table>

<ul id="right_menu"></ul>

<script type="text/javascript" src="<?=ADMIN_URL?>apps/video/js/thirdparty.js"></script>
<script type="text/javascript">
    $(function(){
        thirdparty.init();
    });
</script>
<?php $this->display('footer');