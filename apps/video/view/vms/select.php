<?php $this->display('header');?>
<!--tablesorter-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/tablesorter/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.tablesorter.js"></script>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.ulist.js"></script>
<!--contextmenu-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/contextMenu/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.contextMenu.js"></script>
<!--pagination-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/pagination/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.pagination.js"></script>
<script type="text/javascript" src="apps/video/js/vms.js"></script>

<link rel="stylesheet" type="text/css" href="apps/video/css/style.css">

<style type="text/css">
    .piclist li .duration{position:absolute;z-index:2;top:78px;left:11px;height:20px; width:120px; background:url(images/trans_bg.png) transparent;text-indent:5px;color:#fff;}
</style>

<div class="bk_8"></div>
<div class="tag_1" style="margin-bottom:0;">
    <ul class="tag_list" id="pagetab">
        <li><a href="javascript:vms.where($('#search_f'),1);" class="s_3">已完成</a></li>
        <li><a href="javascript:vms.where($('#search_f'),0);">转码中</a></li>
    </ul>
    <div class="f_r" style="width:245px">
        <span>最大允许上传 <span id="maxSize" style="color:red;">0MB</span></span>
        <span id="up" class="button">上传视频</span>
        <button style="margin-right:10px;" type="button" class="button_style_1" onclick="vms.reload();">刷新</button>
    </div>
</div>
<div class="table_head operation_area" style="border-bottom:0;background:#F3F9FA;">
    <div class="f_l search_icon" style="width:470px;">
        <form id="search_f" onsubmit="return false;">
            <input type="hidden" id="status" name="status" value="1" />
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td width="160"><input type="text" size="12" name="keywords" class="search_input_text" style="width:145px" value="<?=$keywords?>" /></td>
                    <td width="90"><input id="addtime_from" name="addtime_from" type="text" class="input_calendar search_input_text" value="<?=$addtime_from?>" size="12" style="width:85px;"/></td>
                    <td class="t_c" width="15">至</td>
                    <td width="90"><input id="addtime_to" name="addtime_to" type="text" class="input_calendar search_input_text" value="<?=$addtime_to?>" size="12" style="width:85px;"/></td>
                    <td class="t_c" width="60"><input type="button" value="搜索" class="button_style_1" onclick="vms.where($('#search_f'));return false;" /></td>
                </tr>
            </table>
        </form>
    </div>
</div>
<div class="bk_8"></div>

<div class="piclist" id="item_list">
    <ul></ul>
</div>
<div class="bk_8"></div>
<!--右键菜单-->
<ul id="right_menu" class="contextMenu">
    <li><a href="#vms.check">选择</a></li>
    <li class="preview"><a href="#vms.preview">预览</a></li>
    <li class="view"><a href="#vms.view">查看</a></li>
    <li class="edit"><a href="#vms.edit">编辑</a></li>
</ul>
<div class="table_foot">
    <div id="pagination" class="pagination f_r"></div>
    <p class="f_l">
        <button type="button" onclick="vms.check()" class="button_style_2" style="margin-right:10px;">确定</button>
        <button type="button" onclick="vms.reload()" class="button_style_1">刷新</button>
    </p>
</div>
<script type="text/javascript">
    var upload_max_filesize = <?php echo $upload_max_filesize; ?> + 0;
    $(document).ready(function() {
        $.getJSON('?app=video&controller=vms&action=get_setting&type=system', function(json){
            if(json.state && json.data){
                upload_max_filesize = json.data.maxsize ? json.data.maxsize : upload_max_filesize;
                $('#maxSize').html(Math.round(upload_max_filesize/1024/1024) + 'M');
            }
        });
        vms.init(0, '<?=$upurl?>', '<?=$playerurl?>', '<?=$filetype?>');
    });
</script>
<?php $this->display('footer');