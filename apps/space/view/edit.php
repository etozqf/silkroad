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
<form name="column_edit" id="column_edit" method="POST" class="validator" action="?app=space&controller=index&action=edit&spaceid=<?=$spaceid?>">
<table border="0" cellspacing="0" cellpadding="0" class="table_form">
	<tbody>
		<tr>
			<th>咨询类别：</th>
			<td>
				<select name="typeid">
					<?php foreach ($types as $key => $value): ?>	
					<option value="<?=($key+1)?>" <?php echo (($key+1)==$typeid)?'selected':''?>  ><?=$value?></option>	
					<?php endforeach ?>
				</select>
			</td>
		</tr>
		<tr><th>子类别：</th><td>
			<?php 
				foreach($sub_type_list as $value):
				if($value['sid']==$sub_type):
			?>	
			<input type="radio" name="sub_type" value="<?=$value['sid']?>" checked="checked"/>&nbsp;&nbsp;<?=$value['name']?>&nbsp;&nbsp; 	
			<?php
				else:
			?>
			<input type="radio" name="sub_type" value="<?=$value['sid']?>"/>&nbsp;&nbsp;<?=$value['name']?>&nbsp;&nbsp; 
			<?php		
				endif;	
				endforeach;
			?>
		</td></tr>
		<tr><th width="80"> 名称：</th><td><input type="text" name="name" value="<?=$name?>" size="30"/></td></tr>
		<tr><th> 作者名：</th><td><input type="text" name="author" value="<?=$author?>" size="30"/></td></tr>
		<tr><th><?=element::tips('关联后该用户将可以管理专栏 如果不关联请留空')?>关联用户：</th><td><input name="username" value="<?php echo username($userid);?>" id="username"/></td>
		</tr>
		<tr><th><?=element::tips('个性专栏地址只能由字母和数字组成')?>地址：</th><td> <?php echo SPACE_URL;?><input type="text" name="alias" id="alias" value="<?=$alias?>" size="20"/></td></tr>
		<tr><th> 标签：</th><td><input type="text" name="tags" value="<?=$tags?>" size="30"/></td></tr>
		<tr><th>头像：</th><td><?=element::image('photo', $photo, 30)?></td></tr>
		<tr><th>简介：</th><td><textarea name="description" id="description" style="width:650px;height:100px;"><?=$description?></textarea></tr>
		<tr><th>投资建议：</th><td><textarea name="advice" id="advice" style="width:650px;height:100px;"><?=$advice?></textarea></tr>
		<tr><th>状态：</th><td>
		<select name="status">
			<?php 
			foreach($statuss as $k => $v) {
				if($k == $status) echo '<option value="'.$k.'" selected="selected">'.$v.'</option>';
				else  echo '<option value="'.$k.'">'.$v.'</option>';
			}
			?>
		</select>
		</td></tr>
		<tr><th>排序：</th><td><input type="text" name="sort" value="<?=$sort?>" size="3"/></td></tr>
		<tr><th>发稿权限：</th><td><input type="checkbox" name="iseditor" value="1" <?php if(!empty($iseditor)) { echo 'checked';} ?>/> 免审</td></tr>
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
	$('img.tips').attrTips('tips', 'tips_green', 200, 'top');
	$('#username').autocomplete({
		url : '?app=space&controller=index&action=username&q=%s'
	});

	//编辑器引入 :简介 投资建议
	$('#description').editor();
	$('#advice').editor();

	 //表单提交后回调函数
	$('#column_edit').ajaxForm(function(json){
	  if (json.state) {
	    ct.ok('修改成功');
	    setTimeout(function(){
	      location.href = '?app=space&controller=index&action=index';
	    }, 1000);
	  } else {
	    cmstop.error(json.error);
	  }
	});
});

</script>
<?php $this->display('footer', 'system');?>