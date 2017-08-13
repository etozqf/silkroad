<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta content="IE=EmulateIE7" http-equiv="X-UA-Compatible" />
		<title><?=$head['title']?></title>
		<link rel="stylesheet" type="text/css" href="css/admin.css"/>
		<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.js"></script>
		<script type="text/javascript" src="<?=IMG_URL?>js/config.js"></script>
		<script type="text/javascript" src="<?=IMG_URL?>js/cmstop.js"></script>

		<!--dialog-->
		<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/jquery-ui/dialog.css" />
		<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.ui.js"></script>
		<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.dialog.js"></script>

		<!--validator-->
		<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/validator/style.css"/>
		<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.validator.js"></script>

		<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/pagination/style.css" />
		<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.pagination.js"></script>

		<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>

		<script type="text/javascript" src="<?=ADMIN_URL?>uploader/cmstop.uploader.js"></script>
		<script type="text/javascript" src="<?=ADMIN_URL?>imageEditor/cmstop.imageEditor.js"></script>
		<script type="text/javascript" src="<?=ADMIN_URL?>js/cmstop.filemanager.js"></script>

		<script type="text/javascript">
			$.validate.setConfigs({
				xmlPath:'<?=ADMIN_URL?>apps/<?=$app?>/validators/'
			});
			$(ct.listenAjax);
		</script>
	</head>
<body>
	<div class="bk_8"></div>
	<div class="table_head">
		<div class="button_style_4" id="iport">导入模版</div>
	</div>
	<table width="98%" cellpadding="0" cellspacing="0" class="table_list" id="tablelist" style="margin:8px auto;">
		<thead>
			<tr>
				<th width="30" class="bdr_3 t_c"><input type="checkbox" /></th>
				<th width="250">名称</th>
				<th width="120">操作</th>
				<th>描述</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
	<div class="table_foot">
		<div class="pagination f_r" id="pagination"></div>
		<p class="f_l">
			<button onclick="Action.del()" class="button_style_1">删 除</button>
		</p>
	</div>
<script id="tpl" type="text/template"><tr id="row_{entry}">
	<td class="t_c"><input type="checkbox" value="{entry}"/></td>
	<td class="t_l"><img src="images/pic.gif" thumb="{thumb}" class="thumb" /> {name}</td>
	<td class="t_c">
		<img src="images/backup.gif" alt="导出" width="16" height="16" action="xport" class="hand">&nbsp;
		<img src="images/edit.gif" alt="编辑" width="16" height="16" action="edit" class="hand"/> &nbsp;
		<img src="images/delete.gif" alt="删除" width="16" height="16" action="del" class="hand" />
	</td>
	<td class="t_l">{description}</td>
</tr></script>
<script type="text/javascript">
	var Action = {
		xport:function(entry) {
			window.location = '?app=special&controller=setting&action=exportTemplate&entry='+entry;
		},
		edit:function(entry) {
			ct.formDialog({title:'配置模版', width:400}, '?app=special&controller=setting&action=editTemplate&entry='+entry, function(json){
				if (json.state) {
					inst.updateRow(entry, json.data);
					return true;
				} else {
					return false;
				}
			}, function(form){
				form.find('[name="thumb"]').imageInput(1, 1);
			});
		},
		del:function(entry) {
			var msg;
			if (entry == null) {
				entry = inst.checkedIds();
				if (!entry.length) {
					ct.warn('请选中要删除项');
					return;
				}
				msg = '确定删除选中的方案吗？';
			} else {
				entry = [entry];
				msg = '确定删除该方案吗？';
			}
			function del() {
				var k = entry.shift();
				if (k) {
					$.getJSON('?app=special&controller=setting&action=delTemplate&entry='+k, function(json){
						if (json.state) {
							inst.deleteRow(k);
							del();
						} else {
							ct.warn(json.error);
						}
					});
				} else {
					inst.load();
				}
			}
			ct.confirm(msg, del);
		}
	};
	$('#iport').uploader({
		script:'?app=special&controller=setting&action=addTemplate',
		multi:false,
		fileDesc: 'zip文件',
		fileExt: '*.zip',
		jsonType:1,
		complete:function(json){
			if (json.state) {
				inst.addRow(json.data, 1);
				Action.edit(json.data.entry);
			} else {
				ct.error(json.error);
			}
		}
	});
	var inst = new ct.table('#tablelist', {
		pageSize:10,
		rowCallback : function(entry, tr){
			var tips = tr.find('img.thumb');
			if (tips.attr('thumb')) {
				tips.floatImg();
			} else {
				tips.remove();
			}
			tr.find('.hand').click(function(){
				Action[this.getAttribute('action')](entry);
				return false;
			});
		},
		template : $('#tpl').html(),
		baseUrl : '?app=special&controller=setting&action=pageTemplate'
	});
	inst.load();
</script>
</body>
</html>