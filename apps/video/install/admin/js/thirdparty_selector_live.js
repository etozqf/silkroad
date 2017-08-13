(function($){
var tableApp = null, liveData = {};
var thirdpartySelectorLive = {
    apiId: 0,
    apiType: 'ctvideo',
    init: function(tid, ttype){
        this.apiId = tid;
        this.apiType = ttype;
        var template = '<tr id="row_{channelid}" value="{channelid}">'+
            '<td class="t_c"><input type="radio" name="channelid" value="{channelid}" /></td>'+
            '<td>{channelname}</td>'+
            '</tr>';
        tableApp = new ct.table('#item_list', {
            rowIdPrefix : 'row_',
            rightMenuId : 'right_menu',
            pageSize : 10,
            template : template,
            baseUrl  : '?app=video&controller=thirdparty&action=api&id='+tid+'&do=get_live',
            jsonLoaded: function(json){
                if(json.state && json.data){
                    for (var i=0,t;t=json.data[i];i++) {
                        liveData[json.data[i].channelid] = json.data[i];
                    }
                }else{
                    json.data = [];
                    if(json.error) ct.error(json.error);
                }
            },
            rowCallback : function(id, tr){
                var input = tr.find('input')[0];
                tr.bind('click', function(){
                    input.checked = true;
                    thirdpartySelectorLive.videoInfo(id);
                });
            },
            dblclickHandler : function(id, tr){
                thirdpartySelectorLive.check(id);
            }
        });
        window.setTimeout(function(){
            tableApp.load();
        }, 300);
        $('#btn_ok').click(function(){
            thirdpartySelectorLive.check(0);
        });
        $('#btn_cancel').click(function(){
            thirdpartySelectorLive.cancel();
        });
    },
    videoInfo: function(id){
        if(liveData[id] == undefined){
            ct.error('第三方视频数据有误');
        }else{
            if(liveData[id].playerparams){
                var code = '['+this.apiType+'Live]'+liveData[id].playerparams+'[/'+this.apiType+'Live]';
                previewVideo(code);
            }else{
                previewVideo('');
            }
        }
    },
    check: function(id){
        if(tableApp.checkedRow() === undefined || tableApp.checkedRow() == null || tableApp.checkedRow().find('input').val() == undefined){
            ct.warn('没有选项被选中');
            return;
        }
        var id = tableApp.checkedRow().find('input').val();
        var t = liveData[id];
        var r = {
            vid: t.channelid,
            video: $('#code').val()
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
    cancel: function(){
        if (parent)
        {
            window.getDialog && getDialog().dialog('close');
        }
    }
}
window.thirdpartySelectorLive = thirdpartySelectorLive;
})(jQuery);

function previewVideo(code){
    $('#code').val(code);
    if(!code){
        $('#player').hide();
        $('#thumb').show();
    }else{
        $('#thumb').hide();
        $('#player').show();
    }

    $('#previewForm').attr('action', '?app=video&controller=thirdparty&action=preview_video&islive=1&width=500&height=385');
    $('#previewForm').submit();
    return true;
}
