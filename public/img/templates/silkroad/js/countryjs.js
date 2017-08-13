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
            title: 'Confirm',
            width: 250,
            content: 'Please select at least one item by clicking the square ahead of item title.',
            okValue: 'OK',
            ok: function () {
                this.close();
                return false;
            }
        }).show();
        return;
    }

   
    var d2 = dialog({
        title: 'Confirm',
        content: "Ready to create a PDF file?",
        width: 250,
        okValue: 'Create',
        cancelValue: 'Cancel',
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
    openLoading(true, "The report is being generated,please wait...");
   $.post(''+APP_URL+'/?app=country&controller=index&action=en_makepdf', {
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
                title: 'Confirm',
                width: 250,
                content: 'A PDF fill is created, please download it.',
                okValue: 'OK',
                ok: function () {
                    window.open(vlurl, "_blank");
                    this.close();
                    return false;
                }
            }).show();

        }
        else {

            if (data.message == "nologin") {

                alert('Please re login system');
                window.location.href = EN_WWW_URL;
            }
            else {
                //异常报告
                vl = decodeURIComponent(data.message);
                alert("Country report error:" + vl);
            }
        };
    }, "json");
}


function openNode(rthis,aparentetype)
{
    var st = $(rthis).find(".gjml_but").html();
    var ad = $(rthis).parent(aparentetype).next("dd").children('.gjml_tagsub').html();
    if (st == "+")
    {
        //说明需要展开
        if (ad)
        {
            $(rthis).find(".gjml_but").html("-");
            $(rthis).parent(aparentetype).next("dd").show(500);
        }
    }
    else 
    {
        //说明需要闭合
        if (ad)
        {
            $(rthis).parent(aparentetype).next("dd").hide(500);
            $(rthis).find(".gjml_but").html("+");
        }
    }    
}

