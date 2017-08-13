<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$CONFIG['charset']?>" />
<title><?=$head['title']?></title>
<link rel="stylesheet" type="text/css" href="css/admin.css"/>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/config.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/cmstop.js"></script>

<!--ajax form-->
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.cookie.js"></script>

<!--dialog-->
<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/jquery-ui/dialog.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.dialog.js"></script>

<!--validator-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/validator/style.css"/>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.validator.js"></script>

<!--contextmenu-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/contextMenu/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.contextMenu.js"></script>

<link href="<?=IMG_URL?>js/lib/tree/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.tree.js"></script>

<!-- 时间选择器 -->
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.datepicker.js"></script>
<link href="<?=IMG_URL?>js/lib/datepicker/style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
var current_id = '<?=$current_id?>',app_name='<?=$app?>',controller_name='<?=$controller?>';
$.validate.setConfigs({
    xmlPath:'<?=ADMIN_URL?>apps/<?=$app?>/validators/<?=$controller?>/'
});
$(ct.listenAjax);
</script>
<script type="text/javascript" src="apps/<?=$app?>/js/attribute.js"></script>
</head>
<body>
<div style="margin:10px">
  <input type="button" value="新建地区属性" class="button_style_1" onclick="<?=$app?>.add(null)"/>
  <input type="button" value="修复地区属性" class="button_style_1" onclick="<?=$app?>.repair()"/>
</div>
<div class="box_11"></div>
<div class="bg_1" id="tree_in">
  <div class="w_160 box_6 mar_r_8 f_l" style="position:relative;width:255px;height:740px;background: #F9FCFD;overflow-x:hidden;">
    <h3>
<?php
if ($_roleid == 1 || $_roleid == 2)
{
?>
    <a href="javascript: <?=$app?>.add(null);" class="new f_r dis_b mar_5"><img src="images/space.gif" alt="新建地区属性" title="新建地区属性" height="16" width="16" /></a>
<?php
}
?>
    <span class="dis_b b">地区属性列表</span>
    </h3>
    <div class="tree" id="<?=$app?>_tree" idv="tree" style="position:absolute;z-index:3;"></div>
    

  </div>
  <div class="f_l">
    <div class="bk_10"></div>
    <div id="top<?=$app?>" style="display: none;">
    <table class="table_form" width="650">
      <caption>新建地区属性</caption>
    </table>
    </div>
    
    <div id="sub<?=$app?>" class="tag_1" style="width:670px;">
      <ul class="tag_list">
        <li><a href="javascript: <?=$app?>.edit(current_id);" id="edit" class="s_3">修改</a></li>
        <li><a href="javascript: <?=$app?>.add(current_id);" id="add">新建子地区属性</a></li>
      </ul>
      <div>
        <input type="button" value="移动地区属性" class="button_style_1" onclick="<?=$app?>.move(current_id)"/>
        <input type="button" value="删除地区属性" class="button_style_1" onclick="<?=$app?>.del(current_id)"/>
      </div>
    </div>
    
    <div id="<?=$app?>_edit_box" class="clear"></div>
  </div>
  <div class="clear"></div>
</div>
<script type="text/javascript">
attribute.reload(current_id);
</script>
<?php $this->display('footer', 'system');
