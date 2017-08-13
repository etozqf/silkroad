function copyToClipboard(txt)
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

function addfavorite()
{
	var url = document.URL;
    var title = title;
    var ua = navigator.userAgent.toLowerCase();
    if (ua.indexOf("360se") > -1) {
        alert("由于360浏览器功能限制，请按 Ctrl+D 手动收藏！");
    }
    else if (ua.indexOf("msie 8") > -1) {
        window.external.AddToFavoritesBar(url, title); //IE8
    }
    else if (document.all) {
		try{
			window.external.addFavorite(url, title);
		}catch(e){
			alert('您的浏览器不支持,请按 Ctrl+D 手动收藏!');
		}
	}
	else if (window.sidebar) {
		window.sidebar.addPanel(title, url, "");
	}
	else {
		alert('您的浏览器不支持,请按 Ctrl+D 手动收藏!');
	}
} 
function changeFont(t){ 
	//改变字号
	var tosmall = $(t).hasClass('decrease')?true:false;
	//if(tosmall && !$(t).hasClass('sactive')) return;
	//if(!tosmall && !$(t).hasClass('bactive')) return;
	if(tosmall){
		$(t).removeClass('sactive');
		$('.big').addClass('bactive');
		$(t).addClass('decurrent').prev().removeClass('incurrent');
	}else{
		$(t).removeClass('bactive');
		$('.small').addClass('sactive');
		$(t).addClass('incurrent').next().removeClass('decurrent');
	}
	$('.article-detail-inner').removeClass(tosmall?'fontSizeBig':'fontSizeSmall').addClass(tosmall?'fontSizeSmall':'fontSizeBig');
}

function fulltext(){
	//阅读全文
	var full = function() {
		// 去掉标题中的页码
		$('h1').html($('h1').html().replace(/（\d）/, ''));
		
		$('.article-detail-inner').html(context);
		$('#show-all-cont').html('分页阅读').parent().siblings().hide();
		$('.article-nav').hide();
        imageZoom();
	}
	if(context ==''){
		// 判断当前页是否为首页或尾页
		window.isFirstPage = window.isLastPage = false;
		if (!$('.page').length) {
			isFirstPage = isLastPage = true;
		} else {
			var length = $('.page').find('td').length - 3;
			var index = $('.page').find('td').index($('.page').find('.now').parent());
			if (index == 1) isFirstPage = true;
			if (index == length) isLastPage = true;
		}
		$.getJSON(APP_URL+'?app=article&controller=article&action=fulltext&jsoncallback=?&contentid='+contentid, function(data){
			context = data.content;
			full();
		});
	}else{
		if($('#show-all-cont').html() == '分页阅读'){
			$('h1').html(title);
			$('#show-all-cont').html('阅读全文');
			$('.article-detail-inner').html(content);
			$('#show-all-cont').parent().siblings().show();
			$('.article-nav').show();
            imageZoom();
		}else{
			full();
		}
	}
	function imageZoom() {
	    grab_img_zoom($('.article-content img'),500);
	}
}
$(function(){
	if ($('.article-aside').length >0 && $('.article-aside').height()) {
		var contentheight = $('.article-aside').height();
		if (navigator.userAgent.indexOf("MSIE")>0 && navigator.userAgent.indexOf("MSIE 6.0")>0) {
			$('.article-content').css('height', contentheight+'px');
			$('.article-detail').css('height', contentheight+'px');
		} else {
			$('.article-content').css('min-height', contentheight+'px');
			$('.article-detail').css('min-height', contentheight+'px');
		}
	}
	var cyinterval = setInterval(function(){
		if ($('#bottombar-wrap-w').length > 0){
			$('#bottombar-wrap-w').css('display', 'none');
			clearInterval(cyinterval);
		}
	}, 10);
});