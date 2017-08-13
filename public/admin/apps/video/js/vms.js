(function($){
    var tableApp = null, jsonData = {}, playerURL='', ismanage = 1;
    var vms = {
        init:function(manage, url, url2, filetype){
            playerURL = url2;
            ismanage = manage;
            var template = '';
            var url3 = '?app=video&controller=vms&action=ls';
            if(ismanage){
                template =
                    '<tr id="row_{vid}">'+
                        '<td><input type="checkbox" value="{vid}" /></td>'+
                        '<td>{title}</td>'+
                        '<td class="t_c">{duration}</td>'+
                        '<td class="t_c">{status}</td>'+
                        '<td class="t_c">{created}</td>'+
                        '<td class="t_c">'+
                        '<img src="images/view.gif" class="manage view" title="访问" alt="访问" width="16" height="16" />&nbsp;'+
                        '<img src="images/video.gif" class="manage preview" title="访问" alt="访问" width="16" height="16" />&nbsp;'+
                        '<img src="images/edit.gif" class="manage edit" alt="编辑" title="编辑" width="16" height="16"/>&nbsp;'+
                        '<img src="images/delete.gif" class="manage del" alt="删除" title="删除" width="16" height="16"/>'+
                        '</td>'+
                        '</tr>';
            }else{
                template =
                    '<li id="row_{vid}">'+
                        '<div class="item-thumb">' +
                        '<span class="item-control" style="display:none;">' +
                        '<div class="item-control-edit" title="编辑" onclick="vms.edit({vid});"></div>' +
                        '<div class="item-control-view" title="查看" onclick="vms.view({vid});"></div>' +
                        '<div class="item-control-preview" title="预览" onclick="vms.preview({vid});"></div>' +
                        '<div class="item-control-check" title="选择" onclick="vms.check({vid});"></div>' +
                        '</span>' +
                        '<img src="{pic}" width="120" height="90" />' +
                        '</div>' +
                        '<p><span><input type="radio" value="{vid}" /> {title}</span></p>'+
                        '<p class="duration">{duration}</p>'+
                        '</li>';
            }
            tableApp = new ct.table('#item_list', {
                rowIdPrefix : 'row_',
                rightMenuId : 'right_menu',
                pageSize : ismanage ? 15 : 12,
                rowCallback : ismanage ? function(id, tr){
                    tr.find('img.view').click(function(){
                        vms.view(id);
                    });
                    tr.find('img.preview').click(function(){
                        vms.preview(id);
                    });
                    tr.find('img.edit').click(function(){
                        vms.edit(id);
                    });
                    tr.find('img.del').click(function(){
                        vms.del(id);
                    });
                } : function(id, tr){
                    var input = tr.find('input')[0];
                    if(jsonData[id].picnum == 0)
                    {
                        manage || $("input", tr).remove();
                    }
                    tr.bind('check',function(){
                        input.checked = true;
                    }).bind('unCheck',function(){
                            input.checked = false;
                        });
                    tr.hover(function(e) {
                        $(e.currentTarget).find('.item-control').show();
                    }, function(e) {
                        $(e.currentTarget).find('.item-control').hide();
                    });
                },
                dblclickHandler : ismanage ? function(id){
                    vms.edit(id);
                } : function(id){
                    vms.check(id);
                },
                jsonLoaded : function(json){
                    if (!json.data) {
                        return;
                    }
                    jsonData = {};
                    for (var i=0,t;t=json.data[i++];) {
                        formatData(t);
                    }
                },
                template : template,
                baseUrl  : url3
            });
            tableApp.load();

            $('#up').uploader({
                script : url,
                fileDesc : '视频文件',
                fileExt : filetype,
                fileDataName : 'f_video',
                jsonType:1,
                complete:function(json, data) {
                    if (json.state) {
                        jsonData = {};
                        formatData(json.data);
                        //上传完成即弹出设置视频名称
                        vms.edit(jsonData[json.data.vid].vid, 1);
                        tableApp.load();
                    } else {
                        ct.error(json.error);
                    }
                },
                error:function(data) {
                    ct.error(data.type+':'+data.info);
                }
            });

            $('.tag_list a').click(function(){
                $('.tag_list .s_3').removeClass('s_3');
                $(this).addClass('s_3');
            }).focus(function(){
                    this.blur();
                });

            $("#addtime_from").DatePicker({'format':'yyyy-MM-dd'});
            $("#addtime_to").DatePicker({'format':'yyyy-MM-dd'});
        },
        where:function(where,v){
            if(v != undefined)
            {
                where[0].reset();
                $("#status").val(v);
            }
            tableApp.load(where);
        },
        reload:function(){
            tableApp.load();
        },
        view:function(id){
            ct.ajaxDialog({title:'查看视频信息', width:400, height:370}, '?app=video&controller=vms&action=view&vid='+id, function(dialog){
                var data = 'vid='+id;
                $.getJSON("?app=video&controller=vms&action=info", data, function(json){
                    if(json.state){
                        jsonData = {};
                        formatData(json.data);
                        if(json.data.status != '0'){
                            $('#title', dialog).html(jsonData[id].title);
                        }else{
                            $('#title', dialog).html(jsonData[id].title + ' <font color=red>[转码中]</font>');
                        }
                        $('#tags', dialog).html(jsonData[id].tags);
                        $('#duration', dialog).html(jsonData[id].duration);
                        $('#bitrate', dialog).html(jsonData[id].bitrate);
                        $('#scale', dialog).html(jsonData[id].scale);
                        if(jsonData[id].pic != ''){
                            $('#pic', dialog).html('<img src="'+jsonData[id].pic+'" width="375" height="210" style="margin-left:6px;" />');
                        }else{
                            // 重新设置高度，当没有图片时去掉空白
                            dialog.height(dialog.height() + 38 - 210);
                        }
                    }else{
                        $('#title', dialog).html('载入出错');
                    }
                });
            });
        },
        preview:function(id){
            ct.iframe({title:'预览视频播放', width:530, height:475,url:'?app=video&controller=vms&action=preview&vid='+id},function(){
                return true;
            });
        },
        edit:function(id, isup){
            var url = '?app=video&controller=vms&action=edit&vid='+id;
            ct.form('编辑视频',url,450,435,function(json){
                    if(json.state){
                        if( isup == "1"){
                            ct.ok("视频上传成功，进入转码队列。");
                        }else{
                            jsonData = {};
                            formatData(json.data);
                            tableApp.updateRow(id, jsonData[id]).trigger('check');
                        }
                    }else{
                        return false;
                    }
                    return true;
                },
                function(form, dialog){
                    var data = 'vid=' + id;
                    $.getJSON("?app=video&controller=vms&action=info", data, function(json){
                        if(json.state){
                            $('#title', form).val(json.data.title);
                            $('#tags', form).val(json.data.tags);
                            var pic = '';
                            if(json.data.picnum != 0){
                                var exti = json.data.pic.lastIndexOf('-'+json.data.picid+'.');
                                var picpre = json.data.pic.substring(0,exti);
                                for(i=1;i<=json.data.picnum;i++){
                                    pic = pic + '<li id="pic'+i+'"><img id="img'+i+'" src="'+picpre+'-'+i+'.jpg" width="120" height="90" /><input name="picid" type="radio" value="'+i+'" /></li>';
                                }
                            }else{
                                pic = '<li class="trip">视频正在转换中，图片尚未生成...</li>';
                            }
                            $('#pic', form).html(pic);
                            if(json.data.picnum != "0"){
                                $("input[name=picid]", form).get((json.data.picid-1)).checked=true;
                            }else{
                                // 重新设置高度，当没有图片时去掉空白
                                dialog.height(form.height() + 38);
                            }
                        }else{
                            ct.error(json.error);
                        }
                    });
                });
        },
        del:function(id){
            var msg = '';
            if(id === undefined){
                id = tableApp.checkedIds();
                if (!id.length) {
                    ct.warn('请选中要删除项');
                    return;
                }
                msg = '确定删除选中的<b style="color:red">'+id.length+'</b>条记录吗？';
            }else{
                msg = '确定删除编号为<b style="color:red">'+id+'</b>的记录吗？';
            }
            ct.confirm(msg,function(){
                var data = 'vid='+id;
                $.getJSON('?app=video&controller=vms&action=delete', data, function(json){
                    json.state
                        ? (ct.warn(json.message), tableApp.deleteRow(id))
                        : ct.warn(json.error);
                });
            });
        },
        check:function(id){
            if (id) {
                var id = id;
            } else {
                if(tableApp.checkedRow() === undefined || tableApp.checkedRow() == null || tableApp.checkedRow().find('input').val() == undefined){
                    ct.warn('没有选项被选中');
                    return;
                }
                var id = tableApp.checkedRow().find('input').val();
            }
            var t = jsonData[id];
            var r = {
                id:t.id,
                title:t.title,
                tags:t.tags,
                duration:t.duration,
                playtime:t.playtime,
                bitrate:t.bitrate,
                pic:t.pic
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
        batImport: function(){
            var d = ct.form('批量导入','?app=video&controller=vms&action=import',450,435,function(json){
                    return true;
                },
                function(json,dialog){
                    var importBox = $('#importBox', dialog);
                    var tpl;
                    $.getJSON('?app=video&controller=vms&action=get_import', function(json){
                        if(json.state && json.data.length > 0){
                            for(var i=0;i<json.data.length;i++){
                                tpl = '<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form" style="margin-bottom:10px;" id="'+json.data[i].id+'">'
                                    + '<tbody>'
                                    + '<tr><th width="80">文件名称：</th><td><input type="hidden" name="file" value="'+json.data[i].name+'" />'+json.data[i].name+'</td></tr>'
                                    + '<tr><th>视频名称：</th><td><input type="text" name="title" value="" size="53" placeholder="视频标题" /></td></tr>'
                                    + '<tr><th>视频标签：</th><td><input type="text" name="tags" value="" size="53" placeholder="多个tag用空格隔开" /></td></tr>'
                                    + '<tr><td style="border-bottom:1px dashed #9ECBD8;height:10px;" colspan="2"></td></tr>'
                                    + '</tbody>'
                                    + '</table>';
                                importBox.append(tpl);
                            }
                        }else{
                            $('.suggest', dialog).show();
                        }
                    });
                },
                function(form, dialog){
                    var isok = true;
                    $('input', form).each(function(){
                        if(!$(this).val()) isok = false;
                    });
                    if(!isok){
                        ct.error('请填写视频信息，不允许空值！');
                        return false;
                    }
                    //处理导入，单次单条接收
                    var tables = $('table', dialog);
                    var setTable = function(i){
                        if(i>=tables.length){
                            d.dialog('close');
                            $('#pagetab').find('a:eq(1)').click();
                            vms.where($('#search_f'),0);
                            return true;
                        }
                        var id,file,title,tags;
                        $(tables[i]).css('background', '#FFFDDD');
                        id = $(tables[i]).attr('id');
                        file = $(tables[i]).find('input[name="file"]').val();
                        title = $(tables[i]).find('input[name="title"]').val();
                        tags = $(tables[i]).find('input[name="tags"]').val();
                        $.getJSON(
                            '?app=video&controller=vms&action=set_import',
                            {id: id, file: file, title: title, tags: tags},
                            function(json){
                                if(json && json.state){
                                    $(tables[i]).remove();
                                    setTable(++i);
                                }else{
                                    ct.error(json.error);
                                }
                            }
                        );
                    }
                    setTable(0);
                    return false;
                });
        }
    };
    window.vms = vms;
    function formatData(t) {
        t.playtime = t.duration;
        t.duration = friendSecond(t.duration);
        t.duration || (t.duration = '未知');
        t.bitrate = formatRate(t.bitrate);
        t.created = t.addtime;
        t.status = t.status == 1 ? '已转码' : '转码中';
        t.pic || (t.pic = "images/conter_bg.gif");
        jsonData[t.vid] = t;
    }

    function formatSecond(second)
    {
        var str = hour = minute = '';
        if (second > 3600)
        {
            hour = Math.floor(second / 3600);
            second = second % 3600;
        }
        if (second > 60)
        {
            minute = Math.floor(second / 60);
            second = second % 60;
        }
        if(hour)
        {
            str = str + hour + "小时";
        }
        if(minute)
        {
            str = str + minute + "分";
        }
        if(second)
        {
            str = str + second + "秒";
        }
        return str;
    }

    function friendSecond(second)
    {
        var minute = '';
        if(!second) {
            return '00:00';
        }
        minute = Math.floor(second / 60);
        second = second % 60;
        if(minute.toString().length == 1){
            minute = '0' + minute;
        }
        if(second.toString().length == 1){
            second = '0' + second;
        }
        return minute + ':' + second;
    }

    function formatRate(bit)
    {
        if(!bit) return ;
        var ext = 'bps';
        if(bit > 1024)
        {
            bit = Math.floor(bit / 1024);
            ext = 'kbps';
        }
        return bit + ext;
    }
})(jQuery);
