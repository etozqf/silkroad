(function($){
var tableApp = null;
var thirdparty = {
    init: function(){
        var template = '<tr id="row_{id}" value="{id}">'+
            '<td width="30" class="t_c" style="cursor: move;">{key}</td>'+
            '<td width="200">{title}</td>'+
            '<td>{apiurl}</td>'+
            '<td width="100" class="t_c">{status}</td>'+
            '<td width="60" class="t_c"><img src="images/edit.gif" class="manage edit" alt="编辑" title="编辑" width="16" height="16"/> <img src="images/delete.gif" class="manage del" alt="删除" title="删除" width="16" height="16"/></td>'+
            '</tr>';
        tableApp = new ct.table('#item_list', {
            rowIdPrefix : 'row_',
            rightMenuId : 'right_menu',
            pageSize : 15,
            jsonLoaded: function(json){
                if(json.data){
                    for (var i=0,t;t=json.data[i];i++) {
                        if(json.data[i]){
                            json.data[i].key = i+1;
                            if(json.data[i].status == 1){
                                json.data[i].status = '启用';
                            }else{
                                json.data[i].status = '禁用';
                            }
                        }
                    }
                }
            },
            rowCallback : function(id, tr){
                tr.find('.edit').click(function(){
                    thirdparty.edit(id);
                });
                tr.find('.del').click(function(){
                    thirdparty.del(id);
                });
            },
            dblclickHandler : function(id, tr){
                thirdparty.edit(id);
            },
            template : template,
            baseUrl  : '?app=video&controller=thirdparty&action=ls'
        });
        tableApp.load();
        $('#item_list').bind('update', function() {
            var tbody = $(this).find('tbody:first');
            tbody.sortable({
                axis: 'y',
                handle: 'td:first',
                forceHelperSize: true,
                placeholder: 'tr-placeholder',
                opacity: 0.8,
                start: function(ev, ui) {
                    $('<td colspan="' + ui.item.children().length + '">&nbsp;</td>').appendTo(ui.placeholder);
                    var cWidth = 0;
                    ui.item.children().not(':eq(2)').each(function(){
                        cWidth += parseInt($(this).attr('width'));
                    });
                    ui.helper.find('td:eq(2)').width(ui.item.width() - cWidth);
                    ui.helper.find('>td').css('border-bottom', 'none');
                    ui.helper.css('background-color', '#FFF');
                    ct.IE && ui.helper.css('margin-left', '0px');
                },
                stop: function(ev, ui) {
                    var oldIndex = parseInt(ui.item.find('td:first').html()),
                        newIndex = tbody.find('>tr').index(ui.item.get(0)) + 1,
                        diff = 0;
                    diff = oldIndex - newIndex;
                    if (diff) {
                        ui.item.find('>td').css('border-bottom','1px dotted #D7E3F2');
                        tbody.find('>tr').each(function(index, tr) {
                            $(tr).find('td:first').text(index + 1);
                        });
                        // resort, send all row
                        var sort = [];
                        tbody.find('>tr').each(function(index, tr) {
                            sort[sort.length] = $(tr).attr('value');
                        });
                        $.post("?app=video&controller=thirdparty&action=resort", {
                            sort: sort.join(',')
                        });
                    }
                }
            });
        });
        $('#btn_search').click(function(){
            tableApp.load($('#search_f').serialize());
        });
        $('#btn_add').click(function(){
            thirdparty.add();
        });
    },
    add: function(){
        var url = '?app=video&controller=thirdparty&action=add';
        ct.form('添加接口',url,420,210,function(json){
            if(json.state){
                ct.ok('添加成功');
                tableApp.load('keywords=');
                return true;
            }else{
                return false;
            }
        });
    },
    edit: function(id){
        var url = '?app=video&controller=thirdparty&action=edit&id='+id;
        ct.form('编辑接口',url,420,210,function(json){
            if(json.state){
                ct.ok('编辑成功');
                tableApp.load('keywords=');
                return true;
            }else{
                return false;
            }
        });
    },
    del: function(id){
        var msg = '确定删除编号为<b style="color:red">'+id+'</b>的记录吗？';
        ct.confirm(msg,function(){
            $.getJSON('?app=video&controller=thirdparty&action=delete&id='+id, function(json){
                if(json && json.state){
                    ct.ok(json.message);
                    tableApp.deleteRow(id);
                }else{
                    ct.error(json.error);
                }
            });
        });
    }
}
window.thirdparty = thirdparty;
})(jQuery);
