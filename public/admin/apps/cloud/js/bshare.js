window.bshare = {
	bind : function () {
		ct.formDialog({title:'已有账号绑定', width:300}, '?app=cloud&controller=bshare&action=bind', function(json) {
			if (json.state) {
				bshare.login();
				return true;
			} else {
				ct.error('绑定失败');
			}
		});
	},
	reg : function () {
		ct.formDialog({title:'快速注册新账号', width:300}, '?app=cloud&controller=bshare&action=reg', function(json) {
			if (json.state) {
				bshare.login();
				return true;
			} else {
				ct.error('绑定失败');
			}
		});
	},
	login : function () {
		$.get('?app=cloud&controller=bshare&action=get', {}, function(json) {
			if (typeof(json.uuid) != 'undefined') {
				$('#bshareUuid').html(json.uuid);
				$('#bshareLogining').hide();
				$('#bshareLogined').show();
				bshare.uuid = json.uuid;
			} else {
				$('#bshareLogined').hide();
				$('#bshareLogining').show();
			}
		}, 'json');
	},
	logout : function () {
		$.get('?app=cloud&controller=bshare&action=logout', {}, function() {
			bshare.login();
		}, 'json');
	}
}