var box, leftBox, rightBox, centerBox,
	leftList, centerList, rightList,
	
	BOX_MARGIN_TOP, BOX_MARGIN_BOTTOM, H3_HEIGHT, BTNAREA_HEIGHT,
	MARGIN_X_WIDTH, BOX_BORDER_WIDTH, BOX_BORDER_HEIGHT, 
	LEFTBOX_SOLID_WIDTH, RIGHTBOX_DIFF_WIDTH;

var adapt = function(){
	var clientHeight = document.documentElement.clientHeight;
	if (clientHeight > 250) {
		var boxHeight = clientHeight - BOX_MARGIN_TOP - BOX_MARGIN_BOTTOM;
		var inHeight = boxHeight - BOX_BORDER_HEIGHT;
		box.height($.boxModel ? inHeight : boxHeight);
		leftList.height(inHeight - H3_HEIGHT);
		var listareaHeight = inHeight - H3_HEIGHT - BTNAREA_HEIGHT;
		centerList.height(listareaHeight);
		rightList.height(listareaHeight);
	}
	var clientWidth = document.documentElement.clientWidth;
	if (clientWidth > 700)
	{
		var boxWidth = clientWidth - MARGIN_X_WIDTH;
		var inWidth = boxWidth - BOX_BORDER_WIDTH;
		box.width($.boxModel ? inWidth : boxWidth);
		var remainWidth = inWidth - LEFTBOX_SOLID_WIDTH;
		centerBox.width(parseInt(remainWidth * .6));
		var remainWidth = remainWidth - centerBox[0].offsetWidth;
		rightBox.width(remainWidth - RIGHTBOX_DIFF_WIDTH);
	}
};
var bulidLeft = function(json) {
	var li = $('<li url="'+json.url+'" catid="'+json.catid+'" catname="'+json.catname+'"><a title="'+json.title+'" href="javascript:;">'+json.title+'</a></li>');
	li.appendTo(leftBox.find('ul')).bind('click', function(e) {
		leftBox.find('.focus').removeClass('focus');
		var o = $(e.currentTarget);
		o.addClass('focus');
		getCenter();
	});
};
var getCenter = function() {
	var li = leftBox.find('li.focus');
	if (!li.length) {
		ct.error('异常错误');
		return;
	}
	param = {
		'url': encodeURIComponent(li.attr('url')),
		'catid': li.attr('catid'),
		'epaperid': $('input[name=epaperid]').val(),
		'editionid': $('input[name=editionid]').val()
	};
	$.post('?app=epaper&controller=import&action=get_content', param, function(json) {
		if (json.total) {
			centerList.find('ul').empty();
			$.each(json.data, function(i,k) {
				buildCenter(k);
			});
			formatCenter();
		}
	}, 'json');
}
var buildCenter = function(json) {
	var markreadString = (json.status == 'new' ? '<img class="markread" width="16" height="16" title="标记已读" src="images/txt.gif" onclick="markread($(this).parents(\'li\'));return false;" />' : '<img class="markread" width="16" height="16" style="visibility: hidden;" title="标记已读" src="images/txt.gif" onclick="markread($(this).parents(\'li\'));return false;" />');
	var marktaskString = (json.status != 'spiden' ? '<img class="marktask" width="16" height="16" src="images/add_1.gif" title="添加到采集任务" onclick="marktask($(this).parents(\'li\'));return false;" />' : '');
	var li = $(new Array('<li class="content-list '+json.status+'">',
			'<h3 class="item-header">',
				'<span class="header-right">',
					markreadString,
					marktaskString,
				'</span>',
				'<span class="header-left">'+json.title+'</span>',
					'<a href="'+json.url+'" target="_blank">',
						'<img width="16" height="16" src="images/view.gif" title="访问原始地址" alt="访问原始地址">',
					'</a>',
			'</h3>',
		'</li>').join('\r\n'));
	li.appendTo(centerList.find('ul')).bind('click', function(e) {
		var li = $(e.currentTarget),
		desc = li.children('.item-desc'),
		notShow = !desc.length || !desc.is(':visible');
		centerList.find('li').removeClass('focus').removeClass('expanded').children('.item-desc').hide();
		if (!desc.length) {
			url = li.find('a').attr('href');
			$.post('?app=epaper&controller=import&action=get_detail', {
				'epaperid': $('input[name=epaperid]').val(),
				'url': url
			}, function(html) {
				li.append('<div class="item-desc">'+html+'</div>').addClass('focus').addClass('expanded').find('.item-desc').bind('click', function(e) {
					e.stopPropagation();
				});
			});
		} else {
			if (notShow) {
				desc.show();
				li.addClass('expanded');
			}
		}
		li.addClass('focus');
		e.stopPropagation();
	});
	li.find('.markread').bind('click', function(e) {
		e.stopPropagation();
	});
	li.find('.marktask').bind('click', function(e) {
		e.stopPropagation();
	});
}
var formatCenter = function() {
	var nav = centerBox.find('h3');
	nav.find('span').eq(0).bind('click', function() {
		nav.find('span').removeClass('active');
		nav.find('span').eq(0).addClass('active');
		centerList.find('li').hide();
		centerList.find('.new').show();
	}).find('em').html(centerList.find('.new').length);
	nav.find('span').eq(1).bind('click', function() {
		nav.find('span').removeClass('active');
		nav.find('span').eq(1).addClass('active');
		centerList.find('li').hide();
		centerList.find('.viewed').show();
	}).find('em').html(centerList.find('.viewed').length);
	nav.find('span').eq(2).bind('click', function() {
		nav.find('span').removeClass('active');
		nav.find('span').eq(2).addClass('active');
		centerList.find('li').hide();
		centerList.find('.spiden').show();
	}).find('em').html(centerList.find('.spiden').length);
	if (!nav.find('.active').length) {
		nav.find('span').eq(0).click();
	} else {
		nav.find('.active').click();
	}
}
var marktask = function(li) {
	var title = li.find('.header-left').html(),
	url = li.find('a').attr('href'),
	catid = leftList.find('.focus').attr('catid'),
	catname = leftList.find('.focus').attr('catname');
	if (rightList.find('tbody').find('[url='+url+']').length) {
		return;
	}
	var tr = $(new Array(
		'<tr class="" url="'+url+'">',
			'<td>',
				'<a href="'+url+'" target="_blank">'+title+'</a>',
			'</td>',
			'<td class="t_c" width="100">',
				'<span class="catname" title="点击修改" onclick="get_cat(this)">',
					'<input type="hidden" value="'+catid+'">',
					'[<b>'+catname+'</b>]',
				'</span>',
			'</td>',
			'<td class="t_c" width="50">',
				'<img class="hand del" width="16" height="16" src="images/del.gif" onclick="$(this).parents(\'tr\').remove();">',
			'</td>',
		'</tr>').join('\r\n')
	);
	tr.find('img').one('mousedown', function(e) {
		var tr = $(e.currentTarget).parent('td').parent('tr'),
		url = tr.attr('url'),
		cur = centerList.find('a[href="'+url+'"]');
		if (cur.length > 0) {
			cur.parent().find('.marktask').attr('src', 'images/add_1.gif');
		}
	});
	tr.appendTo(rightList.find('tbody'));
	li.find('.marktask').attr('src', 'images/add_1_disabled.gif');
	rightCount();
}

var markread = function(li) {
	var param = {
		'title': li.find('.header-left').html(),
		'url': li.find('a').attr('href'),
		'catid': leftList.find('.focus').attr('catid'),
		'epaperid': $('input[name=epaperid]').val(),
		'editionid': $('input[name=editionid]').val()
	};
	$.post('?app=epaper&controller=import&action=markread', param, function(json) {
		if(json.state) {
			li.removeClass().addClass('content-list').addClass('viewed').find('.markread').css('visibility', 'hidden');
			li.children('.item-desc').length && li.children('.item-desc').hide();
		}
		formatCenter();
	}, 'json');
}
var get_cat = function(span) {
	span = $(span);
	var $select = span.next('select'), select = $select[0];
	var input = span.find('input');
	var b = span.find('b');
	if ($select.length) {
		span.hide();
		$select.show().focus();
	} else {
		$.get('?app=spider&controller=spider&action=getcat',
		function(html){
			$select = $(html).change(function(){
				input.val(this.value);
			});
			select = $select[0];
			select.value = input.val();
			span.hide().after($select);
			select.focus();
		});
	}
};
var addAlltask = function(){
	centerList.find('.marktask').click();
}
var rightCount = function() {
	var c = rightList.find('tr').length;
	rightBox.find('em').html(c);
}
var run = function() {
	if (autoFlag) {
		return;
	}
	if (runFlag) {
		stopFlag = true;
		return;
	}
	var epaperid = $('input[name=epaperid]').val(),
	editionid = $('input[name=editionid]').val(),
	status = $('#status').val(),
	successCount = 0,
	errorCount = 0,
	trs = rightBox.find('tr'),
	index = 0;
	runFlag = true;
	var exec = function() {
		if (stopFlag || index >= trs.length) {
			runFlag = false;
			stopFlag = false;
			ct.ok('成功 '+successCount+' 个, 失败 '+errorCount+' 个');
			getCenter();
			return;
		}
		var tr = $(trs[index++]);
		var param = {
			'url': tr.attr('url'),
			'title': tr.find('a').html(),
			'catid': tr.find('input[name="catid"]').val() || tr.find('input').val(),
			'epaperid': epaperid,
			'editionid': editionid,
			'status': status
		};
		tr.find('img').attr('src', 'images/loading.gif');
		$.post('?app=epaper&controller=import&action=import', param, function(json){
			if (json.state) {
				successCount++;
				tr.find('img').attr('src', 'images/sh.gif');
			} else {
				errorCount++;
				tr.find('img').attr('src', 'images/del.gif');
			}
			setTimeout(function(){
				tr.fadeOut(function(){
					tr.remove();
					rightCount();
				});
			}, 5000);
			exec();
		}, 'json');
	}
	try {
		exec();
	} catch (exc) {}
}

var autoRun = function() {
	if (runFlag) {
		return;
	}
	//autoFlag = true;
	var autoRunDialog = new dialog(),
	listIndex,contentIndex, getDetailFlag, importContentLoop, importDetailLoop;
	autoRunDialog.open({
		hasOverlay:1,
		html: '<div style="height: 264px; padding-left: 12px; margin: 0px auto;"><div id="info" style="height: 200px; overflow-x: hidden; overflow-y: scroll; width: 372px; padding-top: 16px;margin-bottom:12px;"></div><button class="button_style_1" onclick="stopFlag=true;">暂停</button>&nbsp;&nbsp;<button id="autoclose" class="button_style_1" style="display:none;margin-right:22px; float:right;">关闭</button></div>'
	});
	var importList = function(json) {
		var l = json[listIndex++];
		if (!l || listIndex >= json.length) {
			console('已导入全部版块');
			stopFlag = false;
			autoFlag = false;
			$('#autoclose').show();
			return;
		}
		if (l.autoblock) {
			importList(json);
			return;
		}
		console('开始获取版块<font style="font-weight:bold;">'+l.title+'</font>内容');
		importContent(l, json);
	}
	var importContent = function(list, listJson) {
		var param = {
			'url': list.url,
			'catid': list.catid,
			'epaperid': $('input[name=epaperid]').val(),
			'editionid': $('input[name=editionid]').val()
		};
		$.post('?app=epaper&controller=import&action=get_content', param, function(json) {
			if (stopFlag) {
				stop();
				return;
			}
			contentIndex = 0;
			if (json.total) {
				getDetailFlag = true;
				var importDetailLoop = setInterval(function(){
					if (stopFlag) {
						stop();
						clearInterval(importDetailLoop);
					}
					if (getDetailFlag) {
						if (contentIndex >= json.data.length) {
							console('版面<font style="font-weight:bold;">'+list.title+'</font>完成');
							contentIndex = 0;
							clearInterval(importDetailLoop);
							importList(listJson);
						}
						importDetail(json.data[contentIndex++],list.catid);
					}
				}, 1000);
			}
		}, 'json');
	}
	var importDetail = function(content, catid) {
		var param = {
			'url': content.url,
			'title': content.title,
			'catid': catid,
			'epaperid': $('input[name=epaperid]').val(),
			'editionid': $('input[name=editionid]').val(),
			'state': $('#status').val()
		};
		if (content.status == 'spiden') {
			console('文章<font style="font-weight:bold;">'+content.title+'</font>已录入,跳过');
			return;
		}
		console('开始导入文章<font style="font-weight:bold;">'+content.title+'</font>');
		getDetailFlag = false;
		$.post('?app=epaper&controller=import&action=import', param, function(json){
			if(json.state) {
				console('<font style="color:#78C370; font-weight:bold;">导入成功</font>');
			} else {
				console('<font style="color:#FF0000; font-weight:bold;">导入失败</font>');
			}
			getDetailFlag = true;
		}, 'json');
	}
	var stop = function() {
		console('操作中断');
		$('#autoclose').show();
		clearInterval('importContentLoop');
		clearInterval('importDetailLoop');
		
	}
	var console = function(s) {
		$('#info').append(s+'<br />').scrollTop(1000000);
	}
	$('#autoclose').click(function(){
		autoRunDialog.close();
		stopFlag = false;
	});

	/* start */
	console('开始自动导入本期报纸...');
	$.get('?app=epaper&controller=import&action=get_list', {epaperid:$('input[name=epaperid]').val(), editionid:$('input[name=editionid]').val()}, function(json){
		if (stopFlag) {
			stop();
			return;
		}
		listIndex = 0;
		importList(json);
	}, 'json');
};
$(document).ready(function(){
	// 布局处理
	(function(){
		box = $('#box');
		leftBox = $('#leftBox');
		centerBox = $('#centerBox');
		rightBox = $('#rightBox');
		leftList = $('#leftList');
		centerList = $('#centerList');
		rightList = $('#rightList');
		
		var firstH3 = leftBox.find('h3'),
			firstBtnArea = centerBox.find('div.btn_area'),
			boxOffset = box.offset();
		BOX_MARGIN_TOP = boxOffset.top;
		BOX_MARGIN_BOTTOM = 5;
		H3_HEIGHT = firstH3[0].offsetHeight;
		BTNAREA_HEIGHT = firstBtnArea[0].offsetHeight;
		MARGIN_X_WIDTH = parseFloat(box.css('marginLeft')) * 2;
		BOX_BORDER_WIDTH = parseFloat(box.css('borderLeftWidth')) * 2;
		BOX_BORDER_HEIGHT = parseFloat(box.css('borderTopWidth')) * 2;
		LEFTBOX_SOLID_WIDTH = leftBox[0].offsetWidth;
		RIGHTBOX_DIFF_WIDTH = rightBox[0].offsetWidth - rightBox.width();
		adapt();
		window.onresize = function() {
			adapt();
		}
	})();
	// 加载 leftlist
	$.get('?app=epaper&controller=import&action=get_list', {epaperid:$('input[name=epaperid]').val(), editionid:$('input[name=editionid]').val()}, function(json){
		leftBox.find('ul').empty();
		var hasContent = false;
		$.each(json, function(i,k) {
			hasContent = true;
			bulidLeft(k);
		});
		if (!hasContent) {
			ct.error('报纸不存在或网络异常');
		}
		leftBox.find('ul>li:first').click();
	}, 'json');
	// 更改报纸/期号时重载
	$('input[name=epaperid]').add($('input[name=editionid]')).change(function() {
		var baseUrl = '?app=epaper&controller=epaper&action=import&id=',
		id = $('input[name=epaperid]').val(),
		editionid = $('input[name=editionid]').val();
		location.href = baseUrl + id + '&editionid=' + editionid;
	});
});