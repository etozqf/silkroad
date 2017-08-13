<?php $this->display('header', 'system'); ?>
<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<script type="text/javascript" src="imageEditor/cmstop.imageEditor.js"></script>
<script type="text/javascript" src="js/cmstop.filemanager.js"></script>
<script type="text/javascript" src="apps/mobile/js/signature.js"></script>
<div class="bk_8"></div>
<h2 class="title mar_l_8">直播设置</h2>
<form id="setting_form" action="?app=mobile&controller=setting&action=live" method="POST" class="validator">
	<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
		<tr>
			<th width="160">标题：</th>
			<td><input type="text" name="setting[title]" value="<?php echo $title;?>" style="width:150px;" /></td>
		</tr>
		<tr>
			<th>简介：</th>
			<td><textarea name="setting[info]" style="width:150px;"><?php echo $info;?></textarea></td>
		</tr>
		<tr>
			<th>iPhone直播地址：</th>
			<td><input type="text" name="setting[address][iphone]" value="<?php echo $address['iphone'];?>" style="width:150px;" /></td>
		</tr>
		<tr>
			<th>iPhone缩略图：</th>
			<td>
				<?php echo element::image('setting[thumb][iphone]', $thumb['iphone'], 20, 1, 'data-validator-tips="缩略图规格560x420"');?>
			</td>
		</tr>
		<tr>
			<th>Android直播地址：</th>
			<td><input type="text" name="setting[address][android]" value="<?php echo $address['android'];?>" style="width:150px;" /></td>
		</tr>
		<tr>
			<th>Android缩略图：</th>
			<td><?php echo element::image('setting[thumb][android]', $thumb['android'], 20, 1, '');?>
		</tr>
		<tr>
			<th>iPad直播地址：</th>
			<td><input type="text" name="setting[address][ipad]" value="<?php echo $address['ipad'];?>" style="width:150px;" /></td>
		</tr>
		<tr>
			<th>iPad缩略图：</th>
			<td><?php echo element::image('setting[thumb][ipad]', $thumb['ipad'], 20, 1, '');?>
		</tr>
		<tr>
			<th>Android pad直播地址：</th>
			<td><input type="text" name="setting[address][pad]" value="<?php echo $address['pad'];?>" style="width:150px;" /></td>
		</tr>
		<tr>
			<th>Android pad缩略图：</th>
			<td><?php echo element::image('setting[thumb][pad]', $thumb['pad'], 20, 1, '');?>
		</tr>
		<tr>
			<th></th>
			<td><input type="submit" id="submit" value="保存" class="button_style_2"></td>
		</tr>
</form>
<script type="text/javascript">
$(function(){
	$('#setting_form').ajaxForm();
})
</script>