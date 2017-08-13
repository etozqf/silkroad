/**
 * Created by 郭Sir on 2017/4/26.
 */
// 搜索区块
$(".history-list").on("click","li",function () {//填充历史记录
    $(".search-input [name=wd]").val($(this).html());
    $(".history-list").hide();
});

$(".search-input .history:eq(0)").on({//展开历史记录
    "click":function(){
        $(".history-list").toggle();
    }
});

$(".search-input .history:eq(1)").on({//展开高级
    "click":function(){
        $(".category-list").toggle();
    }
});

$(".category-list label [type=checkbox]").on({//分类选择搜索
    "change":function () {
        $(this).parents("label").find("i.icon").toggleClass("on")
    }
});