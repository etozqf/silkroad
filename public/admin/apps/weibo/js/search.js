window.result = {};
window.weibo = {
	content : {},
	page : 1,
	hasNext : 0,
	type : 'tencent_weibo',
	push : function (html) {
		weibo.selectList.append('<tr><td>' + html + '</td><td><img class="hand delete" width="16" height="16" alt="删除" title="删除" src="images/delete.gif"></td></tr>');
		var id,firstLetter;
		var tmp_array = weibo.selectList.find('.weibo-search-box:last').attr('class').split(' ');
		while (id = tmp_array.pop()) {
			firstLetter = id.substr(0, 1);
			if (firstLetter == 't' || firstLetter == 's') break;
		}
		weibo.selectList.find('tr:last').find('.delete').unbind().bind('click', function () {
			weibo.pop(id);
		});
		result[id] = weibo.content[id.split('_')[1]];
	},
	pop : function (id) {
		if (weibo.list.find('.'+id).length > 0) {
			weibo.list.find('.'+id).parents('tr').find(':checkbox')[0].checked = false;
		}
		weibo.selectList.find('.'+id).parents('tr').remove();
		if (typeof(result[id]) != 'undefined') delete(result[id]);
	},
	search : function (form, callback, force) {
		if (form.search_type.value == 'user' && !force) {
			weibo.get_user(form, callback);
			return false;
		}
		if (weibo.list.find('tr').length > 0) {
			var pageString = '&pageflag=1&pagetime='+weibo.list.find('tr:last').find('.weibo-search-time').attr('time')+'&lastid=';
			var tmp_array = weibo.list.find('tr:last').find('.weibo-search-box').attr('class').split(' ');
			while (id = tmp_array.pop()) {
				if (id[0] == 't' || id[0] == 's') break;
			}
			pageString += id;
		} else {
			var pageString = '&pageflag=0&lastid=0&pagetime=0';
		}
		window.loading = true;
		$.get('?app=weibo&controller=api&action=search', $(form).serialize() + '&page=' + weibo.page + '&weibo_type=' + weibo.type + pageString, function (json) {
			if (json.state && json.data) {
				if (weibo.type == 'tencent_weibo') {
					weibo.hasNext = json.data.hasnext;
				} else if (weibo.type == 'sina_weibo') {
					weibo.hasNext = json.next;
				}
				try {
					weibo.parse(json.data);
				} catch (exc) {
					console.info(exc);
					ct.error('异常错误');
				}
			} else {
				ct.error(typeof(json.error)=='string' ? json.error : '读取微博信息失败');
			}
			window.loading = false;
		}, 'json');
		if (typeof(callback) == 'function') callback();
	},
	parse : function (data) {
		if (weibo.type == 'tencent_weibo') {
			$.each(data.info, function (i, item) {
				weibo.content[item.id] = item;
				weibo.content[item.id]['weibo_type'] = 'tencent_weibo';
				var checked = !!result['tencent_'+item.id] ? ' checked="checked"' :''
				item.vip = !!item.isvip ? 'weibo-search-tencent-vip-1' : '';
				if (item.image) {
					var image = '<img class="weibo-search-image" src="'+item.image[0]+'/120" alt="" />';
				} else {
					var image = '';
				}
				var tr = $(new Array('<tr>',
					'<th width="20" valign="top"><input type="checkbox" name="" value="'+checked+'" style="margin-top:14px;" /></th>',
					'<td>',
						'<div class="weibo-search-box tencent_'+item.id+'">',
							'<div class="weibo-search-head"><img src="'+item.head+'/40" alt="" onerror="this.src=\''+IMG_URL+'images/blank.png\'" /></div>',
							'<div class="weibo-search-panel">',
								'<div class="weibo-search-info">',
									'<div class="weibo-search-name">'+item.nick+'</div>',
									'<div class="weibo-search-ico-tencent '+item.vip+'"></div>',
									'<div class="weibo-search-time" time="'+item.timestamp+'">'+time2String(item.timestamp)+'</div>',
								'</div>',
								'<div class="weibo-search-content">'+formatTencentText(item.text)+'</div>',
								image,
							'</div>',
						'</div>',
					'</td>',
				'</tr>').join('\r\n')).appendTo(weibo.list);
				//tr.find(':checkbox').css('top', tr.position().top+10+'px').css('position', 'absolute').css('left', '14px');
			});
		} else if (weibo.type == 'sina_weibo') {
			$.each(data.data, function(i, item) {
				weibo.content[item.id] = item;
				weibo.content[item.id]['weibo_type'] = 'sina_weibo';
				var checked = !!result['sina'+item.id] ? ' checked="checked"' : ''
				var date = new Date(parseInt(item.created));
				item.created = date.getTime() / 1000;
				item.vip = item.user.status == 0 ? '' : 
					(item.user.status == 1 ? ' weibo-search-sina-vip-0' : 
					' weibo-search-sina-vip-1');
				if (item.thumb) {
					var image = '<img class="weibo-search-image" src="'+item.thumb+'" alt="" />';
				} else {
					var image = '';
				}
				var tr = $(new Array('<tr>',
					'<th valign="top"><input type="checkbox" name="" value="'+checked+'" style="margin-top:14px;" /></th>',
					'<td>',
						'<div class="weibo-search-box sina_'+item.id+'">',
							'<div class="weibo-search-head"><img src="'+item.user.avatr+'" alt="" onerror="this.src=\''+IMG_URL+'images/blank.png\'" width="40" height="40" /></div>',
							'<div class="weibo-search-panel">',
								'<div class="weibo-search-info">',
									'<div class="weibo-search-name">'+item.user.name+'</div>',
									'<div class="weibo-search-ico-sina'+item.vip+'"></div>',
									'<div class="weibo-search-time" time="'+item.created+'">'+time2String(item.created)+'</div>',
								'</div>',
								'<div class="weibo-search-content">'+formatSinaText(item.text)+'</div>',
								image,
							'</div>',
						'</div>',
					'</td>',
				'</tr>').join('\r\n')).appendTo(weibo.list);
				//tr.find(':checkbox').css('top', tr.position().top+10+'px').css('position', 'absolute').css('left', '14px');
			});
		}
		weibo.list.find(':checkbox').unbind().bind('click', function(e) {
			var input = $(e.currentTarget);
			setTimeout(function(){
				if (input[0].checked) {
					var html = input.parent().next().html();
					weibo.push(html);
				} else {
					var id;
					var tmp_array = input.parent().next().children('.weibo-search-box').attr('class').split(' ');
					while (id = tmp_array.pop()) {
						if (id[0] == 't' || id[0] == 's') break;
					}
					weibo.pop(id);
				}
			},100);
			e.stopPropagation();
		});
		weibo.list.find('td').bind('click', function(e) {
			$(e.currentTarget).parent('tr').find(':checkbox').click();
			e.stopPropagation();
		});
	},
	get_user : function(form, callback, page) {
		if (window.loading) {
			return;
		}
		window.loading = true;
		page = page || 1;
		$.get('?app=weibo&controller=api&action=get_user&weibo_type='+weibo.type, {
			"keyword":form.keyword.value,
			"page":page
		}, function(json) {
			window.loading = false;
			if(!json.state) {
				ct.error('微博接口调用失败');
				return;
			}
			if (!json.data || json.data.totalnum==0) {
				ct.ok('指定用户不存在');
				return;
			}
			$('#prev_user').hide();
			$('#next_user').hide();
			if (json.data.hasnext%2) {
				$('#next_user').one('click', function() {
					$('#get_user-list').empty();
					weibo.get_user(form, callback, page+1);
				}).show();
			}
			if (json.data.hasnext>1) {
				$('#prev_user').one('click', function() {
					$('#get_user-list').empty();
					weibo.get_user(form, callback, page-1);
				}).show();
			}
			$('#get_user').show();
			$('#get_user_keyword').html(form.keyword.value);
			var list = $('#get_user-list').empty();
			if (weibo.type == 'tencent_weibo') {
				var template = new Template(new Array('<li>',
					'<div class="weibo-search-head">',
						'{%head || ""%}',
					'</div>',
					'<div class="weibo-getuser-info">',
						'<span class="weibo-search-name">',
							'{%nick || ""%}',
							'{%vip || ""%}',
						'</span>',
						'<span class="weibo-getuser-name">@{%name||""%}</span>',
					'</div>',
				'</li>').join('\r\n'));
				$.each(json.data.info, function(i, k){
					$(template.render({
						'head': k.head?'<img src="'+k.head+'/40" alt="" />':'',
						'nick': k.nick,
						'vip': k.isvip?'<div class="weibo-search-ico-tencent weibo-search-tencent-vip-1"></div>':'',
						'name': k.name
					})).appendTo(list).one('click', function(){
						form.keyword.value = k.name;
						weibo.search(form, callback, 1);
						$('#get_user').hide();
						$('#changeuser').unbind();
					});
				});
			} else if(weibo.type == 'sina_weibo') {
				var template = new Template('<li><div class="weibo-getuser-name"><span class="weibo-getuser-namestring">{%screen_name || ""%}</span>&nbsp;&nbsp;<span title="粉丝">{%followers_count || "0"%}</span></div></li>');
				$.each(json.data.info, function(i, k){
					$(template.render({
						'screen_name': k.name,
						'followers_count': k.followers_count
					})).appendTo(list).one('click', function(){
						form.keyword.value = k.name;
						weibo.search(form, callback, 1);
						$('#get_user').hide();
						$('#changeuser').unbind();
					});
				});
			}
		}, 'json');
	}
}
var time2String = function (timeStamp) {
	var today = new Date();
	var date = new Date();
	date.setTime(timeStamp * 1000);
	topdayTimeStamp = parseInt(today.getTime() / 1000);
	var detlaTime = topdayTimeStamp - timeStamp;
	// 一小时内
	if (detlaTime < 60) {
		return detlaTime + '秒前';
	};
	if (detlaTime < 3600) {
		return parseInt(detlaTime / 60) + '分' + (detlaTime % 60) + '秒前';
	};
	// 今天
	morning = new Date();
	morning.setHours(0 ,0, 0, 0);
	morningTimeStamp = morning.getTime() / 1000;
	if (timeStamp >= morningTimeStamp) {
		return '今天' + date.getHours() + '时' + date.getMinutes() + '分';
	};
	// 昨天
	if (timeStamp >= (morningTimeStamp - 86400)) {
		return '昨天' + date.getHours() + '时' + date.getMinutes() + '分';
	};
	// 今年
	if (date.getFullYear() == today.getFullYear()) {
		return (date.getMonth() + 1) + '月' + date.getDate() + '日' + ' ' + date.toTimeString().substr(0,8);
	};
	// 更早
	return date.getFullYear() + '年' + (date.getMonth() + 1) + '月' + (date.getDay() + 1) + '日' + ' ' + date.toTimeString().substr(0,8);
}

var weiboSearch = function (form, type) {
	weibo.page = 1;
	weibo.list.empty();
	weibo.search(form);
}

function formatTencentText(text) {
    return text.replace(/(#([^#]+)#)/g, function() {
        return '<a href="http://k.t.qq.com/k/' + encodeURIComponent(RegExp.$2) + '" target="_blank">' + RegExp.$1 + '</a>';
    }).replace(/(@([^\s:\b]+))/g, function() {
        return '<a href="http://t.qq.com/' + RegExp.$2 + '" target="_blank">' + RegExp.$1 + '</a>';
    });
}

function formatSinaText(text) {
    return text.replace(/(https?:\/\/[^\s\b]+)/g, function() {
        return '<a href="' + RegExp.$1 + '" target="_blank">' + RegExp.$1 + '</a>';
    }).replace(/(#([^#]+)#)/g, function() {
        return '<a href="http://s.weibo.com/weibo/' + encodeURIComponent(RegExp.$2) + '" target="_blank">' + RegExp.$1 + '</a>';
    }).replace(/(@([^\s:\b]+))/g, function() {
        return '<a href="http://weibo.com/n/' + RegExp.$2 + '" target="_blank">' + RegExp.$1 + '</a>';
    });
}

$(document).ready(function () {
	ct.listenAjax();
	$('select').selectlist();
	weibo.selectList = $('#weibo_select_list');
	weibo.list = $('#weibo_list');
	weibo.list.find(':checkbox').unbind().bind('click', function(e) {
		var input = $(e.currentTarget);
		if (input[0].checked) {
			var html = input.parent().next().html();
			weibo.push(html);
		} else {
			var id;
			var tmp_array = input.parent().next().children('.weibo-search-box').attr('class').split(' ');
			while (id = tmp_array.pop()) {
				if (id[0] == 't' || id[0] == 's') break;
			}
			weibo.pop(id);
		}
	});

	$('#scroll_div').scroll(function(){
		var o = $('#scroll_div');
		if (o.scrollTop()+o.height() > o.get(0).scrollHeight - 90)
		{
			if (window.loading ) return;
			if (window.show_more_lock) return;
			if (!weibo.hasNext) return;
			window.show_more_lock = true;	// 时间锁,避免瞬间执行多次
			weibo.page++;
			weibo.search($('#search_form')[0], function() {
				setTimeout(function() {
					window.show_more_lock = false;
				}, 500);
			}, 1);
		}
	});	
});