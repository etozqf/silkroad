var list = {
	'count': 0,
	'total': 0,
	'page': 0,
	'keyword': $('#keyword'),
	'container': $('#table_list'),
	'condition': {},
	'length': 0,
	'request': function(p, noConsole) {
		var page = p || 1,
		data = list.condition;
		data['page'] = page;
		data['keyword'] = list.keyword.val();
		if (!data['keyword']) return;
		$.get(api_url+'spiderVideo', data, function(response) {
			if (response.state && response.total!='0') {
				// soku有个限制,显示页数最大为35页(16*35=560)
				list.total = Math.min(parseInt(response.total, 10), 560);
				list.page = page;
				$.each(response.data, function(i,k) {
					if (!k || sourceAllowed.indexOf(k.source) == -1) {
						return true;
					}
					list.container.append(list.build(k));
					list['length']++;
				});
				if (list['length'] < 12) {
					list.request(list.page + 1, true);
					return;
				} else {
					list.count += list['length'];
					list['length'] = 0;
				}
				if (list.count < list.total) {
					list.loadMore();
				}
			} else {
				if (!noConsole) {
					ct.error(response.error || '搜索不到匹配的结果');
				}
			}
		}, 'json');
	},
	'build': function(data) {
		var html = new Array('<div class="video-search-item">',
			'<div class="video-search-item-thumb">',
				'<a class="choose" href="'+(data.url||'')+'" title="'+(data.title||'')+'">',
					'<img src="'+(data.thumb||'')+'" width="140" height="90" alt="" onerror="this.src=\'images/space.gif\'" />',
					'<div class="video-search-item-overlay"></div>',
					'<div class="video-search-item-time">'+(data.date||'')+'</div>',
					'<div class="video-search-item-preview" url="'+(data.url||'')+'"></div>',
				'</a>',
			'</div>',
			'<div class="video-search-item-title choose"><a href="'+(data.url||'')+'" title="'+(data.title||'')+'">'+(data.shorttitle||'')+'</a></div>',
			'<div class="video-search-item-source">来源: <span>'+(data.source||'')+'</span></div>',
		'</div>').join('\r\n');
		html = $(html);
		html.find('.video-search-item-preview').bind('click', function(e) {
			e.stopPropagation();
			var obj = $(e.currentTarget);
			window.open(obj.attr('url'));
			return false;
		});
		html.find('.choose').bind('click', function(e) {
			e.stopPropagation();
			var obj = $(e.currentTarget),
			time = (function(time) {
				var result = 0, i = 0;
				if (!time) return '';
				time = time.split(':');
				while (time.length>0) {
					result += time.pop() * Math.pow(60, i++);
				}
				return result;
			})(obj.children('.video-search-item-time').html());
			_url = obj.attr('href');
			if (_url.indexOf('soku') != -1) {
				// 过滤soku前缀
				_url = 'http'+_url.split('http')[2];
			}
			$.get(api_url+'spiderContent', {
				'url': _url
			}, function(response) {
				if (response.state && response.model==4) {
					response['time'] = time;
					window.dialogCallback.ok(response, _url);
				} else {
					ct.error('视频信息分析失败');
				}
			}, 'json');
			return false;
		});
		return html;
	},
	'loadMore': function() {
		if (list.count < list.total) {
			list.bindScroll();
		}
	},
	'bindScroll': function() {
		list.container.parent().unbind().bind('scroll', function(event) {
			var obj = event.currentTarget,
			scrollHeight = obj.scrollTopMax || obj.scrollHeight - obj.clientHeight;
			if (scrollHeight == obj.scrollTop) {
				list.container.unbind();
				list.request(list.page + 1, true);
			}
		});
	}
};
$(function() {
	$('.video-search-cond > a').bind('click', function(e) {
		var a = $(e.currentTarget),data;
		if (a.hasClass('cur')) return;
		a.parent().children('.cur').removeClass('cur');
		a.addClass('cur');
		data = a.attr('data').split('|');
		list.condition[data[0]] = data[1];
		list.container.parent().unbind()[0].scrollTop = 0;
		list.container.empty();
		list.request(list.page);
	});
	$('#search_form').bind('submit', function() {
		list.container.parent().unbind()[0].scrollTop = 0;
		list.container.empty();
		list.request();
		return false;
	}).submit();
});