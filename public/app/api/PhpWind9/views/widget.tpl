<table class="discuz_form" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tbody>
	<tr>
		<th width="120">
			<img class="tips hand" width="16" height="16" align="absmiddle" tips="设置标题包含的关键字。注意: 留空为不进行任何过滤；" src="images/question.gif" />关键字：
		</th>
		<td>
			<input width="150" type="text" name="options[keyword]" value="<?=htmlspecialchars($options['keyword'])?>" />
		</td>
	</tr>
	<tr>
		<th>
			<img class="tips hand" width="16" height="16" align="absmiddle" tips="设置作者名称。" src="images/question.gif" />
			作者：
		</th>
		<td>
			<input width="150" type="text" name="options[username]" value="<?php echo $options['username'];?>" />
		</td>
	</tr>
	<tr>
		<th>
			<img class="tips hand" width="16" height="16" align="absmiddle" tips="设置允许参与新帖调用的版块，全选或全不选均为不做限制" src="images/question.gif" />
			所在版块：
		</th>
		<td>
			<select class="discuz_selectlist" multiple="multiple" name="options[fids]">
				<?php foreach($forumlist as $item):?>
				<option value="<?php echo $item['fid']?>"<?php if(in_array($item['fid'], $options['fids'])):?> selected="selected"<?php endif;?>><?php echo $item['name']?></option>
				<?php endforeach;?>
			</select>
		</td>
	</tr>
	<tr>
		<th>最后更新时间：</th>
		<td>
			<select class="discuz_selectlist" name="options[lastpost]">
				<option value="0"<?php if($options['lastpost'] == 0):?> selected="selected"<?php endif;?>>不限</option>
				<option value="86400"<?php if($options['lastpost'] == 86400):?> selected="selected"<?php endif;?>>一天内</option>
				<option value="604800"<?php if($options['lastpost'] == 604800):?> selected="selected"<?php endif;?>>一周内</option>
				<option value="2592000"<?php if($options['lastpost'] == 2592000):?> selected="selected"<?php endif;?>>一个月内</option>
			</select>
		</td>
	</tr>
	<tr>
		<th>帖子类型：</th>
		<td>
			<select class="discuz_selectlist" name="options[filter]">
				<option value=""<?php if(empty($options['filter'])):?> selected="selected"<?php endif;?>>全部帖子</option>
				<option value="digest"<?php if($options['filter'] == 'digest'):?> selected="selected"<?php endif;?>>精华帖子</option>
				<option value="topped"<?php if($options['filter'] == 'topped'):?> selected="selected"<?php endif;?>>置顶帖子</option>
			</select>
		</td>
	</tr>
	<tr>
		<th>排序规则：</th>
		<td>
			<select class="discuz_selectlist" name="options[order]">
				<option value="created_time"<?php if($options['order'] == 'topped'):?> selected="selected"<?php endif;?>>按发帖时间</option>
				<option value="lastpost_time"<?php if($options['lastpost_time'] == 'digest'):?> selected="selected"<?php endif;?>>按回复时间</option>
				<option value="replies"<?php if($options['order'] == 'replies'):?> selected="selected"<?php endif;?>>按回复数</option>
			</select>
		</td>
	</tr>
	</tbody>
</table>