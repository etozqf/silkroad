/**
 * Created by Administrator on 2016/3/3.
 */
$(function () {
	// 项目选择
	$(".proje_seachblock dl:gt(1)").hide();
	$(".moredl").click(function () {
		$(this).toggleClass("on");
		if ($(this).hasClass("on")) {
			$(".proje_seachblock dl:gt(1)").show();
			$(this).children(".ico").css("background-position", "0 -7px");
		} else {
			$(".proje_seachblock dl:gt(1)").hide();
			$(this).children(".ico").css("background-position", "0 0");
		}
	});
	var btns = "<div class='btndiv'><input type='submit' value='OK' class='submitbtn'><input type='reset' value='Cancel' class='resetbtn'></div>";
	$(".morebtn").click(function () {
		$(".proje_seachblock dl").removeClass("active");
		$(this).parents("dl").addClass("active");
		$(this).parents("dl").find("ul").addClass("option-ul");
		$(".btndiv").remove();
		$(this).parent(".option").append(btns);
		// 取消按钮
		$(".resetbtn").on("click", function () {
			$(this).parents("dl").removeClass("active");
			$(this).parents("dl").find("ul").removeClass("option-ul");
			$(this).parents("dl").find(".all span").addClass("on");
			$(this).parents("dd").find("label").removeClass("on");
			$(".btndiv").remove();
		})
		// 边框线
		var h = $(this).parents("dl").height();
		$(this).parents("dl").find(".line").height(h + 6);
		// 不限
		$(this).parents("dl").find(".all > span").removeClass("on");
	});
	// 发布时间
	$("#DdDate label").click(function () {
		var last = $("#DdDate label").length - 1;
		if ($(this).index() == last) {
			$(".choosedate").show();
		} else {
			$(".choosedate").hide();
		}
	});
	// 点击加红
	$("dd.option label").click(function () {
		if ($(this).parents("dl").hasClass("active")) {
			$(this).toggleClass("on");
			return false;
		} else {
			$(this).addClass("on").siblings().removeClass("on");
		}
		;
		$(this).parents("dl").find(".all > span").removeClass("on");

	});
	// 不限点击加红
	$("dd.all span").click(function () {
		$(this).addClass("on");
		$(this).parents("dl").find(".option  label").removeClass("on");
		if ($(this).parents("dl").hasClass("active")) {
			$(this).parents("dl").find("ul").removeClass("option-ul");
			$(this).parents("dl").removeClass("active");
			$(".btndiv").remove();
		}
	});
	//排序查询
	$(".auto-box ul li").click(function () {
		var index = $(".auto-box ul li").index(this);
		$(this).addClass("active").siblings().removeClass("active");
		if ($("#li_" + index + "").val() == "0") {
			$("#li_" + index + "").val("1");
			$(this).find("span").removeClass("down").addClass("up");
		}else if($("#li_" + index + "").val() == "1"){
			$("#li_" + index + "").val("2");
			$(this).find("span").removeClass("up").addClass("down1");
		}else{
			$("#li_" + index + "").val("0");
			$(this).find("span").removeClass("down1").addClass("up");
		}
	});
})