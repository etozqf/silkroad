var readmanage = {
    add : function(idtype) {
        ct.form('添加阅读权限','?app=member&controller=index&action=addreadmanage&idtype='+idtype+'&userid='+userid,480,300,function(response){
            if (!response.state){
                ct.tips(response.message, 'ok');
                setTimeout(function(){
                    window.location.href = '?app=member&controller=index&action=readmanage&idtype='+response.idtype+'&userid='+userid;
                }, 2000);
            }
            else{
                ct.tips(response.message, 'error');
                return false;
            }
            return true;
        },function(){
            return true;
        },function(){
            //var ids = '';
            //if(idtype == 'proid') {
            //    $.each($('.selectree [type="checkbox"]:checked').not(':disabled'), function(){
            //        ids += $(this).val() + ',';
            //    })
            //}
            //if(!ids) {
            //    ct.tips('请添加内容', 'error');
            //    return false;
            //}
            //$('[name="ids"]').val(ids);
            //return true;
        });
    },

    delete : function(id) {
        var mids = id ? id : '';
        if(!mids) {
            $.each($('#list_body [type="checkbox"]:checked'), function(){
                mids += ',' + $(this).val();
            })
        }
        if(!mids) {
            ct.tips('请选择你要删除的对象', 'error');
            return false;
        }
        $.ajax({
            type : 'post',
            url : '/?app=member&controller=index&action=deletereadmanage&userid='+userid,
            data : {'mids':mids},
            dataType : 'json',
            success : function(s) {
                if(s.state) {
                    ct.tips(s.message, 'error');
                } else {
                    ct.tips(s.message, 'ok');
                    setTimeout(function(){
                        window.location.reload();
                    }, 2000);
                }
            },
            error : function() {
                ct.tips('系统异常', 'error');
            }
        });
    },

    checkall : function(obj) {
        if($(obj).attr('checked')) {
            $('#list_body [type="checkbox"]').attr('checked', true);
        } else {
            $('#list_body [type="checkbox"]').attr('checked', false);
        }
    }
};