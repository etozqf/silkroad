var position = {
	search :function() {
		ct.ajax('园区搜索', '?app=system&controller=position&action=search', 360, 180, null, function(dialog){
			tableApp.load(dialog.find('form'));
			return true;
		});
	},
	add : function() {
		ct.form('添加园区','?app=system&controller=position&action=add',400,250,function(json){
			if(json.state) {
				tableApp.addRow(json.data);
				return true;
			}
		},function(form){
			var title = form.find('input[name=position]').maxLength();
			var colorInput = form.find('input[name=style]');
		    colorInput.next('img').titleColorPicker(title, colorInput);
		});
	},
	edit: function(id,tr) {
		var url = '?app=system&controller=position&action=edit&positionid='+id;
		tr.trigger('check');
		ct.form('编辑园区',url,400,200,function(json){
			tableApp.updateRow(id, json.data);
			return true;
		},function(form){
			var colorInput = form.find('input[name=style]');
		    colorInput.next('img').titleColorPicker(false, colorInput);
		});
	},
	del : function(id) {
		var msg;
		if (id === undefined) {
			id = tableApp.checkedIds();
			if (!id.length) {
				ct.warn('请选中要删除项');
				return;
			}
			msg = '确定删除选中的<b style="color:red">'+id.length+'</b>条记录吗？';
		} else {
			msg = '确定删除编号为<b style="color:red">'+id+'</b>的记录吗？';
		}
		ct.confirm(msg,function(){
			var data = 'id='+id;
			$.getJSON('?app=system&controller=position&action=delete',data,function(json){
				json.state
				 ? (ct.warn('删除完毕'), tableApp.deleteRow(id))
				 : ct.warn(json.error);
			});
		});
	}
}