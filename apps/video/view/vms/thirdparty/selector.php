<?php $this->display('header', 'system');?>

<!--tablesorter-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/tablesorter/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.tablesorter.js"></script>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>
<!--pagination-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/pagination/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.pagination.js"></script>

<style>
    .thp_box{
        width: 100%;
        height:405px;
        overflow:hidden;
    }
    .thp_left{
        display: block;
        float: left;
        width: 135px;
        height:407px;
        overflow-x: hidden;
        overflow-y: auto;
    }
    .thp_center{
        display: block;
        float: left;
        width: 360px;
        overflow: hidden;
        padding-left: 5px;
        padding-right: 5px;
        padding-bottom: 5px;
        border-left: 1px solid #81C7E4;
        border-right: 1px solid #81C7E4;
        position: relative;
    }
    .thp_right{
        display: block;
        float: right;
        width: 270px;
        padding:10px;
        overflow: hidden;
    }
    #thumb {
        padding-bottom:10px;
        position: relative;
        width: 266px;
        height: 180px;
    }
    .player {
        width: 266px;
        height: 180px;
        display: none;
    }
    .playicon {
        background: url("images/play_icon.png") no-repeat scroll 0 0 transparent;
        height: 32px;
        left: 117px;
        position: absolute;
        top: 76px;
        visibility: hidden;
        width: 32px;
    }
    .previewCode {
        width:266px;
        height:180px;
        border:0;
        margin:0;
        padding:0;
        overflow: hidden;
    }
    .table_head {
        width: 360px;
        padding:0;
    }
    .data_list {
        height: 325px;
        overflow: hidden;
    }
    .table_foot {
        padding: 6px 1px;
    }

    .button-area{
        border: 1px solid #fff;
        border-top-width: 0;
        padding-top: 8px;
        padding-bottom: 8px;
        background:url(apps/system/images/dialog.png) repeat-x 0 -60px;
        margin-top:0;
        text-align:right;
        padding-right:2px;
    }
    button {
        font-family: Arial,Verdana,"宋体";
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
    .lineTextarea{
        width:180px;height:48px;line-height:23px;padding:0 3px;margin:0;
    }
</style>

<div class="thp_box">
    <div class="thp_left">
        <div class="tree">
            <ul style="display: block;">
                <li idv="0">
                    <div id="treeall" class="itemarea">
                        <b class="bitarea"></b>
                        <span id="0" class="txtarea">全部</span>
                    </div>
                </li>
            </ul>
        </div>
        <div id="thp_tree" class="tree"></div>
    </div>
    <div class="thp_center">
        <div class="bk_10"></div>
        <div class="table_head">
            <form name="search_f" id="search_f" action="">
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr width="100%">
                        <td align="left"><input type="text" name="keyword" id="keyword" class="search_input_text" style="width:90px" value="" placeholder="视频名称" /></td>
                        <td width="80"><input type="text" id="created_min" name="created_min" class="input_calendar search_input_text" value="" style="width:70px;" /></td>
                        <td class="t_c" width="20">至</td>
                        <td width="80"><input type="text" id="created_max" name="created_max" class="input_calendar search_input_text" value="" style="width:70px;" /></td>
                        <td class="t_r" width="45"><input type="button" id="btn_search" value="搜索" class="button_style_1" style="margin-right:0;"/></td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="bk_8"></div>

        <div class="data_list">
            <table width="100%" cellpadding="0" cellspacing="0" id="item_list" class="tablesorter table_list">
                <thead>
                <tr>
                    <th width="30" class="t_l bdr_3">选择</th>
                    <th>视频名称</th>
                    <th width="110">添加时间</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div class="table_foot">
            <div id="pagination" class="pagination f_r"></div>
        </div>
    </div>
    <div class="thp_right">
        <div id="thumb">
            <img src="images/novideo.png" width="266" height="180"/>
            <span class="playicon"></span>
        </div>
        <table cellpadding="0" cellspacing="0" width="100%" class="table_form">
            <tr><th width="80">视频名称：</th><td id="title">&nbsp;</td></tr>
            <tr><th width="80">视频时长：</th><td id="time">&nbsp;</td></tr>
            <tr><th width="80">视频标签：</th><td id="tags">&nbsp;</td></tr>
            <tr><th width="80">创建时间：</th><td id="createdstr">&nbsp;</td></tr>
            <tr><th width="80">发布时间：</th><td id="publishedstr">&nbsp;</td></tr>
            <tr><th width="80">播放量：</th><td id="playcount">&nbsp;</td></tr>
            <tr>
                <th width="80">播放代码：</th>
                <td><textarea class="lineTextarea" id="playercode"></textarea></td>
            </tr>
        </table>
    </div>
</div>

<div class="clear"></div>

<div class="button-area">
    <button id="btn_ok" type="button">确定</button>
    <button id="btn_cancel" type="button">取消</button>
</div>

<script type="text/javascript" src="<?=ADMIN_URL?>apps/video/js/thirdparty_selector.js"></script>
<script type="text/javascript">
    $(function(){
        thirdpartySelector.init('<?php echo $id; ?>', '<?php echo $apitype; ?>');
    });
</script>
<?php $this->display('footer');