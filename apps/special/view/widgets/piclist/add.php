<div class="tabs">
	<ul target="tbody.method">
		<li>发稿推送</li>
		<li>手工维护</li>
		<li>自动调用</li>
	</ul>
</div>
<form>
	<input type="hidden" name="method" value="0" />
	<table width="95%" border="0" cellspacing="0" cellpadding="0">
		<tbody class="method">
			<tr>
				<th width="80">名称：</th>
				<td><input type="text" style="width:300px" name="place[name]" /></td>
			</tr>
			<tr>
				<th>备注：</th>
				<td><textarea style="width:300px" name="place[description]"></textarea></td>
			</tr>
		</tbody>
		<tbody class="method">
			<tr>
				<td colspan="2">
					<button type="button" title="批量导入数据" id="adder">导入</button>
					<button type="button" title="清空数据" id="clear">清空</button>
					<div class="list-area"></div>
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
					<input type="hidden" value="cmstop" name="port" />
					<input type="hidden" value="" name="portdata" />
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
					图片尺寸：
						<input name="width" type="text" size="4" value="120" /> &#x00D7; <input name="height" size="4" value="90" type="text" />
					<span id="autoListConfig">条数：<input name="rows" type="text" size="4" value="10" /></span>
					标题长度：<input name="length" type="text" size="4" value="" />
				</td>
		    </tr>
			<tr>
				<td><a id="template" style="cursor:pointer">编辑模板</a></td>
			</tr>
		</tbody>
	</table>
</form>
<div class="bk_5"></div>