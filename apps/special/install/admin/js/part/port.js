(function(){
var PORTS = {};
function getPort(port, ok, error, data) {
	var url = '?app=system&controller=port&action=request&type=port&port='+port;
	if ((port in PORTS) && (data in PORTS[port].templates)) {
		ok(PORTS[port], $(PORTS[port].templates[data]));
	} else {
		json(url, data, function(ret){
			if (!ret.state) {
				return error(ret.error);
			}
			if (port in PORTS) {
				PORTS[port].templates[data] = ret.html;
				ok(PORTS[port], $(ret.html));
			} else {
				fet(ret.assets, function(){
					if (!(port in PORTS)) {
						return error('Invalid data port "'+port+'", unregistered in script.');
					}
					PORTS[port].templates = {};
					PORTS[port].templates[data] = ret.html;
					ok(PORTS[port], $(ret.html));
				});
			}
		}, error, 'POST');
	}
}

$.extend(DIY, {
	registerPort:function(key, port){
		PORTS[key] = port;
	},
	initPort:function(type, form, tbody){
		var data = form[0].portdata.value,
		container = tbody.find('td.container'),
		_port, view = null, inited = {},
		fragment = $('<div></div>'),
		tabs = tbody.find('.dataport-tabs').children();
		$(form[0].portdata).remove();
		
		function loading(){
			message(spinning());
		}
		function message(msg){
			container.html(msg);
		}

		tabs.each(function(i){
			var port = this.getAttribute('data-port');
			var t = $(this);
			t.click(function(){
				if (port == _port) return;
				view && view.appendTo(fragment);
				tabs.removeClass('active');
				t.addClass('active');
				go(port);
			});
			!_port && port == (form[0].port.value || 'cmstop') && t.click();
		});
		
		function go(port){
			form[0].port.value = port;
			_port = port;
			if (port in inited) {
				container.html(view = inited[port]);
				return;
			}
			loading();
			getPort(port, function(init, v){
				if (port == _port) {
					view = v;
					inited[port] = view;
					container.html(view);
					init(view, form);
				}
			}, function(error){
				port == _port && message(error || "请求异常");
			}, port == _port ? data : "");
		}
	}
});
})();
