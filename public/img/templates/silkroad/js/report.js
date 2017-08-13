/**
 * Created by Administrator on 2016/3/2.
 */
$(function () {
	/**
	 *        幻灯片
	 */
	$("#AllCheck").click(function () {

		if ($(this).prop("checked")) {
			$("#DCountryTree").find("input[type=checkbox]").prop("checked", true);
		}
		else {
			$("#DCountryTree").find("input[type=checkbox]").prop("checked", false);
		}
	});
	//一级菜单展开与关闭
	$(".rc-list dd .b-close").click(function () {
		//获取+、-符号
		var ico = $(this).html();
		//获取二级隐藏菜单
		var box = $(this).parent().find(".hidden-box");
		//获取二级隐藏菜单的总高度
		var box_height = box.height();
		//获取父级dd
		var boxP = $(this).parent();
		//计算整体dd的高度
		var zongH = 40 + box_height;
		if (box.length <= 0) {
			//没有二级菜单展开文字
			var gjml_tagsub = $(this).parent().find(".gjml_tagsub");
			if (gjml_tagsub.length > 0) {
				if (ico == "+") {
					var erH = gjml_tagsub.height() + 80;
					$(this).parent().animate({height: "" + erH + "px"}, 500);
					gjml_tagsub.fadeIn(500);
					$(this).html("-");
				} else {
					$(this).parent().animate({height: "40px"}, 500);
					gjml_tagsub.fadeOut(500);
					$(this).html("+");
				}
			}
		} else {
			if (ico == "+") {
				//修改父级dd的高度
				boxP.animate({height: "" + zongH + "px"}, 500);
				box.show(500);
				$(this).html("-");
			}
			else {
				boxP.animate({height: "40px"}, 500);
				box.hide(500);
				$(this).html("+");
			}
		}
	});
	//二级菜单展开与关闭
	$(".b-close1").click(function () {
		var ico1 = $(this).html();
		var gjml_tagsub = $(this).parent().find(".gjml_tagsub");
		var erH = gjml_tagsub.height() + 80;
		//获取二级隐藏菜单
		var box = $(this).parents(".hidden-box");
		//获取二级隐藏菜单的总高度
		var box_height = box.height();
		//计算整体dd的高度
		var zongH = 20 + box_height;
		var Total_h = zongH + erH;
		//获取二级隐藏菜单
		var boxD = $(this).parents('dd');
		if (ico1 == "+") {
			$(this).parent().animate({height: "" + erH + "px"}, 500);
			boxD.animate({height: "" + Total_h + "px"}, 500);
			gjml_tagsub.fadeIn(500);
			$(this).html("-");
		} else {
			$(this).parent().animate({height: "" + 40 + "px"}, 500);
			boxD.animate({height: "" + (Total_h - zongH + 40) + "px"}, 500);
			gjml_tagsub.fadeOut(500);
			$(this).html("+");
		}
	});
	//鼠标经过按钮
	var lis = $(".rc-list dd .b-close,.hidden-box ul li .b-close1");
	lis.hover(function () {
			els = $(this);
			els.addClass("curr").siblings().removeClass("curr");
		},
		function () {
			els = $(this);
			els.removeClass("curr");
		});

	$(".bigMenu").click(function () {
		if ($(this).prop("checked")) {
			$(this).parents("dd").find("input[type=checkbox]").prop("checked", true);
		}
		else {
			$(this).parents("dd").find("input[type=checkbox]").prop("checked", false);
		}
	});
})
