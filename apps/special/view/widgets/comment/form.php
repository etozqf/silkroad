<form>
	<table width="95%" border="0" cellspacing="0" cellpadding="0">
		<tbody>
			<tr>
				<td colspan="2">
				<?php if(empty($topicid)):?>
				<strong style="color:#f00;">*请先开启评论</strong>
				<?php endif;?>
				</td>
			</tr>
			<tr>
				<th width="120">最大条数：</th>
				<td>
					<input type="text" name="data[rows]" value="<?php echo empty($data['rows'])?$rows:$data['rows'];?>" size="10"/> 
				</td>
			</tr>
		</tbody>
	</table>
</form>