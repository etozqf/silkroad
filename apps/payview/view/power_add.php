<form name="payview_order_add" id="payview_order_add" method="POST" class="validator" action="?app=payview&controller=payview_power&action=edit">
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
		<th>阅读权限开始时间：</th>
		<td><input type="text" name="starttime" class="input_calendar" value="" size="20"/></td>
	</tr>
	<tr>
		<th>阅读权限结束时间：</th>
		<td><input type="text" name="endtime" class="input_calendar" value="" size="20"/></td>
	</tr>
</table>
</form>