//JavaScript Document
//the js file for payview page

var payview_power = {
	
	edit: function (oid)
	{
		var url = '?app=payview&controller=payview_power&action=edit&oid='+oid;
		var title = '编辑订阅权限';
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
	
	del : function(oid,str) {
		var msg = '确定删除<b style="color:red">'+str+'</b>的记录吗？';
		ct.confirm(msg, function(){
			$.getJSON('?app=payview&controller=payview_power&action=delete&oid='+oid, function(response){
				if (response.state) {
					tableApp.deleteRow(oid);
					ct.ok('操作成功');
				} else {
					ct.error(response.error);
				}
			});
		});
	},
	
	clear_past : function() {
		$.getJSON('?app=payview&controller=payview_power&action=clear_past', function(response){
			if (response.state) {
				ct.ok('过期权限清理成功');
				document.location.reload();
			} else {
				ct.error(response.error);
			}
		});
	},
	
	clear_cache : function() {
		$.getJSON('?app=payview&controller=payview_power&action=clear_cache', function(response){
			if (response.state) {
				ct.ok('清除权限缓存成功');
				document.location.reload();
			} else {
				ct.error(response.error);
			}
		});
	},
	
	reload : function() {
		$.getJSON('?app=payview&controller=payview_power&action=reload', function(response){
			if (response.state) {
				ct.ok('重新载入权限数据成功');
				document.location.reload();
			} else {
				ct.error(response.error);
			}
		});
	},
}

