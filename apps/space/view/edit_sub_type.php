<?php $this->display('header', 'system');?>

<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/tablesorter/style.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/pagination/style.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/contextMenu/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.tablesorter.js"></script>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.contextMenu.js"></script>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.pagination.js"></script>
<script type="text/javascript" src="apps/space/js/space.js"></script>

<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/autocomplete/style.css" />
<script src="<?=IMG_URL?>js/lib/cmstop.autocomplete.js" type="text/javascript"></script>
<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<script type="text/javascript" src="imageEditor/cmstop.imageEditor.js"></script>
<script type="text/javascript" src="js/cmstop.filemanager.js"></script>

<!-- 文本编辑器引入 -->
<script src="<?=ADMIN_URL?>tiny_mce/tiny_mce.js" type="text/javascript" class="editorjs"></script>
<script src="<?=ADMIN_URL?>tiny_mce/editor.js" type="text/javascript" class="editorjs"></script>


<div class="bk_8"></div>
<form name="column_add" id="column_add" method="POST" class="validator" action="?app=space&controller=index&action=check_edit_sub_type">
<input type="hidden" name="sid" value="<?=$list['sid']?>"/>
<table border="0" cellspacing="0" cellpadding="0" class="table_form">
	<tbody>
		<tr><th width="80"> 分类名称：</th><td><input type="text" name="name" value="<?=$list['name']?>" size="30"/></td></tr>
		<tr><th>状态：</th><td>
		<select name="status">
			<?php 
				if($list['status']==1):
			?>
			<option value="1" selected="selected">正常</option>
			<option value="2">禁用</option>
			<?php
				else:
			 ?>
			<option value="1">正常</option>
			<option value="2" selected="selected">禁用</option>
			<?php 
				endif;
			?>
			</select>
		</td></tr>
		<tr><th>排序：</th><td><input type="text" name="sort" value="<?=$list['status']?>" size="3"/></td></tr>
		<tr>
            <th width="120"></th>
            <td colspan="3">
               <input type="submit" value="保存" class="button_style_2"/>
            </td>
        </tr>
	</tbody>
</table>
</form>
<script type="text/javascript">
$(function(){
	$("#column_add").ajaxForm(function(json){
		if(json.state){
			ct.ok(json.message);
			setTimeout(function(){
				location.href='?app=space&controller=index&action=type';
			},1000);
		}else{
			ct.error(json.message);
		}
	})
});

</script>
<?php $this->display('footer', 'system');?>
