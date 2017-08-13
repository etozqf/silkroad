<style type="text/css">
.dialog-box input[type="text"], .dialog-box textarea {width:250px;}
.api_param td {width:120px;}
.api_param input[type="text"] {width:80px;}
.api_param_add {color:#077AC7; text-decoration:none;}
.table_form td a,.table_form td a:hover {color:#077AC7;text-decoration:none;}
.dialog-box input[type="text"].input-icon, .dialog-box input[type="text"].input-icon_gray {width:184px;}
.param_row {height:32px;}
</style>
<div class="bk_8"></div>
<form name="thirdlogin_add" id="thirdlogin_add" method="POST" action="?app=cloud&controller=thirdlogin&action=edit&id=<?=$_GET['id']?>">
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
  <tr>
    <th width="80"><span class="c_red">*</span> 名称:</th>
    <td><input type="text" name="name" value="<?=$name?>" /></td>
  </tr>
  <tr>
    <th width="80"><span class="c_red">*</span> 接口:</th>
    <td>
		<select name="interface" id="interface">
			<?php foreach($interface_data as $item):?>
			<option value="<?=$item?>" <?php if($item == $interface):?>selected="selected"<?php endif;?>><?=$item?></option>
			<?php endforeach;?>
		</select>
	</td>
  </tr>
  <tr>
    <th width="80"><span class="c_red">*</span> 分享:</th>
    <td><label><input type="checkbox" name="isshare" value="1"<?php if($isshare):?> checked="checked"<?php endif;?> />允许评论通过此接口发送到微博</label></td>
  </tr>
  <tr>
    <th> 描述:</th>
    <td><textarea name="description"><?=$description?></textarea></td>
  </tr>
  <tr>
    <th><span class="c_red">*</span> 已授权图标:</th>
    <td>
		<input type="text" name="icon" value="<?=$icon?>" class="input-icon" />
		<div id="uploadify" class="uploader" style="margin-bottom: -8px;"><input type="button" value="上传图片" class="button_style_1" /></div>
	</td>
  </tr>
  <tr>
    <th><span class="c_red">*</span> 未授权图标:</th>
    <td>
		<input type="text" name="icon_gray" value="<?=$icon_gray?>" class="input-icon_gray" />
		<div id="uploadify_gray" class="uploader" style="margin-bottom: -8px;"><input type="button" value="上传图片" class="button_style_1" /></div>
	</td>
  </tr>
  <?php $authorize = json_decode($authorize, 1);foreach($params_data as $key=>$item):?>
  <tr class="third-params-tr">
	<th><span class="c_red">*</span> <?php echo $key;?>:</th>
	<td>
		<input type="hidden" name="paramname[]" value="<?php echo $item?>" />
		<input type="text" name="paramvalue[]" value="<?php echo $authorize[$item];?>" />
	</td>
  </tr>
  <?php endforeach;?>
</table>
</form>
<script type="text/javascript">
$('#interface').selectlist().bind('changed', function(e, t) {
	changeInterface(t.checked[0]);
});
var thumbPreview = function(elm, src) {
	var thumbDiv = $('<div id="thirdlogin-thumb" style="display:none; position: absolute; overflow: hidden; background: #CCC; z-index:8888;"></div>');
	thumbDiv.appendTo('body');
	thumbDiv.append('<img src="'+src+'" alt="" />');
	$(document).one('keydown', function(e) {
		if (e.keyCode == 27) {	// ESC
			thumbDiv.hide();
		}
	});
	elm.hover(function() {
		thumbDiv.css({
			'top'	: elm.outerHeight(true)+elm.offset().top+1,
			'left'	: elm.offset().left - thumbDiv.find('img').outerWidth() + elm.outerWidth()
		});
		thumbDiv.show();
	}, function() {
		thumbDiv.hide();
	});
}
$('#uploadify').uploader({
	script			: '?app=system&controller=upload&action=upload',
	fileDesc		: '图像',
	fileExt			: '*.gif;*.png;',
	multi			: false,
	complete:function(json, data) {
		json = eval('('+json+')');
		if (json.state) {
			thumbPreview($('#uploadify').parent().find('.input-icon').val(UPLOAD_URL + json.file), UPLOAD_URL + json.file);
		} else {
			ct.error('上传失败');
		}
	},
	error:function(data) {
		ct.error('上传失败');
	}
});
$('#uploadify_gray').uploader({
	script			: '?app=system&controller=upload&action=upload',
	fileDesc		: '图像',
	fileExt			: '*.gif;*.png;',
	multi			: false,
	complete:function(json, data) {
		json = eval('('+json+')');
		if (json.state) {
			thumbPreview($('#uploadify_gray').parent().find('.input-icon_gray').val(UPLOAD_URL + json.file), UPLOAD_URL + json.file);
		} else {
			ct.error('上传失败');
		}
	},
	error:function(data) {
		ct.error('上传失败');
	}
});
thumbPreview($('.input-icon'), $('.input-icon').val());
thumbPreview($('.input-icon_gray'), $('.input-icon_gray').val());
</script>