var Dock = (function(){
var _DOCKS = {};
var Dock = function(dock) {
	var $dock = $(dock), name = $dock.attr('name'), title = $dock.attr('tips');

	Dock.registerDock(name, this);
	$dock.addClass('diy-panel-dock-' + name);

	this.getName = function(){
		return name;
	};
	this.getElement = function(){
		return $dock;
	};
	this.unactive = function(){
		$dock.removeClass('diy-actived');
	};
	this.active = function(){
		$dock.addClass('diy-actived');
	};
	this.disable = function(){
		$dock.removeClass('diy-enable');
	};
	this.isEnable = function(){
		return $dock.hasClass('diy-enable');
	};
	this.enable = function(){
		$dock.addClass('diy-enable');
	};
	this.bind = function(event, fn){
		$dock.bind(event, fn);
		return this;
	};
	this.trigger = function(event){
		$dock.triggerHandler(event);
	};
};
$.extend(Dock, {
	getAllDocks:function(){
		return _DOCKS;
	},
	registerDock:function(name, dock){
		_DOCKS[name] = dock;
	},
	getDock:function(name){
		return _DOCKS[name];
	}
});
return Dock;
})();