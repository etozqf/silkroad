<?php $this->display('header', 'system');?>

<!--tablesorter-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/tablesorter/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.tablesorter.js"></script>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>
<!--pagination-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/pagination/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.pagination.js"></script>

<div class="bk_10"></div>
<div class="table_head">
    <div class="search_icon f_r">
        <form name="search_f" id="search_f" action="">
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td width="160"><input type="text" name="keywords" id="keywords" class="search_input_text" style="width:145px" value="" placeholder="视频标题" /></td>
                    <td class="t_c" width="45"><input type="button" id="btn_search" value="搜索" class="button_style_1" /></td>
                </tr>
            </table>
        </form>
    </div>
    <div class="f_l">
        <input type="button" name="add" id="btn_add" value="添加视频" class="button_style_4 f_l" />
    </div>
</div>
<div class="bk_8"></div>

<table width="98%" cellpadding="0" cellspacing="0" id="item_list" class="tablesorter table_list" style="margin-left:6px;">
    <thead>
    <tr>
        <th width="30" class="t_l bdr_3" id="move">排序</th>
        <th>视频标题</th>
        <th class="sorter" width="150"><div>栏目</div></th>
        <th class="sorter" width="150"><div>发布时间</div></th>
        <th width="60">管理操作</th>
    </tr>
    </thead>
    <tbody></tbody>
</table>

<ul id="right_menu"></ul>

<script type="text/javascript" src="<?=ADMIN_URL?>apps/system/js/datapicker.js"></script>
<script type="text/javascript" src="<?=ADMIN_URL?>apps/video/js/videolist.js"></script>
<script type="text/javascript">
    $(function(){
        videoManage.listid = <?php echo $listid; ?> + 0;
        videoManage.sorttype = <?php echo $sorttype; ?> + 0;
        videoManage.init();
    });
</script>
<?php $this->display('footer');