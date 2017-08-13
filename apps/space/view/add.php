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
<form name="column_add" id="column_add" method="POST" class="validator" action="?app=space&controller=index&action=add">
<table border="0" cellspacing="0" cellpadding="0" class="table_form">
	<tbody>
		<tr><th>咨询类别：</th><td>
		<select name="typeid" class="typeid">
			<?php 

			foreach($types as $k => $v) {
				$selected = ($k==0)?'selected="selected"':'';
				$k+=1;
				echo '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
			}
			?>
		</select>
		</td></tr>
		<tr class="space_sub_type" style="display: none"><th>子类别：</th><td>
			
			<?php 
				foreach($sub_type_list as $value):
			?>	
			<input type="radio" name="sub_type" value="<?=$value['sid']?>"/>&nbsp;&nbsp;<?=$value['name']?>&nbsp;&nbsp; 	
			<?php	
				endforeach;
			?>


		</td></tr>
		<tr><th width="80"> 名称：</th><td><input type="text" name="name" value="<?=$name?>" size="30"/></td></tr>
		<tr><th> 作者名：</th><td><input type="text" name="author" value="<?=$author?>" size="30"/></td></tr>
		<tr><th><?=element::tips('关联后该用户将可以管理专栏 如果不关联请留空')?>关联用户：</th><td><input name="username" value="" id="username"/></td>
		</tr>
		<tr><th><?=element::tips('个性专栏地址只能由字母和数字组成')?>地址：</th><td> <?php echo SPACE_URL;?><input type="text" name="alias" id="alias" value="<?=$alias?>" size="20"/></td></tr>
		<tr><th>头像：</th><td><?=element::image('photo', '', 30)?></td></tr>
		<tr><th> 标签：</th><td><input type="text" name="tags" value="<?=$tags?>" size="48"/></td></tr>
		<tr><th>简介：</th><td><textarea name="description" id="description" style="width:650px;height:100px;"><?=$description?></textarea></tr>
		<tr><th>投资建议：</th><td><textarea name="advice" id="advice" style="width:650px;height:100px;"><?=$advice?></textarea></tr>
		<tr><th>状态：</th><td>
		<select name="status">
			<?php 
			foreach($statuss as $k => $v) {
				$selected = ($k==3)?'selected="selected"':'';
				echo '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
			}
			?>
			</select>
		</td></tr>
		<tr><th>排序：</th><td><input type="text" name="sort" value="1" size="3"/></td></tr>
		<tr><th>发稿权限：</th><td><input type="checkbox" name="iseditor" value="1"/> 免审</td></tr>
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
	$('#column_add').ajaxForm(function(json){
	  if (json.state) {
	    ct.ok('修改成功');
	    setTimeout(function(){
	      location.href = '?app=space&controller=index&action=index';
	    }, 1000);
	  } else {
	    cmstop.error(json.error);
	  }
	});

	/*咨询类别选择为机构研究时(值为3),下方的子类别单选择钮才会显示*/
	$(".typeid").change(function(){
		var value=$(".typeid option:selected").val();
		if(value==3){
			$(".space_sub_type").show();
		}else{
			$(".space_sub_type").hide();
		}
	})


});

</script>
<?php $this->display('footer', 'system');?>
