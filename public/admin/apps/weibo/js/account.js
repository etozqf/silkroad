var sortNum = 0;
var row_template = new Array(
	'<tr id="account_{weiboid}">',
		'<td width="60" style="cursor: move;" class="sort"><span></span><input type="hidden" name="sort" value="{sort}" /></td>',
		'<td width="160">{name}</td>',
		'<td width="120">{typename}</td>',
		'<td width="240" class="expires">{expires}&nbsp;<a href="javascript:;" onclick="weiboAccountUpdate(\'{type}\',{weiboid})">(重新授权)</a></td>',
		'<td width="70">',
			'<div class="weibo-switch">{state}</div>',
		'</td>',
		'<td width="60"><img class="weibo-delete" src="images/delete.gif" alt="删除" title="删除" /></td>',
	'</tr>'
).join('\r\n');
var accountTable = new ct.table('#weibo_account', {
	rowIdPrefix : 'account_',
	template : row_template,
	baseUrl  : '?app=weibo&controller=weibo&action=ls',
	rowCallback : function(id, tr) {
		// sort
		tr.children('.sort').children('span').html(++sortNum);
		// state
		window.ctswitch(tr.find('.switch')[0]);
		tr.find('.switch').click(function(obj) {
			$.get('?app=weibo&controller=weibo&action=set_state', {
					'weiboid':id, 
					'state':$(obj.currentTarget).attr('checked') * 1
				}, function(json) {
					if (!json.state) {
						ct.error('操作失败');
						$(obj.currentTarget).attr('checked', !$(obj.currentTarget).attr('checked'));
					}
				}, 'json'
			);
			tr.click();
		});
		// delete
		tr.find('.weibo-delete').bind('click', function() {
			weiboAccountRemove(id);
		});
	},
	jsonLoaded : function(json) {
		for (var i=0,item;item=json.data[i];) {
			if (item.state == '1') {
				json.data[i++]['state'] = '<input class="switch" type="checkbox" name="" value="1" checked="checked" />';
			} else {
				json.data[i++]['state'] = '<input class="switch" type="checkbox" name="" value="1" />';
			}
		}
	}
});

var weiboAccountAdd = function(type) {
	window.authWin = window.open('?app=weibo&controller=api&action=auth&type='+type, '微博授权', 'width=640,height=480,location=no,menubar=no,scrollbars=yes');
	var report = setInterval(function() {
		if (window.authWin.closed) {
			sortNum = 0;
			accountTable.reload();
			clearInterval(report);
		}
	}, 1000);
}

var weiboBind = function() {
	ct.error('必须先绑定应用信息');
	ct.assoc.open('?app=system&controller=setting&action=api', 'newtab');
}

var weiboAccountUpdate = function(type, id) {
	window.authWin = window.open('?app=weibo&controller=api&action=auth&type='+type+'&weiboid='+id, '重新授权', 'width=640,height=480,location=no,menubar=no,scrollbars=yes');
	var report = setInterval(function() {
		if (window.authWin.closed) {
			sortNum = 0;
			accountTable.reload();
			clearInterval(report);
		}
	}, 1000);
}

var weiboAccountRemove = function(id) {
	cmstop.confirm('确定要取消这个微博绑定吗?', function() {
		$.get('?app=weibo&controller=weibo&action=remove', {weiboid:id}, function(json) {
			if (!json.state) {
				ct.error('删除失败');
			}
			sortNum = 0;
			accountTable.reload();
		}, 'json');
	})
}

$(document).ready(function() {
	accountTable.load();
	var startPostion, stopPostion, inSorting = false
	$('#sortable').sortable({
		'handle':'td:first-child',
		'axis':'y',
		'start':function(e, u) {
			if (inSorting) {
				return false;
			}
			startPostion = $('#sortable').find('tr').index(u.item);
		},
		'stop':function(e,u) {
			stopPostion = $('#sortable').find('tr').index(u.item);
			var deltaPostion = stopPostion - startPostion, sortSet = new Array(), mov, obj;
			var getId = function(jObj){
				return jObj.attr('id').substr(8);
			}
			if (deltaPostion > 0) {
				// 下移
				mov = u.item.prev().children('.sort').children('input').val() - u.item.children('.sort').children('input').val();
				sortSet.push('['+getId(u.item)+','+mov+']');
				for(var i = 0; i < deltaPostion; i++) {
					obj = (obj || u.item).prev();
					sortSet.push('['+getId(obj)+',-1]');
				}
			} else if (deltaPostion < 0) { 
				// 上移
				deltaPostion = -deltaPostion;
				mov = u.item.next().children('.sort').children('input').val() - u.item.children('.sort').children('input').val();
				sortSet.push('['+getId(u.item)+','+mov+']');
				for(var i = 0; i < deltaPostion; i++) {
					obj = (obj || u.item).next();
					sortSet.push('['+getId(obj)+', 1]');
				}
			}
			sortSet = '['+sortSet.join(',')+']';
			$.get('?app=weibo&controller=weibo&action=sort', {sort:sortSet}, function (json) {
				if (!json.state) {
					ct.error('异常错误');
				}
				sortNum = 0;
				accountTable.reload();
			}, 'json');
		}
	});
});