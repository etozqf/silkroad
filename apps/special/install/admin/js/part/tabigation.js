var Tabigation = function(place, clause){
	var $box = $('<div class="diy-tabigation"></div>').appendTo(place),
		$nav = $('<div class="diy-tabigation-nav"></div>').appendTo($box),
		_focused = -1, _navCache = [];

	this.focus = function(index){
		index > -1 && index < _navCache.length && _navCache[index].triggerHandler('tabfocus');
	};

	this.add = function(label, init){
		var nav = $('<div class="diy-tabigation-nav-item">'+label+'</div>').appendTo($nav),
			tab = $('<div class="diy-tabigation-item"></div>').appendTo($box), inited = 0, index = _navCache.length;
		nav.bind('tabfocus', function(){
			_focused > -1 && _focused != index && _navCache[_focused].triggerHandler('tabblur');
			_focused = index;
			nav.addClass('diy-tabigation-nav-current');
			tab.show();
			if (!inited){
				init(tab, nav);
				inited = 1;
			}
		}).bind('tabblur', function(){
			tab.hide();
			nav.removeClass('diy-tabigation-nav-current');
		}).bind(clause, function(){
			nav.triggerHandler('tabfocus');
		});
		_navCache.push(nav);
		return this;
	};
};