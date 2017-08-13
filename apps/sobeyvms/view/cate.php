<?php $this->display('header', 'system'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL;?>js/lib/tablesorter/style.css" />
<link rel="stylesheet" type="text/css" href="apps/sobeyvms/css/style.css" />
<!-- autocomplete -->
<script type="text/javascript" src="<?php echo IMG_URL;?>js/lib/cmstop.autocomplete.js"></script>
<!-- selectree -->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL;?>js/lib/selectree/selectree.css" />
<script type="text/javascript" src="<?php echo IMG_URL;?>js/lib/cmstop.selectree.js"></script>
<!-- template -->
<script type="text/javascript" src="<?php echo IMG_URL;?>js/lib/cmstop.template.js"></script>

<div class="bk_10"></div>
<form id="sobeyvms_cate" action="?app=sobeyvms&action=cate" method="POST" name="sobeyvms_cate">
	<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
		<tr>
			<th width="160">开启定时同步：</th>
			<td>
				<label class="mar_r_8">
					<input type="radio" name="state" value="1"<?php if ($state == '1'):?> checked="checked"<?php endif;?> /> 是
				</label>
				<label>
					<input type="radio" name="state" value="0"<?php if ($state != '1'):?> checked="checked"<?php endif;?> /> 否
				</label>
			</td>
		</tr>
		<tr>
			<th width="160">同步时间(小时)：</th>
			<td>
				<input type="number" min="1" name="ttl" value="<?php echo (empty($ttl) ? 1 : $ttl);?>" />
			</td>
		</tr>
		<tr>
			<th>栏目关系：</th>
			<td>
				<table id="table-list" border="0" cellspacing="0" cellpadding="0" class="tablesorter table_list mar_l_8">
					<thead>	
						<tr>
							<th width="30">ID</th>
							<th width="100">索贝视频栏目</th>
							<th width="100">关联栏目</th>
							<th width="80">同步</th>
						</tr>
					</thead>
					<tbody id="list"></tbody>
				</table>
			</td>
		</tr>
		<tr>
			<th width="160">采集开始时间：</th>
			<td>
				<input type="text" class="input_calendar" name="earliest" value="<?php echo empty($earliest) ? date('Y-m-d') : date('Y-m-d', $earliest);?>" size="20" />
			</td>
		</tr>
		<tr>
			<th width="160">发布状态：</th>
			<td>
				<label><input type="radio" name="status" value="3" <?php if($status != '6'):?>checked="checked"<?php endif;?> /> 待审</label>
				<label><input type="radio" name="status" value="6" <?php if($status == '6'):?>checked="checked"<?php endif;?> /> 发布</label>
			</td>
		</tr>
		<tr>
			<th></th>
			<td>
				<input type="submit" class="button_style_2" value="提交" />
			</td>
		</tr>
	</table>
</form>
<script type="text/template" id="list-template">
	<tr data-id={%catalogId%}>
		<td>{%catalogId%}</td>
		<td>{%name%}</td>
		<td class="t_c">
			<input class="category" type="text" value="" data-value="请选择" data-catid="" name="cate[{%catalogId%}][relation]" />
		</td>
		<td class="t_c">
			<input type="checkbox" name="cate[{%catalogId%}][sync]" value="1" />
		</td>
	</tr>
</script>
<script type="text/javascript">
	var relationCategory = <?php echo empty($cate) ? '{}' : json_encode($cate);?>;
</script>
<script type="text/javascript" src="apps/sobeyvms/js/cate.js"></script>