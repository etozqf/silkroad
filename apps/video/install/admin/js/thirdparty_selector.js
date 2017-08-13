(function($){
var tableApp = null, videoData = {};
var thirdpartySelector = {
    apiId: 0,
    apiType: 'ctvideo',
    init: function(tid, ttype){
        this.apiId = tid;
        this.apiType = ttype;
        var template = '<tr id="row_{vid}" value="{vid}">'+
            '<td class="t_c"><input type="radio" name="vid" value="{vid}" /></td>'+
            '<td>{title}</td>'+
            '<td class="t_r">{createdstr}</td>'+
            '</tr>';
        tableApp = new ct.table('#item_list', {
            rowIdPrefix : 'row_',
            rightMenuId : 'right_menu',
            pageSize : 10,
            num_display_entries: 4,
            num_edge_entries: 1,
            template : template,
            baseUrl  : '?app=video&controller=thirdparty&action=api&id='+tid+'&do=get_video',
            jsonLoaded: function(json){
                if(json.state && json.data){
                    for (var i=0,t;t=json.data[i];i++) {
                        if(json.data[i] && json.data[i].created){
                            json.data[i].createdstr = outputDateTime(json.data[i].created);
                        }else{
                            json.data[i].createdstr = '';
                        }
                        if(json.data[i] && json.data[i].published){
                            json.data[i].publishedstr = outputDateTime(json.data[i].published);
                        }else{
                            json.data[i].publishedstr = '';
                        }
                        videoData[json.data[i].vid] = json.data[i];
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
                    thirdpartySelector.videoInfo(id);
                });
            },
            dblclickHandler : function(id, tr){
                thirdpartySelector.check(id);
            }
        });
        window.setTimeout(function(){
            tableApp.load();
        }, 300);
        $("#created_min").DatePicker({'format':'yyyy-MM-dd'});
        $("#created_max").DatePicker({'format':'yyyy-MM-dd'});
        $('#treeall').bind('click', function(){
            $(this).toggleClass('active');
            $('#thp_tree').find('.active').removeClass('active');
            tableApp.load('catid=0');
        });
        $('#thp_tree').tree({
            url:"?app=video&controller=thirdparty&action=api&id="+tid+"&do=get_category&catid=%s",
            paramId : 'catid',
            paramHaschild:"hasChildren",
            renderTxt:function(div, id, item){
                return $('<span id="'+id+'">'+item.name+'</span>');
            },
            active : function(div, id, item){
                $('#treeall').removeClass('active');
                $('#keyword').val('');
                $('#created_min').val('');
                $('#created_max').val('');
                tableApp.load('catid='+id);
            }
        });
        $('#btn_search').click(function(){
            tableApp.load($('#search_f').serialize());
        });
        $('#playercode').bind('focus', function(){
            $(this).select();
        });
        $('#btn_ok').click(function(){
            thirdpartySelector.check(0);
        });
        $('#btn_cancel').click(function(){
            thirdpartySelector.cancel();
        });
        $('#thumb').mouseover(function(){
            $('.playicon').css('visibility', 'visible');
        }).mouseout(function(){
            $('.playicon').css('visibility', 'hidden');
        }).css('cursor', 'pointer').bind('click', function(){
            previewVideo($('#playercode').val());
        });
    },
    videoInfo: function(id){
        var i, key, fields = ['title','tags', 'time', 'publishedstr', 'createdstr', 'playcount', 'playercode', 'thumb'];
        if(videoData[id] == undefined){
            ct.error('第三方视频数据有误');
        }else{
            for (i in fields){
                key = fields[i];
                if(key == 'thumb'){
                    if(videoData[id][key]){
                        $('#'+key+' > img')[0].src = videoData[id].thumb;
                    }else{
                        $('#'+key+' > img')[0].src = 'images/novideo.png';
                    }
                }else if(key == 'playercode'){
                    if(videoData[id].playerparams){
                        $('#'+key).val('['+this.apiType+']'+videoData[id].playerparams+'[/'+this.apiType+']');
                    }else{
                        $('#'+key).val('');
                    }
                }else if(key == 'time'){
                    $('#'+key).html(formatSecond(videoData[id].time));
                }else if(key == 'playcount'){
                    $('#'+key).html(videoData[id].playcount);
                }else{
                    $('#'+key).html(videoData[id][key] + '&nbsp;');
                }
            }
        }
    },
    check: function(id){
        if(tableApp.checkedRow() === undefined || tableApp.checkedRow() == null || tableApp.checkedRow().find('input').val() == undefined){
            ct.warn('没有选项被选中');
            return;
        }
        var id = tableApp.checkedRow().find('input').val();
        var t = videoData[id];
        var r = {
            vid: t.vid,
            title: t.title,
            tags: t.tags,
            time: t.time,
            video: $('#playercode').val(),
            thumb: t.thumb
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
window.thirdpartySelector = thirdpartySelector;
})(jQuery);

function previewVideo(code){
    if(!code) return false;
    parent.ct.iframe({
        title:'?app=video&controller=thirdparty&action=preview_video&islive=0&width=530&height=450&code='+encodeURIComponent(code),
        width:530,
        height:475
    });
    return true;
}

function outputDateTime(timer){
    timer = parseInt(timer);
    if(!timer) return '';
    if(timer.length == 10){
        timer = timer * 1000;
    }
    var d = new Date(timer);
    if(!d) return '';
    return d.getFullYear() + '-'
        + str_pad(d.getMonth() + 1,0,2,0) + '-'
        + str_pad(d.getDate(),0,2,0) + ' '
        + str_pad(d.getHours(),0,2,0) + ':'
        + str_pad(d.getMinutes(),0,2,0);
}

/**
 * 补齐字符串位数
 *
 * @param str
 * @param pad
 * @param length
 * @param pos 0 左边，1 右边
 * @return string
 */
function str_pad(str,pad,length,pos){
    str = str.toString();
    if(str.length < length){
        if(pos==0){
            str = str_repeat(pad, length - str.length) + str;
        }else{
            str = str + str_repeat(pad, length - str.length);
        }
    }
    return str;
}

/**
 * 重复输出N次字符串
 *
 * @param str
 * @param num
 * @return string
 */
function str_repeat(str,num){
    if(num>1){
        for(var i=1;i<=num;i++){
            str = str + str.toString();
        }
    }
    return str;
}

function formatSecond(second)
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

