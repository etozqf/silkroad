(function($){
// 使用的数据模板
var tpl = $.trim('<li>\
					<div class="ov bor-box">\
						<a href="{%url%}" target="_blank" class="f-l">\
							<img src="{%thumb%}" width="132" height="100" alt="{%title%}"/>\
						</a>\
						<div class="del-box">\
							<h3>\
								<a href="{%url%}" target="_blank">{%title%}</a>\
							</h3>\
							<div class="del-mid">\
								<p class="del-box-p">{%description%}</p>\
								<div class="del-btn">\
									<div class="Amount">\
										<span> <span class="fon-20 cor-e43">{%itemsum%}</span></span>\
										<span>Investment Amount</span>\
									</div>\
									<a class="btn" target="_blank" href=""></a>\
								</div>\
							</div>\
							<div class="del-bottom">\
								<p class="del-box-p">\
									<span><b>Country：</b>{%countryalias%}</span><span><b>Industry：</b>{%tradealias%}</span>\
									<span><b>Valid Until：</b>{%stoptime%}</span>\
								</p>\
								<div class="info limit">\
									<span id="collect_{%contentid%}" class="fav ie6_png32" catid="{%catid%}" contentid="{%contentid%}">Bookmark</span>\
								</div>\
							</div>\
						</div>\
					</div>\
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
$('.auto-box ul li').click(function(){
	$(this).siblings().find('.on').removeClass('on');
	if($(this).children().attr('dataid')=='desc'){
		$(this).children().attr('dataid','asc');
		$(this).children().attr('class','down on');
	}else{
		$(this).children().attr('dataid','desc');
		$(this).children().attr('class','up on');
	}
	submitData();
});
var btns = "<div class='btndiv'><input type='submit' value='OK' class='submitbtn'><input type='reset' value='Cancel' class='resetbtn'></div>";
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
			if($(this).parents('dd').find(".on").length==0)
			{
				onAll($(this));
			}
			submitData();//提交选项数据
		});
		return false;
	}
	//当单机普通分类触发
	if($(this).parents("dl").hasClass("active")){
		// 为多选状态时触发
		$(this).toggleClass("on");
		return false;
	}else{
		//当为单选状态时触发
		$(this).addClass("on").parent().siblings().children().removeClass("on");
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
		if($(this).parents('dd').find(".on").length==0)
		{
			onAll($(this));
		}
		submitData();//提交选项数据
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
		if($(".btndiv").parents("dl").find("dd:nth-child(3)").find(".on").length==0)
		{
			$(".btndiv").parents("dl").find(".all span").addClass("on");
		}
		$(".btndiv").remove();
	}
	var t = t.parents("dl");
	t.siblings().removeClass("active");
	t.addClass("active");
	t.find(".all span").removeClass("on");
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
	t.parents("dl").find('.choosedate input').addClass("on");
	t.addClass("on");
	t.parents("dl").find('.choosedate').show();
	t.parents("dl").find("dd:nth-child(3)").append(btns);
	
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
	$(".proje_seachblock .bd .on,.auto-box .on").each(function(){
		var name = $(this).attr("dataname");
		var value = $(this).attr("dataid");
		if(value != 0 && value != undefined && value != '' && value != null){
			if((name=="starttime" || name=="stoptime") && value != "desc" && value!= "asc"){
					value = new Date(value.replace(/-/g,'/')).getTime();
					data += "&"+m+"="+name+"_"+value;
			}else{
				data += "&"+m+"="+name+"_"+value;
			}
			m++;
		}
	});
	$.fn.itemSearch(url,data);
};
})(jQuery);