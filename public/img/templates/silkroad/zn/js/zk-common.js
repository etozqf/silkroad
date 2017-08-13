//tab
$(function(){
    // $(".n_ind_contitle li").bind("click",function(){
    //     var index = $(this).index();
    //     $(this).addClass("n_ind_contitle_on").siblings().removeClass("n_ind_contitle_on");
    //     $(".n_ind_contmain .news").eq(index).show().siblings().hide();
    // })

    $("#tab-title-1 li").hover(
    	function (){
    		var index = $(this).index();
    		$(this).addClass('check').siblings('li').removeClass('check');
    		$("#rank ul").eq(index).css('display','block').siblings().css('display','none');
    		
    	}
    )
})