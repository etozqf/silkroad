(function($){
var tableApp = null;
var videolist = {
    init: function(){
        var template = '<tr id="row_{listid}" sorttype="{sorttype}">'+
            '<td><input type="checkbox" value="{listid}" /></td>'+
            '<td>{listname}</td>'+
            '<td class="t_c">{videonum}</td>'+
            '<td class="t_c">{createdtime}</td>'+
            '<td class="t_c"><img src="images/refresh.gif" class="manage html" alt="更新视频页面" title="更新视频页面" width="16" height="16"/>&nbsp;&nbsp;<img src="images/video.gif" class="manage video" alt="管理视频" title="管理视频" width="16" height="16"/>&nbsp;&nbsp;<img src="images/edit.gif" class="manage edit" alt="编辑" title="编辑" width="16" height="16"/>&nbsp;&nbsp;<img src="images/delete.gif" class="manage del" title="删除" alt="删除" width="16" height="16"/></td>'+
            '</tr>';
        tableApp = new ct.table('#item_list', {
            rowIdPrefix : 'row_',
            rightMenuId : 'right_menu',
            pageSize : 15,
            rowCallback : function(id, tr){
                var input = tr.find('input')[0];
                tr.bind('check',function(){
                    input.checked = true;
                }).bind('unCheck',function(){
                    input.checked = false;
                });
                tr.find('.html').click(function(){
                    videolist.html(id);
                });
                tr.find('.video').click(function(){
                    var sorttype = tr.attr('sorttype');
                    videolist.video(id, sorttype);
                });
                tr.find('.edit').click(function(){
                    videolist.edit(id);
                });
                tr.find('.del').click(function(){
                    videolist.del(id);
                });
            },
            dblclickHandler : function(id, tr){
                var sorttype = tr.attr('sorttype');
                videolist.video(id, sorttype);
            },
            template : template,
            baseUrl  : '?app=video&controller=videolist&action=ls'
        });
        tableApp.load();
        $("#created_min").DatePicker({'format':'yyyy-MM-dd'});
        $("#created_max").DatePicker({'format':'yyyy-MM-dd'});
        $('#btn_search').click(function(){
            tableApp.load($('#search_f').serialize());
        });
        $('#btn_add').click(function(){
            videolist.add();
        });
        $('#btn_reload').click(function(){
            tableApp.reload();
        });
        $('#btn_delete').click(function(){
           videolist.del();
        });
    },
    selector: function(){
        var template = '<tr id="row_{listid}">'+
            '<td><input type="radio" name="listid" value="{listid}" /></td>'+
            '<td>{listname}</td>'+
            '<td class="t_c">{videonum}</td>'+
            '<td class="t_c">{createdtime}</td>'+
            '</tr>';
        tableApp = new ct.table('#item_list', {
            rowIdPrefix : 'row_',
            rightMenuId : 'right_menu',
            pageSize : 10,
            rowCallback : function(id, tr){
                var input = tr.find('input')[0];
                tr.bind('check',function(){
                    input.checked = true;
                }).bind('unCheck',function(){
                        input.checked = false;
                    });
            },
            dblclickHandler : function(id, tr){
                videolist.selector_ok();
            },
            template : template,
            baseUrl  : '?app=video&controller=videolist&action=ls'
        });
        tableApp.load();
        $("#created_min").DatePicker({'format':'yyyy-MM-dd'});
        $("#created_max").DatePicker({'format':'yyyy-MM-dd'});
        $('#btn_search').click(function(){
            tableApp.load($('#search_f').serialize());
        });
        $('#btn_add').click(function(){
            videolist.add();
        });
        $('#btn_reload').click(function(){
            tableApp.reload();
        });
        $('#btn_ok').click(function(){
            videolist.selector_ok();
        });
        $('#btn_cancel').click(function(){
            videolist.selector_cancel();
        })
    },
    html: function(id){
        var msg = '正在更新中...';
        var loadingBox = $('<div class="loading" style="position:fixed;visibility:hidden"><sub></sub> '+msg+'</div>').appendTo(document.body);
        var style = cmstop.pos('center',loadingBox.outerWidth(true), loadingBox.outerHeight(true));
        style.visibility = 'visible';
        loadingBox.css(style);
        $.getJSON('?app=video&controller=videolist&action=html&listid='+id, function(json){
            loadingBox.remove();
            loadingBox = null;
            if(json.state){
                ct.ok(json.info);
            }else{
                ct.error(json.error);
            }
        });
    },
    video: function(id,sorttype){
        ct.iframe(
            {
                url: '?app=video&controller=videolist&action=video&listid='+id+'&sorttype='+sorttype,
                width: 650,
                height: 450
            },
            function(){},
            function(){},
            function(){
                window.location.reload();
            }
        );
    },
    add: function(){
        var url = '?app=video&controller=videolist&action=add';
        ct.form('添加专辑',url,420,100,function(json){
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
        var url = '?app=video&controller=videolist&action=edit&listid='+id;
        ct.form('更新专辑',url,420,100,function(json){
            if(json.state){
                tableApp.updateRow(id, json.data);
                return true;
            }else{
                return false;
            }
        });
    },
    del: function(id){
        var msg = '';
        if(id === undefined){
            id = tableApp.checkedIds();
            if (!id.length) {
                ct.warn('请选中要删除项');
                return false;
            }
            msg = '确定删除选中的<b style="color:red">'+id.length+'</b>条记录吗？';
        }else{
            msg = '确定删除编号为<b style="color:red">'+id+'</b>的记录吗？';
        }
        ct.confirm(msg,function(){
            var data = 'listid='+id;
            $.getJSON('?app=video&controller=videolist&action=delete', data, function(json){
                json.state
                    ? (ct.warn(json.message), tableApp.deleteRow(id))
                    : ct.warn(json.error);
            });
        });
    },
    selector_ok: function(){
        if(tableApp.checkedRow() === undefined || tableApp.checkedRow() == null || tableApp.checkedRow().find('input').val() == undefined){
            ct.warn('没有选项被选中');
            return;
        }
        var listid = tableApp.checkedRow().find('input').val();
        var r = {
            listid: listid,
            listname: tableApp.checkedRow().find('td:eq(1)').html(),
            videonum: tableApp.checkedRow().find('td:eq(2)').html()
        };
        if (parent)
        {
            if (window.dialogCallback && dialogCallback.ok)
            {
                dialogCallback.ok(r);
            }
            else
            {
                window.getDialog && getDialog().dialog('close');
            }
        }
    },
    selector_cancel: function(){
        if (parent)
        {
            window.getDialog && getDialog().dialog('close');
        }
    }
}
window.videolist = videolist;

var tableAppM = null;
var videoManage = {
    listid: 0,
    sorttype: 0,
    init: function(){
        var template = '<tr id="row_{contentid}" value="{contentid}">'+
            '<td width="30" class="t_c" style="cursor: move;">{key}</td>'+
            '<td><a href="{url}" target="_blank">{title}</a></td>'+
            '<td width="150" class="t_c">{catname}</td>'+
            '<td width="150" class="t_c">{publishedtime}</td>'+
            '<td width="60" class="t_c"><img src="images/delete.gif" class="manage del" alt="删除" width="16" height="16"/></td>'+
            '</tr>';
        tableAppM = new ct.table('#item_list', {
            rowIdPrefix : 'row_',
            rightMenuId : 'right_menu',
            pageSize : 15,
            jsonLoaded: function(json){
                if(json.data){
                    if(videoManage.sorttype){
                        for (var i=json.data.length,k=0,t;t=json.data[--i];k++) {
                            if(json.data[i]){
                                json.data[i].key = k+1;
                            }
                        }
                    } else {
                        for (var i=0,t;t=json.data[i];i++) {
                            if(json.data[i]){
                                json.data[i].key = i+1;
                            }
                        }
                    }
                }
            },
            rowCallback : function(id, tr){
                tr.find('.del').click(function(){
                    videoManage.del(id);
                });
            },
            template : template,
            baseUrl  : '?app=video&controller=videolist&action=video_ls&listid='+this.listid+'&sorttype='+this.sorttype
        });
        tableAppM.load();
        var $keywords = $('#keywords');
        var placeholder = '视频标题';
        $keywords.val(placeholder);
        $keywords.bind('focusin', function(){
            if($(this).val() == placeholder) $(this).val('');
        }).bind('focusout', function(){
            if($(this).val() == '') $(this).val(placeholder);
        });
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
                    ui.item.children().not(':eq(1)').each(function(){
                        cWidth += parseInt($(this).attr('width'));
                    });
                    ui.helper.find('td:eq(1)').width(ui.item.width() - cWidth);
                    ui.helper.find('>td').css('border-bottom', 'none');
                    ui.helper.css('background-color', '#FFF');
                    ct.IE && ui.helper.css('margin-left', '0px');
                },
                stop: function(ev, ui) {
                    var oldIndex = parseInt(ui.item.find('td:first').html()),
                        newIndex = tbody.find('>tr').index(ui.item.get(0)) + 1,
                        diff = 0;
                    if(videoManage.sorttype){
                        newIndex = tbody.find('>tr').length - newIndex + 1;
                    }
                    diff = oldIndex - newIndex;
                    if (diff) {
                        ui.item.find('>td').css('border-bottom','1px dotted #D7E3F2');
                        tbody.find('>tr').each(function(index, tr) {
                            if(videoManage.sorttype){
                                $(tr).find('td:first').text(tbody.find('>tr').length - index);
                            }else{
                                $(tr).find('td:first').text(index + 1);
                            }
                        });
                        // resort, send all row
                        var sort = [];
                        tbody.find('>tr').each(function(index, tr) {
                            sort[sort.length] = $(tr).attr('value');
                        });
                        if(videoManage.sorttype) sort.reverse();
                        $.post("?app=video&controller=videolist&action=video_resort", {
                            listid: videoManage.listid,
                            sort: sort.join(',')
                        });
                    }
                }
            });
        });
        $('#btn_search').click(function(){
            if($keywords.val() == placeholder){
                $keywords.val('');
            }
            tableAppM.load($('#search_f').serialize());
        });
        $('#btn_add').click(function(){
            videoManage.add();
        });
    },
    add: function(){
        $.datapicker({
            url: '?app=system&controller=port&action=picker&modelid=4',
            width: 580,
            picked: function(items){
                var ids = [],
                    titles = {},
                    contentid;
                for(var i=0;i<items.length;i++){
                    contentid =  items[i].contentid;
                    ids[i] = contentid;
                    titles[contentid] = items[i].title;
                }
                $.getJSON('?app=video&controller=videolist&action=video_add&contentid='+ids.join(',')+'&listid='+videoManage.listid, function(json){
                    if(json && json.state){
                        if(json.data.fail){
                            var msg = '';
                            for(var i=0;i<json.data.fail;i++){
                                if(!json.data.faildata[i].listname){
                                    msg += '<span>'+titles[json.data.faildata[i].contentid]+' 添加失败<br/></span>';
                                }else{
                                    msg += '<span>'+titles[json.data.faildata[i].contentid]+' 已经存在视频专辑[<font color=green>'+json.data.faildata[i].listname+'</font>]中<br/></span>';
                                }
                            }
                            var ddd = ct.alert(msg,'warning');
                            ddd.find('sub').remove();
                            ddd.css('text-align', 'left');
                            ddd.find('a[href=close]').css('display', 'block').css('text-align', 'right');
                        }
                        tableAppM.load('keywords=');
                    }else{
                        ct.error('数据异常');
                    }
                });
            },
            multiple: true
        });
    },
    del: function(id){
        var msg = '确定删除编号为<b style="color:red">'+id+'</b>的记录吗？';
        ct.confirm(msg,function(){
            $.getJSON('?app=video&controller=videolist&action=video_delete&contentid='+id+'&listid='+videoManage.listid, function(json){
                if(json && json.state){
                    ct.ok(json.message);
                    tableAppM.deleteRow(id);
                }else{
                    ct.error(json.error);
                }
            });
        });
    }
}
window.videoManage = videoManage;
})(jQuery);
