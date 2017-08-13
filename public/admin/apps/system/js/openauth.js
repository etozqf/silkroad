var App = function(){
var row_template = 
'<tr id="row_{userid}">\
	<td class="t_c">\
	   <input type="checkbox" class="checkbox_style" value="{userid}" />\
	</td>\
	<td class="t_r">{userid}</td>\
	<td><a href="javascript: url.member({userid});">{username}</a></td>\
	<td class="t_c">{auth_key}</td>\
	<td class="t_c">{auth_secret}</td>\
	<td class="t_c">{disabled}</td>\
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
acaTreeTemplate = '\
<tr id="acarow_{acaid}">\
<td class="t_l">\
    <label><input type="checkbox" class="checkbox_style" name="acaid[]" value="{acaid}" /> {name}</label>\
</td>\
</tr>',
acaTreeOptions = {
    idField:'acaid',
    treeCellIndex:0,
    template:acaTreeTemplate,
    rowIdPrefix:'acarow_',
    collapsed:1,
    baseUrl:'?app=system&controller=openauth&action=acatree',
    rowReady:function(id,tr,json)
    {
        var checkbox = tr.find('input:checkbox');
        if (json.disabled)
        {
            checkbox.remove();
            return;
        }
        checkbox.click(function(){
            this.checked
                ? tr.getDescendants().find('input:checkbox').attr('disabled', true).attr('checked', true)
                : tr.getDescendants().find('input:checkbox').removeAttr('disabled').attr('checked', false);
        });
        json.checked && checkbox.attr('checked',true);
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
    },
    add:function(){
        ct.form('添加接口授权',addUrl,400,255,function(json){
            if (json.state) {
                a.table.addRow(json.data);
                ct.tips('添加授权成功','success');
                return true;
            }
        },function(form,dialog){
            return true;
        },function(form, dialog, options) {
            return true;
        });
    },
    edit:function(id, tr){
        var url = editUrl+'&userid='+id;
        tr.trigger('check');
        ct.form('更新接口授权', url, 400,300, function(json){
        	if (json.state) {
	            a.table.updateRow(id, json.data);
	            return true;
        	}
        },function(form, dialog){
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
			var t2 = new ct.treeTable(divs.eq(1).find('table.acaTreeTable'), acaTreeOptions);
			t2.load('userid='+id);
            var t3 = new ct.treeTable(divs.eq(3).find('table.pageTreeTable'), pageTreeOptions);
            t3.load('userid='+id);
        },function(form, dialog, options) {
            return true;
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
            $.post(delUrl,'userid='+id,
            function(json){
                json.state
                 ? (ct.warn('删除完毕'), a.table.deleteRow(id))
                 : ct.warn(json.error);
            },'json');
        });
    },
    reload: function(){
        a.table.reload();
    }
};
return a;
}();