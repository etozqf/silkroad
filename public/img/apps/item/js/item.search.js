(function($){
// 使用的数据模板
var tpl = $.trim('<li>\
					<img src="{%thumb%}" width="100" height="133" alt="{%title%}">\
					<div class="main">\
						<h3><a href="{%url%}">{%title%}</a></h3>\
						<div class="intro">{%description%}</div>\
						<div class="info"><span><i>国家地区：</i>{%countryname%}</span><span><i>行业：</i>{%tradename%}</span></div>\
						<div class="time"><i>发布时间： </i>{%starttime%}<i>&nbsp;&nbsp;&nbsp;有效时间： </i>{%stoptime%}</div>\
					</div>\
					<div class="price"><b>{%itemsum%}</b><br>项目总投资</div>\
					<a href="{%gusturl%}" class="talk" style="display:none"></a>\
					<span class="fav" catid="{%catid%}" id="collect_{%contentid%}" contentid="{%contentid%}" style="display:none">收藏</span>\
				</li>');
$.fn.itemSearch = function(url,data){
	if(!data){data=""};
	var url = url+data+"&pagesize=5&page=%p&jsoncallback=?"
    // // 分页查询数据
    $("#tablelist").tablelist({
      baseurl:url,
      pager:".page",
      template:tpl,
      pagesize:5,
    });
};

$.fn.itemSearch(url);
})(jQuery);
(function($){
// 项目选择
$(".proje_seachblock dl:gt(2)").hide();
$(".moredl").click(function(){
	$(this).toggleClass("on");
	if($(this).hasClass("on")){ 
		$(".proje_seachblock dl:gt(2)").show();
		$(this).children(".ico").css("background-position","0 -7px");
	}else{  
		$(".proje_seachblock dl:gt(2)").hide();
		$(this).children(".ico").css("background-position","0 0"); 
	}
});
//时间排序
$('.sortbox li').click(function(){
	if($(this).attr('dataid')=='desc'){
		$(this).attr('dataid','asc');
		$(this).children().attr('class','down');
	}else{
		$(this).attr('dataid','desc');
		$(this).children().attr('class','up');
	}
	submitData();
});
var btns = "<div class='btndiv'><input type='submit' value='确定' class='submitbtn'><input type='reset' value='取消' class='resetbtn'></div>";
//当直接点击分类属性时触发
$("dd.option label").click(function(){
	//去掉其它大分类的多选样式
	if($(this).parents("dl").siblings('.active').length>0){
		var onlength = $(this).parents("dl").siblings('.active').find('dd:nth-child(3)').find('.on').length;
		if(onlength==0){$(this).parents("dl").siblings('.active').find(".all").find("span").addClass("on");};
		$(this).parents("dl").siblings(".active").removeClass("active");
	}
	//当点击自定义时间时触发
	if($(this).hasClass("liberty")){
		dateLiberty($(this));
		//点击取消按钮
		$(".resetbtn").on("click",function(){
			onAll($(this));
		});
		// 点击确定按钮
		$(".submitbtn").on("click",function(){
			if($(this).parent().siblings(".on").length==0)
			{
				onAll($(this));
			}
			submitData();//提交选项数据
		});
		return false;
	}
	//当单机普通分类触发
	if($(this).parents("dl").hasClass("active")){
		// 多为多选状态时触发
		$(this).toggleClass("on");
		return false;
	}else{
		//当为单选状态时触发
		$(this).addClass("on").siblings().removeClass("on");
	}
	$(this).parents("dl").find(".all > span").removeClass("on");
	$(this).parents("dl").find(".choosedate").hide().find(".on").removeClass("on");
	submitData();//提交选项数据
})
// 点击多选按钮
$(".proje_seachblock .morebtn").click(function(){
	onMore($(this));
	//点击取消按钮
	$(".resetbtn").on("click",function(){
		onAll($(this));
	});
	// 点击确定按钮
	$(".submitbtn").on("click",function(){
		if($(this).parent().siblings(".on").length==0)
		{
			onAll($(this));
		}
		submitData();
	});
});

// 点击"不限"
$(".proje_seachblock .all span").click(function(){
	if($(this).hasClass("on")) return false;
	onAll($(this));
	submitData();
});
//选择时间范围框是
$(".porj_diminput").click(function(){
	if($(".btndiv").length==0) $(this).parents("#DdDate").append(btns);
	//取消绑定事件
	$(".resetbtn").off("click");
	$(".submitbtn").off("click");
	// 点击取消按钮
	$(".resetbtn").on("click",function(){
		onAll($(this));
	});
	// 点击确定按钮
	$(".submitbtn").on("click",function(){
		submitData();
	});
});
//多选
var onMore = function(t) {
	if($(".btndiv").length>0){
		if($(".btndiv").parents("dl").find("dd:nth-child(3)").children(".on").length==0)
		{
			$(".btndiv").parents("dl").find(".all span").addClass("on");
		}
		$(".btndiv").remove();
	}
	var t = t.parents("dl");
	t.siblings().removeClass("active");
	$(".btndiv").remove();
	t.addClass("active");
	t.find(".all").children().removeClass("on");
	t.find("dd:nth-child(3)").append(btns);
	var h = t.height();
	t.find(".line").height(h+6);
};
//不限制方法
var  onAll = function(t){
	var t = t.parents("dl");
	if(t.find(".choosedate").length>0) t.find(".choosedate").hide();
	t.removeClass("active").siblings().removeClass("active");
	t.find(".on").removeClass("on");
	t.find(".all").children().addClass("on");
	t.find(".btndiv").remove();
};
//点击自定义时间时执行
var dateLiberty = function(t){
	$(".btndiv").remove();
	t.parents("dl").find(".all > span").removeClass("on");
	t.parents("dl").removeClass("active");
	t.siblings().removeClass("on");
	t.next().children().addClass("on");
	t.next().show();
	t.parent().append(btns);
	t.addClass("on");
	
};
//数据提交方法
var submitData = function(){
	$(".proje_seachblock").find(".bd dl").removeClass("active");
	if($(".btndiv").length>0) $(".btndiv").remove();
	$('.porj_diminput').each(function(){
		$(this).attr("dataid",$(this).val());
	});
	var data = '';
	var m=1;
	$(".proje_seachblock .bd .on,.sortbox .on").each(function(){

		var name = $(this).attr("dataname");
		var value = $(this).attr("dataid");
		if(value != 0 && value!= undefined && value != '' && value != null){
			if(name=="starttime" || name=="stoptime"){
				if(value=="2015" || value=="2016"){
					var value1 = new Date(value);
					var value2 = new Date(value+"/12/31 23:59:59");
					data += "&"+m+"="+name+"_"+value1.getTime()+"&"+(++m)+"=stoptime_"+value2.getTime();
				}else if(value == "desc" || value=="asc"){
					//不进行重新赋值
					data += "&"+m+"="+name+"_"+value;
				}else{
					value = new Date(value.replace(/-/g,'/')).getTime();
					data += "&"+m+"="+name+"_"+value;
				}
			}else{
				data += "&"+m+"="+name+"_"+value;
			}
			m++;
		}
	});
	$.fn.itemSearch(url,data);
};
})(jQuery);