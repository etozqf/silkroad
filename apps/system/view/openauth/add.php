<div class="bk_8"></div>
<form name="<?=$controller?>_<?=$action?>" id="<?=$controller?>_<?=$action?>" method="POST" class="validator" action="?app=<?=$app?>&controller=<?=$controller?>&action=<?=$action?>">
<table border="0" cellspacing="0" cellpadding="0" class="table_form">
	<tbody>
		<tr><th width="80"><?=element::tips('用于内容访问和添加的授权后台帐号')?>授权用户：</th>
            <td><input type="text" name="username" value="" id="username" maxlength="25" style="width:150px;" /></td>
        </tr>
        <tr><th>授权公钥：</th><td><input type="text" name="auth_key" value="<?=$auth_key?>" maxlength="32" style="width:260px;" /></td></tr>
        <tr><th>授权私钥：</th><td><input type="text" name="auth_secret" value="<?=$auth_secret?>" maxlength="32" style="width:260px;" /></td></tr>
        <tr><th>备注：</th><td><textarea name="remarks"  style="width:260px;height:60px;"><?=$remarks?></textarea></tr>
		<tr><th>状态：</th><td>
		    <select name="disabled">
                <?php $disabled = 0; ?>
                <option value="1" <?php if($disabled) echo 'selected="selected"'; ?>>禁用</option>
                <option value="0" <?php if(!$disabled) echo 'selected="selected"'; ?>>启用</option>
		    </select>
		</td></tr>
		<!-- <tr><th><?=element::tips('去重只会跟会标题去重，重复文章会自动保存为草稿')?>是否去重：</th><td>
		    <input class="checkbox_style" type="checkbox" value="1">
		</td></tr> -->
	</tbody>
</table>
</form>
<script type="text/javascript">
$(function(){
    $('img.tips').attrTips('tips', 'tips_green', 200, 'top');
	$('#username').autocomplete({
		url : '?app=system&controller=openauth&action=username&q=%s'
	});
});
</script>