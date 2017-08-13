(function($){
	//分类列表获取分类数据执行函数
var treeOptions = {
	url:'?app='+app_name+'&controller='+controller_name+'&action=cate&id=%s',
	paramId : controller_name+'id',
	paramHaschild:'hasChildren',
	renderTxt:function(div, id, item){
		return $('<span id='+id+'>'+item.name+'</span>');
	},
	active : function(div, id, item){
		$('#topattribute').hide();
	    $('#subattribute').show();
		current_id = id;
		attribute.edit(current_id);
	}
};
window.attribute =  {
	//添加一个分类数据
	add: function (parentid) {
		if (parentid)
		{
			$('#edit').attr('class', '');
			$('#add').attr('class', 's_3');
			$('#'+app_name+'_edit_box').load('?app='+app_name+'&controller='+controller_name+'&action=add&parentid='+parentid);
		}
		else
		{
			$('#topattribute').show();
			$('#subattribute').hide();
			$('#'+app_name+'_edit_box').load('?app='+app_name+'&controller='+controller_name+'&action=add');
		}
	},
	
	//添加一个分类数据提交时的确认提示
	add_submit: function (response)
	{
		if (response.state)
		{
            this.reload(response.id);
			document.getElementById('attribute_add').reset();
			ct.ok('保存成功');
		}
		else
		{
			ct.error(response.error);
		}
	},
	
	// 修改分类数据信息
	edit: function (id)
	{
		$('#add').attr('class', '');
		$('#edit').attr('class', 's_3');
		$('#'+app_name+'_edit_box').load('?app='+app_name+'&controller='+controller_name+'&action=edit&id='+id);
	},
	
	//修改分类信息提交时的确认提示
	edit_submit: function (response)
	{
		if (response.state)
		{
            this.reload(response.id);
			ct.ok('保存成功');
		}
		else
		{
			ct.error(response.error);
		}
	},
	
	//删除一条数据信息
	del: function (id)
	{
		var span = $('#'+id);
		ct.confirm('确认删除 <span class="c_red">'+span.html()+'</span> 属性吗？', function (){
			$.getJSON('?app='+app_name+'&controller='+controller_name+'&action=delete&id='+id, function(response) {
				if (response.state)
				{
					var li = span.closest('li');
					var s = li.siblings('li:eq(0)');
					if (s.length) {
						s.triggerHandler('clk.tree');
					} else {
						s = li.parent().parent();
						if (s[0].nodeName == 'LI') {
							attribute.reload(s.attr('idv'));
						} else {
							attribute.add();
						}
					}
					li.remove();
					ct.ok('类型删除成功');
				}
				else
				{
					ct.error(response.error);
				}
			});
		}, function (){
			
		});
	},
	
	//移动分类
	move: function (id)
	{
		//ct.form 将移动的模板加载到ct.form提供的样式中,通过js弹出样式,并可以传参调样式,执行相关函数
		ct.form('移动类型', '?app='+app_name+'&controller='+controller_name+'&action=move&id='+id, 350, 300, function(response){
			if (response.state) {
				attribute.reload(id);
				return true;
			} else {
				ct.error(response.error);
			}
		}, function (dialog){
			dialog.find('#'+controller_name+'_move').tree({async:false,expanded:true});
		});
	},
	
	//左侧分类列表 循环分类执行函数
	reload: function (id, path)
	{
		$('#'+app_name+'_tree').empty();
		$('#'+app_name+'_tree').tree($.extend({
			prepared:function(){
				var t = this;
				if (path) {
					t.open(path, true);
				} else {
					$.getJSON('?app='+app_name+'&controller='+controller_name+'&action=path&id='+id, function(path){
						t.open(path, true);
					});
				}
			}
		}, treeOptions));
	},
	
	// 分类修复功能
	repair: function ()
	{
		$.getJSON('?app='+app_name+'&controller='+controller_name+'&action=repair', function(response){
			if (response.state)
			{
				ct.alert('操作成功', 'ok');
			}
			else
			{
				ct.error(response.error);
			}
		});
	}
};
})(jQuery);