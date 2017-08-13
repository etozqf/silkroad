/**
 * Created by Administrator on 2016/9/7.
 */
$(document).ready(function(){
	function focusTxt() {
		var a0 = arguments[0][0],
			a1 = arguments[0][1];
		$('.' + a0).focus(function () {
			if (this.value == this.defaultValue) this.value = '';
		}).blur(function () {
			if (this.value == '') {
				this.value = this.defaultValue;
				if ($(this).is('.' + a1)) $(this).removeClass(a1);
			}
		}).keypress(function () {
			if (!$(this).is('.' + a1)) $(this).addClass(a1);
		});
	}
	focusTxt(['txt-focus']);
	//文字滚动
	scrollLeftToRight();
	//下拉列表
	var selects=$("#select");
	var options=$("#option");
	var state=true;
	selects.click(function(){
		if(state){
			if(!($(this).is(":animated"))){
			/*	$(".sel-em").removeClass("s-right");
				$(".sel-em").addClass("s-down");*/
				options.slideDown();
			}else{
				options.css("display","none");
			}
			state=false;
		}else{
			if(!($(this).is(":animated"))){
				//$(".sel-em").removeClass("s-down");
				//$(".sel-em").addClass("s-right");
				options.slideUp();
			}else{
				$(this).stop(true,true);
				options.css("display","");
			}
			state=true;
		}
	});
	$("#option li").click(function(){
		var op = $(this).attr("value");
		if(op=='中文'){window.location.href="http://db.silkroad.news.cn";}
		else if (op=='新华丝路网'){window.location.href="http://silkroad.news.cn";}
		// options.css("display","none");
		// selects.children("h3").text($(this).attr("tip"));
		// $(".valt").val($(this).attr("tip"));
		// state=false;
	});
	$("#option li").hover(function(){
		$(this).addClass("curr").siblings().removeClass("curr");
	});
	options.click(function(){
		selects.click(function(){return false;});
	});
	$()
	//导航菜单高亮
	var lis = $(".head-nav ul li");
	lis.hover(function () {
			els = $(this);
			els.addClass("curr").siblings().removeClass("curr");
		},
		function () {
			els = $(this);
			els.removeClass("curr");
		});
	var lis2 = $(".head-nav dd");
	lis2.hover(function () {
			els = $(this);
			els.addClass("curr").siblings().removeClass("curr");
		},
		function () {
			els = $(this);
			els.removeClass("curr");
		});
	//返回顶部
	$(".return_top").click(function(){
		$("html,body").animate({"scrollTop":0},1000);
	});

});
function scrollLeftToRight() {
	var $wrap = $('.scrollText');
	var $ul = $wrap.find('ul');
	var wrap_width = $wrap.width();
	var timer = null;
	var li_w = 0;

	$ul.find('li').each(function () {
		li_w += $(this).outerWidth();
	});

	if (li_w <= wrap_width) {
		return false;
	}

	$ul.css('width', li_w);

	var i = 0;
	var x = 0;

	function _marquee() {
		var _w = $ul.find('li:eq(0)').outerWidth();
		i++;
		if (i >= _w) {
			$ul.find('li:eq(0)').remove();
			i = 0;
			x = 0;
		} else {
			$ul.find('li:eq(0)').css('marginLeft', -i);
			if (i >= Math.max(_w - wrap_width, 0)) {
				if (x === 0) {
					var _li = $ul.find('li:eq(0)').clone(true);
					$ul.append(_li.css('marginLeft', 0));
					x = 1;
				}
			}
		}
		var _ul_w = 0;
		$ul.find('li').each(function () {
			_ul_w += $(this).outerWidth();
		});

		$ul.css('width', _ul_w);
	}
	timer = setInterval(_marquee, 40);
	$(".scrollText").hover(function () {
		clearInterval(timer);
	}, function () {
		timer = setInterval(_marquee, 40);
	});
}
//弹出框效果
function popbox(obj) {
	if (!(0 <= navigator.userAgent.indexOf("Firefox"))) {
		var a = navigator.appName,
			b = navigator.appVersion.split(";")[1];
		if ("Microsoft Internet Explorer" == a && "MSIE6.0" == b || "Microsoft Internet Explorer" == a && "MSIE7.0" == b) {
			height_e = $(document.body).height();
			$('.exoperation').css("height", height_e);
		}
	}
	$('.exoperation').show();
	$(obj).fadeIn({
		height: 'toggle'
	});
}
function popclose(obj) {
	$('.exoperation').hide();
	$(obj).hide();
}
//弹出居中
$(function () {
	var d = $(window).height();
	var c = $(".login-box,.subscribe-box,.reseach-subscribe").height();
	var b = (d - c) / 2
	$(".login-box,.subscribe-box,.reseach-subscribe").attr("style", "margin-top:" + b + "px");
})
