<table class="discuz_form" width="100%" border="0" cellspacing="0" cellpadding="0">
	<tbody>
	<tr>
		<th width="120">
			<img class="tips hand" width="16" height="16" align="absmiddle" tips="设置标题包含的关键字。注意: 留空为不进行任何过滤；<br/>关键字中可使用通配符 *； <br/>匹配多个关键字全部，可用空格或 AND 连接。如 cmstop AND discuz；<br/> 匹配多个关键字其中部分，可用 | 或 OR 连接。如 cmstop OR discuz" src="images/question.gif" />标题关键字：
		</th>
		<td>
			<input width="150" type="text" name="options[keyword]" value="<?=htmlspecialchars($options['keyword'])?>" />
		</td>
	</tr>
	<tr>
		<th>
			<img class="tips hand" width="16" height="16" align="absmiddle" tips="设置要楼主UID，<br />多个UID请用半角逗号“,”隔开。" src="images/question.gif" />
			楼主UID：
		</th>
		<td>
			<input width="150" type="text" name="options[uids]" value="<?php echo $options['uids'];?>" />
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
		<th>
			<img class="tips hand" width="16" height="16" align="absmiddle" tips="设置特定分类信息的主题。注意: 全选或全不选均为不进行任何过滤" src="images/question.gif" />
			分类信息：
		</th>
		<td>
			<select class="discuz_selectlist" multiple="multiple" name="options[sortids]">
				<?php foreach($threadtype as $item):?>
				<option value="<?php echo $item['typeid']?>"<?php if(in_array(0, $options['sortids'])):?> selected="selected"<?php endif;?>><?php echo $item['name']?></option>
				<?php endforeach;?>
			</select>
		</td>
	</tr>
	<tr>
		<th>
			<img class="tips hand" width="16" height="16" align="absmiddle" tips="设置特定的主题范围。注意: 全选或全不选均为不进行任何过滤" src="images/question.gif" />
			精华主题过滤：
		</th>
		<td>
			<select class="discuz_selectlist" multiple="multiple" name="options[digest]">
				<?php foreach($data['digest']['value'] as $item):?>
				<option value="<?php echo $item[0];?>"<?php if(in_array($item[0], $options['digest'])):?> selected="selected"<?php endif;?>><?php echo $language[$item[1]];?></option>
				<?php endforeach;?>
			</select>
		</td>
	</tr>
	<tr>
		<th>
			<img class="tips hand" width="16" height="16" align="absmiddle" tips="设置特定的主题范围。注意: 全选或全不选均为不进行任何过滤" src="images/question.gif" />
			置顶主题过滤：
		</th>
		<td>
			<select class="discuz_selectlist" multiple="multiple" name="options[stick]">
				<?php foreach($data['stick']['value'] as $item):?>
				<option value="<?php echo $item[0];?>"<?php if(in_array($item[0], $options['stick'])):?> selected="selected"<?php endif;?>><?php echo $language[$item[1]];?></option>
				<?php endforeach;?>
			</select>
			
		</td>
	</tr>
	<tr>
		<th>
			<img class="tips hand" width="16" height="16" align="absmiddle" tips="设置是否只显示推荐的主题" src="images/question.gif" />
			推荐主题过滤：
		</th>
		<td>
			<label><input type="radio" value="1" name="options[recommend]"<?php if($options['recommend']==1):?> checked="checked"<?php endif;?> />是</label>
			<label><input type="radio" value="0" name="options[recommend]"<?php if($options['recommend']==0):?> checked="checked"<?php endif;?> />否</label>
		</td>
	</tr>
	<tr>
		<th>
			<img class="tips hand" width="16" height="16" align="absmiddle" tips="设置特定的主题范围。注意: 全选或全不选均为不进行任何过滤" src="images/question.gif" />
			特殊主题过滤：
		</th>
		<td>
			<select class="discuz_selectlist" multiple="multiple" name="options[special]">
				<?php foreach($data['special']['value'] as $item):?>
				<option value="<?php echo $item[0];?>"<?php if(in_array($item[0], $options['special'])):?> selected="selected"<?php endif;?>><?php echo $language[$item[1]];?></option>
				<?php endforeach;?>
			</select>
		</td>
	</tr>
	<tr>
		<th>必须含图片附件：</th>
		<td>
			<label><input type="radio" value="1" name="options[picrequired]"<?php if($options['picrequired']==1):?> checked="checked"<?php endif;?> />是</label>
			<label><input type="radio" value="0" name="options[picrequired]"<?php if($options['picrequired']==0):?> checked="checked"<?php endif;?> />否</label>
		</td>
	</tr>
	<tr>
		<th>主题排序方式：</th>
		<td>
			<select class="discuz_selectlist" name="options[orderby]">
				<?php foreach ($data['orderby']['value'] as $item):?>
				<option value="<?php echo $item[0];?>"<?php if(in_array($item[0], $options['orderby'])):?> selected="selected"<?php endif;?>><?php echo $language[$item[1]];?></option>
				<?php endforeach;?>
			</select>
		</td>
	</tr>
	<tr>
		<th>最后更新时间：</th>
		<td>
			<select class="discuz_selectlist" name="options[lastpost]">
				<?php foreach($data['lastpost']['value'] as $item):?>
				<option value="<?php echo $item[0];?>"<?php if(in_array($item[0], $options['lastpost'])):?> selected="selected"<?php endif;?>><?php echo $language[$item[1]];?></option>
				<?php endforeach;?>
			</select>
		</td>
	</tr>
	</tbody>
</table>