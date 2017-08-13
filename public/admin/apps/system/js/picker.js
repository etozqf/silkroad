(function(){
var _box,
	_port, _multi,
	_showMoreLock = false, _count=0,
	_total=0, _page = 0, _lastWhere = '', _checked=[],
	_table, _tbody, _tfoot, _template, _PORTS = {};
function loading(){
	message('<img src="images/loading.gif" />');
}
function message(msg){
	if (_tfoot) {
		_tfoot.find('td').html(msg);
		_tfoot.show();
	}
}
function hide(){
	if (_tfoot) {
		_tfoot.hide();
		_tfoot.find('td').empty();
	}
}
function init(){
	_box = $('#box');
	_multi = /[?&]multi=/i.test(location.search);
	var where = $('#where');
	var inited = {};
	var form = null;
	var fragment = $('<div></div>');
	var url = '?app=system&controller=port&action=request&type=picker';
	if (/[?&]modelid=([\d,]+)/.test(location.search)) {
		url += '&modelid=' + RegExp.$1;
	}
    if (/[?&]catid=([\d,]+)/.test(location.search)) {
        url += '&catid=' + RegExp.$1;
    }
	var tabs = $('.tabs li').click(function(){
		var t = $(this);
		var port = t.attr('port');
		if (port == _port) return;
		form && form.appendTo(fragment);
		if (!t.hasClass('active')) {
			tabs.removeClass('active');
			t.addClass('active');
			go(port);
		}
	});
	tabs.length ? tabs.eq(0).click() : go('cmstop');
	function go(port){
		_port = port;
		_tbody && _tbody.empty();
		if (port in inited) {
			where.html(form = inited[port]);
			query(form);
			return;
		}
		loading();
		$.getJSON(url+'&port='+port, function(ret){
			if (port != _port) {
				return;
			}
			if (!ret.state) {
				message(ret.error);
				return;
			}
			fet(ret.assets, function(){
				if (port != _port) {
					return;
				}
				if (!(port in _PORTS)) {
					return message('Invalid port "'+id+'", unregistered in script.');
				}
				var view = $(ret.html);
				if (!(form = view.filter('form:first')).length
					&& !(form = view.find('form:first')).length)
				{
					return message('html slice must be contain a form'); 
				}
				inited[port] = form;
				where.html(form);
				_PORTS[port](form);
				query(form);
			});
		});
	}
	
	_table = $('\
	<table cellpadding="0" cellspacing="0" class="table_list">\
		<thead>\
			<th class="bdr" width="30">'+(_multi ? '<input type="checkbox" />' : '&nbsp;')+'</th>\
			<th>标题</th>\
			<th width="50">权重</th>\
			<th width="120">时间</th>\
		</thead>\
		<tbody></tbody>\
	</table>').appendTo(_box);
	_tbody = _table.find('tbody');
	_tfoot = $('<tfoot><tr><td class="empty" colspan="4" style="text-align:center"></td></tr></tfoot>');
    _tbody.after(_tfoot);
	_template = '\
	<tr>\
		<td class="t_c"><input type="'+(_multi ? 'checkbox' : 'radio')+'" /></td>\
		<td><a href="{url}" target="_blank" tips="{tips}">{title}</a></td>\
		<td class="t_r">{weight}</td>\
		<td class="t_r">{date}</td>\
	</tr>';
	_multi && _table.find('input').click(function(){
		_tbody.find('tr').trigger(this.checked ? '_check_' : '_uncheck_');
	});
	loading();
	_box.bind('mousewheel.scrolltable',function(e, delta){
		delta < 0 && scroll();
		e.stopPropagation();
	}).bind('scroll.scrolltable', scroll);
}

function scroll(){
	if (!_showMoreLock && _count < _total 
		&& _box.scrollTop() + _box.height() > _box[0].scrollHeight - 20)
	{
		loadPage();
	}
	return false;
}
function check(tr, input, json){
	tr.addClass('active');
	_checked.indexOf(json) == -1 && _checked.push(json);
	input[0].checked = true;
}
function uncheck(tr, input, json){
	tr.removeClass('active');
	var i = _checked.indexOf(json);
	i == -1 || _checked.splice(i, 1);
	input[0].checked = false;
}
function buildRow(json){
	var tr = $(_template.replace(/{(\w+)}/gm, function(s,k){
		return (k in json) ? json[k] : s;
	}));
	var input = tr.find('input:'+(_multi ? 'checkbox' : 'radio')+':first');
	tr.click(_multi ? function(){
		tr.hasClass('active') ? uncheck(tr, input, json) : check(tr, input, json);
	} : function(){
		_tbody.find('tr.active').triggerHandler('_uncheck_');
		check(tr, input, json);
	}).bind('_uncheck_',function(){
		uncheck(tr, input, json);
	}).bind('_check_',function(){
		check(tr, input, json);
	});
	_multi || tr.dblclick(ok);
	
	var a = tr.find('a');
	a.attrTips('tips');
	if (json.thumb && json.thumb != 'null') {
		var img = $('<img thumb="'+json.thumb+'" style="margin-right:3px;vertical-align:middle;" src="images/thumb.gif"/>');
		img.floatImg({url:UPLOAD_URL,height:200});
		a.before(img);
	}
	return tr;
}
function query(where){
	_checked = [];
	_lastWhere = where.jquery ? where.serialize() : where;
	_total = 0;
	_page = 1;
	_count = 0;
	_query(_lastWhere+'&page='+_page, queryOk);
}
function _query(where, success) {
	var port = _port;
	loading();
	$.ajax({
		url:'?app=system&controller=port&action=page&port='+port,
		type:'POST',
		dataType:'json',
		data:where,
		beforeSend:function(){_showMoreLock=1;},
		success:function(json){
			port == _port && success(json);
		},
		complete:function(xhr, status){
			_showMoreLock = 0;
			port == _port && hide();
		}
	});
}
function pageOk(json){
	var l;
	if (!json) return false;
	if (json.total) _total = parseInt(json.total, 10);
	if (json.data && (l = json.data.length)) {
		_count += l;
		for (var i=0;i<l;i++) {
			_tbody.append(buildRow(json.data[i]));
		}
	}
}
function queryOk(json){
	_tbody.empty();
	if (json && json.data) {
		_total = parseInt(json.total);
		_page = 1;
		_count = json.data ? json.data.length : 0;
		for(var i=0;i<_count;i++){
			_tbody.append(buildRow(json.data[i]));
		}
	} else {
		_total = 0;
		_page = 1;
		_count = 0;
	}
}
function loadPage(){
	_query(_lastWhere+'&page='+(++_page), pageOk);
}
function ok(){
	if (! _checked.length) {
		ct.warn('未选择');
		return;
	}
	if (parent) {
		if (window.dialogCallback && dialogCallback.ok) {
			dialogCallback.ok(_checked, _port);
		} else {
			window.getDialog && getDialog().dialog('close');
		}
	}
}
function cancel(){
	if (parent) {
		window.getDialog && getDialog().dialog('close');
	}
}
window.PICKER = {
	ok:ok,
	cancel:cancel,
	register:function(key, fn){
		_PORTS[key] = fn;
	},
	query:query,
	checked:function(){
		return _checked;
	},
	init:init
};
})();