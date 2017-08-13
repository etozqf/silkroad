<?php $this->display('header', 'system');?>

<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>

<div class="bk_8"></div>
<div class="table_head">
  <input type="button" value="添加" class="button_style_2 f_l" onclick="port.add();"/>
</div>
<div class="bk_8"></div>
<table width="98%" cellpadding="0" cellspacing="0" id="item_list" class="table_list" style="margin-left:6px;">
  <thead>
    <tr>
      <th width="100" class="bdr_3">标识</th>
      <th width="150">系统名称</th>
      <th>接口地址</th>
      <th width="100">服务状态</th>
      <th width="100">操作</th>
    </tr>
  </thead>
  <tbody id="list_body">
  </tbody>
</table>
<script type="text/javascript">
var row_template = '<tr id="row_{portid}">\
 	<td class="t_c">{port}</td>\
	<td class="t_l">{name}</td>\
	<td class="t_l">{url}</td>\
	<td class="t_c">{state}</td>\
	<td class="t_c"><img src="images/edit.gif" alt="编辑" width="16" height="16" class="hand edit"/> &nbsp;<img src="images/delete.gif" alt="删除" width="16" height="16" class="hand delete" /></td>\
</tr>';

var tableApp = new ct.table('#item_list', {
    rowIdPrefix : 'row_',
    rowCallback : function(id, tr){
		tr.find('img.edit').click(function(){
			port.edit(id);
		});
		tr.find('img.delete').click(function(){
			port.del(id);
		});    
	},
    template : row_template,
    baseUrl  : '?app=system&controller=port&action=ls'
});
var port = {
	add:function(){
		ct.formDialog({title:'添加数据端口',width:400}, '?app=system&controller=port&action=add', function (json){
			if (json.state) {
				tableApp.addRow(json.data);
				return true;
			} else {
				return false;
			}
		});
	},
	edit: function(portid){
		ct.formDialog({title:'编辑数据端口',width:400}, '?app=system&controller=port&action=edit&portid='+portid, function(json){
			if (json.state)
			{
				tableApp.updateRow(portid, json.data);
				return true;
			}
			else
			{
				return false;
			}
		});
	},
	del: function (portid){
		ct.confirm('确定删除编号为<b style="color:red">'+portid+'</b>的数据端口吗？', function(){
			$.getJSON('?app=system&controller=port&action=del&portid='+portid, function(json){
				if (json.state) {
					tableApp.load();
				} else {
					ct.error('删除失败');
				}
			});
		});
	}
};

tableApp.load();
</script>
<?php $this->display('footer', 'system');