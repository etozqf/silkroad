<form name="payview_order_edit" id="payview_order_edit" method="POST" action="?app=payview&controller=payview_power&action=edit">
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
	<input type="hidden" name="username" id="username" value="<?=$data['username']?>"/>
	<input type="hidden" name="oid" id="oid" value="<?=$data['oid']?>"/>
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
			$selected = $data['pvcid']==$r['pvcid'] ? ' selected' : '';
			echo '<option value="'.$r['pvcid'].'"' .$selected. '>' . $r['title'] .'('.$r['timetype'].'个月)</option>';
		}
		?>
			</select>
		</td>
	</tr>
	<tr>
		<th>阅读权限开始时间：</th>
		<td><input type="text" name="starttime" class="input_calendar" value="<?=$data['starttime']?>" size="20"/></td>
	</tr>
	<tr>
		<th>阅读权限结束时间：</th>
		<td><input type="text" name="endtime" class="input_calendar" value="<?=$data['endtime']?>" size="20"/></td>
	</tr>
</table>
</form>