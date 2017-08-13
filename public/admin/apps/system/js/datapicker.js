(function($){
var OPTIONS = {
	multiple:false,
	picked:function(items){},
	url:'?app=system&controller=port&action=picker'
};
$.datapicker = function(options){
	var o = $.extend({
		width:550
	}, OPTIONS, options||{});
	o.url = o.multiple ? (o.url + (o.url.indexOf('?') == -1 ? '?' : '&') + 'multi=1') : o.url;
	var d = ct.iframe(o, {
		ok:function(checked, port){
			o.picked(checked, port);
			d.dialog('close');
		}
	});
};
})(jQuery);


