/**
 * Created by 郭Sir on 2017/4/25.
 */

var maxData=4;

var searchFun={
    charDate:function () {//日期柱状图
        var myChart = echarts.init(document.getElementById('charDate'));
        var option = {
            color: ['#00A2C3'],
            tooltip : {
                trigger: 'axis',
                axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                    type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                }
            },
            grid: {
                left: '3%',
                right: '8%',
                bottom: '3%',
                top:"25px",
                containLabel: true
            },
            xAxis : [
                {
                    type : 'category',
                    data : ['2016-3', '', '', '', '','','', '','','','','', '2017-3'],
                    axisTick: {
                        alignWithLabel: true
                    }
                }
            ],
            yAxis : [
                {
                    type : 'value'
                }
            ],
            series : [
                {
                    name:'数据',
                    type:'bar',
                    barWidth: '60%',
                    data:['1','2', '0.6', '3', '2.1','2.1','2.1', '3.2','3.2','3.2', '2.6', '2.6', '2.6']
                }
            ]
        };
        myChart.setOption(option);
    },
    charSource:function () {//来源柱状图
        var myChart = echarts.init(document.getElementById('charSource'));
        var option = {
            color: ['#00A2C3'],
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                }
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                top:"20px",
                containLabel: true
            },
            xAxis: {
                type: 'value',
                show:false,
                boundaryGap: [0, 0.01]
            },
            yAxis: {
                type: 'category',
                data: ['商务部','人民网','新华网','商务部','人民网','新华网'],
            },
            series: [
                {
                    name: '数据量',
                    type: 'bar',
                    data: [18203, 23489, 29034, 29034, 29034, 29034],
                    barMaxWidth:"20px"
                }
            ]
        };
        myChart.setOption(option);
    },
    charAddr:function () {//地区柱状图
        var myChart = echarts.init(document.getElementById('charAddr'));
        var option = {
            color: ['#00A2C3'],
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                }
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                top:"20px",
                containLabel: true
            },
            xAxis: {
                type: 'value',
                show:false,
                boundaryGap: [0, 0.01]
            },
            yAxis: {
                type: 'category',
                data: ['武汉','襄阳','黄石','黄冈','仙桃','潜江'],
            },
            series: [
                {
                    name: '数据量',
                    type: 'bar',
                    data: [18203, 23489, 29034, 29034, 29034, 29034],
                    barMaxWidth:"20px"
                }
            ]
        };
        myChart.setOption(option);
    },
    updateDataHtml:function () {//更新已选数据
        var htmlTmp="";
        if(localStorage.preData.length>2){
            var dataTmp=JSON.parse(localStorage.preData);
            $(".sea-con-pre .tit span").html(dataTmp.length);
            for(var i in dataTmp){
                htmlTmp+='<li><a class="f-l text-overflow" href="'+dataTmp[i].link+'" target="_blank">'+dataTmp[i].title+'</a><i class="f-r icon2" data-id="'+dataTmp[i].id+'"></i><i class="clear"></i></li>';
            }
            $(".sea-con-pre ul.list").html(htmlTmp);
        }else{//清空html
            $(".sea-con-pre .tit span").html(0);
            $(".sea-con-pre ul.list").html("");
        }
    },
    delLocalData:function (id) {//删除浏览器指定数据
        var tmpData=JSON.parse(localStorage.preData);
        for (var i in tmpData){
            if(tmpData[i].id==id){
                tmpData.splice(i,1)
            }
        }
        localStorage.preData=JSON.stringify(tmpData)
    },
    updateListStatus:function () {//更新列表选中状态
        var tmpData=JSON.parse(localStorage.preData);
        $(".sea-con-list .con li").find("label [type=checkbox]").prop("checked",false);
        $(".sea-con-list .con li").find("i.icon").removeClass("on");
        for(var i in tmpData){
            $(".sea-con-list .con li").each(function () {
                if(tmpData[i].id==$(this).find("label [type=checkbox]").val()){
                    $(this).find("label [type=checkbox]").prop("checked",true);
                    $(this).find("label i.icon").addClass("on");
                }
            })
        }
    },
    allChangeStatus:function () {//控制全选
        var nullcheck=$(".sea-con-list .con li").length-$(".sea-con-list .con li").find("input[type=checkbox]:checked").length;//列表区剩余可选数
        if((JSON.parse(localStorage.preData).length)+nullcheck>maxData){//禁止全选
            $(".sea-con-list .filter label").css({"cursor":"no-drop"}).find("input[type=checkbox]").attr("disabled","disabled")
        }else {//可以全选
            $(".sea-con-list .filter label").css({"cursor":"default"}).find("input[type=checkbox]").removeAttr("disabled");
        }
    },
    isAllSelect:function () {//是否是全选状态
        var isallval=0;
        $(".sea-con-list .con li").each(function () {//判断是否是全选数据
            var _this=$(this).find("label input[type=checkbox]");
            var _data=JSON.parse(localStorage.preData);
            for(var i in _data){
                if(_data[i].id==_this.val()){
                    isallval++;
                }
            }
        });
        if(isallval==$(".sea-con-list .con li").length){//是全选状态
            $("#filter-all").prop("checked",true);
            $("#filter-all").parents("label").find("i.icon").addClass("on");
        }else{//非全选状态
            $("#filter-all").prop("checked",false);
            $("#filter-all").parents("label").find("i.icon").removeClass("on");
        }
    }


};
searchFun.charDate();
searchFun.charSource();
searchFun.charAddr();

if(!localStorage.preData){//页面重载加入已有数据
    localStorage.preData="[]";
}else{
    searchFun.isAllSelect();

    searchFun.updateListStatus();
    searchFun.updateDataHtml();
    searchFun.allChangeStatus();
}

$(".search-input .type [name=mode]").on({//搜索类型
    "change":function () {
        $(this).parents("label").siblings().find("i.icon").removeClass("on");
        $(this).parents("label").find("i.icon").addClass("on");
    }
});

// 选择数据区块
$(".sea-con-list .con li label [type=checkbox]").on({//选择列表数据【分条选择】
    "change":function () {
        if($(this).prop("checked")&&localStorage.preData.length>2&&(JSON.parse(localStorage.preData)).length>maxData-1){//数据溢出
            alert("文章选择不能超过"+maxData+"篇");
            $(this).prop("checked",!$(this).prop("checked"));
            return false;
        }
        if($(this).prop("checked")){//增加数据
            var preSendData={
                id:$(this).val(),
                title:$(this).parents("li").find(".txt a").text(),
                link:$(this).parents("li").find(".txt a").attr("href")
            };
            if((JSON.parse(localStorage.preData)).length>0){
                var tmpData=JSON.parse(localStorage.preData);
            }else{
                var tmpData=[];
            }
            tmpData.push(preSendData);
            localStorage.preData=JSON.stringify(tmpData)
        }else{//删除数据
            searchFun.delLocalData($(this).val());
        }
        $(this).parents("label").find("i.icon").toggleClass("on");
        searchFun.allChangeStatus();
        searchFun.updateDataHtml();
        searchFun.isAllSelect();
    }
});

$(".sea-con-list .filter label [type=checkbox]").on({//选择列表数据【全选】
    "change":function () {
        $(this).parents("label").find("i.icon").toggleClass("on");
        if($(this).prop("checked")){//全选
            var tmpData=[];
            if((JSON.parse(localStorage.preData)).length>0){//本地有数据，去重
                var hasData=true;
                tmpData=JSON.parse(localStorage.preData);
            }else{
                var hasData=false;
            }
            $(".sea-con-list .con li label [type=checkbox]").each(function () {
                $(this).prop("checked",true);
                $(this).parents("label").find("i.icon").addClass("on");
                if(hasData){//本地有数据，去重
                    var tmpData2=false;
                    for(var i in tmpData){
                        if(tmpData[i].id==$(this).val()){
                            tmpData2=true;
                        }
                    }
                    if(!tmpData2){
                        var preSendData={
                            id:$(this).val(),
                            title:$(this).parents("li").find(".txt a").text(),
                            link:$(this).parents("li").find(".txt a").attr("href")
                        };
                        tmpData.push(preSendData);
                    }
                }else{//本地无数据
                    var preSendData={
                        id:$(this).val(),
                        title:$(this).parents("li").find(".txt a").text(),
                        link:$(this).parents("li").find(".txt a").attr("href")
                    };
                    tmpData.push(preSendData);
                }
            });
            localStorage.preData=JSON.stringify(tmpData);

            searchFun.updateDataHtml();
        }else{//取消全选
            $(".sea-con-list .con li label [type=checkbox]").each(function () {
                $(this).prop("checked",false);
                $(this).parents("label").find("i.icon").removeClass("on");
                var tmpData=JSON.parse(localStorage.preData);
                for(var i in tmpData){
                    if(tmpData[i].id==$(this).val()){
                        searchFun.delLocalData($(this).val());
                        searchFun.updateDataHtml();
                    }
                }
            })

        }
    }
});

// 删除已有数据
$(".sea-con-pre ul.list").on("click","li i.icon2",function () {
    searchFun.delLocalData($(this).attr("data-id"));
    searchFun.updateListStatus();
    searchFun.updateDataHtml();
    searchFun.isAllSelect();
});

//清空所有选择的数据
$(".sea-con-pre .setnull").on({
    "click":function () {
        localStorage.preData="[]";
        searchFun.updateDataHtml();
        searchFun.updateListStatus();
        searchFun.isAllSelect();
    }
});