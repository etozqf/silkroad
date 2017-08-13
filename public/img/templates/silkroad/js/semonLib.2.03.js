
;(function (global) {
	/**
	 * version	: 2.03
	 *修改 dialog,dialogPos
	 */
	function SemonLib ()
	{
		this.version	= "2.03" ;
	}
	/**
	 * 	幻灯片
	 */
	SemonLib.prototype.pictureSlide = function(Conf) {
		var	This				= {};									//	保存所有配置信息
			This.autoRoll 		= this.initVal(Conf.autoRoll,false);			//	是否自动播放
			This.autoRollTime	= this.initVal(Conf.autoRollTime,2500);		//
			This.direction 		= this.initVal(Conf.direction,"next");		//;
		var	animateType		= this.initVal(Conf.animateType,"single");	//;	single,group
		var	animate_down 	= true;
		var	prev	 			=This.prev		= Conf.prev
		var	next 			= This.next		= Conf.next;
		var	container 		= This.container	= Conf.container
		var	ul 				= This.ul			= Conf.ul;
		var 	li 				= This.li			= Conf.li
		var	slideDirec 	        = This.slideDirec
						= typeof Conf.slideDirec == "boolean" ? Conf.slideDirec : true;		//	滚动方向 ；上下（false）或 左右（true）

		var	slideStep 		= This.slideStep		= this.initVal(Conf.slideStep,1);			//	一次滑动及步长，即，一次滑动几屏
		var	WOH 		= slideDirec ? "W" : "H";
		var	TOL 			= slideDirec ? "Left" : "Top";
		var	rTOL 		= slideDirec ? "Right" : "Bottom";
		var	tol 			= slideDirec ? "left" : "top";
		var	ulAttr 		= {};
			ulAttr.W 		= ul.width();
			ulAttr.H 		= ul.height();
		var 	liAttr 		= {};
			liAttr.W 		= li.eq(0).outerWidth(true);
			liAttr.H 		= li.eq(0).outerHeight(true);
		var	Switch 		= true;										//	是否开启滚动效果
		var	currNo 		= This.currNo 		= 0;							//	当前active序列号
		var	prevNo		= -1;
			This.stop_roll	= false;										//	动画是否停止进行
			This.stop_roll_isValid
						= true;										//	This.stop_roll 是否可以生效
		var	liSize		= This.liSize		=  li.size();
		var	items 		= [];
		var	first_items	= null;
		var	last_items	= null;
		var	switchBtns	= This.switchBtns
						=  typeof Conf.switchBtnConf != 'undefined' ? Conf.switchBtnConf.selecter : undefined;
		//	将外部配置参数
		format_switchBtns();
		//	设置步长
		This.set_slideStep	= function (val)
		{
			slideStep = val;
		}
		//
		container.css({
			overflow	: 'hidden',
			position	: 'relative'
		});
		ul.css({
			position:'relative',
			overflow:'visible'
		});
		li.css({
			position:'absolute'
		});

		//	初式化位置

		(function () {
			for (var i=0;i<liSize;i++)
			{
				items[i]			= li.eq(i);
				items[i].posNo		= i;
				var	measure 		= i*liAttr[WOH];
					items[i][TOL]	= 0;
					items[i][TOL] 	= measure;
				var	style 		= {};
					style[tol]		= measure;
				items[i].css(style);
			}
			ul[TOL]	= 0;
			ul[rTOL]	= 0+ulAttr[WOH];
		})()
		first_items	= items[0];
		last_items	= items[liSize-1];
		//	判断是否符合滑动条件
		function judge_switch ()
		{
			if (li.size() <=Math.ceil(liAttr[WOH]/liAttr[WOH])) Switch = false;
		}
		var direction		= This.direction;
		var timeHandle	= null;
		//	自动滚动
		function delayRoll()
		{
			clearTimeout(timeHandle);
			timeHandle = setTimeout(function () {
				if (!This.autoRoll) return;
				if ( direction =='prev')prev.trigger('click');
				if ( direction =='next')next.trigger('click');
			},This.autoRollTime)
		}
		delayRoll();
		/**
		 *
		 */
		prev.click (function () {
			var direc		= "prev";
			judge_switch();
			if (Switch && !This.stop_roll)
			{
				if (!animate_down) return ;
				var prevNo	= currNo;
				foramt_currNo(direc);
				switchBtn_activeFn();
				var	type			= slideStep > 1 ? "linear" : "swing";
				var	runTime		= get_runTime(slideStep);
				var	interval		= runTime.interval;
				var	speed		= [];
				var	delayTimme	= 0;
				var	isFirst		= true;
				var	isLast		= false;
				var	isDone		= true;
				var	NoA			= Math.abs(prevNo-1) % liSize;
				var	NoB			= currNo;
				var	isSingle		= animateType === "single" && slideStep>1 ?  true : false;
				for (var i in interval)
				{
					speed[i]		=  runTime.speed;
					isFirst		= true;
					isLast		= true;
					(function (i,isFirst,isLast,isDone){
						setTimeout(function () {
							animate_down 	= false;
							ul_action(
								true,type,1,speed[i]
								, function ()
								{
									if (isFirst && isSingle) This.transpos(NoA,NoB);
									li_action(direc);
								}
								, function ()
								{
									animate_down	= true;
									slideStep			= 1;
									(isDone && isSingle) && item_sort(currNo);
								}
							);
						},delayTimme);
					})(i,isFirst,isLast,isDone)
					delayTimme	+= speed[i];
					if (isDone  && isSingle) break;
				}
			}
			direction		= direc;
			delayRoll();
			return false;
		});
		next.click (function () {
			var direc		= "next";
			judge_switch();
			if (Switch && !This.stop_roll)
			{
				if (!animate_down) return ;
				var prevNo	= currNo;
				foramt_currNo(direc);
				switchBtn_activeFn();
				var	type			= slideStep > 1 ? "linear" : "swing";
				var	runTime		= get_runTime(slideStep);
				var	interval		= runTime.interval;
				var	speed		= [];
				var	delayTime	= 0;
				var	isFirst		= true;
				var	isLast		= false;
				var	isDone		= true;
				var	NoA			= (prevNo+1) % liSize;
				var	NoB			= currNo;
				var	isSingle		= animateType === "single" && slideStep>1 ?  true : false;
				for (var i in interval)
				{
					speed[i]		=  runTime.speed;
					isFirst		= true;
					isLast		= true;
					;(function (i,isFirst,isLast,isDone) {
						setTimeout(function () {
							animate_down 	= false;
							ul_action(
								false,type,1,speed[i]
								, function ()
								{
									if (isFirst && isSingle) This.transpos(NoA,NoB);
								}
								, function ()
								{
									(isDone && isSingle) ? item_sort(currNo) : li_action(direc);
									animate_down	= true;
									slideStep			= 1;
								}
							);
						},delayTime);
					})(i,isFirst,isLast,isDone);
					delayTime	+= speed[i];
					if (isDone  &&isSingle) break;
				}
			}
			direction = direc;
			delayRoll();
			return false;
		});
		//
		function get_runTime(step)
		{
			var	baseTime	= 500;
			var	runTime	= {};
			var	interval	= [];
			var 	allTime	= step * baseTime > 1500 ? 1500 : step * baseTime;
			var	speed	=  Math.ceil(allTime / step);
			for (var i = 0;i<step;i++)
			{
				interval.push( i * speed);
			}
			runTime.interval	= interval;
			runTime.speed	= speed;
			return runTime;
		}

		//	鼠标覆盖则停止自动滚动
		prev.mouseover(function (e) {
			e.stopPropagation();
		});
		next.mouseover(function (e) {
			e.stopPropagation();
		});

		li.mousemove(function (e) {
			This.stop_roll_isValid && (This.stop_roll = true);
			This.stop_roll_isValid	= true;
			e.stopPropagation();
		});
		li.mouseout(function (e) {
			This.stop_roll_isValid && (This.stop_roll = false);
			This.stop_roll_isValid	= true;
			e.stopPropagation();
		});
		//
		function init_items()
		{
			for (var i=0;i<liSize;i++)
			{
				items[i]			= li.eq(i);
				items[i].posNo	= i;
			}
		}
		//
		function ul_action($direc,easing,step,runtime,beforeFn,afterFn)
		{
			typeof  beforeFn === "function"&& beforeFn();
			var	sign	= $direc ? 1 : -1;
			ul[TOL]			= ul[TOL]+liAttr[WOH] * sign * step;
			ul[rTOL]			= ul[rTOL] + liAttr[WOH] * sign * step;
			var style			= {};
			style[tol]		= ul[TOL];
			ul.animate(
				style,
				runtime,
				easing,
				function ()
				{
					animate_down	= true;
					typeof  afterFn === "function"&& afterFn();
				})

		}
		//
		function li_action($direc)
		{
			var	isPrev		= $direc === "prev";
			var	moveItem	= isPrev	? last_items : first_items;		//	移动的li
			var	referItem	= isPrev	? first_items : last_items;		//	目的位置的
			var	sign		= isPrev 	? -1 : 1;
			moveItem[TOL]	= referItem[TOL] + liAttr[WOH] * sign;
			var style		= {};
			style[tol]		= moveItem[TOL];

			moveItem.css(style);

			var	firstNo	= first_items.posNo;
			var	lastNo	= last_items.posNo;
			if (isPrev)
			{
				firstNo	= lastNo;
				lastNo	= (lastNo - 1 + liSize) % liSize;
			}
			else
			{
				lastNo	= firstNo;
				firstNo	= Math.abs(firstNo + 1) % liSize
			}
			first_items	= items[firstNo];
			last_items	= items[lastNo];

		}
		//	切换按钮
		function format_switchBtns()
		{
			if (typeof switchBtns == "undefined") return;
			var event 		= Conf.switchBtnConf.event;					//
			switchBtns.each(function (i) {
				$(this).bind(event,function ()
				{
					if (!animate_down) return ;
					var cNo 		= currNo;
					var diffVal	= i - cNo;
					slideStep 		= Math.abs(diffVal)>0 ? Math.abs(diffVal) : 1;

					switchBtn_activeFn();

					if (diffVal > 0)		next.trigger("click");
					else if (diffVal < 0)	prev.trigger("click");
				})
			})
		}
		//
		function switchBtn_activeFn()
		{
			if (typeof switchBtns != "undefined" && typeof  Conf.switchBtnConf.activeFn === "function")
			{
				Conf.switchBtnConf.activeFn(switchBtns,currNo);
			}
		}

		//	当前窗口中的 li 编号
		function foramt_currNo (direc,fn)
		{
			slideStep		= slideStep % liSize;
			if (slideStep == 0) return;
			currNo		= This.currNo;
			var	maxNo	= liSize - 1;
			if (direc==="next")
			{
				currNo	+= slideStep;
				if (currNo > maxNo)
				{
					currNo	= currNo % maxNo -1;
				}
			}
			else
			{
				currNo	-= slideStep;
				if (currNo < 0)
				{
					currNo	= maxNo + currNo + 1;
				}
			}
			This.currNo	= currNo;
			typeof fn === "function" && fn();
		}
		//
		function item_sort(startNo)
		{
			var	No	= startNo;
			var	Sort	= [];
			var	measure		= items[startNo][TOL];
			for (var i=0;i<liSize;i++)
			{
				Sort.push(No);
				if (i>0) measure	= measure + liAttr[WOH];
				items[No][TOL] 	= measure;
				var	style 		= {};
				style[tol]		= measure;
				items[No].css(style);
				No++;
				No	= No%liSize;
			}
			first_items	= items[Sort[0]];
			last_items	= items[Sort[liSize-1]];
			get_limit_item();
		}
		//
		This.transpos = function(NoA,NoB)
		{
			if (NoA == NoB) return;
			var a = items[NoA][TOL];
			var b = items[NoB][TOL];
			items[NoA][TOL] = b;
			items[NoB][TOL] = a;
			var style = {};
			style[tol] = b;
			items[NoA].css(style);
			style[tol] = a;
			items[NoB].css(style);
			get_limit_item();
		}
		//
		function get_limit_item()
		{
			var	firstNo	= 0;
			var	lastNo	= liSize-1;
			var	_low		= items[firstNo][TOL];
			var	_up		= items[lastNo][TOL];
			for (var i =0;i<liSize;i++)
			{
				if (_low > items[i][TOL])
				{
					firstNo	= i;
					_low		= items[i][TOL];
				}
				else if (_up < items[i][TOL])
				{
					lastNo	= i;
					_up		= items[i][TOL];
				}
			}
			first_items	= items[firstNo];
			last_items	= items[lastNo];
		}

		//
		return This;
	}

	/**
	 * 	图片简介
	 * 	鼠标覆盖出现
	 *	鼠标移开隐藏
	 */
	SemonLib.prototype.summaryShade = function (Conf) {
		var	This			= {};
		var	initSelecter	= this.initSelecter;
		var	allowInit		= This.allowInit	= ["textItems","overitems","actionEvent"];
		var	textItems		= This.textItems	= initSelecter(Conf.textItems);
		var	overitems	= This.overitems	= initSelecter(Conf.overitems);
		var	actionEvent	= "mouseover";
		var	negativeEvent	= "mouseleave";
		var	activeFn		= This.activeFn	= typeof Conf.activeFn		== "function" ? Conf.activeFn		: active;
		var	negativeFn	= This.negativeFn	= typeof Conf.negativeFn	== "function" ? Conf.negativeFn	: negative;
		this.ConfInit(This,Conf,allowInit);		//	导入配置参数

		overitems.each(function (i) {
			$(this).on(actionEvent,function () {
				activeFn(textItems.eq(i));
			});
			$(this).on(negativeEvent,function () {
				negativeFn(textItems.eq(i));
			});
		});

		function active(textitem)
		{
			textitem.stop().slideDown();
		}
		function negative(textitem)
		{
			textitem.stop().slideUp();
		}




	} // SummaryShade
	/**
	 * 	图片切换
	 */
	SemonLib.prototype.overSwitchover = function (config) {
		var This		= {};
		var overObj	= this.initSelecter(config.overObj);
		var enterShow = this.initSelecter(config.enterShow);
		var leaveShow = this.initSelecter(config.leaveShow);
		overObj.mouseenter(function () {
			enter();
		});
		overObj.mouseleave(function () {
			leave();
		});

		function enter () {
			leaveShow.hide();
			enterShow.show();
		}
		function leave () {
			leaveShow.show();
			enterShow.hide();
		}
		return This;
	}
	/**
	 * 	对话框
	 */
	SemonLib.prototype.dialog = function(Conf)
	{
		var	This				= {};
	 	var	win				= $(window);
		var	is_ie6			= this.is_ie6;
		var	container		= This.container	= this.initSelecter(Conf.container);
		var	closeBtn		= This.closeBtn	= this.initSelecter(Conf.closeBtn);
		var	style			= This.style		= init_style(Conf);
		var	scroll_Y 		= $(document).scrollTop();
		var	scroll_X 		= $(document).scrollLeft();
		var	prevScroll		= {T:scroll_Y,L:scroll_X};
		//
		container.css(style);
		//
		function init_style(conf)
		{
			var style		= typeof conf.style	=== "object" ? conf.style : {};
			var Offset	= container.offset();
			var pos		= {};
			pos.top		= typeof style.top	!== "undefined" ? style.top : Offset.top;
			pos.left		= typeof style.top	!== "undefined" ? style.left : Offset.left;
			pos.position	= is_ie6 ? "absolute" : "fixed" ;

			return pos;
		}
		function  ie6_initPos()
		{
			var pos 	= {};
			pos.top	= parseInt(container.css("top"));
			pos.left	= parseInt(container.css("left"));
			scroll_X 	= $(document).scrollLeft();
			scroll_Y 	= $(document).scrollTop();
			pos.top	= pos.top	+ scroll_Y - prevScroll.T;
			pos.left	= pos.left	+ scroll_X - prevScroll.L;
			container.css(pos);
			prevScroll		= {T:scroll_Y,L:scroll_X};
		}
		//
		$(window).bind('resize scroll',function (e) {
			if (is_ie6) ie6_initPos();
		})
		if (closeBtn) closeBtn.click(function () {
			container.hide();
		})

		return This;
	}
	 /**
	  *
	  */
	SemonLib.prototype.dialogPos = function (Conf)
	{
	  	var	This			= {};
	  	var	win			= $(window);
		var	container	= This.container		= this.initSelecter(Conf.container);
		var	containerW	= container.width();
		var	containerH	= container.height();
		var	posType		= This.posType				= this.initVal(Conf.posType,"left");					//	left,right,center
		var	winSideMargin	= This.winSideMargin	= this.initVal(Conf.winSideMargin,0);					//	距离窗口边距
		var	mainScreMargin	= This.mainScreMargin	= this.initVal(Conf.mainScreMargin,null);				//	距离主屏边距
		var	mainScreW		= This.mainScreW		= this.initVal(Conf.mainScreW,0);						//	页面主体内容屛宽
		var	isOverMainScre	= This.isOverMainScre	= this.initVal(Conf.isOverMainScre,false);		  		//	窗口变化，是否重叠于页面主体内容之上
		var	closeType		= This.closeType			= this.initVal(Conf.closeType,"winSide");				//	窗口位置模式，贴窗口边界（"winSide"），还是主屏（"mainScre"），铺满屏（"fullScre"）,居中（"center"）
	  	var	mainScrePos		= This.mainScrePos		= this.initVal(Conf.mainScrePos,{});					//	主屏左右边界坐标
	  	var	allowInit		= ["posType","winSideMargin","mainScreMargin","mainScreW","isOverMainScre"];
	  	var	sideSpaceW , winW , winH , containerOffset;
		//this.ConfInit(This,Conf,allowInit);
	  	/**
	  	 * 	初始化变量值
	  	 */
	  	function init_var()
	  	{
	  		winW			= win.width();
	  		winH			= win.height();
	  		sideSpaceW		= (winW - mainScreW)/2;							//	两边空白宽度
	  		//sideSpaceW		= Math.abs(sideSpaceW);
	  		containerOffset	= container.offset();
			containerW	= container.width();
			containerH	= container.height();
			var	diff	= winW - mainScreW;
			mainScrePos.LX	= diff>0 ? diff/2 : 0;
			mainScrePos.RX	= diff>0 ? winW - diff/2 : mainScreW;
			setPos();
	  	}
		//
	  	//
	  	function setPos ()
	  	{
			closeType == "winSide"		&& closeWinSide();
			closeType == "mainScre"		&& closeMainScre();
			closeType == "fullScre"		&& fullScre();
			closeType == "center"		&& center();
	  	}
	  	//	贴浏览器窗口
	  	function closeWinSide ()
	  	{
	  		var	style		= {};
	  		if (posType == "left")
	  		{
				style.left	= 0 + winSideMargin;
	  			if (!isOverMainScre && sideSpaceW < containerW + winSideMargin)
				{
					style.left = mainScrePos.LX - containerW;
				}
	  		}
	  		else if (posType == "right")
	  		{
				style.left	= winW - containerW - winSideMargin;
	  			if (!isOverMainScre && sideSpaceW < containerW + winSideMargin)
				{
					style.left	= mainScrePos.RX;
				}
	  		}
			container.css(style);
	  	}
	  	//	贴主屏
	  	function closeMainScre ()
	  	{
	  		var	style		= {};

	  		if (posType == "left")
			{
				style.left	= mainScrePos.LX - mainScreMargin - containerW;
				if (!isOverMainScre)
				{
					if (sideSpaceW < containerW + mainScreMargin &&sideSpaceW >= containerW)
					{
						style.left	= 0;
					}
					else if (sideSpaceW < containerW)
					{
						style.left = mainScrePos.LX - containerW;
					}
				}
			}
	  		else if (posType == "right")
			{

				style.left	= mainScrePos.RX + mainScreMargin;

				if (!isOverMainScre)
				{
					if (sideSpaceW < (containerW + mainScreMargin) && sideSpaceW >= containerW)
					{
						style.left = winW - containerW;
					}
					else if (sideSpaceW < containerW)
					{
						style.left = mainScrePos.RX;
					}
				}
			}

			container.css(style);
	  	}
		//	铺满
		function fullScre()
		{
			var	style		= {
				width	: winW,
				height	: winH,
				top		: 0,
				left		:0
			};
			container.css(style);
		}
		//
		function center()
		{
			var	L	= (winW	- containerW)/2;
			var	T	= (winH	- containerH)/2;
			var	style	= {
				top		: T,
				left		: L
			}
			container.css(style);
		}
	  	//
		init_var();
	  	//
	  	win.on("resize ready",function () {

			init_var();
	  		setPos();
	  	});
	 	return This;
	}
	 /**
	  * 	锚点导航
	  */
	SemonLib.prototype.anchorNav = function (Conf)
	{
		var	This			= {};
		var	btnList		= This.btnList		= Conf.btnList;
		var	screenList	= This.screenList		= Conf.screenList;
		var	prevNO		= 0;
		var	rollBaseTime
						= This.rollBaseTime	= 2000;
		var	size			= btnList.length;
		var	allowInit	= ["baseTime"];
		var	activeFn		= This.activeFn		= Conf.activeFn;

		this.ConfInit(This,Conf,allowInit);					//	引入外部配置

		for (var i=0;i<btnList.length;i++)
		{
			(function (j) {
				btnList[j].click(function () {
					goTo(j,screenList[j]);
				});
			})(i);
		}
		/*
		 * 	滚动到锚点
		 */
		function goTo (NO,screenListItem)
		{
			var	jqOffset	=	screenListItem.offset();
			var	diff		=	Math.abs(NO - prevNO) + 1;
			var	rollTime	=	Math.abs(rollBaseTime*(diff/size));
			var	T		=	jqOffset.top;
			activeFn(prevNO,NO);
			if (rollTime<500) rollTime=500;
			$('body,html').stop().animate({scrollTop : T},rollTime);
			prevNO		=	NO;
		}

		return This;
	}
	/**
	* 	选项卡
	*/
	SemonLib.prototype.Tab	= function (Conf)
	{
		var	This				= {};
		var	allowConfVar	= This.allowConfVar	= ["tabBtn","tabContent","activeEvent","callback"];
		var	btnItems		= This.btnItems		= this.initSelecter(Conf.btnItems);
		var	contentItems	= This.contentItems	= this.initSelecter(Conf.contentItems);
		var	activeEvent		= This.activeEvent	= typeof  Conf.activeEvent === "undefined"	? "mouseover" : Conf.activeEvent;
		var	callback			= This.callback	= typeof	Conf.callback === "undefined"	? function(){} : Conf.callback;
		var	prevNO			= 0;
		btnItems.each(function (i) {
			btnItems.eq(i).on(activeEvent,function () {

				if (i == prevNO) return;
				var	preC		= contentItems.eq(prevNO);
				var	currC		= contentItems.eq(i);
				var	preB		= btnItems.eq(prevNO);
				var	currB		= btnItems.eq(i);
				var	currNO		= i;
				preC.hide();
				currC.show();
				callback(prevNO,currNO);
				prevNO			= i;
			});
		});

		return This;
	}
	/**
	* 切换箭头
	*/
	SemonLib.prototype.SwitchArrow	= function (Conf)
	{
		var	This			= {};
		var	allowParam	= ["prev","next","overLayer"];
		var	prev			= This.prev		= Conf.prev;
		var	next			= This.next		= Conf.next;
		var	overLayer		= This.overLayer	= Conf.overLayer;
		var	layerLeave	= true;
		var	arrowLeave	= true;
		var	overLayerOffset
						= overLayer.offset();
		var	W			= overLayer.width();
		var	L			= overLayerOffset.left;
		var	midPoint		= L	+  W/2;
		var	cursorPos		= -1;
		var	TimeHd		= null;
		overLayer.on("mousemove",function (e) {

			cursorPos		= e.pageX;
			activeFn(cursorPos,midPoint);

		});
		overLayer.on("mouseenter",function (e) {
			layerLeave		= false;
		});
		overLayer.on("mouseleave",function (e) {
			layerLeave		= true;
			negativeFn();
		});
		//
		prev.mouseenter(function () {
			arrowLeave	= false;
		})
		next.mouseenter(function () {
			arrowLeave	= false;
		})
		//
		prev.mouseleave(function () {
			arrowLeave	= true;
			negativeFn();
		});
		next.mouseleave(function () {
			arrowLeave	= true;
			negativeFn();
		});
		//
		function get_pageX(fn)
		{
			overLayer.trigger("mouseenter");
			TimeHd	= setTimeout(get_pageX,500);
			fn();
		}
		//
		function init()
		{
			var	W		= overLayer.width();
			var	L		= overLayer.left;
			var	midPoint	= L	+ L	+  W/2;
		}
		//
		function activeFn(cursorPos,midPoint)
		{
			if (cursorPos<=midPoint)
			{
				next.hide();
				prev.show();
			}
			else if  (cursorPos > midPoint)
			{
				prev.hide();
				next.show();
			}
		}
		//
		function negativeFn()
		{
			setTimeout(function () {
				if(arrowLeave && layerLeave)
				{
					prev.hide();
					next.hide();
				}
			},100);

		}
		//
		$(window).on("resize",function () {
			init();
		});

		return This;
	}
	/**
	 * 	带下拉菜单的导航
	 */
	SemonLib.prototype.nav	= function (Conf)
	{
		var	This			= {}
		var	T			= this;
		var	navItems		= Conf.navItems;
		var	subMenus	= Conf.subMenus;
		var	assocNO		= Conf.assocNO;
		var	activeFn		= Conf.activeFn;
		var	negativeFn	= Conf.negativeFn;
		var	isHide		= [];
		subMenus.hide();
		navItems.each(function (i)
		{
			isHide[i]		= false;
			var	smNo	= assocNO[i];
			navItems.eq(i).mouseenter(function () {
				isHide[i]	=	false;
				active(i);
			});
			navItems.eq(i).mouseleave(function () {
				isHide[i]	=	true;
				negative(i);
			});
			if (T.isNumber(smNo))
			{
				subMenus.eq(smNo).mouseenter(function () {
					isHide[i]	=	false;
					active(i);
					navItems.eq(i).trigger("mouseover");
				});
				subMenus.eq(smNo).mouseleave(function () {
					isHide[i]	=	true;
					negative(i);
				});
			}

		});

		function active (NO) {
			var	smNo	= assocNO[NO];
			activeFn(NO);
			T.isNumber(smNo) &&subMenus.eq(smNo).show();
		}
		function negative (NO) {
			var	smNo	= assocNO[NO];
			negativeFn(NO);
			setTimeout(function () {
				if (! isHide[NO]) return;
				T.isNumber(smNo) && subMenus.eq(smNo).hide();
			},10)
		}
	}
	/**
	 * 栅格动画
	 * @param Conf
	 */
	SemonLib.prototype.gridSlide = function(Conf)
	{
		var	This			= {};
		var	container	= This.container	= Conf.container;
		var	titleItems	= This.titleItems	= Conf.titleItems;
		var	imgItems	= This.imgItems	= Conf.imgItems;
		var	runTime		= this.initVal(Conf.runTime,"normal");;
		var	W			= imgItems.eq(0).width();
		var	activeFn		= Conf.activeFn;
		var	activeEvent	= typeof  Conf.activeEvent == "undefined" ? 'click' : Conf.activeEvent;
		var 	prevNo		= null;
		var	runDone	= true;
		imgItems.width(0);
		imgItems.show();
		titleItems.each(function (i) {
			$(this).on(activeEvent,function () {
				if (!runDone || prevNo == i) return;
				runDone	= false;
				activeFn(i,prevNo);
				if (null !== prevNo)imgItems.eq(prevNo).animate({width:"0px"},runTime,"swing");
				imgItems.eq(i).animate({width:W+"px"},runTime,"swing",function () {
					prevNo		= i;
					runDone	= true;
				});
			});
		});
	}
	/**
	 * 返回顶部
	 * @param Conf
	 */
	SemonLib.prototype.goToTop	= function (Conf)
	{
		var	clickBtn		= Conf.clickBtn;
		var	rollBaseTime	= 1000;
		var	rollBaseHeight
						= 1000;
		var	currHeight	= 0;
		var	rollTime	=	0;
		clickBtn.click(function () {
			currHeight	= $(document).scrollLeft();
			var	rate		= currHeight / rollBaseHeight;
			rollTime		= rate * rollBaseTime;
			if (rollTime<500) rollTime=500;
			$('body,html').stop().animate({scrollTop : 0},rollTime);
		});
	}
	 /**
	 * 	参数：css选择器或jquery对象
	 * 	返回：jquery 对象
	 */
	SemonLib.prototype.initSelecter = function (selecter)
	{
		if (typeof selecter == "undefined") return null;
		return  selecter instanceof jQuery ? selecter : $(selecter);
	}
	 /**
	 * 判断是否为ie6
	 */
	// SemonLib.prototype.browserCheck = function ()
	// {
	// 	this.is_ie6 = false;
	// 	if ($.browser.msie && $.browser.version==6.0)
	// 	{
	// 		this.is_ie6 = true;
	// 	}
	// }
	/**
	* 	将配置参数写入This
	*/
	SemonLib.prototype.ConfInit = function (This,conf,allowInit)
	{
		for(i in conf)
		{
			if (typeof allowInit === "Array" && allowInit.length > 0 && this.in_array(allowInit,i))
			{
				This[i] = conf[i];
			}
			else
			{
				This[i] = conf[i];
			}
		}
	}
	/**
	 *给变量赋值，如果所给值为undefined及是否使用默认值
	 */
	SemonLib.prototype.initVal = function (val,deVal)
	{
		var	ret	= deVal;
		if (typeof val !==  "undefined")
		{
			ret	= val;
		}
		return	ret;
	}
	/**
	*
	*/
	SemonLib.prototype.isArray = function (param)
	{
		var	ret	=	false;
		if (typeof param === "Array")	ret = true;
		return ret;
	}
	/**
	*
	*/
	SemonLib.prototype.in_array = function (arr,search,strict)
	{
		var ret = false;
		for (var i=0;i<arr.length;i++)
		{
			if (strict && arr[i] === search) ret = true;
			if (!strict && arr[i] == search) ret = true;
		}
		return ret;
	}
	/**
	 *
	 * @param num
	 * @returns {boolean}
	 */
	SemonLib.prototype.isNumber = function (num)
	{
		var	ret	= false;
		if (typeof num === "number")	ret = true;
		return ret;

	}
	//插件入口
	SemonLib.prototype.extend = function (name, fn)
	{
		SemonLib.prototype[name] = fn;
	};
	/**
	 * jquery 版本兼容
	 * @param name
	 * @param fn
	 */
	SemonLib.prototype.jqueryCompatible = function ()
	{
		var	jquery	= global.jQuery;
		if (typeof jquery(document).on !== "function" && typeof jquery(document).bind === "function")
		{
			jquery.fn.extend({
				on	: function (E,F) {
					return $(this).bind(E,F);
				}
			});;
		}
	};
	/**
	*
	*/
	global.P = function (info)
	{
		console.log(info);
	}

	global.SemonLib	= new  SemonLib ();

	global.SemonLib.jqueryCompatible();
	global.SL			= global.SemonLib;

	// SL.browserCheck();


})(typeof window !== 'undefined' ? window : this);


/**
 * 	动画库
 */
;(function (global) {

	/**
	 * 动画库
	 */
	 function SemonAnimate(jq)
	 {
	 	this.element	= this.initSelecter(jq);
	 };
	/**
	 * 动画调用接口
	 */
	SemonAnimate.prototype.animate	= function (css,time,fn)
	{

	}
	/**
	 *
	 */
	SemonAnimate.prototype.move		= function (css,time)
	{




	}
	/**
	 *
	 */
	SemonAnimate.prototype.getPosDiff	= function (css,elleItem)
	{
		var	offset	= elleItem.offset();

	}
	 /**
	 * 	参数：css选择器或jquery对象
	 * 	返回：jquery 对象
	 */
	SemonAnimate.prototype.initSelecter = function (selecter) {
		if (typeof selecter == "undefined") return null;
		return  selecter instanceof jQuery ? selecter : $(selecter);
	}



	global.SA	= function (seleter)
	{
		return new SemonAnimate(seleter)
	}

})(typeof window !== 'undefined' ? window : this);
