/**
 * Created by Administrator on 2016/8/30.
 */

$(function () {
	//列表鼠标经过样式
	$(".summary-list li").click(function () {
		$(this).addClass("on");
	});
	// $(".summary-list li").mouseleave(function () {
	// 	$(this).removeClass("on");
	// });
       //tab菜单切换
	// cmstopTabs({
	// 	tabStyle: 'on',
	// 	title   : '#tabm1',
	// 	cont    : "#tabd1"
	// });
});
//选项卡
function cmstopTabs(o) {
	var tit = $(o['title']),
		cont = $(o['cont']),
		tabsty = o['tabStyle'];
	var tits = tit.find('li'),
		conts = cont.find('.box-show');
	tits.click(function () {
		var index = tits.index($(this));
		$(this).addClass(tabsty).siblings().removeClass(tabsty);
		$(conts[index]).show().siblings().hide();
		return false;
	});
}
