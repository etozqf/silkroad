<form name="<?=$controller?>_save" id="<?=$controller?>_save" action="?app=pay&controller=platform&action=save" method="POST">
    <input type="hidden" name="apiid" value="<?= $apiid?>">
	<table class="table_form mar_l_8" cellpadding="0" cellspacing="0" width="98%">
	<tr>
		<th width="120">名称：</th>
		<td><?= $name?></td>
	</tr>
	<tr>
		<th>LOGO：</th>
		<td><input type="text" name="logo" value="<?= $logo?>" size="40"></td>
	</tr>
	<tr>
		<th>网址：</th>
		<td><input type="text" name="url" value="<?= $url?>" size="40"></td>
	</tr>
	<tr>
		<th>描述：</th>
		<td><textarea rows="5" cols="50" name="description"><?=htmlspecialchars($description)?> 
		</textarea></td>
	</tr>
	<tr>
		<th>手续费：</th>
		<td><input type="text" name="payfee" value="<?= $payfee?>" size="10"></td>
	</tr>
	<tr>
		<th>个人或企业：</th>
		<td>
			<label><input type="radio" name="setting[b2b_or_b2c]" value="b2c" <?php if(!$setting['b2b_or_b2c'] || $setting['b2b_or_b2c']=='b2c'){echo 'checked="checked"';} ?>> 个人（b2c）</label>
			<label><input type="radio" name="setting[b2b_or_b2c]" value="b2b" <?php if($setting['b2b_or_b2c']=='b2b'){echo 'checked="checked"';} ?>> 企业（b2b）</label>
			<label><input type="radio" name="setting[b2b_or_b2c]" value="all" <?php if($setting['b2b_or_b2c']=='all'){echo 'checked="checked"';} ?>> 以上两个都要</label>
		</td>
	</tr>
	<tr>
		<th>商户号：</th>
		<td><input type="text" name="setting[merid]" value="<?= $setting['merid']?>" size="30"></td>
	</tr>
	<tr>
		<th>接口版本号：</th>
		<td><input type="text" name="setting[version]" value="<?= $setting['version']?>" size="30"></td>
	</tr>
	<!--<tr>
		<th>私钥文件：</th>
		<td><input type="text" name="setting[PRI_KEY]" value="<?= $setting['PRI_KEY']?>" size="40"></td>
	</tr>
	<tr>
		<th>公钥文件：</th>
		<td><input type="text" name="setting[PUB_KEY]" value="<?= $setting['PUB_KEY']?>" size="40"></td>
	</tr>
	<tr>
		<th>支付请求地址：</th>
		<td><input type="text" name="setting[REQ_URL_PAY]" value="<?= $setting['REQ_URL_PAY']?>" size="50"></td>
	</tr>
	<tr>
		<th>查询请求地址：</th>
		<td><input type="text" name="setting[REQ_URL_QRY]" value="<?= $setting['REQ_URL_QRY']?>" size="50"></td>
	</tr>
	<tr>
		<th>退款请求地址：</th>
		<td><input type="text" name="setting[REQ_URL_REF]" value="<?= $setting['REQ_URL_REF']?>" size="50"></td>
	</tr>-->
	<tr>
		<th>排序：</th>
		<td><input type="text" name="sort" value="<?= $sort?>" size="5"></td>
	</tr>
	<tr>
		<th>说明：</th>
		<td></td>
	</tr>
	</table>
</form>