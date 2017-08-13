// 已废弃，只用于兼容旧版本

// 如果前台没有cmstop时
window.cmstop = (typeof(cmstop) == 'undefined') ? {} : cmstop;
if (cmstop.loginDialog == undefined) {
	cmstop.loginDialog = function() {
		var username;
		var loginUpdate = function() {
			// 初次授权的应用请求跳转至绑定注册页面
			if ($.cookie(COOKIE_PRE+'thirdtoken') && $.cookie(COOKIE_PRE+'thirdtoken').length > 0) {
				location.href = APP_URL + "?app=member&controller=index&action=registerwithtoken&ref=" + encodeURIComponent(location.href);
			}
			// 状态判断
			if ($.cookie(COOKIE_PRE+'auth')) {
				username = $.cookie(COOKIE_PRE+'username');
				if(username == null) {
					$('.loginform-user-info').show().empty().append('<a class="fl-l quickLogout" href="javascript:;">退出</a><div class="seccode-area" style="visibility: hidden;">' + getSeccode() +'</div>');
				} else {
					$('.loginform-user-info').show().empty().append('<a class="name fl-l" href="'+APP_URL+'?app=space&controller=panel&action=index">' + username + '</a><a class="fl-l quickLogout" href="javascript:;">退出</a>'+getAnonymous()+'<div class="seccode-area" style="visibility: hidden;">' + getSeccode() +'</div>');
				}
				$('.login-warn').hide();
				
			} else {
				if (!islogin) {
					$('.login-warn').hide();
					$('.loginstatus').show();
				} else {
					$('.loginstatus').hide();
					$('.login-warn').show();
				}
			}
			$('.comment-form').find('textarea').one('click', function(e) {
				$(e.currentTarget).parents('.comment-form').find('.seccode-area').css('visibility', 'visible');
			});
			$('.quickLogout').unbind().bind('click', function() {
				logout();
			});
		}

		var logout = function() {
			$.getJSON(APP_URL+'?app=member&controller=index&action=ajaxlogout&jsoncallback=?', function(json){
				if(json.state) {
					$('.loginstatus').hide();
					$('.login-warn').show();
					if (typeof (username) != 'undefined') username = '';
					loginUpdate();
				} else {
					alert('退出失败');
				}
			});
		};

		var getAnonymous = function() {
			return '<label><input type="checkbox" name="anonymous" value="1" style="vertical-align:middle;" />匿名发表</label>';
		};
		loginUpdate();
	}
}

// 兼容访谈
window.loginForm = {
	getDialog: function() {
		return cmstop.loginDialog.dialog;
	},
	update: function() {
		cmstop.loginDialog.loginUpdate();
	}
};