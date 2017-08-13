<?php $this->display('header', 'system'); ?>
<script src="tiny_mce/tiny_mce.js" type="text/javascript"></script>
<script src="tiny_mce/editor.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL;?>js/lib/editplus/style.css" />
<script src="<?php echo IMG_URL;?>js/lib/cmstop.editplus.js" type="text/javascript"></script>

<link rel="stylesheet" href="apps/mobile/css/base.css" />
<script type="text/javascript" src="apps/mobile/js/signature.js"></script>
<div class="bk_8"></div>
<div class="tag_1">
	<ul class="tag_list">
		<li><a href="?app=mobile&controller=setting&action=static_page&page=about" value="0" <?php if($page == 'about'):?>class="s_3" <?php endif;?>>关于我们</a></li>
		<li><a href="?app=mobile&controller=setting&action=static_page&page=declare" value="1" <?php if($page == 'declare'):?>class="s_3" <?php endif;?>>免责申明</a></li>
	</ul>
</div>
<div class="mar_l_8">
	<textarea id="content" style="visibility:hidden;height:450px;width:630px;top:0;left:0;position:relative;"><?php echo $content;?></textarea>
	<div class="bk_8"></div>
	<div class="clear">
		<button id="save" class="button_style_2">保存</button>
		<button id="preview" class="mar_l_8 button_style_2">预览</button></div>
</div>
<form id="preview_form" method="post" action="?app=mobile&controller=setting&action=static_page" target="blank" style="display:none;">
	<textarea name="preview"></textarea>
	<input type="submit" />
</form>
<script type="text/javascript">

	$(function() {
		var editplus = $('#content').editplus();
		editplus.textarea.css('visibility', 'visible');
		editplus.textarea.val(editplus.textarea.text())

		$('#save').bind('click', function(){
			$.post('?app=mobile&controller=setting&action=static_page&page=<?php echo $page;?>', {
				'content': editplus.textarea.val()
			}, function(res){
				res.state ? ct.ok('保存成功') : ct.error('保存失败');
			}, 'json');
		});

		$('#preview').bind('click', function(){
			var form = $('#preview_form');
			form.children('[name="preview"]').val(editplus.textarea.val());
			form.submit();
		});
	});
</script>