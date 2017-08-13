<div class="tabs">
	<ul target="tbody.method">
		<li>发稿推送</li>
		<li>手工维护</li>
		<li>自动调用</li>
	</ul>
</div>
<form>
	<input type="hidden" name="method" value="<?=$method?>" />
	<input type="hidden" name="widgetid" value="<?=$_REQUEST['widgetid']?>" />
	<table width="95%" border="0" cellspacing="0" cellpadding="0">
		<tbody class="method">
			<tr>
				<th width="80">名称：</th>
				<td><input type="text" style="width:300px" name="place[name]" value="<?=htmlspecialchars($place['name'])?>" /></td>
			</tr>
			<tr>
				<th>备注：</th>
				<td><textarea style="width:300px" name="place[description]"><?=htmlspecialchars($place['description'])?></textarea></td>
			</tr>
		</tbody>
		<tbody class="method">
			<tr>
				<td colspan="2">
					<button type="button" title="批量导入数据" id="adder">导入</button>
					<button type="button" title="清空数据" id="clear">清空</button>
					<div class="list-area"><?=json_encode($list)?></div>
				</td>
			</tr>
		</tbody>
		<tbody class="method">
			<tr>
				<th width="80">数据接口：</th>
				<td>
					<div class="dataport-tabs">
						<a data-port="cmstop">CmsTop</a>
						<?php
						foreach(table('port') as $v):
						if ($v['disabled']) continue;
						?>
						<a data-port="<?=$v['port']?>"><?=$v['name']?></a>
						<?php endforeach;?>
					</div>
					<input type="hidden" value="<?=$port?>" name="port" />
					<input type="hidden" value="<?=http_build_query($options)?>" name="portdata" />
				</td>
			</tr>
			<tr>
				<td colspan="2" class="container"></td>
			</tr>
		</tbody>
	</table>
	<table width="95%" border="0" cellspacing="0" cellpadding="0">
		<caption>高级</caption>
		<tbody>
			<tr>
				<td>
					<span id="autoListConfig">条数：<input name="rows" type="text" size="4" value="<?=$rows?>" /></span>
					标题长度：<input name="length" type="text" size="4" value="<?=$length?>" />
					时间格式：<select name="dateformat">
					<?php foreach(array(
					  'Y-m-d H:i:s', 'Y-m-d H:i', 'Y-m-d', 'm-d H:i', 'm-d',
					  'Y/m/d H:i:s', 'Y/m/d H:i', 'Y/m/d', 'n/j H:i', 'n/j',
					  'Y.m.d H:i:s', 'Y.m.d H:i', 'Y.m.d', 'n.j H:i', 'n.j',
					  'Y年n月j日 H:i:s', 'Y年n月j日 H:i', 'Y年n月j日', 'n月j日 H:i', 'n月j日') as $fmt):?>
					<option value="<?=$fmt?>" <?=$fmt==$dateformat?'selected':''?>><?=date($fmt)?></option>
					<?php endforeach;?>
					</select>
				</td>
		    </tr>
			<tr>
				<td><a id="template" style="cursor:pointer">编辑模板</a></td>
			</tr>
		</tbody>
	</table>
</form>
<div class="bk_5"></div>