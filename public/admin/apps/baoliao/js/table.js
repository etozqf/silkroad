jQuery.fn.extend({
	table: function() {
		var $ = jQuery,
		elm = this,
		parse = function() {
			var self = this,
			option, dbFlag;
			self.total = 0;
			var _html = function(json) {
				var string = option.template, r;
				for(var i in json) {
					r = new RegExp('\\['+i+'\\]', "g");
					string = string.replace(r, json[i]);
				}
				return $(string);
			},
			_click = function(e) {
				if (typeof(option.dbclick)=='function') {
					if (e.currentTarget == dbFlag) {
						option.dbclick(e.currentTarget);
					} else {
						dbFlag = e.currentTarget;
						setTimeout(function() {
							dbFlag = undefined;
						}, 200);
					}
				}
			},
			_page = function() {
				var _parseBox = function(i, s) {
					!s && (s = i);
					if (i != option.page) {
						$('<a href="javascript:;">'+s+'</a>').appendTo(option.pagefield).bind('click', function() {
							var index = i;
							self.reload({'page':index});
							_page();
						});
					} else {
						option.pagefield.append('<span class="current">'+i+'</span');
					}
				}
				if (option.pagefield) {
					var pageNum = Math.ceil(self.total / option.pagesize);
					if (pageNum < 2) return;
					option.pagefield.empty();
					if (pageNum < 4) {
						for (var i=1; i<pageNum+1; i++) {
							_parseBox(i);
						}
					} else {
						if (option.page < 3) {
							_parseBox(1);
							_parseBox(2);
							_parseBox(3);
							_parseBox(pageNum, '..'+pageNum);
						} else if (option.page > pageNum - 2) {
							_parseBox(1, '1..');
							_parseBox(pageNum - 2);
							_parseBox(pageNum - 1);
							_parseBox(pageNum);
						} else {
							_parseBox(1, '1..');
							_parseBox(option.page - 1);
							_parseBox(option.page);
							_parseBox(option.page + 1);
							_parseBox(pageNum, '..'+pageNum);
						}
					}
				}
			}
			self.load = function(o) {
				o.page = 1;
				o.orderby = undefined;
				self.reload(o);
			}
			self.reload = function(o) {
				option = $.extend(option, o);
				elm.empty();
				var data = {};
				option.orderby && (data.orderby = option.orderby);
				option.page && (data.page = option.page);
				data.pagesize = option.pagesize ? option.pagesize : 20;
				option.where && (data = $.extend(data, option.where));
				data['request_random'] = Math.random();
				$.get(option.url, data, function(response) {
					if (!response || !response.state) {
						return;
					}
					(typeof(option.json_callback)=='function') && option.json_callback(response);
					self.total = response.total;
					if (option.page && option.pagesize && (response.total > 0) && (response.total > option.pagesize)) {
						_page();
					} else {
						option.pagefield.empty();
					}
					$.each(response.data, function(i,k) {
						var tr = $(_html(k)).appendTo(elm);
						if (typeof(option.row_callback)=='function') {
							option.row_callback(tr, k);
						}
						tr.bind('click', function(e){
							_click(e);
						});
					});
					(typeof(option.parsed_callback)=='function') && option.parsed_callback(response);
				}, 'json');
			}
		}
		return new parse();
	}
});