var Hotkey = (function(){
	var _HOOKS = {};
	return {
		init:function() {
			$doc.keydown(function(e){
				if (DIY.isReady() && e.ctrlKey && e.shiftKey && e.keyCode) {
					var hooks = _HOOKS[e.keyCode];
					if (hooks && hooks.length) {
						e.preventDefault();
						e.stopPropagation();
						for (var i=0,fn;fn=hooks[i++];) {
							fn(e);
						}
					}
				}
			});
			return Hotkey;
		},
		bind:function(key, fn) {
			key = key.charCodeAt(0);
			if (!(key in _HOOKS)) {
				_HOOKS[key] = [];
			}
			_HOOKS[key].push(fn);
			return Hotkey;
		}
	};
})();

DIY.registerInit(function(){
	Hotkey.bind('S', function(e){
		DIY.save();
	}).bind('V', function(e){
		DIY.preview();
	}).bind('P', function(e){
		DIY.publish();
	}).bind('Z', function(e){
		History.back();
	}).bind('Y', function(e){
		History.forward();
	}).init();
});