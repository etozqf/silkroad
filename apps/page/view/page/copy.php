<div class="bk_5"></div>
<form method="POST" action="?app=page&controller=page&action=copy">
	<input type="hidden" name="pageid" value="<?=$pageid?>" />
	<table width="100%" class="mar_l_8" cellpadding="0" cellspacing="0">
		<tbody>
		<tr>
			<th width="80">克隆：</th>
			<td>
				<?php echo $page['name'];?>
			</td>
		</tr>
		<tr>
			<th>页面名称：</th>
			<td>
				<input type="text" name="name" value="" size="40" />
			</td>
		</tr>
		<tr>
			<th>PSN：</th>
			<td>
				<?php echo element::psn('path', 'template_path', '', 40, 'dir');?>
			</td>
		</tr>
		<tr>
			<th>模板名称：</th>
			<td>
				<input type="text" name="template_file" value="" size="35" />.html
			</td>
		</tr>
		<tr>
			<th>网址：</th>
			<td>
				<?php echo element::psn('url', 'url', '', 40, 'file');?>
			</td>
		</tr>
		</tbody>
	</table>
</form>
<script type="text/javascript">

</script>
<div class="bk_5"></div>