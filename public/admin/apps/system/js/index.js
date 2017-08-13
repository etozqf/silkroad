$(document).ready(function(){
	var timeCount, checkOnlyOne;
	$('#logout').click(function(){
		window.saveSession(function() {
			$.getJSON('?app=system&controller=admin&action=logout', function(json){
				if (json.state == true) {
					if (json.synclogout) {
						var len = json.synclogout.length;
						for(var k=0; k<len; k++) {
							$.getScript(json.synclogout[k]);
						}
						setTimeout(function (){
							location.href = WWW_URL;
						}, 500);
					} else {
						location.href = WWW_URL;
					}
				}
			});
		});
	});

	//cmstop_toolbox
	(function(){
		var timeout = false;
		var span = $('#get_toolbox');
		var toolBox = {
			obj : $('div#toolbox'),
			show : function() {
				toolBox.obj.show().css({
					'top':span.outerHeight(true)+span.offset().top+1,
					'left':(span.offset().left - toolBox.obj.outerWidth() + span.outerWidth()),
					'visibility':'visible'
				});
				$('#get_toolbox').addClass('sc_now');

				toolBox.obj.bind('mouseenter',function() {
					if (timeout !== false) {
						clearTimeout(timeout);
						timeout = false;
					}
				}).bind('mouseleave', function() {
					if (timeout === false) {
						timeout = setTimeout(function() {
							toolBox.hide();
						},1350);
					}
				});
			},
			hide : function() {
				if (timeout !== false) {
					clearTimeout(timeout);
					timeout = false;
				}
				$('body').unbind('mouseover.toolbox');
				$('body').unbind('click.toolbox');
				$('#get_toolbox').removeClass('sc_now');
				toolBox.obj.hide().css({'visibility':'hidden','display':'block'});
			}
		};

		span.bind('click', function() {
			toolBox.show();
			setTimeout(function() {
				$('body').one('click.toolbox', function() {
					toolBox.hide();
				});
			}, 500);
		});
		if (cmstop.IE > 6) {
			toolBox.obj.find('div.poparea').eq(0).hide();
			toolBox.obj.find('div.poparea').eq(1).show();
		} else {
			toolBox.obj.find('div.poparea').eq(1).hide();
			toolBox.obj.find('div.poparea').eq(0).show();
		}
	})();

	// timeout
    timeCount = {
        refresh: function() {
            var t = this;
			var timeout = $.cookie(COOKIE_PRE + 'timeout')>>>0;
			if (++timeout > 10) {
                checkOnlyOne.clear();
				return t.logout();
			}
			$.cookie(COOKIE_PRE + 'timeout', String(timeout), {path: COOKIE_PATH, domain: COOKIE_DOMAIN});
			window.__timeCountHandle = setTimeout(function(){
                t.refresh();
            }, autolock * 6000);
            return true;
		},
        logout: function(url) {
            var t = this;
			var body = document.body,
			overlay = $('<div></div>').appendTo(body).css({
				'position': 'fixed',
				'width': '100%',
				'height': '100%',
				'background': 'black',
				'opacity': '0.4',
				'top': '0',
				'left': '0',
				'z-index': '999998'
			}),
			dialog = $('<div class="dialog-box"></div>').css({
				'position': 'absolute',
				'width': '400px',
				'top': '50%',
				'left': '50%',
				'height': '200px',
				'margin-top': '-100px',
				'margin-left': '-200px',
				'background': '#f5f5f5',
				'z-index': '999999',
				'border': '5px solid #666666',
				'background-image': 'url(css/images/timeout.png)',
				'background-repeat': 'no-repeat'
			});
            url = url || '?app=system&controller=admin&action=timeout';
			$('<link>').attr({
				"rel" : "stylesheet",
				"type" : "text/css",
				"href" : IMG_URL + "js/lib/jquery-ui/dialog.css"
			}).appendTo($('head'));
			$.get(url, null, function(html) {
				var s = new Array();
				html = html.replace(/<script(?:[^>]+?type="([^"]*)")?[^>]*>([^<][\s\S^(?:<\/script>)]+?)<\/script>/ig, function($1, $2, $3){
					if ($2 && $2 != 'text/javascript') {
						return $1;
					}
					s.push($3);
					return '';
				});
				dialog.append(html).appendTo(body);
				for (var i=0,l=s.length; i<l; i++) {
					window[ "eval" ].call( window, s[i] );
				}
				dialog.find('form').bind('submit', function(event) {
					var form = $(this);
					$.post(form.attr('action'), form.serialize(), function(req) {
						if (!req.state) {
							$('#seccode_image').click();
							return ct.error(req.error || '登陆失败');
						}
						overlay.remove();
						dialog.remove();
						$.cookie(COOKIE_PRE + 'timeout', '0', {path: COOKIE_PATH, domain: COOKIE_DOMAIN});
                        t.refresh();
                        checkOnlyOne.init();
                        return true;
					}, 'json');
					return false;
				});
			}, 'string');
		},
		bindIframe: function(elm) {
            var t = this;
			$.each(elm.find('iframe'), function(i, ifm) {
				var body;
				try {
					if (ifm.contentDocument) {
						body = ifm.contentDocument.body;
					} else if (ifm.contentWindow) {
						body = ifm.contentWindow.document.body;
					}
				} catch	(e) {}
				if (typeof body != 'object') {
					// browser not support
					clearTimeout(window.__timeCountHandle);
					return false;
				}
				elm = $(body);
				elm.bind('mousemove', function() {
					$.cookie(COOKIE_PRE + 'timeout', '0', {path: COOKIE_PATH, domain: COOKIE_DOMAIN});
				}).bind('keypress', function() {
					$.cookie(COOKIE_PRE + 'timeout', '0', {path: COOKIE_PATH, domain: COOKIE_DOMAIN});
				});
                t.bindIframe(elm);
                return true;
			});
		},
        init: function(){
            var t = this;
         	$.cookie(COOKIE_PRE + 'timeout', '0', {path: COOKIE_PATH, domain: COOKIE_DOMAIN});
            if(window.__timeCountHandle){
			    clearTimeout(window.__timeCountHandle);
            }

            $('iframe').bind('load', function() {
                $.cookie(COOKIE_PRE + 'timeout', '0', {path: COOKIE_PATH, domain: COOKIE_DOMAIN});
                t.bindIframe($('body'));
            });
            t.bindIframe($('body'));
            t.refresh();
        }
    };

    // onlyone
    checkOnlyOne = {
        clear: function(){
            clearInterval(window.__checkOnlyOneHandle);
		    clearTimeout(window.__timeCountHandle);
        },
        init: function(){
            var t = this;
            if(window.__checkOnlyOneHandle){
			    clearInterval(window.__checkOnlyOneHandle);
            }
            window.__checkOnlyOneHandle = setInterval(function(){
                $.getJSON('?app=system&controller=admin&action=checkonlyone', function(json){
                    if (json && json.state == 0){
                        t.clear();
                        return timeCount.logout('?app=system&controller=admin&action=checkonlyone&logout=1');
                    }
                })
            }, 10000);
        }
    };

	if (window.top != self) {
		window.open(location, '_blank');
		ct.assoc.close();
	} else {
		superAssoc.init(function(){
            timeCount.init();
            if (checkonlyone) {
                checkOnlyOne.init();
            }
        });
	}
});