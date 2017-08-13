<?php $this->display('header', 'system');?>
<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<script type="text/javascript" src="js/cmstop.filemanager.js"></script>
<div class="bk_10"></div>
<form id="setting_edit_basic" action="?app=system&controller=setting&action=edit" method="POST" class="validator">
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
	<caption>站点信息</caption>
	<tr>
		<th width="150">网站名称：</th>
		<td><input id="sitename" type="text" name="setting[sitename]" value="<?=$sitename?>" size="30"/></td>
	</tr>
	<tr>
		<th>网站地址：</th>
		<td><input id="siteurl" type="text" name="setting[siteurl]" value="<?=$siteurl?>" size="50"/></td>
	</tr>
	<tr>
		<th>访问统计代码：</th>
		<td><textarea  name="setting[statcode]" rows="6" cols="50" class="bdr"><?=$statcode?></textarea></td>
	</tr>
	<tr>
		<th>网站关闭：</th>
		<td>
		<input type="radio" name="setting[closed]" value="1" class="radio" <?php if ($closed) echo 'checked';?>/>是 <input type="radio" name="setting[closed]" value="0" class="radio" <?php if (!$closed) echo 'checked';?>>否</td>
	</tr>
	<tr>
		<th>关闭原因：</th>
		<td><textarea name="setting[closedreason]" rows="6" cols="50" class="bdr"><?=$closedreason?></textarea></td>
	</tr>
</table>
<div class="bk_10"></div>
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
	<caption>社交信息</caption>
	<tr>
		<th width="150">腾讯微博地址：</th>
		<td><input id="tencent_weibo" type="text" name="setting[tencent_weibo]" value="<?=$tencent_weibo?>" size="30"/></td>
	</tr>
	<tr>
		<th width="150">新浪微博地址：</th>
		<td><input id="sina_weibo" type="text" name="setting[sina_weibo]" value="<?=$sina_weibo?>" size="30"/></td>
	</tr>
	<tr>
		<th>微信公众账号：</th>
		<td><?=element::image('setting[wxqrcode]', $wxqrcode, 30)?>  <span>请上传微信公众账号的二维码</span></td>
	</tr>
	<tr>
		<th></th>
		<td valign="middle">
		  <input type="submit" id="submit" value="保存" class="button_style_2"/>
		</td>
	</tr>
</table>
</form>
<div class="bk_10"></div>
<script type="text/javascript">
$(function(){
	$("#multiUp").uploader({
		script : '?app=editor&controller=filesup&action=upload',
		fileDataName : 'multiUp',
		fileExt : '<?=$allow?>',
		buttonImg : 'images/multiup.gif',
		complete:function(response, data){
			response =(new Function("","return "+response))();
			if(response.state) {
				tinyMCE.activeEditor.execCommand('mceInsertContent', false, response.code);
				ct.ok(response.msg);
			} else {
				ct.error(response.msg);
			}
		}
	});
	$('#setting_edit_basic').ajaxForm(function(json){
		if(json.state) ct.tips(json.message);
		else ct.error(json.error);
	});
});
</script>
<?php $this->display('footer', 'system');