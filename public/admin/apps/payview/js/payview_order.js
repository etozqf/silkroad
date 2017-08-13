//JavaScript Document
//the js file for payview page

var payview_order = {
	add: function ()
	{
		var url = '?app=payview&controller=payview_order&action=add';
		var title = '添加订阅订单';
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
			
			// 日历
			form.find('input.input_calendar').focus(function(){
				WdatePicker({dateFmt:'yyyy-MM-dd'});
			});
			var payview_category = form.find('#payview_category');
			var pvcid = form.find('#pvcid');
			pvcid.val(payview_category.find('option:selected').val());
			payview_category.change(function(){
				pvcid.val(payview_category.find('option:selected').val());
			});
		});
	},
	
	edit: function (oid)
	{
		var url = '?app=payview&controller=payview_order&action=edit&oid='+oid;
		var title = '编辑订阅订单';
		ct.form(title, url, 600,'auto',function(json) {
			if (json.state) {
				if(oid) {
					tableApp.updateRow(oid, json.data);
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
			
			// 日历
			form.find('input.input_calendar').focus(function(){
				WdatePicker({dateFmt:'yyyy-MM-dd'});
			});
			var payview_category = form.find('#payview_category');
			payview_category.change(function(){
				form.find('#pvcid').val(payview_category.find('option:selected').val());
			});
		});
	},
	
	view: function (oid) 
	{
		ct.assoc.open('?app=payview&controller=payview_order&action=view&oid='+oid, 'newtab');
	},
	update_power : function(oid) {
		if (oid === undefined) {
			oid = tableApp.checkedIds();
		}
		if (oid.length === 0) {
			ct.warn('请选择要操作的记录');
			return false;
		}
		$.getJSON('?app=payview&controller=payview_order&action=update_power&oid='+oid, function(response){
			if (response.state) {
				ct.ok('操作成功');
			} else {
				ct.error(response.error);
			}
		});
	},
	del : function(oid) {
		if (oid === undefined) {
			oid = tableApp.checkedIds();
			var msg = '确定删除选中的<b style="color:red">'+oid.length+'</b>条记录吗？';
		} else {
			var msg = '确定删除编号为<b style="color:red">'+oid+'</b>的记录吗？';
		}
		if (oid.length === 0) {
			ct.warn('请选择要操作的记录');
			return false;
		}
		ct.confirm(msg, function(){
			$.getJSON('?app=payview&controller=payview_order&action=delete&oid='+oid, function(response){
				if (response.state) {
					tableApp.deleteRow(oid);
					ct.ok('操作成功');
				} else {
					ct.error(response.error);
				}
			});
		});
	}
}

