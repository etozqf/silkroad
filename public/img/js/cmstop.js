/**
 * cmstop basework
 */
Array.prototype.indexOf || (Array.prototype.indexOf = function(item,i){
	// for ie
	i || (i = 0);
	var length = this.length;
	if(i < 0){
		i = length + i;
	}
	for(; i < length; i++){
		if(this[i] === item) return i;
	}
	return -1;
});
(function($, window, undefined){
var document = window.document, doc = $(document), doe = document.documentElement,
userAgent = navigator.userAgent.toLowerCase(),
IE = /opera/.test(userAgent) ? 0 : parseInt((/.+msie[\/: ]([\d.]+)/.exec(userAgent) || {1:0})[1]),
IEMode = IE > 7 ? document.documentMode : 0,
IE7 = (IE == 7 || IEMode == 7),
IE8 = IEMode == 8 && IE > 7,
IE9 = IEMode == 9 && IE > 8,
listenning = 1,
_loadingBox = null,
_tips = null,
httpRegex = /^http[s]?\:\/\//i;
function createTips(className, html) {
	var t;
	if (_tips) {
		t = _tips[0];
		var timer = _tips.data('timer');
		_tips.stop();
		timer && (clearTimeout(timer), clearInterval(timer));
	} else {
		t = document.createElement('div');
		_tips = $(t).appendTo(document.body);
	}
	t.className = className;
	t.style.cssText = 'position:fixed;visibility:hidden;z-index:1000000;';
	_tips.html(html);
	return _tips;
}

var bubble = function(){
	this.bubble = $(
	'<div class="bubble">'+
		'<div class="corner tl"></div>'+
		'<div class="corner tr"></div>'+
		'<div class="corner bl"></div>'+
		'<div class="corner br"></div>'+
		'<div class="top"></div>'+
		'<div class="cnt"></div>'+
		'<div class="bot"></div>'+
		'<div class="point"></div>'+
	'</div>').appendTo(document.body);
	this.pointer = this.bubble.find('.point');
	this.cnt = this.bubble.find('.cnt');
};
bubble.prototype = {
	pointTo:function(o){
		var x, y;
		if (o.nodeType == 1 ? (o = $(o)) : o.jquery) {
			var offset = o.offset();
			x = offset.left + parseInt(o[0].offsetWidth / 2);
			y = offset.top + parseInt(o[0].offsetHeight / 2);
		} else if (o.originalEvent) {
			x = o.pageX;
			y = o.pageY;
		} else {
			return;
		}

		var ww = doe.clientWidth, wh = doe.clientHeight, 
			sL = doc.scrollLeft(), sT = doc.scrollTop(),
			$b = this.bubble, b = $b[0], bw, bh,
			pclass, bTop, bLeft;
		b.style.cssText = '';
		bw = b.offsetWidth;
		bh = b.offsetHeight;
		if (!bw|| !bh) {
			b.style.display = 'block';
			bw = b.offsetWidth;
			bh = b.offsetHeight;
		}
		b.style.width = bw+'px';
		if ((wh / 2) > (y - sT)) {
			bTop = y + parseInt(this.pointer.height()) + 13;
			pclass = 'S';
		} else {
			bTop = y - bh - parseInt(this.pointer.height()) - 13;
			pclass = 'N';
		}
		if ((ww / 2) > (x - sL)) {
			bLeft = x - 13;
			pclass += 'W';
		} else {
			bLeft = x - bw + 10;
			pclass += 'E';
		}
		this.pointer[0].className = 'point '+pclass;
		$b.css({left:bLeft, top:bTop});
		return this;
	},
	setYellow:function(flag){
		this.bubble[flag ? 'addClass' : 'removeClass']('yellow');
		return this;
	},
	html:function(html){
		this.cnt.html(html);
		return this;
	},
	get:function(){
		return this.bubble;
	}
};
var cmstop = {
	IE : IE,
	IE7 : IE7,
	IE8 : IE8,
	pos:function (pos, width, height) {
		pos || (pos = 'right');
    	var sL = doc.scrollLeft(), sT = doc.scrollTop(),
    		iH = doe.clientHeight, iW = doe.clientWidth;
    	var style = {}, offset;
    	if (pos == 'top') {
    		style.top = 2;
    		style.left = (iW-width)/2;
    	} else if (pos == 'right') {
    		style.top = 2;
    		style.right = 2;
    	} else if (pos == 'center') {
    		style.top = (iH-height) * .382;
    		style.left = (iW-width)/2;
    	} else if (pos.nodeType == 1 ? (pos = $(pos)) : pos.jquery ) {
    		offset = pos.offset();
    		offset.left = offset.left - sL;
    		offset.top = offset.top - sT;
    		if (offset.left + width > iW) {
    			style.left = offset.left - width + pos.outerWidth();
    		} else {
    			style.left = offset.left;
    		}
    		var ph = pos.outerHeight();
    		if (offset.top + height + ph > iH) {
    			style.top = offset.top - height;
    		} else {
    			style.top = offset.top + pos.outerHeight();
    		}
    	} else if (pos.originalEvent) {
    		offset = {
    			left : pos.pageX - sL,
    			top  : pos.pageY - sT
    		};
    		if (offset.left + width > iW) {
    			style.left = offset.left - width;
    		} else {
    			style.left = offset.left;
    		}
    		if (offset.top + height > iH) {
    			style.top = offset.top - height;
    		} else {
    			style.top = offset.top;
    		}
    	}
    	return style;
	},
	/**
	 * get correct reference of function
	 */
	func:function(ns, context) {
        if (typeof ns == 'function') {
            return ns;
        }
        if (typeof ns == 'string') {
            ns = ns.split('.');
            var o = (context || window)[ns[0]], w = null;
            if (!o) return null;
            for (var i=1,l;l=ns[i++];) {
                if (!o[l]) {
                    return null;
                }
                w = o;
                o = o[l];
            }
            return o && (function(){
                return o.apply(w, arguments);
            });
        }
        return null;
    },
    /**
     * parse string to JSON object
     */
    parseToJSON: function(data) {
        if (data) {
            try {
                return window.JSON.parse(data);
            } catch (e) {
                try {
                    return (new Function('return ' + data))();
                } catch (e) {}
            }
        }
        return data;
    },
    /**
     * detect if error occured after $.load / $(elem).load,
     * used for formDialog / ajaxDialog or manual call.
     */
    detectLoadError: function(elem) {
        var data = elem && elem.jquery && elem.html() || elem;
        if (! data) return false;
        if ($.isFunction(data.charAt) && data.charAt(0) != '{') {
            data = undefined;
            return false;
        }
        data = this.parseToJSON(data);
        if (data && data.state != undefined && ! data.state) {
            ct.error(data.error || '加载时遇到问题，请重新尝试');
            data = undefined;
            return true;
        }
        data = undefined;
        return false;
    },
    openWindow:function() {
        var interval, timeout, signal, _url, _name;
        function execute() {
            endLoop();
            var $link = $('<a href="'+_url+'" target="_blank"></a>').css({
                    position:'absolute', top:'-9999em', left:'-9999em', overflow:'hidden'
                }).appendTo(document.body),
                link = $link[0];
            try {
                link.click();
            } catch (ex) {
                try {
                    var ev = document.createEvent('MouseEvents');
                    ev.initEvent('click', true, true);
                    link.dispatchEvent(ev);
                } catch (ex) {
                    window.open(_url, _name);
                }
            } finally {
                setTimeout(function() {
                    $link.remove();
                }, 500);
            }
        }
        function endLoop() {
            signal = false;
            interval && (clearInterval(interval), interval = null);
            timeout && (clearTimeout(interval), timeout = null);
        }
        function main(url, name) {
            _url = url;
            _name = name;
            if (interval) {
                signal = true;
                return;
            }
            execute();
        }
        main.startLoop = function() {
            if (interval) endLoop();
            interval = setInterval(function() {
                if (signal) {
                    execute();
                }
            }, 100);
            timeout = setTimeout(endLoop, 10000);
        };
        main.endLoop = endLoop;
        return main;
    }(),
    renderTemplate:function(template, data) {
        if ($.isPlainObject(data)) {
            template = template.replace(/\{([^\}]+?)\}/gm, function(match, key) {
                var parts = key.split('.'), part, result = data;
                while (part = parts.shift()) {
                    if (!(result = result[part])) break;
                }
                return (typeof result == 'undefined' || result === null) ? '' : result;
            });
        }
        return template;
    },
    getParam:function(param, url){
        url || (url = location.search);
        return ((new RegExp('[&?]'+param+'=(\\w+)').exec(url)) || {1 : null})[1];
    },
    
    /**
     * listen
     */
	listenAjax:function() {
		$().ajaxStart(function(){
			listenning && cmstop.startLoading();
			listenning = 1;
		}).ajaxStop(function(){
			cmstop.endLoading();
		}).ajaxError(function(){
			cmstop.endLoading();
		});
	},
    stopListenOnce:function() {
        listenning = 0;
    },
    startLoading:function(pos, msg, width) {
    	if (_loadingBox) return _loadingBox;
    	msg || (msg = '载入中……');
    	_loadingBox = $('<div class="loading" style="position:fixed;visibility:hidden"><sub></sub> '+msg+'</div>')
    		.appendTo(document.body);
    	if (!isNaN(width = parseFloat(width)) && width)
    	{
    		_loadingBox.css('width', width);
    	}
    	var style = cmstop.pos(pos, _loadingBox.outerWidth(true), _loadingBox.outerHeight(true));
    	style.visibility = 'visible';
    	_loadingBox.css(style);
    	return _loadingBox;
    },
    endLoading:function() {
    	_loadingBox && _loadingBox.remove();
    	_loadingBox = null;
    },
    tips:function(msg, type, pos, delay) {
    	(!type || type == 'ok') && (type = 'success');
    	var tips = createTips('ct_tips '+type, '<sub></sub> '+msg), ival,
    	a = $('<a style="margin-left:10px;color:#000080;text-decoration:underline;" href="close">知道了</a>').click(function(e){
    		e.stopPropagation();
    		e.preventDefault();
    		tips.fadeOut('fast');
    		ival && clearTimeout(ival);
    		ival = null;
    	}).appendTo(tips);
    	pos || (pos = 'center');
    	var style = cmstop.pos(pos, tips.outerWidth(true), tips.outerHeight(true));
    	style.visibility = 'visible';
    	tips.css(style);
    	delay === undefined && (delay = 3);
    	delay && (ival = setTimeout(function(){
    		tips.fadeOut('fast');
    	}, delay * 1000), tips.data('timer', ival));
		return tips;
    },
	timer:function(msg, sec, type, callback, pos) {
		type || (type = 'success');
		msg = msg.replace('%s','<b class="timer">'+sec+'</b>');
		var tips = createTips('ct_tips '+type, '<sub></sub> '+msg),
			timer = tips.find('b.timer'),
			clause = tips.find('.clause');
    	pos || (pos = 'center');
    	var style = cmstop.pos(pos, tips.outerWidth(true), tips.outerHeight(true));
    	style.visibility = 'visible';
    	tips.css(style);
		var iv = setInterval(function(){
			timer.text(--sec);
			sec < 1 && last();
		}, 1000);
		tips.data('timer', iv);
		var last = function(){
			iv && clearInterval(iv);
			iv = null;
			tips.hide();
			callback();
			return false;
		};
		clause.click(last);
		return tips;
	},
    alert:function(msg, type) {
    	return this.tips(msg, type, 'center', 0);
    },
	ok:function(msg, pos, delay) {
        return this.tips(msg, 'success', pos, delay);
    },
    error:function(msg, pos, delay) {
        return this.tips(msg, 'error', pos, delay);
    },
    warn:function(msg, pos, delay) {
    	return this.tips(msg, 'warning', pos, delay);
    },
	confirm:function(msg, ok, cancel, pos) {
		var tips = createTips('ct_tips confirm', '<sub></sub> '+msg+'<br/>');
		$('<button type="button" class="button_style_1">确定</button>').click(function(){
    		ok && ok(tips);
    		tips.hide();
    	}).appendTo(tips);
    	$('<button type="button" class="button_style_1">取消</button>').click(function(){
    		cancel && cancel();
    		tips.hide();
    	}).appendTo(tips);
    	pos || (pos = 'center');
    	var style = cmstop.pos(pos, tips.outerWidth(true), tips.outerHeight(true));
    	style.visibility = 'visible';
    	tips.css(style);
    	return tips;
	},
	iframe:function(opt, callbacks, onload, close){
		typeof opt == 'object' || (opt = {url:opt ? opt.toString() : ''});
		opt = $.extend({
			width : 450,
			height: 'auto',
			maxHeight: 500,
			resizable: false,
			modal : true
		}, opt, {
			close:function(){
                bindclosed
				  ? iframe.trigger('close')
				  : remove();
                ct.func(close) && close();
			}
		});
		var dialog = $(document.createElement('div')),
			iframe = $('<iframe frameborder="0" scrolling="auto" src="'+(opt.url||opt.title)+'" width="100%" height="100%" ></iframe>'),
			masker = $('<div class="masker"></div>').appendTo(dialog),
			bindclosed = 0;
		function remove(){
			iframe.remove();
			dialog.dialog('destroy').remove();
		}
		function showMasker(){
		    masker.show();
		    doc.mouseup(hideMasker);
		}
		function hideMasker(){
		    doc.unbind('mouseup', hideMasker);
		    masker.hide();
		}
		dialog.dialog(opt);
		var span = dialog.prev().mousedown(showMasker).children('span:first'), ival;
		dialog.nextAll('.ui-resizable-handle').mousedown(showMasker);
		dialog.css('overflow', 'hidden');
		iframe.bind('load', function(){
			ival && clearTimeout(ival);
			try {
				var d = this.contentDocument || this.contentWindow.document,
					de = d.documentElement,
					w = (this.contentWindow || this);
				if (! bindclosed) {
					iframe.bind('close',function(){
						ival && clearTimeout(ival);
						iframe.unbind().bind('load', function(){
							setTimeout(remove, 10);
						});
						w.location = "about:blank";
					});
					bindclosed = 1;
				}
				if (opt.width == 'auto' || opt.height == 'auto') {
					opt.width == 'auto' && dialog.width(de.scrollWidth);
					opt.height == 'auto' && dialog.height(de.scrollHeight);
					ival = setInterval(function(){
						opt.width == 'auto' && dialog.width(de.scrollWidth);
						opt.height == 'auto' && dialog.height(de.scrollHeight);
					}, 600);
				}
				dialog.dialog('option', 'position', 'center');
				w.getDialog = function(){
				    return dialog;
				};
				callbacks && (w.dialogCallback = callbacks);
				if (d.title && d.title.length) {
					span.text(d.title);
				} else {
					throw "no title";
				}
			} catch (e) {
			    span.text(this.src);
			}
			typeof onload == 'function' && onload(iframe);
		});
		dialog.append(iframe);
		return dialog;
	},
	ajaxDialog:function(opt, url, load, ok, cancel) {
        var buttons = {}, self = this;
        if (typeof ok == 'function') {
        	buttons['确定'] = function(){
        		ok(dialog) && dialog.dialog('close');
        	}
        }
        if (typeof cancel == 'function') {
        	buttons['取消'] = function(){
        		cancel(dialog) && dialog.dialog('close');
        	}
        }
    	typeof opt == 'object' || (opt = {title:opt ? opt.toString() : ''});
		opt = $.extend({
			width : 450,
			height: 'auto',
			maxHeight: 500,
			resizable: false,
			modal : true
		}, opt, {
            autoOpen:false,
            buttons:buttons,
			close:function(){
				dialog.dialog('destroy').remove();
			}
		});
        var dialog = $(document.createElement('div'));
        function position() {
            dialog.dialog('option', 'position', ['center', 'center']);
        }
        dialog.dialog(opt).load(url, function(){
            if (self.detectLoadError(dialog)) return;
		    dialog.dialog('open');
        	typeof load == 'function' && load(dialog);
            position();
        }).bind('ajaxload',function(){
            if (self.detectLoadError(dialog)) return;
            typeof load == 'function' && load(dialog);
            position();
        }).css('position', 'relative');
        return dialog;
    },
    formDialog:function(opt, url, submitBack, formReady, beforeSubmit, beforeSerialize)
    {
        var form = null;
        function load(dialog) {
            form = $('form:first', dialog);
            var wrap = dialog.parent(), masker, 
            	buttonArea = dialog.nextAll('div.btn_area'),
            	msg, mival = null;
            if (form.length) {
	            masker = $('<div class="masker"></div>').insertBefore(dialog);
                typeof formReady == 'function' && formReady(form, dialog);
                var success = function(json){
                	if (json && ('state' in json)) {
                		var type = json.state ? 'ok' : 'error',
                			msg = json.msg || (json.state ? json.info : json.error);
                        msg && ct[type](msg || '提交时遇到了问题，请检查后重试');
                	}
					if (typeof submitBack == 'function') {
						submitBack(json) && dialog.dialog('destroy').remove();
					} else {
						json && ('state' in json) && json.state && dialog.dialog('destroy').remove()
					}
        			return false;
                },
                complete = function(){
                	masker.hide();
					buttonArea.children('button').attr('disabled', false).removeAttr('disabled');
                },
                beforeSub = function(f, d, options) {
        			buttonArea.children('button').attr('disabled', 'disabled');
					masker.css({height:wrap.height(),width:wrap.width()}).show();
					if (typeof beforeSubmit == 'function' && beforeSubmit(form, dialog, options) === false)
					{
						complete();
						return false;
					}
        		    return true;
        		},
                submit = function(){
                	form.ajaxSubmit({
                        dataType:'json',
                		type:'post',
                		success:success,
						error:function(){ct.error('请求异常');},
                		complete:complete,
                		beforeSubmit:beforeSub,
                		beforeSerialize:beforeSerialize
                    });
                };
                if (form[0].getAttribute('name') && form.validate) {
                    form.validate({
                        submitHandler:submit
                    });
                } else {
                	form.find('input,textarea,select')
                	  .not(':button,:submit,:image,:reset,:hidden,[disabled],[readonly]')
                	  .eq(0).focus();
                	form.submit(function(e){
                        submit();
                        return false;
                    });
                }
            }
        }
        function ok(){
			form && form.submit();
            return false;
        }
        function cancel(){return true;}
        
        return ct.ajaxDialog(opt, url, load, ok, cancel);
    },
	template:function(input){
		input.jquery || (input = $(input));
		var path = input.val();
		var d = ct.iframe({
			title:'?app=system&controller=template&action=selector&path=' + path,
			width:600,
			height:'auto'
		},{
			ok:function(val){
				input.val(val);
				d.dialog('close');
			}
		});
	},
    getCookie:function(name) {
    	var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    },
    setCookie:function(name, value, options) {
    	 options = options || {};

        if (value === null) {
            value = '';
            options = $.extend({}, options);
            options.expires = -1;
        }
        if (!options.expires) {
        	options.expires = 1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString();
        }
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    },
    refet: [],
    fet: function(url, callback) {
        var hasLoaded = false;
        $.each(cmstop.refet, function(i, k) {
            if (url != k.resource) {
                return true;
            }
            hasLoaded = true;
            if (typeof(callback) != 'function') {
                return false;
            }
            if (k.state == 2) {
                callback();
            } else if (k.state == 1) {
                cmstop.refet[i].ready.push(callback);
            }
            return false;
        });
        if (hasLoaded) {
            return;
        }
        var refet = function() {
            var self = this;
            self.resource = url;
            self.ready = [];
            self.state = 1;
            var type = url.split('.').pop();
            if (type == 'js') {
                self.ready[0] = (typeof(callback) == 'function') ? callback : function(){};
                $.getScript(url, function() {
                    $.each(self.ready, function(i, k) {
                        k.call();
                    });
                    self.state = 2;
                });
            } else if (type == 'css') {
                try {
                    var l = document.createElement('link');
                    l.href = url;
                    l.rel = 'stylesheet';
                    l.type = 'text/css';
                    document.getElementsByTagName("head").item(0).appendChild(l);
                } catch (e) {
                    window.document.createStyleSheet(url);
                }
                self.state = 2;
                if (typeof callback === 'function') {
                    callback();
                }
            }
            return this;
        }
        cmstop.refet.push(new refet());
    }
};
window.ct = window.cmstop = cmstop;

cmstop.assoc = {
    refresh:function() {
        if (top != self) {
        	top.superAssoc.refresh()
        }
    },
    open:function(url, target, path) {
    	if (top != self) {
        	window.__ASSOC_TABID__ = top.superAssoc.open(url,target,path);
    	}
    },
    get:function(target) {
    	if (top != self) {
    		return top.superAssoc.get(target);
    	} else {
    		return null;
    	}
    },
    close:function(target) {
    	if (top != self) {
    		top.superAssoc.close(target);
    	}
	},
	opener:function() {
		if (top != self) {
    		return window.__ASSOC_TABID__ && top.superAssoc.get(window.__ASSOC_TABID__);
    	} else {
    		return null;
    	}
	},
    call:function(method) {
    	if (top != self) {
    		var args = Array.prototype.slice.call(arguments,1);
    		return top.superAssoc[method].apply(null,args);
    	}
    }
};
    
$.fn.extend({
	ajaxSubmit:function(options) {
	    if (!this.length) {
	        return this;
	    }
	    if (typeof options == 'function')
	        options = { success: options };
	
	    var url = $.trim(this.attr('action'));
	    if (url) {
		    url = (/^([^#]+)/.exec(url)||{})[1];
	   	}
	   	url = url || window.location.href || '';
	
	    options = $.extend({
	        url:  url,
	        type: this.attr('method') || 'GET'
	    }, options || {});
	
	    // provide opportunity to alter form data before it is serialized
	    if (options.beforeSerialize && options.beforeSerialize(this, options) === false) {
	        return this;
	    }
	
	    var a = this.serializeArray();
	    if (options.data) {
	        options.extraData = options.data;
	        for (var n in options.data) {
	          if(options.data[n] instanceof Array) {
	            for (var k in options.data[n])
	              a.push( { name: n, value: options.data[n][k] } );
	          }
	          else
	             a.push( { name: n, value: options.data[n] } );
	        }
	    }
	    
	    // give pre-submit callback an opportunity to abort the submit
	    if (options.beforeSubmit && options.beforeSubmit(a, this, options) === false) {
	        return this;
	    }
	
	    options.data = a;
		
		$.ajax(options);
	
		this.trigger('form-submit-notify', [this, options]);
	
		return this;
	},
    ajaxForm : function(jsonok, infoHandler, beforeSubmit) {
    	var form = this,
		url = this.attr('action'),
		type = this.attr('method') || 'POST',
		jsonok = cmstop.func(jsonok) || function(json){
			json.state
		    	? cmstop.ok('保存成功')
		    	: cmstop.error(json.error)
		},
		beforeSubmit = cmstop.func(beforeSubmit) || function(){},
    	submitHandler = function(){
    		if (form.data('lock')) return;
    		if (beforeSubmit(form) === false) {
    			return;
    		}
    		var buttons = form.find('*')
    			.filter(':button,:submit,:reset')
    			.attr('disabled','disabled'),
    		complete = function(){
    			form.data('lock', false);
    			buttons.attr('disabled','').removeAttr('disabled');
    		};
    	    form.data('lock', true);
    		$.ajax({
	    		dataType:'json',
	    		url:url,
	    		type:type,
	    		data:form.serialize(),
	    		success:jsonok,
	    		complete:complete,
	    		error:function(){
	    			cmstop.error('请求异常');
	    		}
	    	});
    	};
    	// CTRL + ENTER|S quick submit
		$().unbind('keydown.ajaxForm');
    	$().bind('keydown.ajaxForm',function(e){
    		if (e.ctrlKey && (e.keyCode == 13 || e.keyCode == 83))
    		{
    			e.stopPropagation();
    			e.preventDefault();
    			form.submit();
    		}
    	});
        if (this.attr('name')) {
            this.validate({
                submitHandler:submitHandler,
                infoHandler:infoHandler
            });
        } else {
			this.submit(function(e){
				e.stopPropagation();
				e.preventDefault();
				submitHandler();
			});
        }
        return this;
    },
    floatImg : function(options) {
		var opts = $.extend({
			url: '',
            width: null,
            height: null,
            padding: 5,
            backgroundLoading: '#FFF url(' + IMG_URL + 'images/loading-spinner.gif) no-repeat center center',
            background: '#FFF',
            minWidth: 50,
            minHeight: 50
		}, options||{});
        var elem = this;
        var popview, img;
        var offsetSize = (isNaN(parseInt(opts.padding)) ? 5 : parseInt(opts.padding)) * 2;
        function update(imgWidth, imgHeight) {
            var offset = elem.offset(),
                left = offset.left,
                top = offset.top/* - doc.scrollTop()*/,
                height = elem.height(),
                popviewWidth = popview.outerWidth(true),
                popviewHeight = popview.outerHeight(true),
                docWidth = doc.width(),
                docHeight = doc.height(),
                offsetWdith = (imgWidth ? Math.max(imgWidth, opts.minWidth) + offsetSize : popviewWidth),
                offsetHeight = (imgHeight ? Math.max(imgHeight, opts.minHeight) + offsetSize : popviewHeight),
                downOffset = top + height + offsetSize,
                upOffset = top - offsetSize - offsetHeight,
                innerHeight;
            if (left + offsetWdith > docWidth) {
                left = docWidth - offsetWdith;
            }
            if (downOffset + offsetHeight + 10 <= docHeight) {
                top = downOffset;
            } else if (upOffset > 10) {
                top = upOffset;
            } else {
                if (docHeight - downOffset >= top - offsetSize) {
                    img.height(docHeight - downOffset - 20);
                    top = top + height + offsetSize;
                } else {
                    img.height(top - offsetSize - 10);
                    top = 0;
                }
            }
            popview.css({
                left: left,
                top: top
            });
            if (imgHeight) {
                innerHeight = popview.innerHeight();
                if (imgHeight < innerHeight) {
                    img.css({ 'margin-top': ((innerHeight - offsetSize - imgHeight) / 2).toFixed(2) + 'px' });
                } else {
                    img.css({ 'margin-top': '' });
                }
                img.css({ display: '' });
                popview.css({ background: opts.background });
            } else {
                popview.css({ background: opts.backgroundLoading });
            }
        }
        elem.hover(function() {
            var src = elem.val() || elem.attr('thumb');
            popview = $('.ui-floatImg');
            if (!src) {
                popview.hide();
                return;
            }
            if (!popview.length) {
                popview = $('<div class="ui-floatImg"></div>').hide().css({
                    position: 'absolute',
                    left: '-9999em',
                    top: '-9999em',
                    zIndex: 99999,
                    minWidth: opts.minWidth,
                    minHeight: opts.minHeight,
                    padding: '5px',
                    border: 'solid 1px #CCC',
                    background: opts.backgroundLoading,
                    boxShadow: '0 0 2px #AAA',
                    textAlign: 'center'
                }).appendTo(document.body);
            } else {
                popview.hide().empty();
            }
            if (popview.data('timeout')) clearTimeout(popview.data('timeout'));
            update();
            popview.show();
            src = (!src.match(/^https?:\/\//) && opts.url ? (opts.url + src) : src);
            img = $('<img />').css({
                position: 'absolute',
                left: '-9999em',
                top: '-9999em',
                display: 'block'
            }).load(function() {
                var width = this.width,
                    height = this.height,
                    ratio_w, ratio_h, ratio;
                if (opts.width || opts.height) {
                    ratio_w = opts.width / width;
                    ratio_h = opts.height / height;
                    ratio = ratio_w && ratio_h
                        ? (ratio_w < ratio_h ? ratio_w : ratio_h)
                        : ratio_w || ratio_h;
                    if (opts.width && width > opts.width) {
                        img.width(opts.width);
                        width = opts.width;
                        height = parseInt((height * ratio).toFixed(0));
                        img.height(height);
                    } else if (opts.height && height > opts.height) {
                        img.height(opts.height);
                        width = parseInt((width * ratio).toFixed(0));
                        height = opts.height;
                        img.width(width);
                    }
                }
                img.hide().appendTo(popview).css({
                    position: '',
                    left: '',
                    top: ''
                });
                update(width, height);
            }).attr('src', src + (src.indexOf('?') > -1 ? '&' : '?') + '_=' + (new Date()).getTime());
        }, function() {
            if (!popview) return;
            if (popview.data('timeout')) clearTimeout(popview.data('timeout'));
            popview.data('timeout', setTimeout(function() {
                popview.hide();
            }, 300));
        });
		return this;
	},
	attrTips : function(attr, theme) {
		var b, $b,
		ihide = function(){
			var delay = $b.data('delay');
			delay && clearTimeout(delay);
			$b.data('delay', null);
			$b.stop(1).css({opacity:'',display:'none'});
		};
		if (bubble.inst) {
			b = bubble.inst;
			$b = b.get();
		} else {
			b = new bubble();
			bubble.inst = b;
			$b = b.get();
		}
		var pos = null;
		this.bind('mouseover', function(e){
			pos = e;
			var t = this,
				c = this.getAttribute(attr),
				delay = $b.data('delay');
			delay && clearTimeout(delay);
			if (!c) return;
			delay = setTimeout(function(){
				$b.data('point', t);
				b.setYellow(theme != 'tips_green');
				b.html(c);
				b.pointTo(pos);
				$b.fadeIn('normal');
			}, 200);
			$b.data('delay', delay);
		}).bind('mouseout', ihide).bind('mousemove',function(e){
			// fixed: 清空attr值buddle不会被抑制
			var t = this,
				c = this.getAttribute(attr);
			if (!c) return;
			pos = e;
			if ($b.data('point') != this) return;
			b.pointTo(e);
		});
		doc.bind('mousedown.bubble', ihide);
		return this;
 	},
	maxLength : function() {
		this.each(function(){
			var maxLength = this.maxLength;
			var s = $('<strong class="c_green" style="margin-left:5px">0</strong>')
				.insertAfter(this);
			$.event.add(this, 'keyup', function(ev){
				$.textLength(this, s, maxLength, ev);
			});
		}).keyup();
		return this;
	}
});
$.textLength = function(el, strong, maxLength, ev) {
	if (maxLength && maxLength > 0) {
		var l = $.natureLength(el.value);
		strong.html(l);
		if (l > maxLength) {
            strong.addClass('c_red');
            ev.preventDefault();
            el.value = el.value.substr(0, maxLength);
        }
	} else {
		strong.html($.natureLength(el.value));
	}
	if (el.tagName == 'TEXTAREA' && el.scrollHeight > el.clientHeight) {
		el.style.height = el.scrollHeight + 'px';
	}
};
$.natureLength = function(val) {
    return val ? Math.ceil(val.replace(/[^\x00-\xff]/gm, '__').length / 2) : 0;
};
$.isPlainObject || ($.isPlainObject = function(val) {
    return val && Object.prototype.toString.call(val) === '[object Object]' && 'isPrototypeOf' in val;
});

$.ajaxSetup({
	beforeSend:function(xhr){
		xhr.setRequestHeader("If-Modified-Since","0");
		xhr.setRequestHeader("Cache-Control","no-cache");
	}
});

})(jQuery, window);

(function($){
var slice = [].slice;
$.extend($.event.special, {
    mousewheel: {
        setup: function() {
            if ('onmousewheel' in this) {
                this.onmousewheel = wheelHandler;
            } else {
                this.addEventListener('DOMMouseScroll', wheelHandler, false);
            }
        },
        teardown: function() {
            if ('onmousewheel' in this) {
                this.onmousewheel = null;
            } else {
                this.removeEventListener('DOMMouseScroll', wheelHandler, false);
            }
        }
    },
    input : {
        setup: function() {
            if ('onpropertychange' in this) {
                this.onpropertychange = inputHandler;
            } else {
                this.addEventListener('input', inputHandler, false);
            }
        },
        teardown: function() {
            if ('onpropertychange' in this) {
                this.onpropertychange = null;
            } else {
                this.removeEventListener('input', inputHandler, false);
            }
        }
    }
});
function wheelHandler(event) {
    var args = slice.call( arguments, 1 ), delta = 0;
    event = $.event.fix(event || window.event);
    event.type = "mousewheel";
    delta = event.wheelDelta
        ? event.wheelDelta / 120
        : (event.detail ? (-event.detail/3) : 0);
    event.delta = delta;
    // Add events and delta to the front of the arguments
    args.unshift(event, delta);
    return $.event.handle.apply(this, args);
}
function inputHandler(event){
    var args = slice.call(arguments, 1);
    event = $.event.fix(event || window.event);
    if (event.type == 'propertychange' && event.originalEvent.propertyName !== "value") {
        return;
    }
    event.type = 'input';
    args.unshift(event);
    return $.event.handle.apply(this, args);
}

$.fn.extend({
    mousewheel:function(fn){
        return fn ? this.bind("mousewheel", fn) : this.trigger("mousewheel");
    },
    input:function(fn){
        return fn ? this.bind("input", fn) : this.trigger("input");
    }
});

$.fn.serializeObject || ($.fn.serializeObject = function() {
    var output = {},
        array = this.serializeArray();
    $.each(array, function() {
        if (output[this.name] !== undefined) {
            if (! output[this.name].push) {
                output[this.name] = [output[this.name]];
            }
            output[this.name].push(this.value || '');
        } else {
            output[this.name] = this.value || '';
        }
    });
    return output;
});
})(jQuery);

var url = {
	member: function (userid) {
		ct.assoc.open('?app=member&controller=index&action=profile&userid='+userid, 'newtab');
	},
	ip: function (ip) {
		ct.assoc.open('?app=system&controller=ip&action=show&ip='+ip, 'newtab');
	}
};

// expired, do not use
(function(ct){
$.extend(ct,{
    ajax:function(title, url, width, height, load, ok, cancel)
    {
        return ct.ajaxDialog({
            title:title,
            width:width,
            height:height
        }, url, load, ok, cancel);
    },
    form:function(title, url, width, height, submitBack, formReady, beforeSubmit, beforeSerialize)
    {
        return ct.formDialog({
            title:title,
            width:width,
            height:height
        }, url, submitBack, formReady, beforeSubmit, beforeSerialize);
    }
});
})(cmstop);

