<?php $this->display('header', 'system');?>
<div class="bk_10"></div>
<form id="setting_edit_api" action="?app=system&controller=setting&action=edit" method="POST" class="validator">
    <?php $auth_config = app_config('system', 'auth'); ?>
	<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
		<caption>腾讯微博API</caption>
		<tr>
			<th width="150">App Key：</th>
			<td><input id="tencent_appkey" type="text" name="setting[tencent_appkey]" value="<?=$tencent_appkey?>" size="40"/>&nbsp;&nbsp;&nbsp;<a href="http://dev.t.qq.com/" target="_blank">立即申请</a></td>
		</tr>
		<tr>
			<th width="150">App Secret：</th>
			<td><input id="tencent_Secret" type="text" name="setting[tencent_appsecret]" value="<?=$tencent_appsecret?>" size="40"/></td>
		</tr>
        <tr>
            <th>应用网址：</th>
            <td><input type="text" readonly="readonly" value="<?=$auth_config['tencent_weibo']['authorize_callback_url']?>" size="40" title="只读，复制到应用申请处使用" /></td>
        </tr>
	</table>
	<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
		<caption>新浪API</caption>
		<tr>
			<th width="150">App Key：</th>
			<td><input id="sina_key" type="text" name="setting[sina_appkey]" value="<?=$sina_appkey?>" size="40"/>&nbsp;&nbsp;&nbsp;<a href="http://open.weibo.com/" target="_blank">立即申请</a></td>
		</tr>
		<tr>
			<th width="150">App Secret：</th>
			<td><input id="sina_Secret" type="text" name="setting[sina_appsecret]" value="<?=$sina_appsecret?>" size="40"/></td>
		</tr>
        <tr>
            <th>授权回调页：</th>
            <td><input type="text" readonly="readonly" value="<?=$auth_config['sina_weibo']['authorize_callback_url']?>" size="40" title="只读，复制到应用申请处使用" /></td>
        </tr>
	</table>
	<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
		<caption>百度地图API</caption>
		<tr>
			<th width="150">密钥：</th>
			<td><input id="baidumapkey" type="text" name="setting[baidumapkey]" value="<?=$baidumapkey?>" size="40"/>&nbsp;&nbsp;&nbsp;<a href="http://developer.baidu.com/" target="_blank">立即注册</a></td>
		</tr>
	</table>
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <caption>搜狐畅言</caption>
        <tr>
            <th width="150">App ID：</th>
            <td><input id="sina_key" type="text" name="setting[sohu_changyan_appid]" value="<?=$sohu_changyan_appid?>" size="40"/></td>
        </tr>
        <tr>
            <th width="150">App Key：</th>
            <td><input id="sina_Secret" type="text" name="setting[sohu_changyan_appsecret]" value="<?=$sohu_changyan_appsecret?>" size="40"/></td>
        </tr>
        <tr>
            <th></th>
            <td valign="middle"><input type="submit" id="submit" value=" 保存 " class="button_style_2"/></td>
        </tr>
    </table>
</form>
<div class="bk_10"></div>
<script type="text/javascript">
$(function(){
	$('#setting_edit_api').ajaxForm(function(json){
		if(json.state) ct.tips(json.message);
		else ct.error(json.error);
	});
});
</script>
<?php $this->display('footer', 'system');