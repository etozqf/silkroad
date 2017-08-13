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
                    <td width="160"><input type="text" name="keywords" id="keywords" class="search_input_text" style="width:145px" value="" /></td>
                    <td width="90"><input type="text" id="created_min" name="created_min" class="input_calendar search_input_text" value="" style="width:80px;" /></td>
                    <td class="t_c" width="15">至</td>
                    <td width="90"><input type="text" id="created_max" name="created_max" class="input_calendar search_input_text" value="" style="width:80px;" /></td>
                    <td class="t_c" width="60"><input type="button" id="btn_search" value="搜索" class="button_style_1" /></td>
                </tr>
            </table>
        </form>
    </div>
    <div class="f_l">
        <input type="button" name="add" id="btn_add" value="添加专辑" class="button_style_4 f_l" />
    </div>
</div>
<div class="bk_8"></div>

<table width="98%" cellpadding="0" cellspacing="0" id="item_list" class="tablesorter table_list" style="margin-left:6px;">
    <thead>
    <tr>
        <th width="30" class="t_l bdr_3">选择</th>
        <th>专辑名称</th>
        <th class="sorter" width="100"><div>视频数量</div></th>
        <th class="sorter" width="150"><div>添加时间</div></th>
    </tr>
    </thead>
    <tbody></tbody>
</table>

<ul id="right_menu"></ul>

<div class="table_foot">
    <div id="pagination" class="pagination f_r"></div>
    <p class="f_l">
        <button type="button" id="btn_reload" class="button_style_1">刷新</button>
    </p>
</div>

<style>
.button-area{
    border:1px solid #fff;
    border-top-width:0; padding: 8px 0;
    background:url(apps/system/images/dialog.png) repeat-x 0 -60px;
    margin-top:0;
    text-align:right;
    padding-right:2px;
}
button {
    font-family:Arial,Verdana,"宋体";
    font-size:12px;
    vertical-align:middle;
    cursor:pointer;
    margin:0 4px;
    border:1px solid #94C5E5;
    background:url(apps/system/images/dialog.png) repeat-x 0 -110px;
    color:#077AC7;
    height:22px;
}
@-moz-document url-prefix(){
   button { padding:0 5px 3px; }
}
button:hover{ background-position:0 -140px;border-color:#FDCD99;color:#f60;}
</style>
<div class="button-area">
    <button id="btn_ok" type="button">确定</button>
    <button id="btn_cancel" type="button">取消</button>
</div>

<script type="text/javascript" src="<?=ADMIN_URL?>apps/video/js/videolist.js"></script>
<script type="text/javascript">
    $(function(){
        videolist.selector();
    });
</script>
<?php $this->display('footer');