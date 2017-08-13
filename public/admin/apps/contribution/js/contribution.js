//稿件
var baseUrl = '?app=contribution&controller=index';
var contribution = {
	__common : function(contributionid,action) {
		if (contributionid === undefined) {
			contributionid = tableApp.checkedIds();
			var msg = '确定操作选中的<b style="color:red">'+contributionid.length+'</b>条记录吗？';
			var ids = contributionid.join(',');
		} else {
			var msg = '确定操作编号为<b style="color:red">'+contributionid+'</b>的记录吗？';
			var ids = contributionid;
		}
		if (contributionid.length === 0) {
			ct.warn('请选择要操作的记录');
			return false;
		}
		ct.confirm(msg, function(){
			$.post(
				baseUrl+'&action='+action,
				{contributionid:ids},
				function(data){
					if (data.state) {
						if (window.tableApp) {
							tableApp.deleteRow(contributionid);
						}
						ct.ok('操作成功');
					} else {
						ct.error(data.error);
					}
				}, 
			'json')
		});
	},
	reject : function(contributionid) {
		if (contributionid === undefined) {
			contributionid = tableApp.checkedIds();
			var msg = '确定操作选中的<b style="color:red">'+contributionid.length+'</b>条记录吗？';
			var ids = contributionid.join(',');
		} else {
			var msg = '确定操作编号为<b style="color:red">'+contributionid+'</b>的记录吗？';
			var ids = contributionid;
		}
		if (contributionid.length === 0) {
			ct.warn('请选择要操作的记录');
			return false;
		}
		var url = baseUrl + '&action=reject';
		ct.formDialog({
			'width': 260,
			'height': 232
		}, url, function(req) {
			if (req.state) {
				if(window.tableApp) {
					tableApp.deleteRow(contributionid);
				}
				ct.ok('操作成功');
				return true;
			} else {
				ct.error(req.message);
				return false;
			}
		}, function(form) {
			rejectFormReady();
			form.find('[name="contributionid"]').val(ids);
		}, null, function(form) {
			form.find('#annotation').val(window.editor.getCode());
		});
	},
	remove : function(contributionid) {
		contribution.__common(contributionid, 'remove');
	},
	del : function(contributionid) {
		contribution.__common(contributionid, 'delete');
	},
	view :  function(contributionid) {
		ct.assoc.open(baseUrl+'&action=view&contributionid='+contributionid, 'newtab');
	},
	clear :function() {
		ct.confirm('确认清空回收站', function(){
			$.getJSON(
				baseUrl+'&action=clear',
				function(json){
					if (json.state) {
						if (window.tableApp) {
							tableApp.load();
						}
						ct.ok('操作成功');
					} else {
						ct.error(json.error);
					}
				}, 
			'json')
		});
	},
	publish : function(contributionid) {
		ct.assoc.open(baseUrl+'&action=add&contributionid='+contributionid, 'newtab');
	},
	pass :function() {
		var contributionid = tableApp.checkedIds();
		if (contributionid.length === 0) {
			ct.warn('请选择要操作的记录');
			return false;
		}
		ct.confirm('确定通过选中的<b style="color:red">'+contributionid.length+'</b>条记录吗？', function(){
			var successCount = failedCount = 0;
			var pass = function(contributionid) {
				var id = contributionid.shift();
				$.post(baseUrl+'&action=pass', {
					contributionid: id
				}, function(req){
					if (req.state) {
						successCount++;
						tableApp.deleteRow(id);
					} else {
						failedCount++;
					}
					if (contributionid.length) {
						delay = 2;
					} else {
						delay =  0;
					}
					ct.ok('成功<b class="c_green">'+successCount+'</b>条, 失败<b class="c_red">'+failedCount+'</b>条', 'center', delay);
					if (contributionid.length) pass(contributionid);
				},'json');
			}
			pass(contributionid);
		});
	}
}