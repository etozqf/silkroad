jQuery.fn.banqh = function(can){
	can = jQuery.extend({
					box:null,//总框架
					pic:null,//大图框架
					pnum:null,//小图框架
					prev_btn:null,//小图左箭头
					next_btn:null,//小图右箭头
					autoplay:false,//是否自动播放
					interTime:3000,//图片自动切换间隔
					order:0,//当前显示的图片（从0开始）
					picdire:true,//大图滚动方向（true水平方向滚动）
					mindire:true,//小图滚动方向（true水平方向滚动）
					min_picnum:null//小图显示数量
				}, can || {});
	var picnum = jQuery(can.pic).find('ul li').length;
	var picw = jQuery(can.pic).find('ul li').outerWidth(true);
	var pich = jQuery(can.pic).find('ul li').outerHeight(true);
	var poppicw = jQuery(can.pop_pic).find('ul li').outerWidth(true);
	var picminnum = jQuery(can.pnum).find('ul li').length;
	var picpopnum = jQuery(can.pop_pic).find('ul li').length;
	var picminw = jQuery(can.pnum).find('ul li').outerWidth(true);
	var picminh = jQuery(can.pnum).find('ul li').outerHeight(true);
	var pictime;
	var tpqhnum=0;
	var xtqhnum=0;
	jQuery(can.pic).find('ul').width(picnum*picw).height(picnum*pich);
	jQuery(can.pnum).find('ul').width(picminnum*picminw).height(picminnum*picminh);
	jQuery(can.pop_pic).find('ul').width(picpopnum*poppicw);

//点击小图切换大图
	jQuery(can.pnum).find('li').click(function () {
        tpqhnum = xtqhnum = jQuery(can.pnum).find('li').index(this);
        show(tpqhnum);
		minshow(xtqhnum);
    }).eq(can.order).trigger("click");
	if(can.autoplay==true){
//自动播放
		pictime = setInterval(function(){
			show(tpqhnum);
			minshow(tpqhnum)
			tpqhnum++;
			xtqhnum++;
			if(tpqhnum==picnum){tpqhnum=0};
			if(xtqhnum==picminnum){xtqhnum=0};

		},can.interTime);

//鼠标经过停止播放
		jQuery(can.box).hover(function(){
			clearInterval(pictime);
		},function(){
			pictime = setInterval(function(){
				show(tpqhnum);
				minshow(tpqhnum)
				tpqhnum++;
				xtqhnum++;
				if(tpqhnum==picnum){tpqhnum=0};
				if(xtqhnum==picminnum){xtqhnum=0};
				},can.interTime);
			});
	}
//小图左右切换
	jQuery(can.prev_btn).click(function(){
		if(tpqhnum==0){tpqhnum=picnum};
		if(xtqhnum==0){xtqhnum=picnum};
		xtqhnum--;
		tpqhnum--;
		show(tpqhnum);
		minshow(xtqhnum);
		})
	jQuery(can.next_btn).click(function(){
		if(tpqhnum==picnum-1){tpqhnum=-1};
		if(xtqhnum==picminnum-1){xtqhnum=-1};
		xtqhnum++;
		minshow(xtqhnum)
		tpqhnum++;
		show(tpqhnum);
		})
//小图切换过程
	function minshow(xtqhnum){
		var mingdjl_num =xtqhnum-can.min_picnum+2
		var mingdjl_w=-mingdjl_num*picminw;
		var mingdjl_h=-mingdjl_num*picminh;

		if(can.mindire==true){
			jQuery(can.pnum).find('ul li').css('float','left');
			if(picminnum>can.min_picnum){
				if(xtqhnum<3){mingdjl_w=0;}
				if(xtqhnum==picminnum-1){mingdjl_w=-(mingdjl_num-1)*picminw;}
				jQuery(can.pnum).find('ul').stop().animate({'left':mingdjl_w},can.delayTime);
				}

		}else{
			jQuery(can.pnum).find('ul li').css('float','none');
			if(picminnum>can.min_picnum){
				if(xtqhnum<3){mingdjl_h=0;}
				if(xtqhnum==picminnum-1){mingdjl_h=-(mingdjl_num-1)*picminh;}
				jQuery(can.pnum).find('ul').stop().animate({'top':mingdjl_h},can.delayTime);
				}
			}

	}
//大图切换过程
		function show(tpqhnum){
			var gdjl_w=-tpqhnum*picw;
			var gdjl_h=-tpqhnum*pich;
			if(can.picdire==true){
				jQuery(can.pic).find('ul li').css('float','left');
				jQuery(can.pic).find('ul').stop().animate({'left':gdjl_w},can.delayTime);
				}else{
				jQuery(can.pic).find('ul').stop().animate({'top':gdjl_h},can.delayTime);
			}//滚动
			jQuery(can.pnum).find('li').eq(tpqhnum).addClass("on").siblings(this).removeClass("on");
		};
}
