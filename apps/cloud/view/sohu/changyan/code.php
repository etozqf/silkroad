<!-- 向页面中 id 为"changyan_count_unit"的元素传递评论数 -->
<script type="text/javascript" src="http://assets.changyan.sohu.com/upload/plugins/plugins.count.js"></script>
<style >
.head-gold-w {display:none!important;}
</style>
<!-- 单点登录 -->
<script type="text/javascript">
(function($) {
	if (typeof dialog === 'undefined') {
		$.getScript(IMG_URL + "js/lib/cmstop.dialog.js", function() {
			if (typeof LoginForm !== 'undefined' && typeof window.loginForm === 'undefined') {
				window.loginForm = new LoginForm;
			}
		});
		var cssUrl = IMG_URL + "js/lib/dialog/style.css";
		try {
			window.document.head.innerHTML += '<link type="text/css" rel="stylesheet" href="' + cssUrl + '" />';
		} catch (e) {
			window.document.createStyleSheet(cssUrl);
		}
	}
	if (typeof window.loginForm === 'undefined') {
		$.getScript(IMG_URL + "apps/member/js/loginform.js", function() {
			if (typeof dialog !== 'undefined' && typeof window.loginForm === 'undefined') {
				window.loginForm = new LoginForm;
			}
		});
	}
	$('[data-type="ssoAction"]').parents('li').remove();
	$('#SOHUCS').attr('sid', topicid);
})(jQuery);
</script>