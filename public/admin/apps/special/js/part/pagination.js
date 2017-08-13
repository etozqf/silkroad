var Pagination = (function(){

function toInt(num) {
	num = parseInt(num);
	return isNaN(num) ? 0 : num;
}
var FX_SPEED = 250;
return function(place, size, pageSize, pageRender, itemRender){
	var _loading = {}, _runtime,
		_data = null,
		_url = null, _where = null, _isPageRequest = 0,
		_pageInited = 0, _pageCache = {}, _total = 0,
		_pageCount = 1, _current = -1, _xhr = null,
		$box = $('<div class="diy-pagination"></div>').appendTo(place).css(size),
		$pagination = $('<div class="diy-pagination-nav"></div>').appendTo($box);

	function reinit(){
		_runtime = (new Date).getTime();
		$.each(_pageCache, function(k, p){
			p.remove();
			_pageCache[k] = null;
		});
		_xhr && (_xhr.abort(), _xhr = null);
		_pageCache = {};
		_data = null;
		_loading = {};
		_current = -1;
		_pageInited = 0;
		_pageCount = 1;
		$pagination.empty();
	}

	function load(url, success, error, complete){
		var loadtime = _runtime;
		_xhr = $.ajax({
			type:'GET',
			dataType:'json',
			data:_where,
			url:envUrl(url),
			success:function(json){
				_runtime == loadtime && success && success(json);
			},
			error:function(){
				_runtime == loadtime && error && error();
			},
			complete:function(){
				_runtime == loadtime && complete && complete();
			}
		});
	}

	function retrieveData(index){
		var start = index * pageSize;
		return _data.slice(start, start + pageSize);
	}

	function getData(index, ondata, onerror){
		if (_data) {
			// 情况一：参数传递进数据
			// 情况二：无分页的异步载入全部数据
			// 已经有数据直接返回
			_pageInited || initPagination(_data.length);
			return ondata(retrieveData(index));
		}
		// 请求中 或者 请求完毕
		if (_loading.ALL || _loading[index]) {
			return;
		}
		if (_isPageRequest) {
			_loading[index] = 1;
			load(_url.replace('%p', index + 1).replace('%s', pageSize), function(json){
				// init pagination at first time
				_pageInited || initPagination(json.total);
				ondata(json.data);
			}, onerror, function(){
				// complete
				_loading[index] = 0;
			});
		} else {
			_loading.ALL = 1;
			load(_url, function(json){
				_data = json;
				// init pagination
				initPagination(json.length);
				ondata(retrieveData(index));
			}, onerror, function(){
				// complete
				_loading.ALL = 0;
			});
		}
	}
	function initPagination(total){
		_pageInited = 1;
		_total = total;
		_pageCount = Math.ceil(total / pageSize) || 1;
		$pagination.empty();
		if (_pageCount > 1) {
			for (var i=0, nav; i<_pageCount; i++) {
				nav = $('<a class="diy-pagination-nav-item" index="'+i+'"></a>').click(function(){
					goPage(this.getAttribute('index'));
				}).appendTo($pagination);
				_current == i && nav.addClass('diy-pagination-nav-current');
			}
			$pagination.css('left', (size.width - $pagination.outerWidth())/2);
		}
	}

	function addPage(index){
		var shell = $('<div class="diy-pagination-shell"></div>').css('width', size.width).appendTo($box);
		var page = $('<div class="diy-pagination-page"></div>').appendTo(shell);
		pageRender(page);
		_pageCache[index] = shell;
		return page;
	}
	function getPage(index){
		return _pageCache[index];
	}

	function showPage(index){
		if (_current > -1) {
			// animate or quick show
			var compare = _current == index ? 0 : (index > _current ? 1 : -1);
			if (!compare) return;
			// animate
			var cur = getPage(_current), toshow = getPage(index);
			cur.stop(true,true).css({
				position:'absolute',
				left:0
			}).animate({
				left:-compare * size.width
			}, FX_SPEED, function(){
				cur.css({
					display:'none',position:'',left:''
				});
			});
			toshow.stop(true,true).css({
				position:'absolute',
				left:compare * size.width,
				display:'block'
			}).animate({
				left:0
			}, FX_SPEED);
			$pagination.children('[index='+_current+']').removeClass('diy-pagination-nav-current');
			$pagination.children('[index='+index+']').addClass('diy-pagination-nav-current');
		} else {
			// quick show
			$pagination.children('[index='+index+']').addClass('diy-pagination-nav-current');
			getPage(index).css({
				display:'block'
			});
		}
        _current = index;
	}

	function addItem(key, val, page){
		itemRender($('<div class="diy-pagination-page-item"></div>').appendTo(page), val, key);
	}

	function goPage(index, refresh){
		index = toInt(index);
		if (index + 1 > _pageCount) {
			index = _pageCount - 1;
		} else if (index < 0) {
			index = 0;
		}
		if (index in _pageCache) {
			if (refresh) {
				_loading[index in _loading ? index : 'ALL'] = 0;
				_current = -1;
				_pageCache[index].remove();
			} else {
				return showPage(index);
			}
		}

		var page = addPage(index);

		page.append(spinning());
		showPage(index);
		getData(index, function(json){
			page.empty();
			$.each(json, function(k, v){
				addItem(k, v, page);
			});
		}, function(){
			var msg = $('<p>数据载入出错，你可以尝试<a href="javascript:;">刷新</a></p>');
			msg.find('a').click(function(){
				goPage(index, 1);
				return false;
			});
			page.html(msg);
		});
	}

	var mousewheellock = 0;
	function mousewheelunlock(){
		mousewheellock = 0;
	}
	$box.mousewheel(function(e){
		e.preventDefault();
		e.stopPropagation();
		if (mousewheellock) return;
		if (_current > -1) {
			mousewheellock = 1;
			var index = _current - (e.delta > 0 ? 1 : -1);
			index > -1 && index < _pageCount && goPage(index);
			setTimeout(mousewheelunlock, 300);
		}
	});

	this.addItem = function(val){
		initPagination(_total + 1);
		var last = _pageCount - 1;
		_data && _data.push(val);
		if (last in _pageCache) {
			addItem(_total-1, val, getPage(last).find('.diy-pagination-page'));
		} else {
			goPage(last);
		}
	};
	this.goPage = goPage;
	this.setData = function(data){
		reinit();
		_data = data;
		goPage(0);
	};
	this.refresh = function(){
		reinit();
		goPage(0);
	};
	this.setUrl = function(url, where, delay){
		_where = where;
		_url = url;
		_isPageRequest = _url.indexOf('%p') > -1;
        delay || goPage(0);
	};
	this.query = function(where){
		_where = where;
		this.refresh();
	};
};
})();