<form name="payview_order_edit" id="payview_order_edit" method="POST" action="?app=payview&controller=payview_order&action=edit&oid=<?=$oid?>">
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
	<input type="hidden" name="oid" id="oid" value="<?=$oid?>"/>
	<tr>
		<th width="120">订单号：</th>
		<td><?=$data['orderno']?></td>
	</tr>
	<tr>
		<th>订阅用户：</th>
		<td><?=$data['username']?>
		</td>
	</tr>
	<tr>
		<th>订阅栏目组：</th>
		<td>
			<select id="pvcid" name="pvcid">
		<?php
		foreach($payview_category as $r)
		{
			$selected = $data['pvcid']==$r['pvcid'] ? 'selected' : '';
			echo '<option value="'.$r['pvcid'].'"' .$selected. '>' . $r['title'] .'('.$r['timetype'].'个月)</option>';
		}
		?>
			</select>
		</td>
	</tr>
	<tr>
		<th>付费类型：</th>
		<td>
			<label><input type="radio" value="0" name="type" <?php if($data['type']==0){echo 'checked="checked"';} ?> /> 线上</label>
			<label><input type="radio" value="1" name="type" <?php if($data['type']==1){echo 'checked="checked"';} ?> /> 线下</label>
		</td>
	</tr>
	<tr>
		<th>订阅开始时间：</th>
		<td><input type="text" name="starttime" class="input_calendar" value="<?=$data['starttime']?>" size="20"/></td>
	</tr>
	<tr>
		<th>订阅结束时间：</th>
		<td><input type="text" name="endtime" class="input_calendar" value="<?=$data['endtime']?>" size="20"/></td>
	</tr>
	<tr>
		<th>付费状态：</th>
		<td>
			<label><input type="radio" value="0" name="status" <?php if($data['status']==0){echo 'checked="checked"';} ?> /> 未支付</label>
			<label><input type="radio" value="1" name="status" <?php if($data['status']==1){echo 'checked="checked"';} ?> /> 已支付</label>
		</td>
	</tr>
	<tr>
		<th>订阅费用：</th>
		<td><input type="text" name="payfee" value="<?=$data['payfee']?>" size="20"/> 元</td>
	</tr>
	<tr>
		<th>是否开具发票：</th>
		<td>
			<label><input type="radio" value="1" name="is_invoice" <?php if($data['is_invoice']==1){echo 'checked="checked"';} ?> /> 是</label>
			<label><input type="radio" value="0" name="is_invoice" <?php if($data['is_invoice']==0){echo 'checked="checked"';} ?> /> 否</label>
		</td>
	</tr>
	<tr>
		<th>发票抬头：</th>
		<td><input type="text" name="invoice_title" value="<?=$data['invoice_title']?>" size="50"/></td>
	</tr>
	<tr>
		<th>收件人：</th>
		<td><input type="text" name="post_name" value="<?=$data['post_name']?>" size="50"/></td>
	</tr>
	<tr>
		<th>邮寄地址：</th>
		<td><input type="text" name="post_address" size="50" value="<?=$data['post_address']?>"></td>
	</tr>
	<tr>
		<th>邮寄费用：</th>
		<td><input type="text" name="post_fees" size="20" value="<?=$data['post_fees']?>"> 元</td>
	</tr>
	<tr>
		<th>联系电话：</th>
		<td><input type="text" name="post_phone" size="30" value="<?=$data['post_phone']?>"></td>
	</tr>
</table>
</form>