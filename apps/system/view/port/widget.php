<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tbody>
    <?php $all_modelids = !empty($options['modelids']) ? explode(',', $options['modelids']) : null; ?>
    <?php $checked_modelids = explode(',', $options['modelid']); ?>
    <?php if (empty($all_modelids) || count($all_modelids) > 1): ?>
	<tr>
		<th width="80">模型：</th>
		<td>
			<select name="options[modelid]" class="modelset" multiple="multiple">
				<?php foreach (table('model') as $id=>$v): if ($all_modelids && !in_array($id, $all_modelids)) continue; ?>
				<option value="<?=$id?>" ico="<?=$v['alias']?>"<?=in_array($id, $checked_modelids) ? ' selected': ''?>><?=$v['name']?></option>
				<?php endforeach;?>
			</select>
		</td>
	</tr>
    <?php else: ?>
    <input type="hidden" name="options[modelid]" value="<?=$all_modelids[0]?>" />
    <?php endif; ?>
    <?php if (!empty($all_modelids)): ?>
    <input type="hidden" name="options[modelids]" value="<?=implode_ids($all_modelids)?>" />
    <?php endif; ?>
	<tr>
		<th>栏目：</th>
		<td>
			<input width="150" class="selectree" name="options[catid]" value="<?=$options['catid']?>"
				   url="?app=system&controller=category&action=cate&catid=%s"
				   initUrl="?app=system&controller=category&action=name&catid=%s"
				   paramVal="catid"
				   paramTxt="name"
				   multiple="multiple"
				/>
		</td>
	</tr>
	<tr>
		<th>属性：</th>
		<td>
			<input width="150" class="selectree" name="options[proid]" value="<?=$options['proid']?>"
				   url="?app=system&controller=property&action=cate&proid=%s"
				   initUrl="?app=system&controller=property&action=name&proid=%s"
				   paramVal="proid"
				   paramTxt="name"
				   multiple="multiple"
				/>
		</td>
	</tr>
	<tr>
		<th>缩略图：</th>
		<td><label><input type="checkbox" name="options[thumb]"<?=empty($options['thumb']) ? '' : ' checked="checked"'?> value="1" />有缩略图</label></td>
	</tr>
	<tr>
		<th>来源：</th>
		<td>
			<input name="options[sourceid]" class="suggest" width="300" value="<?=$options['sourceid']?>"
				   url="?app=system&controller=source&action=suggest&size=10&q=%s"
				   listUrl="?app=system&controller=source&action=page&page=%s"
				   initUrl="?app=system&controller=source&action=name&sourceid=%s"
				   paramVal="sourceid"
				   paramTxt="name"
				/>
		</td>
	</tr>
	<tr>
		<th>创建人：</th>
		<td>
			<input name="options[createdby]" class="suggest" width="300" value="<?=$options['createdby']?>"
				   url="?app=system&controller=administrator&action=suggest&q=%s"
				   listUrl="?app=system&controller=administrator&action=page&page=%s"
				   initUrl="?app=system&controller=administrator&action=name&userid=%s"
				   paramVal="userid"
				   paramTxt="username"
				/>
		</td>
	</tr>
	<tr>
        <th>权重：</th>
        <td>
            <input name="options[weight][0]" value="<?=$options['weight'][0]?>" size="4" type="text" />
            <label <?=$options['weight']['range']?'':'style="display:none"'?>>
                <span style="margin:0 5px;">~</span>
                <input name="options[weight][1]" value="<?=$options['weight'][1]?>" size="4" type="text" />
            </label>
            <input type="checkbox" <?=$options['weight']['range']?'checked="checked"':''?> onclick="$(this).prev('label')[this.checked ? 'show' : 'hide']()" name="options[weight][range]"/> 范围
            (<em>1~100数字</em>)
        </td>
    </tr>
    <tr>
        <th>发布时间：</th>
        <td>
            <select name="options[published]">
				<option value="0" <?=$options['published']==0 ? 'selected="selected"' : ''?>>全部时间</option>
				<option value="1" <?=$options['published']==1 ? 'selected="selected"' : ''?>>1 天</option>
				<option value="2" <?=$options['published']==2 ? 'selected="selected"' : ''?>>2 天</option>
				<option value="7" <?=$options['published']==7 ? 'selected="selected"' : ''?>>1 周</option>
				<option value="31" <?=$options['published']==31 ? 'selected="selected"' : ''?>>1 个月</option>
				<option value="93" <?=$options['published']==93 ? 'selected="selected"' : ''?>>3 个月</option>
				<option value="186" <?=$options['published']==186 ? 'selected="selected"' : ''?>>6 个月</option>
				<option value="366" <?=$options['published']==366 ? 'selected="selected"' : ''?>>1 年</option>
			</select> 以内
        </td>
    </tr>
    <tr>
    	<th>关键词：</th>
        <td>
            <input class="suggest" name="options[tags]" width="300" value="<?=htmlspecialchars($options['tags'])?>"
				url="?app=system&controller=tag&action=suggest&tag=%s"
				listUrl="?app=system&controller=tag&action=page&page=%s"
				paramVal="tag"
				paramTxt="tag"
				anytext="1"
			/>
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
    <tr>
        <th>条件语句：</th>
        <td>
            <input name="options[where]" value="<?=$options['where']?>" type="text" style="width:300px" />
        </td>
    </tr>
	</tbody>
</table>