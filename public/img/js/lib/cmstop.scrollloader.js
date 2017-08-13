/**
 *	@class 滚动加载
 *	@requires jquery.js
 *	@param {paint object}options:
 *		(jQuery dom)panel
 *		size
 *		url
 *		template
 *		offsetField
 *		sizeField
 *		moreField
 *		method
 *		rowCallback
 *		errorCallback
 */


(function() {
	var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

	this.ScrollLoader = (function() {
		var bindScroll, build;

		ScrollLoader.prototype.offset = 0;

		ScrollLoader.prototype.options = null;

		ScrollLoader.prototype.more = false;

		build = function(data, html) {
			var key, r;
			for (key in data) {
				r = new RegExp("\\[" + key + "\\]", 'img');
				html = html.replace(r, data[key]);
			}
			return $(html);
		};

		bindScroll = function(self) {
			return self.options.panel.bind('scroll', {
				handle: self.load
			}, function(event) {
				var $this;
				$this = $(this);
				if ($this.scrollTop() + $this.height() >= $this.attr('scrollHeight')) {
					return event.data.handle();
				}
			});
		};

		ScrollLoader.prototype.load = function(offset) {
			var data,
				_this = this;
			this.offset = (offset != null ? offset : offset = this.offset + this.options.size);
			data = {};
			data[this.options.offsetField] = this.offset;
			data[this.options.sizeField] = this.options.size;
			return $[this.options.method](this.options.url, data, function(req) {
				var item, li, _i, _len, _ref;
				if (!req.state) {
					if (typeof _this.options.errorCallback === 'function') {
						_this.options.errorCallback(req.error || '');
					}
					return;
				}
				if (_this.offset === 0) {
					_this.options.panel.empty();
				}
				_ref = req.data;
				for (_i = 0, _len = _ref.length; _i < _len; _i++) {
					item = _ref[_i];
					li = build(item, _this.options.template);
					_this.options.panel.append(li);
					if (typeof _this.options.rowCallback === 'function') {
						_this.options.rowCallback(li, item);
					}
				}
				_this.more = !!req[_this.options.moreField];
				if (_this.more) {
					return bindScroll(_this);
				}
			}, 'json');
		};

		function ScrollLoader(options) {
			this.load = __bind(this.load, this);
			if (options.offsetField == null) {
				options.offsetField = 'offset';
			}
			if (options.sizeField == null) {
				options.sizeField = 'size';
			}
			if (options.size == null) {
				options.size = 20;
			}
			if (options.method == null) {
				options.method = 'get';
			}
			if (options.moreField == null) {
				options.moreField = 'more';
			}
			this.options = options;
		}

		return ScrollLoader;

	})();

}).call(this);