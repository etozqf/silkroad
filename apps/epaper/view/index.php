<?php $this->display('header');?>
<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL;?>js/lib/dialog/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL;?>js/lib/cmstop.dialog.js"></script>
<div class="bk_10"></div>
<div class="table_head">
	<button class="button_style_2 f_l" onclick="epaper.add();" type="button">添加</button>
	<div class="button_style_2" type="button" id="import" style="width:40px;">导入</div>
</div>
<div class="bk_8"></div>
<table id="table_list" class="tablesorter table_list ml8" cellspacing="0" cellpadding="0">
	<thead>
		<tr>
			<th class="bdr_3" width="80"></th>
			<th width="300">报纸名称</th>
			<th width="80">状态</th>
			<th width="150">操作</th>
		</tr>
	</thead>
	<tbody id="list_body">
	</tbody>
</table>
<div class="bk_8"></div>
<div style="width:630px">
	<div id="pagination" class="pagination f_r"></div>
	<input class="ml8 button_style_1" type="button" value="刷新" onclick="tableApp.reload();" />
	<input class="ml8 button_style_1" type="button" value="导出" onclick="exportConfig();" />
</div>

<script type="text/javascript">
var row_template = new Array('<tr id="row_{epid}">',
		'<td class="t_c"><input type="checkbox" class="epaper-choose" /></td>',
		'<td class="t_c">{name}</td>',
		'<td class="t_c">{statestring}</td>',
		'<td class="t_c">',
			'<img class="hand" width="16" height="16" alt="抓取" title="抓取" src="apps/epaper/images/import.png" onclick="epaper[\'import\']({epid})">',
			'<img class="hand" width="16" height="16" alt="导出" title="导出" src="images/down.gif" onclick="exportConfig({epid})" />',
			'<img class="hand" width="16" height="16" alt="编辑" title="编辑" src="images/edit.gif" onclick="epaper.edit({epid})">',
			'<img class="hand" width="16" height="16" alt="删除" title="删除" src="images/delete.gif" onclick="epaper[\'delete\']({epid})">',
		'</td>',
	'</tr>').join('');
var tableApp = new ct.table('#table_list', {
    rowIdPrefix : 'row_',
    pageField : 'page',
    pageSize : 30,
    template : row_template,
	dblclickHandler : epaper.edit,
	jsonLoaded : function(json){
		if (json.total < 30) {
			$('#pagination').hide();
		}
	},
    baseUrl  : '?app=epaper&controller=epaper&action=page'
});

var exportConfig = function(id) {
	var ids = new Array();
	if (id) {
		ids.push(id);
	} else {
		$.each($('.epaper-choose'), function(i,k) {
			if (k.checked) {
				ids.push($(k).parents('tr').attr('id').substr(4));
			}
		});
	}
	if (!ids.length) return;
	var url = '?app=epaper&controller=epaper&action=config_export&id='+ids.join(',');
	window.location = url;
}

$(document).ready(function(){
	tableApp.load();
	$('#import').uploader({
		script:'?app=epaper&controller=epaper&action=config_import',
		multi:false,
		fileDesc: 'XML文件',
		fileExt: '*.xml',
		fileDataName:'xmlfile',
		jsonType : 1,
		start:function(){
			ct.startLoading('center');
		},
		complete:function(json){
			if (json.state) {
				ct.ok(json.data);
				tableApp.load()
			} else {
				ct.error(json.error || '导入失败');
			}
		},
		allcomplete:function(){
			ct.endLoading();
		}
	});
});
</script>