/**
 * @author Administrator
 */
 (function(scope){
 	var o = null,
		timer = 0,
		d = document,
		//大图区的li
		li_items = null,
		//缩略图区<div class='thumb' >
		thumb_box = null,
		//缩略图滑动背景 <div class='now-status'>
		thumb_bg = null,
		//所有的缩略图图片{array}
		thumb_img = null,
		//缩略图滑动的单段距离
		thumb_space = 0,
		//缩略图滑动的4个位置坐标[0, 76, 152, 228]
		thumb_spaces = [],
		//标题区<h3 class="h3 pos-a"><a href="" title="">近距离拍摄爆发火山1</a></h3>
		title = null,
		//当前索引值，默认是0
		now_i = 0,
		//自动播放的间隔时间,默认6秒
		interval_t = 6000,
		//缩略图个数
		max_len = 0,
		// 缩略图滑动的方向，默认是垂直v
		aspect = 'top';
	
	//初始化函数
	var init = function(c){
			o = c['id'];
			aspect = (c['way'] && c['way'] == 'left') ? 'left' : 'top';
			title = o.find('.title a');
			thumb_box = o.find('.thumb');
			thumb_img = thumb_box.find('img');
			max_len = thumb_img.size();
			thumb_bg = thumb_box.find('.now-status');
			li_items = o.find('.img li');
			interval_t = c['interval'];
			//thumb_space = thumb_bg.height();
			thumb_space = aspect == 'left' ? thumb_bg.outerWidth() : thumb_bg.outerHeight();
			cacheSpace();
			autoPlay();
			handle();
	}
	//缓存缩略图间距
	var cacheSpace = function(){
			for(var i = 0 ; i < thumb_img.size();i++){
				if(i == 0){
					thumb_spaces.push(0);
					continue;
				} 
				if( i > 0) thumb_spaces.push(i*thumb_space);
			}
	}
	//缩略图滑动
	var changeThumb = function(o,end) {
			aspect =='left' ? thumb_bg.animate({'left': end}) : thumb_bg.animate({'top': end});
			//thumb_bg.animate({'top': end});
	}
	//大图切换效果
	var changeImg = function(i){
			li_items.fadeOut();
			$(li_items[i]).fadeIn();
	}
	//切换标题
	var updateTitle = function(o,i){
			o.text(getTitle(i)).attr('href',getUrl(i));
	}
	//获取大图alt属性上的文字来做为显示的标题
	var getTitle = function(i){
			return $(li_items[i]).find('img').attr('alt');
	}
	//获取url
	var getUrl = function( i ){
			return $(li_items[i]).find('a').attr('href');
	}
	var t = null;
	//绑定事件
	var handle = function(){
			thumb_img.hover(function( e ){
				thumb_bg.stop();
				clearInterval(timer);
				var i = thumb_img.index($(this));
				if(i == now_i) return;
				if(t) clearTimeout(t);
				t = setTimeout(function(){
					run(i);
				},100);
			},function( e ){
				autoPlay();
			});
	}
	var run = function( i ){
			//执行缩略图动画
			changeThumb(thumb_bg,thumb_spaces[i]);
			//大图切换
			changeImg(i);
			//切换标题
			updateTitle(title,i);
			//更新当前索引值
			now_i = i;
	}
	var autoPlay = function(){
			timer = window.setInterval(function(){
				now_i = now_i == max_len-1 ? -1 : now_i;
				now_i += 1;
				run(now_i);
			},interval_t);
	}
	
	if(!scope['slider']) scope['slider'] = {init: init}
	return slider;
 })(this);
 
  
 
 