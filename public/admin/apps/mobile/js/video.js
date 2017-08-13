var contentid = ct.getParam('contentid', location.href),
setHotVideo = function(button) {
	var url = '?app=mobile&controller=video&action=sethot';
	button = $(button),
	action = button.attr('data-action');
	$.post(url, {
		contentid: contentid,
		action: action
	}, function(json){
		if (json.state) {
			if ((action == 'set')) {
				button.attr('data-action', 'revoke').html('取消热门');
			} else {
				button.attr('data-action', 'set').html('设置热门');
			}
			ct.ok('设置成功')
		} else {
			ct.error('操作异常');
		}
	}, 'json');
};