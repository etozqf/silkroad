<?php $this->display('header');?>
<form id="epaper_add" name="epaper_add" action="?app=epaper&controller=epaper&action=edit&type=step2&id=<?php echo $epid;?>" method="post">
	<div class="epaper-nav">
		<div class="epaper-nav-item">1.基本设置</div>
		<div class="epaper-nav-item">2.抓取规则</div>
		<div class="epaper-nav-item cur">3.默认选项设置</div>
	</div>
	<table class="table_form mar_l_8" border="0" width="98%" cellspacing="0" cellpadding="0">
		<caption>默认版面与栏目对应关系</caption>
		<tr>
			<th width="150">默认导入栏目</th>
			<td>
				<?=element::category('defaultcate', 'post[default_catid]', $default_catid)?>
				<a href="javascript:;" onclick="getAll();">全选</a>
				<a href="javascript:;" onclick="getNone();">全不选</a>
			</td>
		</tr>
		<tr>
			<th></th>
			<td>
				<div class="epaper-list">
					<table class="epaper-cate" cellspacing="0" cellpadding="0">
						<thead>
							<tr>
								<th class="t_c" width="600">版面</th>
								<th class="t_c" width="160">栏目</th>
								<th class="t_c" width="60">导入</th>
							</tr>
						</thead>
						<tbody id="list_control">
						<?php foreach($list as $listid=>$item):?>
							<tr>
								<td><?php echo $item['title'] ? $item['title'] : '版面'.$listid;?></td>
								<td><?php echo element::category('cate_'.$listid, "post[import_list][$listid]", $import_list[$listid]);?></td>
								<td><input type="checkbox" name="post[allowed_auto][]" value="<?php echo $listid;?>"<?php if(in_array($listid, $allowed_auto) ):?> checked="checked"<?php endif;?> /></td>
							</tr>
						<?php endforeach;?>
						</tbody>
					</table>
				</div>
			</td>
		</tr>
	</table>
	<table class="table_form mar_l_8" border="0" width="98%" cellspacing="0" cellpadding="0">
		<caption>默认导入的内容状态</caption>
		<tr>
			<th width="150">默认导入状态</th>
			<td>
				<label><input type="radio" name="post[default_state]" value="1"<?php if($default_state=='1'):?> checked="checked"<?php endif;?> />草稿</label>
				<label><input type="radio" name="post[default_state]" value="3"<?php if($default_state=='3'):?> checked="checked"<?php endif;?> />待审</label>
				<label><input type="radio" name="post[default_state]" value="6"<?php if($default_state=='6'):?> checked="checked"<?php endif;?> />已发</label>
			</td>
		</tr>
		<tr>
			<th></th>
			<td>
				<a class="button_style_2" type="button" href="?app=epaper&controller=epaper&action=edit&type=step1&id=<?php echo $epid;?>">返回</a>
				<input class="button_style_2" type="submit" value="保存" />
			</td>
		</tr>
	</table>
</form>
<script type="text/javascript">
var getAll = function() {
	$.each($('#list_control').find(':checkbox'), function(i,k) {
		k.checked = true;
	});
}

var getNone = function() {
	$.each($('#list_control').find(':checkbox'), function(i,k) {
		k.checked = false;
	});
}

$(document).ready(function(){
	$('#epaper_add').ajaxForm(function(json) {
		if (json.state) {
			ct.ok('保存成功, 3秒后自动关闭', null, '3')
			setTimeout(function(){
				ct.assoc.close();
			}, 3000);
		}
	}, null, function(form) {
		if (!$('[name=post[default_catid]]').val()) {
			ct.error('请选择默认栏目')
			return false;
		}
	});
});
</script>