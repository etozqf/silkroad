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
					$('.loginform-user-info').show().empty().append('<div class="username-area"><a class="quickLogout" href="javascript:;">退出</a></div><div class="seccode-area" style="visibility: hidden;">' + getSeccode() +'</div>');
				} else {
					$('.loginform-user-info').show().empty().append('<div class="username-area"><em>' + username + '</em>，<a class="quickLogout" href="javascript:;">退出</a></div>'+getAnonymous()+'<div class="seccode-area" style="visibility: hidden;">' + getSeccode() +'</div>');
				}
				$('.login-warn').hide();
				
			} else {
				if (!islogin) {
					$('.login-warn').hide();
					$('.loginform-user-info').show().empty().append('<span class="info"><a href="javascript:;" class="cloud-login">登录</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'+APP_URL+'?app=member&controller=index&action=register">注册</a></span><div class="seccode-area" style="visibility: hidden;">' + getSeccode()+'</div>');
				} else {
					$('.loginform-user-info').hide();
					$('.login-warn').show();
				}
			}
			$('.comment-form').find('textarea').one('click', function(e) {
				$(e.currentTarget).parents('.comment-form').find('.seccode-area').css('visibility', 'visible');
			});
			$('.quickLogout').unbind().bind('click', function() {
				logout();
			});
			loginDialog('.cloud-login', loginUpdate);
		}

		var logout = function() {
			$.getJSON(APP_URL+'?app=member&controller=index&action=ajaxlogout&jsoncallback=?', function(json){
				if(json.state) {
					$('.loginform-user-info').hide();
					$('.login-warn').show();
					if (typeof (username) != 'undefined') username = '';
					loginUpdate();
				} else {
					alert('退出失败');
				}
			});
		};

		var loginDialog = function(elm, loginCallback) {
			$(elm).unbind().bind('click', function() {
				if (typeof(cmstop.loginDialog.dialog) != 'undefined' && cmstop.loginDialog.dialog.isOpen) return;
				$.getJSON(APP_URL + '?app=member&controller=index&action=loginform&jsoncallback=?', function(html) {
					if (html) {
						cmstop.loginDialog.dialog = new dialog({hasOverlay:1, html:html, hasCloseIco:1, width:384, height:222});
						cmstop.loginDialog.dialog.open();
					}
				});
			});
		};

		var getAnonymous = function() {
			return '<label><input type="checkbox" name="anonymous" value="1" style="vertical-align:middle;" />匿名发表</label>';
		};
		loginUpdate();
		cmstop.loginDialog.loginUpdate = loginUpdate;
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