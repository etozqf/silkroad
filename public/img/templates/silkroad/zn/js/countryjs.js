//一键生成报告
function makePdf()
{
    var arrChk=$("input[name='contentid']:checked");
    var contentids = '';
    var proid = $("input[name='proid']").val();
    $(arrChk).each(function(){
        var v = this.value
        if (v)
        {
            contentids += v + ',';  
        }                      
    });
    if (!contentids)
    {
        var d = dialog({
            title: '提示',
            width: 250,
            content: '请先选中需要作为报告素材的国别资料！',
            okValue: '确定',
            ok: function () {
                this.close();
                return false;
            }
        }).show();
        return;
    }

   
    var d2 = dialog({
        title: '提示',
        content: "您好，确定开始提交制作国别报告？",
        width: 250,
        okValue: '开始制作',
        cancelValue: '取消',
        ok: function () {
            GoDoReport(contentids, proid);
            this.close().remove();
            return false;
        },
        cancel: function () {
            this.close().remove();
            return false;
        }
    });
    d2.show();
}

function GoDoReport(contentids, proid)
{
    openLoading(true, "正在生成国别报告，请稍候..");
   $.post(''+APP_URL+'/?app=country&controller=index&action=makepdf', {
            'contentids': contentids, 
            'proid': proid
        }, function (data, textStatus) {

        colseLoading(true);
        /*返回结果*/
        if (data.state) 
        {
            //后台返回结果
            var vlurl = decodeURIComponent(data.data);
            var d = dialog({
                title: '提示',
                width: 250,
                content: '您好，国别报告已完成，请点击下载！',
                okValue: '下载',
                ok: function () {
                    window.open(vlurl, "_blank");
                    this.close();
                    return false;
                }
            }).show();

        }
        else {

            if (data.message == "nologin") {

                alert('请重新登录系统');
                window.location.href = WWW_URL;
            }
            else {
                //异常报告
                vl = decodeURIComponent(data.message);
                alert("生成国别报告发生错误:" + vl);
            }
        };
    }, "json");
}


function openNode(rthis)
{
    var st = $(rthis).find(".gjml_but").html();
    if (st == "+")
    {
        //说明需要展开
        $(rthis).find(".gjml_but").html("-");
        $(rthis).parent('dt').next("dd").show(500);
        
    }
    else 
    {
        //说明需要闭合
        $(rthis).parent('dt').next("dd").hide(500);
        $(rthis).find(".gjml_but").html("+");
        
    }    
}

