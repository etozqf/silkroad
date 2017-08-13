/// <reference path="~/script/jquery-1.9.1-vsdoc.js" />
var appCountryName = "";
$(function () {

    var countrycode = requestJs("code");
    var countryname = "";
    if (countrycode == undefined || countrycode == null || countrycode == "")
    {
        countrycode = "PK";
        countryname = "巴基斯坦";
    }
    else {
        countrycode = countrycode.toUpperCase();
    }
     
    var isFindCountry = false;
    $.ajaxSettings.async = false;
    $.getJSON("dotfile/CountryCollect.json", function (data) {


        $.each(data, function (i, item) {
            var CCode = item["CCode"];
            if(CCode == countrycode)
            {
                isFindCountry = true;
                $("#DCountryFlag").html("<img src=\"flag/" + item["FlagName"] + "\" style=\"width:195px;height:130px;\" />");
                $("#DCountryName").html(item["Name"] + "<p>" + item["EName"] + "</p>");
                countryname = item["Name"];
                document.title = "新华丝路数据库 _ " + countryname;
                $("#ScountryName").html(countryname);
                return false;
            }

        })

    });
    $.ajaxSettings.async = true;
   
    if(!isFindCountry)
    {
        alert("未找到【" + countrycode + "】代码的国家资料！");
        return;
    }

    $("#AllCheck").click(function () {

        if($(this).prop("checked"))
        {
            $("#DCountryTree").find("input[type=checkbox]").prop("checked", true);
        }
        else {
            $("#DCountryTree").find("input[type=checkbox]").prop("checked", false);
        }
    });

    /*设置右侧栏目的更多Href链接*/
    $("#MoreBestNewA").attr("href", "ZxWeb.htm?code=" + countrycode);
    $("#MoreBestProjectA").attr("href", "ProjectList.htm?code=" + countrycode);
    $("#MoreTZALA").attr("href", "TzalWeb.htm?code=" + countrycode);

    appCountryName = countryname;

    LoadCountryTree(countrycode, countryname);

    LoadCountryProjects(countrycode);

    //加载最新资讯
    LoadCountrySomePartData(countrycode, 1503, 5, "ULCountryBestNews");

    //加载投资案例
    LoadCountrySomePartData(countrycode, 1505, 5, "ULCountryTouZiAnLi");

    /*countrycode国家代码,count需要条数,fillid填充元素的Id*/
    LoadTJExpertForCountryZHP("", 4, "ULRecomExpert")
});


String.prototype.endWith = function (endStr) {
    var d = this.length - endStr.length;
    return (d >= 0 && this.lastIndexOf(endStr) == d)
}

//加载国家导航树
function LoadCountryTree(code, countryname)
{
    $("#DCountryTree").html("<div style=\"text-align:center;\"><img src=\"images/load.gif\" style='vertical-align:middle'> 加载中，请稍候..</div>");
    $.post('AshxAllEvent/CountryComplex.ashx', {
        type: "loadcountrytree",
        countrycode: code,
        countryname: countryname
    }, function (data, textStatus) {
        /*返回结果*/
        if (data.Do) {
            //后台返回结果
            var Func = data.Func;
            if (Func === "")
            {
                $("#ACreateReport").remove();//移除一键生成报告按钮
            }
            else {
                $("#ACreateReport").attr("onclick", Func);
                $("#ACreateReport").show();
            }

            var vl = decodeURIComponent(data.Val);
            $("#DCountryTree").html(vl);
            $("#ULCountryTZJY").html(decodeURIComponent(data.TZJY));

            $('input[id^="ch_"]').click(function () {

                if($(this).prop("checked"))
                {
                    if (String($(this).attr("id")).endWith("_true"))
                    {
                        //console.log("打勾23");
                        //说明有子节点，则把子节点也打上勾
                        $(this).parents("dt").next("div").find("input[type=checkbox]").prop("checked", true);
                    }
                    if (String($(this).attr("id")).endWith("_false2")) {
                        //说明是底层子节点，则把子节点打上勾同时父节点也必须打勾
                        $(this).parents("dd").parent("div").prev("dt").find("input[type=checkbox]").prop("checked", true);
                    }
                }
                else {
                    if (String($(this).attr("id")).endWith("_true")) {
                        //console.log("打勾23");
                        //说明有子节点，则把子节点也移除勾
                        $(this).parents("dt").next("div").find("input[type=checkbox]").prop("checked", false);
                    }
                    
                }

            });
        }
        else {

            if (data.Val == "timeout") {

                alert('请重新登录系统');
                window.location.href = "Index.htm";
            }
            else {

                //异常报告
                vl = decodeURIComponent(data.Val);
                alert("请求发生错误！");
            }
        };
    }, "json");
}


/*展开或关闭TRee*/
//aparentetype为点击展开A元素的父节点的类型，如：dt，dd
function openNode(cid,haschild,rthis,aparentetype)
{
    var st = $(rthis).find(".gjml_but").html();

    if(haschild)
    {
        //有子节点
        
        if(st == "+")
        {
            //console.log("说明需要展开");
            //说明需要展开
            $(rthis).parent(aparentetype).next("div").show(500);
            $(rthis).find(".gjml_but").html("-");
        }
        else {
            //说明需要闭合
            $(rthis).parent(aparentetype).next("div").hide(500);
            $(rthis).find(".gjml_but").html("+");
        }
    }
    else {
        //无子节点
        //console.log(st);

        if (st == "+")
        {
            //console.log("说明需要展开");
            //说明需要展开
            $(rthis).find(".gjml_but").html("-");

            //判断填充内容是否已经存在
            var content = $(rthis).parent(aparentetype).next("dd").find(".gjml_tagsub").html();
            //console.log(content);
            if (content == "") {
                console.log("去异步加载");
                var em = $(rthis).parent(aparentetype).next("dd").find(".gjml_tagsub")[0];
                $(rthis).parent(aparentetype).next("dd").show(500);
                $(em).html("<div style=\"text-align:center;\"><img src=\"images/load.gif\" style='vertical-align:middle'> 加载中，请稍候..</div>");
                //去异步加载
                LoadTreeContentById(cid,em);
            }
            else {
                $(rthis).parent(aparentetype).next("dd").show(500);
            }

        }
        else {
            //说明需要闭合
            $(rthis).parent(aparentetype).next("dd").hide(500);
            $(rthis).find(".gjml_but").html("+");
        }


       
    }
}

//根据编号加载树节点正文内容
function LoadTreeContentById(cid,em)
{
    $.post('AshxAllEvent/CountryComplex.ashx', {
        type: "loadtreenodecontent",
        cid: cid
    }, function (data, textStatus) {
        /*返回结果*/
        if (data.Do) {
            //后台返回结果
            var vl = decodeURIComponent(data.Val);
            //console.log(vl);
            $(em).html(vl);
        }
        else {

            if (data.Val == "timeout") {

                alert('请重新登录系统');
                window.location.href = "Index.htm";
            }
            else {

                //异常报告
                vl = decodeURIComponent(data.Val);
                alert("请求发生错误！");
            }
        };
    }, "json");
}

//一键生成报告
function CreateReport()
{
    var arr = new Array();
    $('input[id^="ch_"]').each(function () {

        if($(this).prop("checked"))
        {
            arr.push($(this).val());
        }
    });

    if (arr.length == 0)
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
            GoDoReport(arr);
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

function GoDoReport(arr)
{
    openLoading(true, "正在生成国别报告，请稍候..");
    $.post('AshxAllEvent/CountryComplex.ashx', {
        type: "createreport",
        countryname: appCountryName,
        cid: arr.join('|')
    }, function (data, textStatus) {

        colseLoading(true);
        /*返回结果*/
        if (data.Do) {
            //后台返回结果
            var vlurl = decodeURIComponent(data.Val);
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

            if (data.Val == "timeout") {

                alert('请重新登录系统');
                window.location.href = "Index.htm";
            }
            else {

                //异常报告
                vl = decodeURIComponent(data.Val);
                alert("生成国别报告发生错误:" + vl);
            }
        };
    }, "json");
}

/*根据Url获取参数*/
function requestJs(paras) {
    var url = location.href;
    var paraString = url.substring(url.indexOf("?") + 1, url.length).split("&");
    var paraObj = {}
    for (i = 0; j = paraString[i]; i++) {
        paraObj[j.substring(0, j.indexOf("=")).toLowerCase()] = j.substring(j.indexOf("=") + 1, j.length);
    }
    var returnValue = paraObj[paras.toLowerCase()];
    if (typeof (returnValue) == "undefined") {
        return "";
    } else {
        return returnValue;
    }
}

