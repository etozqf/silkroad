var App = function(){
var row_template = 
'<tr id="row_{userid}">\
	<td class="t_c">\
	   <input type="checkbox" class="checkbox_style" value="{userid}" />\
	</td>\
	<td class="t_r">{userid}</td>\
	<td>{username}</td>\
	<td>{name}</td>\
	<td class="t_c">{role}</td>\
	<td class="t_c">{department}</td>\
    <td class="t_c">{login_time} {login_place}</td>\
	<td class="t_c">{state}</td>\
	<td class="t_c">\
	   <img class="manage edit" height="16" width="16" alt="编辑" title="编辑" src="images/edit.gif"/>\
       <img class="manage delete" height="16" width="16" alt="删除" title="删除" src="images/delete.gif"/>\
	</td>\
</tr>',
pagePrivTemplate = '\
<tr id="pagepriv_{pageid}">\
	<td class="t_l">\
		<label><input type="checkbox" class="checkbox_style" name="pageid[]" value="{pageid}" /> {name}</label>\
	</td>\
</tr>',
pageTreeOptions = {
	idField:'pageid',
	treeCellIndex:0,
	template:pagePrivTemplate,
	rowIdPrefix:'pagepriv_',
	collapsed:1,
	baseUrl:'?app=system&controller=administrator&action=pagetree',
	rowReady:function(id,tr,json) {
		var checkbox = tr.find('input:checkbox');
		checkbox.click(function(){
			this.checked
			  ? tr.getDescendants().find('input:checkbox').attr('disabled','disabled').attr('checked',true)
			  : tr.getDescendants().find('input:checkbox').removeAttr('disabled').attr('checked', false);
		});
		json.checked && checkbox.attr('checked', true);
		var p = tr.data('parentTr');
		p && p.find('input:checkbox').is(':checked,:disabled')
		  && checkbox.attr('checked', true).attr('disabled', true);
	}
},
init_row_event = function(id, tr){
    tr.find('>td>img.edit').click(function(){
        a.edit(id, tr);
    });
    tr.find('>td>img.delete').click(function(){
        a.del(id);
    });
},
editUrl,delUrl,addUrl,
a = {
    table:null,
    init:function(baseUrl, pageSize){
        editUrl = baseUrl+'&action=edit';
        addUrl = baseUrl+'&action=add';
        delUrl = baseUrl+'&action=delete';
        a.table = new ct.table('#item_list',{
            pageSize: pageSize,
            dblclickHandler : a.edit,
            rowCallback : init_row_event,
            template : row_template,
            baseUrl : baseUrl+'&action=page'
        });
        a.table.load();
        
		$('select[name=search_departmentid],#search_roleid').dropdown({
			search_departmentid:{
				onchange:function(val){
					a.table.load('departmentid='+val+'&roleid='+$('input[name=search_roleid]').val());
				}
			},
			search_roleid:{
				onchange:function(val){
					a.table.load('roleid='+val+'&departmentid='+$('input[name=search_departmentid]').val());
				}
			}
		});
    },
    stat:function(id, tr){
        ct.assoc.open('?app=system&controller=administrator&action=stat&userid='+id, 'newtab');
    },
    priv:function(id, tr){
        ct.assoc.open('?app=system&controller=administrator&action=priv&userid='+id, 'newtab');
    },
    clonepriv:function(id, tr){
    	ct.form('克隆权限','?app=system&controller=administrator&action=clonepriv&srcuserid='+id,
    	400, 200,
    	function(json){
    		if (json.state) {
    			ct.tips('克隆成功','success');
    		} else {
    			ct.error(json.error);
    		}
    		return true;
    	},function(form,dialog){
    		$(form[0].taruserid).suggest();
    	});
    },
    edit:function(id, tr){
        var url = editUrl+'&userid='+id;
        tr.trigger('check');
        ct.form('编辑管理员', url, 420, 500, function(json){
        	if (json.state) {
	            a.table.updateRow(id, json.data);
	            return true;
        	}
        },function(form, dialog){
            new Password(form.find('[name="password"]'));
        	var divs = dialog.find('div.part');
        	dialog.find('div.tabs>ul').tabnav({
				dataType:null,
				forceFocus:true,
				focused:function(li){
					divs.hide();
					divs.eq(li.attr('index')).show();
				}
			});
            dialog.find('[name=catid]').placetree();
			var t2 = new ct.treeTable(divs.eq(2).find('table.treeTable'), pageTreeOptions);
			t2.load('userid='+id);
        	dialog.find('select[name=departmentid]').change(function(){
        		dialog.find('select[name=roleid]').parent()
        		.load('?app=system&controller=role&action=dropdown&departmentid='+this.value);
        	});
            form.find('[name="password"]').bind('focus', function() {
                var input = $(this), tips;
                if (input.parent().children('.validator-tooltips').length > 0) return;
                tips = $('<div class="validator-tooltips"></div>').html('密码长度须大于6且密码不能为单一数字、字母或特殊字符');
                tips.css({
                    left: '0',
                    position: 'absolute',
                    top: '-36px',
                    width: '296px',
                    height: '28px'
                });
                input.after(tips);
            }).bind('blur', function(event) {
                $(this).parent().children('.validator-tooltips').remove();
            });
        },function(form, dialog, options) {
            form.find('[name="password"]').trigger('blur');
            if (!form.find('[name=roleid]').val()) {
                ct.error('请选择角色');
                return false;
            }
            var password = form.find('[name="password"]').val();
            if (password && pwGrade(password) < 2) {
                var tips = $('<div class="validator-tooltips validator-infobox validator-error"></div>').html('密码长度须大于6且密码不能为单一数字、字母或特殊字符');
                tips.css({
                    left: '0',
                    position: 'absolute',
                    top: '-32px',
                    width: '296px',
                    height: '28px',
                    "white-space": 'normal',
                    "line-height": '14px'
                });
                form.find('[name="password"]').after(tips);
                return false;
            }
        });
    },
    add:function(){
        ct.form('添加管理员',addUrl,420, 500,function(json){
        	if (json.state) {
	            a.table.addRow(json.data);
	            ct.tips('添加管理员成功','success');
	            return true;
        	}
        },function(form,dialog){
            new Password(form.find('[name="password"]'));
        	var divs = dialog.find('div.part');
        	dialog.find('div.tabs>ul').tabnav({
				dataType:null,
				forceFocus:true,
				focused:function(li){
					divs.hide();
					divs.eq(li.attr('index')).show();
				}
			});
            dialog.find('[name=catid]').placetree();
			var t2 = new ct.treeTable(divs.eq(2).find('table.treeTable'), pageTreeOptions);
			t2.load();
        	dialog.find('select[name=departmentid]').change(function(){
        		dialog.find('select[name=roleid]').parent()
        		.load('?app=system&controller=role&action=dropdown&departmentid='+this.value);
        	});
            form.find('[name="password"]').bind('focus', function() {
                var input = $(this), tips;
                if (input.parent().children('.validator-tooltips').length > 0) return;
                tips = $('<div class="validator-tooltips"></div>').html('密码长度须大于6且密码不能为单一数字、字母或特殊字符');
                tips.css({
                    left: '0',
                    position: 'absolute',
                    top: '-36px',
                    width: '296px',
                    height: '28px'
                });
                input.after(tips);
            }).bind('blur', function(event) {
                $(this).parent().children('.validator-tooltips').remove();
            });
        },function(form, dialog, options) {
            form.find('[name="password"]').trigger('blur');
            if (!form.find('[name=roleid]').val()) {
                ct.error('请选择角色');
                return false;
            }
            var password = form.find('[name="password"]').val();
            if (password && pwGrade(password) < 2) {
                var tips = $('<div class="validator-tooltips validator-infobox validator-error"></div>').html('密码长度须大于6且密码不能为单一数字、字母或特殊字符');
                tips.css({
                    left: '0',
                    position: 'absolute',
                    top: '-32px',
                    width: '296px',
                    height: '28px',
                    "white-space": 'normal',
                    "line-height": '14px'
                });
                form.find('[name="password"]').after(tips);
                return false;
            }
        });
    },
    del:function(id){
        var msg;
        if (id === undefined)
        {
            id = a.table.checkedIds();
            if (!id.length)
            {
                ct.warn('请选中要删除项');
                return;
            }
            msg = '确定删除选中的<b style="color:red">'+id.length+'</b>条记录吗？';
        }
        else
        {
            msg = '确定删除编号为<b style="color:red">'+id+'</b>的记录吗？';
        }
        ct.confirm(msg,function(){
            $.post(delUrl,'id='+id,
            function(json){
                json.state
                 ? (ct.warn('删除完毕'), a.table.deleteRow(id))
                 : ct.warn(json.error);
            },'json');
        });
    },
    score : function(id){
    	ct.iframe({title:'?app=system&controller=score&action=editor&userid='+id,width:570,height:'auto'});
    }
};
return a;
}();