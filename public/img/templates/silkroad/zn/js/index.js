/**
 * Created by Administrator on 2016/8/2.
 */
$(function () {
	//中国园区、外国园区
	var bool = false;
	$(".n_ind_contitle li").bind("click", function () {
		var index = $(this).index();
		$(this).addClass("n_ind_contitle_on").siblings().removeClass("n_ind_contitle_on");
		if(index==1 && bool==false){
			$('#map_world_container').attr('src',$('#map_world_container').attr('src'));
			bool=true;
		}
		$(".n_ind_contmain .news").eq(index).show().siblings().hide();
	});
	//二级tab菜单1
	$(".tab-menu1 li").bind("click", function () {
		var index = $(this).index();
		$(this).addClass("li-on").siblings().removeClass("li-on");
		$(".tabData .tab-data1").eq(index).show().siblings().hide();
	});
	//二级tab菜单2
	$(".tab-menu2 li").bind("click", function () {
		var index = $(this).index();
		$(this).addClass("li-on").siblings().removeClass("li-on");
		$(".tabData .tab-data2").eq(index).show().siblings().hide();
	});
	$(".jia").click(function () {
		$(".db-right").show();
	});
	$(".tab-data1 li,.tab-data2 li").click(function(){
		$(this).addClass("yq-on").siblings().removeClass("yq-on");
	})
})
