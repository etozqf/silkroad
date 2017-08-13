<?php $this->display('header', 'system');?>
<div class="bk_8"></div>
<form id="setting_form" action="?app=baoliao&controller=setting&action=post" method="post">
	<table class="table_form" width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<th width="120">显示管理员名称</th>
			<td><input class="w_160" type="text" name="setting[admin]" value="<?php echo $admin;?>" /></td>
		</tr>
		<tr>
			<th>管理员回复后显示</th>
			<td>
				<label><input type="radio" name="setting[onlyreply]" value="1"<?php if($onlyreply):?> checked="checked"<?php endif;?> />是</label>
				<label><input type="radio" name="setting[onlyreply]" value="0"<?php if(!$onlyreply):?> checked="checked"<?php endif;?> />否</label>
			</td>
		</tr>
		<tr>
			<th>仅会员报料</th>
			<td>
				<label><input type="radio" name="setting[onlymember]" value="1"<?php if($onlymember):?> checked="checked"<?php endif;?> />是</label>
				<label><input type="radio" name="setting[onlymember]" value="0"<?php if(!$onlymember):?> checked="checked"<?php endif;?> />否</label>
			</td>
		</tr>
		<tr>
			<th>开启验证码</th>
			<td>
				<label><input type="radio" name="setting[hasseccode]" value="1"<?php if($hasseccode):?> checked="checked"<?php endif;?> />是</label>
				<label><input type="radio" name="setting[hasseccode]" value="0"<?php if(!$hasseccode):?> checked="checked"<?php endif;?> />否</label>
			</td>
		</tr>
		<tr>
			<th>开启评论</th>
			<td>
				<label><input type="radio" name="setting[allowedcomment]" value="1"<?php if($allowedcomment):?> checked="checked"<?php endif;?> />是</label>
				<label><input type="radio" name="setting[allowedcomment]" value="0"<?php if(!$allowedcomment):?> checked="checked"<?php endif;?> />否</label>
			</td>
		</tr>
		<tr>
			<th>报料表单选项</th>
			<td>
				<table>
					<?php $item_options = array(
						array('name', '姓名'),
						array('email', '邮箱'),
						array('phone', '电话'),
						array('qq', 'QQ'),
						array('address', '地址')
					);?>
					<?php foreach ($item_options as $option):?>
					<tr>
						<td><label><input type="checkbox" name="item[]" value="<?php echo $option[0];?>"<?php if(in_array($option[0], $item)):?> checked="checked"<?php endif;?> /><?php echo $option[1];?></label></td>
						<td><label><input type="checkbox" name="must_item[]" value="<?php echo $option[0];?>"<?php if(in_array($option[0], $must_item)):?> checked="checked"<?php endif;?> />必填</label></td>
					</tr>
					<?php endforeach;?>
				</table>
			</td>
		</tr>
		<tr>
			<th>允许上传图片数</th>
			<td><input type="text" class="w_80" name="setting[max_picnum]" value="<?php echo $max_picnum;?>" />
		</tr>
		<tr>
			<th class="vtop">过滤敏感字符：</th>
			<td colspan="2"><textarea id="sensekeyword" name="setting[sensekeyword]" rows="8" cols="32"><?php echo $setting['sensekeyword']?></textarea><span onclick="zoomin(this);" style="cursor:pointer">+</span></td>
		</tr>
		<tr>
			<th>报料投递邮箱</th>
			<td><input class="w_160" type="text" name="setting[postto]" value="<?php echo $postto;?>" /></td>
		</tr>
		<tr>
			<th>邮件通知</th>
			<td><label><input type="checkbox" name="setting[notice]" value="1"<?php if($notice):?> checked="checked"<?php endif;?> />开启管理员回复提醒</label></td>
		</tr>
		<tr>
			<th>邮件标题</th>
			<td><input class="w_350" type="text" name="setting[notice_title]" value="<?php echo $notice_title;?>" /></td>
		</tr>
		<tr>
			<th>邮件内容</th>
			<td><textarea class="w_400 h_150" name="setting[notice_content]"><?php echo $notice_content;?></textarea></td>
		</tr>
		<tr>
			<th></th>
			<td><input id="submit" class="button_style_2" type="submit" value="保存" name="publish"></td>
		</tr>
	</table>
</form>
<script type="text/javascript">
$(function() {
	$('#setting_form').ajaxForm();
	$('[name="must_item[]"]').bind('click', function(e) {
		var obj = e.currentTarget,
		item = $(obj).parents('tr').eq(0).find('[name="item[]"]');
		if (e.currentTarget.checked) {

			item.bind('click.foucs', function(e) {
				window.kaze = e.currentTarget;
				e.currentTarget.checked = true
			});
			item[0].checked = true;
		} else {
			$(obj).parents('tr').find('[name="item[]"]').unbind('click.foucs');
		}
	});
});
function zoomin(obj) {
	var d = $('#sensekeyword');
	var rows = d.attr('rows');
	d.attr('rows',rows+1);
}
</script>