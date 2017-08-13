var DIY = (function(){
var _INITS = [], _DIYHOOKS = {}, _READY = 0, _CHANGED = 0,
DIY = {
	designMode:1,
	isReady:function(){
		return _READY;
	},
	isChanged:function(){
		return _CHANGED;
	},
	bind:function(event, fn){
		if (!(event in _DIYHOOKS)) {
			_DIYHOOKS[event] = [];
		}
		_DIYHOOKS[event].push(fn);
		return DIY;
	},
	trigger:function(event, args){
		if (event in _DIYHOOKS) {
			var hooks = _DIYHOOKS[event];
			for (var i=0,fn; fn=hooks[i++];) {
                fn.apply(this, args||[]);
			}
		}
		return DIY;
	},
	tabs:function(dialog, click, focused, event){
		dialog.find('.tabs>ul').each(function(){
			var target = dialog.find(this.getAttribute('target'));
			var tabs = $('li', this).each(function(i){
				$.event.add(this, event || 'click', function(){
					if ($.className.has(this, 'active')) return;
					tabs.removeClass('active');
					$.className.add(this,'active');
					var t = target.hide().eq(i).show();
					typeof click == 'function' && click.apply(this, [i, t]);
				});
			});
			tabs.eq(focused||0).triggerHandler(event || 'click');
		});
	},
	registerInit:function(fn){
		_INITS.push(fn);
	}
};
DIY.bind('ready',function(){
	_READY = 1;
}).bind('init', function(){
	var init;
	while ((init = _INITS.shift()) && false !== init())
	{}
}).bind('changed', function(){
	_CHANGED = 1;
	document.title = '*　' + ENV.title;
}).bind('unchanged', function(){
	_CHANGED = 0;
	document.title = ENV.title;
});
DIY.registerInit(function(){
	if (! ENV.contentid) {
		alert('缺少参数:contentid');
		return false;
	}
	if (! ENV.pageid) {
		alert('缺少参数:pageid');
		return false;
	}
	if (ENV.lockedby) {
        body.message('warn', '当前页面正被'+ENV.lockedby+'编辑');
		return false;
	}
	ENV.title = document.title;
	body.addClass('diy-design-mode');
	DIY.designMode = true;

    var pval = setInterval(lock, 170000);
	function lock(){
		$.get('?app=special&controller=online&action=lock&pageid='+ENV.pageid);
	}
	lock();
	window.onbeforeunload = function(){
		if (DIY.isChanged()) {
			return '尚未保存，您确认放弃更改吗？';
		}
	};
	window.onunload = function(){
		pval && clearInterval(pval);
		$.get('?app=special&controller=online&action=unlock&pageid='+ENV.pageid);
	};
});
return DIY;
})();

// EXPORTS
window.DIY = DIY;