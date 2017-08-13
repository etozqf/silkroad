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

		<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>
	</head>
<body>
	<div class="bk_8"></div>
	<div class="table_head">
		<div class="button_style_4" onclick="Action.add()">添加分类</div>
	</div>
	<table width="98%" cellpadding="0" cellspacing="0" class="table_list" id="tablelist" style="margin:8px auto;">
		<thead>
			<tr>
				<th width="60" class="bdr_3">排序</th>
				<th>名称</th>
				<th width="120">操作</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
<script id="tpl" type="text/template"><tr id="row_{typeid}">
	<td class="t_l">{sort}</td>
	<td class="t_l">{name}</td>
	<td class="t_c">
		<img src="images/edit.gif" alt="编辑" width="16" height="16" action="edit" class="hand"/> &nbsp;
		<img src="images/delete.gif" alt="删除" width="16" height="16" action="del" class="hand" />
	</td>
</tr></script>
<script type="text/javascript">
var Action = {
	add:function() {
		ct.formDialog({title:'添加分类', width:400}, '?app=special&controller=setting&action=addSchemeType', function(json){
			if (json.state) {
				inst.addRow(json.data);
				return true;
			} else {
				return false;
			}
		});
	},
	edit:function(typeid) {
		ct.formDialog({title:'编辑分类', width:400}, '?app=special&controller=setting&action=editSchemeType&typeid='+typeid, function(json){
			if (json.state) {
				inst.updateRow(typeid, json.data);
				return true;
			} else {
				return false;
			}
		});
	},
	del:function(typeid) {
		if (!typeid) return;
		ct.confirm('确定删除该分类吗？', function(){
			$.getJSON('?app=special&controller=setting&action=delSchemeType&typeid='+typeid, function(json){
				if (json.state) {
					inst.deleteRow(typeid);
				} else {
					ct.warn(json.error);
				}
			});
		});
	}
};
var inst = new ct.table('#tablelist', {
	rowCallback : function(typeid, tr){
		tr.find('.hand').click(function(){
			Action[this.getAttribute('action')](typeid);
			return false;
		});
	},
	template : $('#tpl').html(),
	baseUrl : '?app=special&controller=setting&action=getSchemeTypes&data=1'
});
inst.load();
</script>
</body>
</html>