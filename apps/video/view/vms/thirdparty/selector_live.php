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
        width: 270px;
        overflow: hidden;
        padding-left: 5px;
        padding-right: 5px;
        padding-bottom: 5px;
        border-left: 1px solid #81C7E4;
        border-right: 1px solid #81C7E4;
        position: relative;
    }
    .data_list {
        height: 392px;
        overflow-x: hidden;
        overflow-y: auto;
    }
    .thp_right{
        display: block;
        float: right;
        padding:8px;
        overflow: hidden;
    }
    #thumb {
        padding-bottom:10px;
        position: relative;
    }
    .player {
        width: 500px;
        height: 385px;
        display: none;
    }
    .playicon {
        background: url("images/play_icon.png") no-repeat scroll 0 0 transparent;
        height: 32px;
        left: 234px;
        position: absolute;
        top: 177px;
        visibility: hidden;
        width: 32px;
    }
    .previewCode {
        width:500px;
        height:385px;
        border:0;
        margin:0;
        padding:0;
        overflow: hidden;
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
        <div class="bk_8"></div>
        <div class="data_list">
            <table width="100%" cellpadding="0" cellspacing="0" id="item_list" class="tablesorter table_list">
                <thead>
                <tr>
                    <th width="30" class="t_l bdr_3">选择</th>
                    <th>频道名称</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div class="thp_right">
        <div id="thumb">
            <img src="images/novideo.png" width="500" height="385"/>
            <span class="playicon"></span>
            <form id="previewForm" target="previewCode" method="post" action="">
                <textarea id="code" name="code" style="display:none;"></textarea>
            </form>
        </div>
        <div id="player" class="player"><iframe id="previewCode" name="previewCode" class="previewCode"></iframe></div>
    </div>
</div>

<div class="clear"></div>

<div class="button-area">
    <button id="btn_ok" type="button">确定</button>
    <button id="btn_cancel" type="button">取消</button>
</div>

<script type="text/javascript" src="<?=ADMIN_URL?>apps/video/js/thirdparty_selector_live.js"></script>
<script type="text/javascript">
    $(function(){
        thirdpartySelectorLive.init('<?php echo $id; ?>', '<?php echo $apitype; ?>');
    });
</script>
<?php $this->display('footer');