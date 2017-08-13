<form name="payview_order_add" id="payview_order_add" method="POST" class="validator" action="?app=payview&controller=payview_order&action=add">
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
	<tr>
		<th><span class="c_red">*</span> 订阅用户：</th>
		<td><input type="text" name="username" value="" size="30"/></td>
		</td>
	</tr>
	<tr>
		<th>订阅栏目组：</th>
		<td>
			<select id="pvcid" name="pvcid">
		<?php
		foreach($payview_category as $r)
		{
			echo '<option value="'.$r['pvcid'].'">'.$r['title'].'('.$r['timetype'].'个月)</option>';
		}
		?>
			</select>
		</td>
	</tr>
	<tr>
		<th>付费类型：</th>
		<td>
			<label><input type="radio" value="0" name="type" /> 线上</label>
			<label><input type="radio" value="1" name="type" checked="checked" /> 线下</label>
		</td>
	</tr>
	<tr>
		<th>订阅开始时间：</th>
		<td><input type="text" name="starttime" class="input_calendar" value="" size="20"/></td>
	</tr>
	<tr>
		<th>订阅结束时间：</th>
		<td><input type="text" name="endtime" class="input_calendar" value="" size="20"/></td>
	</tr>
	<tr>
		<th>付费状态：</th>
		<td>
			<label><input type="radio" value="0" name="status" checked="checked" /> 未支付</label>
			<label><input type="radio" value="1" name="status" /> 已支付</label>
		</td>
	</tr>
	<tr>
		<th>订阅费用：</th>
		<td><input type="text" name="payfee" value="" size="20"/> 元</td>
	</tr>
	<tr>
		<th>是否开具发票：</th>
		<td>
			<label><input type="radio" value="1" name="is_invoice" /> 是</label>
			<label><input type="radio" value="0" name="is_invoice" checked="checked" /> 否</label>
		</td>
	</tr>
	<tr>
		<th>发票抬头：</th>
		<td><input type="text" name="invoice_title" value="" size="50"/></td>
	</tr>
	<tr>
		<th>收件人：</th>
		<td><input type="text" name="post_name" value="" size="50"/></td>
	</tr>
	<tr>
		<th>邮寄地址：</th>
		<td><input type="text" name="post_address" size="50" value=""></td>
	</tr>
	<tr>
		<th>邮寄费用：</th>
		<td><input type="text" name="post_fees" size="20" value="<?=$setting['post_fees']?>"> 元</td>
	</tr>
	<tr>
		<th>联系电话：</th>
		<td><input type="text" name="post_phone" size="30" value=""></td>
	</tr>
</table>
</form>