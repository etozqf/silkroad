<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tbody>
	<tr>
		<th width="80">栏目：</th>
		<td>
			<input width="200" class="selectree" name="options[nodeid]" value="<?=$options['nodeid']?>"
			   url="<?=$basePath?>?action=node&id=%s&jsoncallback=?"
			   initUrl="<?=$basePath?>?action=name&id=%s&jsoncallback=?"
			   paramVal="ID"
			   paramTxt="NAME"
			   paramHaschild="HASCHILDREN"
			   multiple="multiple"
			/>
		</td>
	</tr>
	<tr>
		<th>缩略图：</th>
		<td><label><input type="checkbox" name="options[thumb]"<?=empty($options['thumb']) ? '' : ' checked="checked"'?> value="1" /> 有缩略图</label></td>
	</tr>
	<tr>
		<th>发布时间：</th>
		<td>
			<select name="options[pubtime]">
				<option value="0" <?=$options['pubtime']==0 ? 'selected="selected"' : ''?>>全部时间</option>
				<option value="1" <?=$options['pubtime']==1 ? 'selected="selected"' : ''?>>最近 1 天</option>
				<option value="2" <?=$options['pubtime']==3 ? 'selected="selected"' : ''?>>最近 3 天</option>
				<option value="7" <?=$options['pubtime']==7 ? 'selected="selected"' : ''?>>最近 1 周</option>
				<option value="31" <?=$options['pubtime']==31 ? 'selected="selected"' : ''?>>最近 1 个月</option>
				<option value="93" <?=$options['pubtime']==93 ? 'selected="selected"' : ''?>>最近 3 个月</option>
				<option value="186" <?=$options['pubtime']==186 ? 'selected="selected"' : ''?>>最近 6 个月</option>
				<option value="366" <?=$options['pubtime']==366 ? 'selected="selected"' : ''?>>最近 1 年</option>
			</select>
		</td>
	</tr>
	<tr>
		<th>作者：</th>
		<td>
			<input width="150" type="text" name="options[author]" value="<?=htmlspecialchars($options['author'])?>" />
		</td>
	</tr>
	<tr>
		<th>来源：</th>
		<td>
			<input width="150" type="text" name="options[source]" value="<?=htmlspecialchars($options['source'])?>" />
		</td>
	</tr>
	<tr>
		<th>关键词：</th>
		<td>
			<input width="300" type="text" name="options[keyword]" value="<?=htmlspecialchars($options['keyword'])?>" />
		</td>
	</tr>
    <tr>
        <th>排序：</th>
        <td>
        	<?php foreach($options['orderby'][0] as $i=>$k):
        		$s = $options['orderby'][1][$i];
        	?>
            <div>
                <select name="options[orderby][0][]">
                    <?php foreach ($sortset as $f=>$n):?>
                    <option value="<?=$f?>" <?=$k==$f?'selected="selected"':''?>><?=$n?></option>
                    <?php endforeach;?>
                </select>
                <input type="hidden" value="<?=$s?>" name="options[orderby][1][]" />
                <input type="checkbox" <?=$s=='desc'?'checked="checked"':''?> onclick="$(this).prev().val(this.checked ? 'desc' : 'asc')" /> 降序
                <a href="javascript:;" onclick="$(this).parent().remove()">删除</a>
            </div>
            <?php endforeach;?>
            <a href="javascript:;" onclick="var a=$(this),d=a.next('div').clone().show();d.find('select,input').removeAttr('disabled');a.before(d);return false;">增加</a>
            <div style="display:none">
                <select name="options[orderby][0][]" disabled="disabled">
                    <?php foreach ($sortset as $f=>$n):?>
                    <option value="<?=$f?>"><?=$n?></option>
                    <?php endforeach;?>
                </select>
                <input type="hidden" disabled="disabled" value="asc" name="options[orderby][1][]" />
                <input type="checkbox" onclick="$(this).prev().val(this.checked ? 'desc' : 'asc')" /> 降序
                <a href="javascript:;" onclick="$(this).parent().remove()">删除</a>
            </div>
        </td>
    </tr>
	</tbody>
</table>