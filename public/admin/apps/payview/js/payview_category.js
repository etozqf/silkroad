//JavaScript Document
//the js file for payview page

var payview_category = {
	add: function ()
	{
		var url = '?app=payview&controller=payview_category&action=add';
		var title = '添加栏目组';
		ct.form(title, url, 600,'auto',function(json) {
			if (json.state) {
				tableApp.addRow(json.data);
				return true;
			} else {
				ct.error(json.error);
				return false;
			}
			return true;
		},function(form, dialog) {
			form.find(".selectree").selectree();
		});
	},
	
	edit: function (pvcid)
	{
		var url = '?app=payview&controller=payview_category&action=edit&pvcid='+pvcid;
		var title = '编辑栏目组';
		ct.form(title, url, 600,'auto',function(json) {
			if (json.state) {
				if(pvcid) {
					tableApp.updateRow(pvcid, json.data);
				} else {
					tableApp.addRow(json.data);
				}
				return true;
			} else {
				ct.error(json.error);
				return false;
			}
			return true;
		},function(form, dialog) {
			form.find(".selectree").selectree();
		});
	},
	
	view: function (pvcid) 
	{
		ct.assoc.open('?app=payview&controller=payview_category&action=view&pvcid='+pvcid, 'newtab');
	},
	del : function(pvcid) {
		if (pvcid === undefined) {
			pvcid = tableApp.checkedIds();
			var msg = '确定删除选中的<b style="color:red">'+pvcid.length+'</b>条记录吗？';
		} else {
			var msg = '确定删除编号为<b style="color:red">'+pvcid+'</b>的记录吗？';
		}
		if (pvcid.length === 0) {
			ct.warn('请选择要操作的记录');
			return false;
		}
		ct.confirm(msg, function(){
			$.getJSON('?app=payview&controller=payview_category&action=delete&pvcid='+pvcid, function(response){
				if (response.state) {
					tableApp.deleteRow(pvcid);
					ct.ok('操作成功');
				} else {
					ct.error(response.error);
				}
			});
		});
	}
}

