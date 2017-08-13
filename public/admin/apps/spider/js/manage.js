(function(){
	var App = {
		init:function(pageSize, siteid) {
			table = new ct.table('#item_list',{
				pageSize: pageSize,
				dblclickHandler : App.edit,
				rowCallback : init_row_event,
				template : row_template,
				baseUrl : '?app=spider&controller=manager&action=page'
			});
			table.load(siteid ? 'siteid='+siteid : null);
			$('#siteid').dropdown({
				siteid:{
					'onchange':function(val){
						table.load('siteid='+val);
					},
					'selected':siteid+''
				}
			});
			$('#import').uploader({
				script:'?app=spider&controller=manager&action=import',
				multi:false,
				fileDesc: 'XML文件',
				fileExt: '*.xml',
				fileDataName:'xmlfile',
				jsonType : 1,
				start:function(){
					ct.startLoading('center');
				},
				complete:function(json, data){
					if (json.state) {
						table.load();
						ct.ok('导入完成');
					} else {
						ct.error(json.error);
					}
				},
				allcomplete:function(){
					ct.endLoading();
				}
			});
		},
		edit:function(id, tr){
			ct.assoc.open('?app=spider&controller=manager&action=editrule&ruleid='+id,'newtab');
		},
		
		add:function(){
			ct.assoc.open('?app=spider&controller=manager&action=addrule','newtab');
		},
		xport:function(id){
			if (id === undefined) {
				id = table.checkedIds();
			}
			var url = '?app=spider&controller=manager&action=export&ruleid='+id;
			window.location = url;
		},
		del:function(id){
			var msg;
			if (id === undefined) {
				id = table.checkedIds();
				if (!id.length)
				{
					ct.warn('请选中要删除项');
					return;
				}
				msg = '确定删除选中的<b style="color:red">'+id.length+'</b>条记录吗？';
			} else {
				msg = '确定删除编号为<b style="color:red">'+id+'</b>的记录吗？';
			}
			ct.confirm(msg,function(){
				$.post('?app=spider&controller=manager&action=delrule','id='+id,
				function(json){
					json.state
					 ? (ct.warn('删除完毕'), table.deleteRow(id))
					 : ct.warn(json.error);
				},'json');
			});
		},
        clearCache: function() {
            var url = '?app=spider&controller=manager&action=clearcache';
            $.post(url, null, function(req){
                if (req.state) {
                    ct.ok('清除成功');
                } else {
                    ct.error(state.error || '清除失败');
                }
            }, 'json');
        }
	};
	window.App = App;
})();