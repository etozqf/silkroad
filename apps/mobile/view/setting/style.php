<?php $this->display('header', 'system'); ?>

<!-- color picker -->
<link href="<?php echo IMG_URL;?>js/lib/colorInput/style.css" rel="stylesheet" type="text/css" />
<script src="<?php echo IMG_URL;?>js/lib/cmstop.colorInput.js" type="text/javascript"></script>

<!-- uploader -->
<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<script type="text/javascript" src="apps/mobile/js/lib/uploader.js"></script>

<link rel="stylesheet" type="text/css" href="/apps/mobile/css/base.css">
<link rel="stylesheet" type="text/css" href="/apps/mobile/css/style.css">

<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL;?>js/lib/jquery-ui/dialog.css">
<script type="text/javascript" src="<?php echo IMG_URL;?>js/lib/json2.js"></script>

<script type="text/javascript" src="apps/mobile/js/signature.js"></script>

<script type="text/javascript">
	var styles = <?php echo json_encode(array_values($styles));?>;
	var using = <?php echo $setting['id'];?> + 0;
</script>

<div class="mobile-style-sider f_l">
	<div class="mobile-style-sider-title">风格列表</div>
	<div id="sider">
		<hr />
	</div>
</div>
<div class="mobile-style-demo f_l">
	<div id="demo" class="mobile-style-iphone">
		<div id="demo-nav"></div>
		<div id="demo-button0">确定</div>
		<div id="demo-button1">普通按钮</div>
		<div class="mobile-style-demo-thumb"><img id="demo-background" src="" alt="" /></div>
	</div>
</div>
<div class="mobile-style-setting f_l">
	<div class="bk_20"></div>
	<form id="style-form">
		<input type="hidden" name="id" value="" />
		<table width="98%" border="0" cellspacing="0" cellpadding="0" class="wechat-style-table table_form mar_l_8">
			<tr>
				<th width="150">导航条颜色</th>
				<td width="420">
					<input type="text" name="style[nav]" data-role="nav" class="color mar_l_8" />
				</td>
			</tr>
			<tr>
				<th>导航按钮颜色</th>
				<td>
					<input type="text" name="style[button0]" data-role="button0" class="color mar_l_8" />
				</td>
			</tr>
			<tr>
				<th>普通按钮颜色</th>
				<td>
					<input type="text" name="style[button1]" data-role="button1" class="color mar_l_8" />
				</td>
			</tr>
			<tr><td colspan="2"></td></tr>
			<tr>
				<th colspan="2">上传背景图片</td>
			</tr>
			<tr>
				<td colspan="2">
					<?php foreach (app_config('mobile', 'background.logo') as $logo): ?>
					<div class="mobile-style-bg-panel" id="<?php echo str_replace('*', '', $logo);?>">
						<input type="hidden" name="style[background][<?php echo $logo;?>]" value="" />
						<div class="mobile-style-bg-title"><?php echo $logo;?></div>
						<img src="" alt="" />
						<div class="mobile-style-bg-upload"></div>
					</div>
					<?php endforeach; ?>
				</td>
			</tr>
			<tr><td colspan="2"></td></tr>
			<tr>
				<td colspan="2">
					<div id="saveas" class="mobile-style-btn-saveas"></div>
					<div id="save" class="mobile-style-btn-save"></div>
					<div id="use" class="mobile-style-btn-use"></div>
					<div id="delete" class="mobile-style-btn-delete"></div>
				</td>
			</tr>
		</table>
	</form>
</div>
<div id="new-style-name" style="display:none;">
	<p>新增风格名称 <input type="text" id="newstylename" style="width:235px;" /></p>
</div>
<script type="text/javascript" src="apps/mobile/js/setting.style.js"></script>