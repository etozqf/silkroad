(function() {
	var __bind = function(fn, me) {
		return function() {
			return fn.apply(me, arguments);
		};
	};

	this.LoginForm = (function() {
		var self;

		self = LoginForm;

		LoginForm.dialog = null;

		LoginForm.prototype.tag = null;

		LoginForm.prototype.triggers = [];

		LoginForm.prototype.login = function(tag) {
			var _this = this;
			if (tag) {
				this.tag = tag;
			}
			if (this.getDialog().isOpen) {
				return;
			}
			return $.getJSON(APP_URL + "?app=member&controller=index&action=loginform&jsoncallback=?", function(html) {
				if (!html) {
					return;
				}
				/*return _this.getDialog().open({
					html: html
				});*/
			});
		};

		LoginForm.prototype.logout = function() {
			var _this = this;
			return $.getJSON(APP_URL + "?app=member&controller=index&action=ajaxlogout&jsoncallback=?", function(json) {
				if (!(json && json.state)) {
					return alert('退出失败');
				}
				return _this.update();
			});
		};

		LoginForm.prototype.update = function() {
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
					_results.push(trigger.login(username));
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

		LoginForm.prototype.addTrigger = function(trigger) {
			return this.triggers.push(trigger);
		};

		LoginForm.prototype.getDialog = function() {
			return self.dialog;
		};

		function LoginForm() {
			this.update = __bind(this.update, this);
			this.logout = __bind(this.logout, this);
			this.login = __bind(this.login, this);
			self.dialog = new dialog({
				hasOverlay: 1,
				hasCloseIco: 1,
				width: 384,
				height: 222
			});
		}

		return LoginForm;

	})();

}).call(this);