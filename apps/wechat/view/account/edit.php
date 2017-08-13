<div class="bk_8"></div>
<form action="?app=wechat&controller=account&action=edit&id=<?php echo $id;?>" method="post">
	<input type="hidden" name="account[type]" value="<?php echo $type;?>" />
	<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
		<tr>
			<th width="80">名称：</th>
			<td width="160">
				<input type="text" name="account[name]" value="<?php echo $name;?>" />
			</td>
		</tr>
		<tr>
			<th>Token：</th>
			<td>
				<input type="text" name="account[token]" value="<?php echo $token;?>" />
			</td>
		</tr>
		<?php if ($type === 'service'):?>
		<tr>
			<th>AppID：</th>
			<td><input type="text" name="account[appid]" value="<?php echo $appid;?>" /></td>
		</tr>
		<tr>
			<th>AppSecret：</th>
			<td><input type="text" name="account[secret]" value="<?php echo $secret;?>" /></td>
		</tr>
		<?php endif;?>
		<tr>
			<th>Url：</th>
			<td>
				<input type="text"  value="<?php echo API_URL;?>wechat/<?php echo $id;?>/" readonly="readonly" />
			</td>
		</tr>
		<tr>
			<th>状态：</th>
			<td>
				<label><input type="radio" name="account[state]" value="enable" <?php if($state != 'disable'):?>checked="checked" <?php endif;?>/>启用</label>
				<label><input type="radio" name="account[state]" value="disable" <?php if($state == 'disable'):?>checked="checked" <?php endif;?>/>禁用</label>
			</td>
		</tr>
	</table>
</form>