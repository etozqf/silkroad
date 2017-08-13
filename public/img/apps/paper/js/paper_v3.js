var calendar = function() {
	var calendarBox = $('#calendarBox');
	calendarBox.is(':visible') ? calendarBox.hide() : calendarBox.show();
}

var loadCalendar = function(url) {
	$.get(url, function (data){
		if(data.length > 1000) {
			var calendarBox = document.getElementById('calendarBox');
			var cparent = calendarBox.parentNode;
			cparent.removeChild(calendarBox);
			cparent.innerHTML += data;
			history_init();
			$('#calendarBox').show();
		}
	});
}

var history_init = function ()
{
	$('#calendarBox').click(function (e){
		e.stopPropagation();	//点击日历框内部时,不触发$(document).click事件
	});
	$('#calendarList>li>a').click(function (){
		if(this.href == 'javascript:;') return false;
	});
	$('#preMonth, #nextMonth, #preYear, #nextYear').click(function (){
		loadCalendar(this.href);
		return false;
	});

	var tarE = $('#calendar'),
		box = $('#calendarBox'),
		offsetE  = tarE.position(),
		ox = offsetE.left,
		oy = offsetE.top;
		box.css({
			'left':ox-82+'px',
			'top': 30+'px'
		});
	
	//加载小时段
	hourDiv();
}

var hourDiv = function ()
{
	var path = location.pathname;
	if(path.indexOf('history') > 0) {
		// /2010-03/04-10.shtml
		var regex = /(\d{4})-(\d\d)\/(\d+)-(\d+)/;
		var date = regex.exec(path);
			
		var url = APP_URL+'?app=history&controller=history&action=getHours&jsoncallback=?';
		var get = {alias: $('#history_index').attr('alias'), year: date[1], month: date[2], day: [date[3]]};
		$.getJSON(url, get, function (data){
			if(data.length < 2) return ;
			$('#hoursDiv').remove();
			$('body').prepend('<div id="hoursDiv">'+date[1]+'年'+date[2]+'月'+date[3]+'日:</div>');
			for(k in data) {
				var url = path.replace(/\d\d\.shtml/, data[k] + '.shtml');
				var a = $('<a></a>').text(data[k]).attr('href', url).attr('target', '_self');
				$('#hoursDiv').append(a);
				if(data[k] == date[4]) a.addClass('current');
			}
			if(date[1] + '年' + date[2] + '月' == $('#calendarBox div.cu_month').text()) {
				return ;
			}
			
			//var url = WWW_URL + 'section/history/' + $('#history_index').attr('alias') + '/' + date[1] + '-' + date[2] + '.html';
			//loadCalendar(url);
		});
	}
}

var prevNextPage =function(t, forward)
{
	var current = $('#page-printed>.c-red');
	if(forward == 'next') {
		var o = current.next('.item');
	}else{
		var o = current.prev('.item');
	}
	if(!o.html()) return false;
	var url = o.find('a:first').attr('href');
	if(url && url != 'javascript:;') location.href = url;
	return false;
}

var relatedContent = function() {
	var current = $('li[rel='+contentid+']'), o,
	html = '<p>上一篇：';
	if (current.prev().length > 0) {
		o = current.prev().find('a');
		html += '<a href="'+o.attr('href')+'" title="'+o.attr('title')+'">'+o.html()+'</a>';
	}
	html += '</p><p>下一篇：';
	if (current.next().length > 0) {
		o = current.next().find('a');
		html += '<a href="'+o.attr('href')+'" title="'+o.attr('title')+'">'+o.html()+'</a>';
	}
	html += '</p>';
	return html;
}

var copyToClipboard = function(txt) 
{  
	//复制网址
	if(window.clipboardData) {
		window.clipboardData.clearData();
		window.clipboardData.setData("Text", txt);
		alert("复制链接成功！");
	} else if(navigator.userAgent.indexOf("Opera") != -1) {
		window.location = txt;
	} else if (window.netscape) {
		try {
			netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
		} catch (e) {
			alert(" 被浏览器拒绝！\n请在浏览器地址栏输入'about:config'并回车\n然后将 'signed.applets.codebase_principal_support'设置为'true'");
		}
		var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
		if (!clip)
		return;
		var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
		if (!trans)
		return;
		trans.addDataFlavor('text/unicode');
		var str = new Object();
		var len = new Object();
		var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
		var copytext = txt;
		str.data = copytext;
		trans.setTransferData("text/unicode",str,copytext.length*2);
		var clipid = Components.interfaces.nsIClipboard;
		if (!clip)
		return false;
		clip.setData(trans,null,clipid.kGlobalClipboard);
		alert("复制链接成功！");
	}
}

var addfavorite = function()
{
	// 添加收藏
   if (document.all)
   {
	  window.external.addFavorite(document.URL, title);
   }
   else if (window.sidebar)
   {
	  window.sidebar.addPanel(title, document.URL, '');
   }
} 

$('#preMonth, #nextMonth, #preYear, #nextYear').click(function (){
	loadCalendar(this.href);
	return false;
});

grab_img_zoom($('.paper-article img'),460);

$(document).ready(function(){
	$('textarea').css('width', '443px');
	if (/^\s*$/.test($('#related_content').html())) {
		$('#related_content').append(relatedContent());
	}

	var zeroclipboard = document.getElementById('zeroclipboard');
	var clip = new ZeroClipboard(zeroclipboard, {
		moviePath: IMG_URL + 'js/zeroclipboard/ZeroClipboard.swf',
		trustedDomains: ['*'],
		allowScriptAccess: "alway"
	});
	zeroclipboard.setAttribute('data-clipboard-text', location.href);
});