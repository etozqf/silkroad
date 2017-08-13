var allowPost = true;
var contentid = null;
var letterCheck = function () {
	var count = function (str) {
		var length = 0;
		var urlReg = /https?\:\/\/[a-z0-9-_\/%?&\.=]*/i;
		str = str.replace(urlReg, function() {
			length += 22;
			return '';
		});
		for (var i = 0, l=str.length; i < l; i++) {
			length++;
			if (str.charCodeAt(i) > 127)
			{
				length++;
			}
		}
		return parseInt((length + 1) / 2);
	}
	var num = 140 - count($('#weibo-input').val());
	if ((num >= 0) && $('#letter-count').hasClass('weibo-red')) {
		$('#letter-count').removeClass('weibo-red');
		$('#submitbutton').removeClass('weibo-disable');
		allowPost = true;
	} else if ((num < 0 ) && !$('#letter-count').hasClass('weibo-red')) {
		$('#letter-count').addClass('weibo-red');
		$('#submitbutton').addClass('weibo-disable');
		allowPost = false;
	}
	$('#letter-count').html(num);
}

var addLink = function(obj) {
	ct.ajaxDialog({title:'选取内容', width:500, height:510}, '?app=weibo&controller=weibo&action=addlink', null, function(dom) {
		if (!dom.find('input:checked').length) {
			return true;
		}
		contentid = dom.find('.repost_content_id:checked').val();
		getContent();
		return true;
	}, function() {
		return true;
	});
}

var getContent = function () {
	$.get('?app=weibo&controller=weibo&action=get_content', {id: contentid}, function(json) {
		if (json.state) {
			$('#weibo-input').val(json.data);
			window.slider.clear();
			for (var i=0, l=json.pic.length; i < l; i++) {
				window.slider.addPic(json.pic[i].url, json.pic[i].choose);
			}
			letterCheck();
			json.title ? document.title = '微博转发-'+json.title : '微博转发';
		} else {
			ct.error('异常错误');
		}
	}, 'json');
}

var postWeibo = function(){
	if (!allowPost) {
		return;
	}
	if (!!window.slider.getChoose()) {
		// 发布包含图片的微博
		$.post('?app=weibo&controller=api&action=post_pic', 
			decodeURIComponent($('input[name="tencent[]"]').serialize()) + '&'
			+ decodeURIComponent($('input[name="sina[]"]').serialize()) + '&'
			+ 'text=' + $('#weibo-input').val() + '&'
			+ 'pic=' + window.slider.getChoose()
		, function(json) {
			if (json.state) {
				ct.ok('发布成功');
				window.slider.clear();
				if (contentid) {
					$.get('?app=weibo&controller=weibo&action=tweeted', {id:contentid});
					contentid = null;
				};
			} else {
				ct.error('以下微博发送失败:<br />' + json.error.join(',<br />') + '<br />');
			}
			$('#weibo-input').val('');
		}, 'json');
	} else {
		// 发布不包含图片的微博
		$.post('?app=weibo&controller=api&action=post', 
			decodeURIComponent($('input[name="tencent[]"]').serialize()) + '&'
			+ decodeURIComponent($('input[name="sina[]"]').serialize()) + '&'
			+ 'text=' + $('#weibo-input').val()
		, function(json) {
			if (json.state) {
				ct.ok('发布成功');
			} else {
				ct.error('以下微博发送失败:<br />' + json.error.join(',<br />') + '<br />');
			}
			$('#weibo-input').val('');
		}, 'json');
	}
}

$(document).ready(function(){
	// TODO: for chrome
	$('#weibo-input').bind('keyup', function () {
		letterCheck();
	}).bind('paste', function () {
		setTimeout(function() {letterCheck();}, 1);
	});
	letterCheck();
	window.slider = new slider();
	$('#uploader').uploader({
		script : '?app=weibo&controller=weibo&action=upload',
		fileDataName : 'Filedata',
		fileExt : '*.jpg;*.gif;*.png;',
		multi : false,
		complete : function(response, data) {
			if(response != '0') {
				window.slider.addPic(response, 1);
			} else {
				ct.error(response.msg);
			}
		}
	}).css('position', 'absolute');
	$('.weibo-checkbox').bind('click', function (obj){
		obj = $(obj.currentTarget);
		if (obj.find('input:checkbox')[0].checked) {
			obj.find('input:checkbox')[0].checked = false;
			obj.removeClass('weibo-checked');
		} else {
			obj.find('input:checkbox')[0].checked = true;
			obj.addClass('weibo-checked');
		}
	});
});