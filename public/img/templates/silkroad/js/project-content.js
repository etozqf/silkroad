$(function(){
    var i = 5;
    var len = $(".thumb li").length;
    var totalPage = Math.ceil(len/i);
    var page = 0;
    var w = $(".thumb .box").width();
    $(".thumb li").on("click",function(){
        var src = $(this).children("img").attr("src");
        $(this).addClass("on").siblings().removeClass("on");
        $("#bigPic").attr("src",src);
    })
    $(".goleft").on("click",function(){
        if(page == totalPage-1){
            return false;
        }else{
            page++;
            $(".thumb ul").animate({"left":-w*page},500);
        }
    })
    $(".goright").on("click",function(){
        if(page == 0){
            return false;
        }else{
            page--;
            $(".thumb ul").animate({"left":-w*page},500);

        }
    })
    // 收藏
    $(".fav").click(function(){
        $(this).toggleClass("on");
        if($(this).parent().hasClass("info")){
            if($(this).hasClass("on")){
                $(this).text("取消收藏")
            }else{
                $(this).text("收藏")
            }
        }
        return false;
    })
    // tab
    $(".detail-tabbox .tab li").bind("click",function(){
        $(this).addClass("on").siblings("li").removeClass("on");
        var index = $(this).index();
        $(".detail-tabbox .tabcon").eq(index).show().siblings(".tabcon").hide();
    })
})