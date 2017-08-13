var form = $('form#memberLogin');
if (form.find('[name=username]').val()){
	form.find('[name=username]').prev().addClass('hidden');
}
if (form.find('[name=seccode]').val()){
	form.find('[name=seccode]').val('验证码');
}
form.bind('submit', function(){
	var username = form.find('[name=username]').val(),
		password = form.find('[name=password]').val(),
		needSeccode = (form.find('#needseccode').css('display') !== 'none'),
		seccode = form.find('[name=seccode]').val(),
		cookietime = form.find('[name=cookietime]'),
		remember = cookietime.is(':checked'),
		cookietime = cookietime.val(),
		param = [];

	if (!username) {
		alert('请输入用户名');
		return false;
	}

	if (!password) {
		alert('请输入密码');
		form.find('[name=password]').val('密码');
		return false;
	}

	if (needSeccode && !seccode) {
		alert('请输入验证码');
		form.find('[name=seccode]').val('验证码');
		return false;
	}

	password = hex_md5(password);
	param.push('username=' + encodeURIComponent(username));
	param.push('password=' + encodeURIComponent(password));
	needSeccode && param.push('seccode=' + encodeURIComponent(seccode));
	remember && param.push('cookietime=' + encodeURIComponent(cookietime));
	$.get(APP_URL+'?app=member&controller=index&action=ajaxlogin&jsoncallback=?&' + param.join('&'), null, function(json) {
		if (json && json.state) {
			form.parents('.ui-dialog-grid').find('[i="close"]').trigger('click');
			if ($('.interview-comment').length > 0) {
				$('.interview-comment .login').hide();
				$('.interview-comment .loginstatus').show();
			} else {
				if (json.message && json.message.indexOf('<script') > -1) {
					var src;
					if (src = /<script[^>]+src="([^"]*)"/.exec(json.message)[1]) {
						$.getScript(src);
					}
				} else {
					loginForm.update();
				}
			}
		} else {
			alert(json && json.error || '登录失败，请重新尝试');
			$('#needseccode').show();
			form.find('[name=password]').val('');
			form.find('[name=seccode]').val('验证码');
		}
	}, 'jsonp');

	return false;
});

window.loginThirdParty = function(elm, type) {
	window.loginWin = window.open('http://app.silkroad.news.cn/?app=cloud&controller=thirdlogin&action=redirect_to_authorize&type=' + type, elm.getAttribute('title'), 'width=640,height=480,location=no,menubar=no,scrollbars=yes');
	var p = setInterval(function() {
		if (loginWin.closed) {
			loginForm.getDialog().close();
			loginForm.update();
			clearInterval(p);
		}
	}, 1000);
}
window.postError = function(e) {
	$('#loginError').empty().show().append(e);
	setTimeout(function() {
		$('#loginError').hide();
	}, '3000');
}
if($('.js-ajax-login')[0]) {
    $(document).on('focus blur', '.js-focus', function(e) {
        $(this).parents('.singup-line').toggleClass('active-border-blue');
    }).on('focus', '.js-focus', function() {
        $(this).siblings('.tip').addClass('hidden');
    });
}
function update() {
	var auth, ref, thirdToken, trigger, username, _i, _j, _len, _len1, _ref, _ref1, _results, _results1;
	thirdToken = $.cookie(COOKIE_PRE + "thirdtoken");
	if (thirdToken && thirdToken.length) {
		ref = encodeURIComponent(location.href);
		location.href = APP_URL + "?app=member&controller=index&action=registerwithtoken&ref=" + ref;
	}
	auth = $.cookie(COOKIE_PRE + "auth");
	if (auth) {
		username = $.cookie(COOKIE_PRE + "username");
		_ref = this.triggers;
		_results = [];
		for (_i = 0, _len = _ref.length; _i < _len; _i++) {
			trigger = _ref[_i];
			//_results.push(trigger.login(username));
		}
		// 第三方标签
		if (this.tag === 'changyan') {
			(typeof SOHUCS !== 'undefined') && SOHUCS.reset();
		}
		return _results;
	} else {
		_ref1 = this.triggers;
		_results1 = [];
		for (_j = 0, _len1 = _ref1.length; _j < _len1; _j++) {
			trigger = _ref1[_j];
			_results1.push(trigger.logout());
		}
		return _results1;
	}
};