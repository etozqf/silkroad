var Theme = (function(){

var ostyle = null, osheet = null;
var Theme = {
	locked:false,
	stoped:false,
	name:null,
	link:null,
	pname:null,
	plink:null,
	willset:null,
	def:null,
	lastDef:null,
	init:function(theme, usedDir){
		Theme.name = theme;
		Theme.pname = theme;
		Theme.usedDir = usedDir || [];
		Theme.link = $('link[theme]');
		Theme.ulink = $('link[used]');
		Theme.def = ENV.themes[theme] && ENV.themes[theme]['define'] || {};
		Theme.lastDef = Theme.def;
	},
	addDir:function(dir){
		if ($.inArray(dir, Theme.usedDir) != -1) return;
		Theme.usedDir.push(dir);
		var used = Theme.usedDir.join(',');
		Theme.paramUse('used='+encodeURIComponent(used),
		function(link){
			link.attr('used', used);
			Theme.ulink.after(link);
			setTimeout(function(){
				Theme.ulink.remove();
				Theme.ulink = link;
			}, 1);
		});
	},
	replace:function(pre, cur) {
		$('.diy-widget').each(function(){
			var w = $(this),
				t = w.find('.diy-title'),
				c = w.find('.diy-content'),
				engine = w.attr('engine');
			w.attr('widget-theme')
				|| switchClass(w, pre.widget, cur.widget, 'widget-');
			w.attr('title-theme')
				|| switchClass(t, pre.title, cur.title, 'title-');
			w.attr('content-theme')
				|| switchClass(c, 
						pre.content && pre.content[engine] || '',
						cur.content && cur.content[engine] || '',
						'content-'+engine+'-');
		});
		$('.diy-frame').each(function(){
			var f = $(this), t = f.children('.diy-title');
			f.attr('frame-theme')
				|| switchClass(f, pre.frame, cur.frame, 'frame-');
			f.attr('title-theme')
				|| switchClass(t, pre.title, cur.title, 'title-');
		});
	},
	paramUse:function(param, ok, stoped) {
		if (Theme.locked) return false;
		Theme.stoped = false;
		Theme.locked = true;
		function roll(){
			Theme.locked = false;
			stoped && stoped();
		}
		json('?app=special&controller=online&action=css&pageid='+ENV.pageid, param,
		function(json){
			if (Theme.stoped || !json.url) return roll();
			Load.link(json.url, function(){
				var l = $(this);
				if (Theme.stoped) {
					l.remove();
					return roll();
				}
				Theme.locked = false;
				return ok && ok(l);
			});
		},
		function(){
			return roll();
		});
	},
	use:function(theme){
		if (Theme.locked
			|| theme == Theme.pname
			|| (Theme.willset && theme != Theme.willset)
			|| !ENV.themes[theme]) return false;
		var def = ENV.themes[theme]['define'];
		if (! def) return false;
		Theme.pname = theme;
		Theme.paramUse('theme='+theme, function(link){
			link.attr('theme', theme);
			Theme.link.after(link);
			Theme.replace(Theme.def, def);
			Theme.link.appendTo($fragments);
			if (Theme.willset) {
				Theme.willset = null;
				Theme.name = theme;
				Theme.link = link;
				Theme.plink = null;
				Theme.def = def;
			} else {
				Theme.plink = link;
				Theme.cdef = def;
			}
		}, function(){
			Theme.plink = null;
			Theme.pname = Theme.name;
			if (Theme.willset) {
				Theme.use(Theme.willset);
			}
		});
	},
	set:function(theme){
		if (theme == Theme.name) {
			return;
		}
		Theme.willset = theme;
		if (theme == Theme.pname){
			if (! Theme.locked) {
				Theme.willset = null;
				Theme.link = Theme.plink;
				Theme.name = Theme.pname;
				Theme.plink = null;
				Theme.def = Theme.cdef;
				Theme.cdef = null;
			}
		} else {
			if (! Theme.locked) {
				return Theme.use(theme);
			}
			Theme.stoped = true;
		}
	},
	reset:function(){
		if (Theme.willset && Theme.pname == Theme.willset) return;
		Theme.stoped = true;
		if (Theme.plink && Theme.pname != Theme.name) {
			Theme.plink.replaceWith(Theme.link);
			Theme.plink = null;
			Theme.pname = Theme.name;
			Theme.replace(Theme.cdef, Theme.def);
			Theme.cdef = null;
		}
	},
    getThemeName:function(type, value){
        var ret = '';
        if (value) {
            if (value !== '(empty)') {
                ret = value;
                Theme.addDir(type+'/'+ret);
            }
        } else {
            type = type.split('/');
            var p = type.shift();
            ret = Theme.def[p];
            while((p = type.shift()) && ret && (ret = ret[p])) {}
        }
        return ret;
    },
	setRule:function(selectorText, ruleText) {
		if (!osheet) {
			ostyle = document.getElementById('ostyle');
			if (!ostyle || ostyle.tagName != 'STYLE') {
				ostyle = document.createElement('style');
				ostyle.type = 'text/css';
				head.appendChild(ostyle);
			}
			osheet = ostyle.sheet || ostyle.styleSheet;
		}
		var oRules = osheet.cssRules || osheet.rules, i = oRules.length-1, r;
		while (i >= 0) {
			r = oRules[i];
			if (r.selectorText == selectorText) {
				osheet.deleteRule ? osheet.deleteRule(i) : osheet.removeRule(i);
				break;
			} else {
				i--;
			}
		}
		if (ruleText) {
			ruleText = macroReplace(ruleText);
			if ( osheet.insertRule ) {
				osheet.insertRule(selectorText + '{' + ruleText + '}', osheet.cssRules.length);
			} else if ( osheet.addRule ) {
				osheet.addRule(selectorText, ruleText);
			}
		}
	}
};

var supportCssOnload = ('onload' in document.createElement('link')) && (/AppleWebKit\/([\d\.]+)/.exec(navigator.userAgent) || {1:'537'})[1] >= '536';

function Load(url, ok, reload){
	var m = /css|js$/.exec(url.toLowerCase()), tag = 'img', attr = 'src';
	if (!m) {
		return Load.img(url, ok);
	}
	if (m[0] == 'css') {
		tag = 'link';
		attr = 'href';
	} else {
		tag = 'script';
	}
	var exists = $(tag).filter(function(){
		var val = this.getAttribute(attr), c = val && val.charAt(url.length);
		return val && val.indexOf(url) === 0 && (c == '?' || c == '&' || c === '');
	});
	if (exists.length) {
		if (!reload) {
			return ok && ok.call(exists[0]);
		}
		exists.remove();
		url += (url.indexOf('?') != -1 ? '&' : '?') + Math.random(5);
	}
	Load[tag](url, ok);
}
$.extend(Load, {
	link:function(url, ok){
		typeof ok == 'function' || (ok = function(){});
		var link = document.createElement('link');
		link.rel = 'stylesheet';
		link.href = url;
		link.type = 'text/css';
	    if (supportCssOnload) {
			link.onload = link.onerror = function(){  
				ok.call(link);
				link.onload = link.onerror = null;
			};
		} else {
			//FF, Safari, Chrome
			var ival = setInterval(function(){  
				try {
					link.sheet.cssRules;
				} catch (e) {
					if (e.code != 1e3 && !e.message.match(/(?:security|denied)/i)) {
						return;
					}
				}
                if (ival) {
                    clearInterval(ival);
                    ival = undefined;
                }
				ok.call(link);
			}, 13);
		}
		if (ostyle) {
			ostyle.parentNode.insertBefore(link, ostyle);
		} else {
			head.appendChild(link);
		}
	    return link;
	},
	script:function(url, ok){
		typeof ok == 'function' || (ok = function(){});
		var script = document.createElement('script'), done = false;
		script.onload = script.onreadystatechange = function(){
			var rs = this.readyState;
			if ( !done && (!rs || rs == 'loaded' || rs == 'complete') )
		    {
				done = true;
				script.onload = script.onreadystatechange = null;
				ok.call(script);
			}
		};
		script.src = url;
		document.body.appendChild(script);
		return script;
	},
	img:function(url, ok){
		var img = new Image();
	    img.onload = function(){ok && ok.call(img, true)}; 
	    img.onerror = function(){ok && ok.call(img, false)};
	    img.src = url;
	}
});

var _cached = {}, url = '?app=special&controller=online&action=getTheme';
$.fn.themeInput = function(ok, set){
	var oknum = 0, total = this.length;
	return this.each(function(){
		var t=this, input=$(t), actived=null, cat=input.attr('cat'),
			data = 'cat='+encodeURIComponent(cat),
			ul = $('<ul class="item-list"></ul>').insertAfter(t);
		function ali(item) {
			var title = item.text||item.name;
			return $('<li name="'+item.name+'" title="'+title+'"><a><img src="'+item.thumb+'" /></a></li>').click(function(){
				t.value = item.name;
				input.triggerHandler('changed', [item.name, item.thumb]);
				actived && removeClass(actived, 'actived');
				actived = this;
				addClass(actived, 'actived');
				return false;
			});
		}
		function b(data){
			for (var k in data) {
				ul.append(ali(data[k]));
			}
			var lis = ul.children();
			if (!set && lis.length) {
				var type = cat.split('/'), c = Theme.def[type[0]]; 
				if (c && type[0] == 'content') {
					c = type[1] ? c[type[1]] : null;
				}
				ul.prepend('<li class="clear"></li>');
				ul.prepend(ali($.extend({
					thumb:'apps/special/images/diy-defaultstyle.png'
				}, c && data[c]||{}, {
					name:'',
					text:'默认'
				})));
			}
			var n = ali({
				name:'(empty)',
				thumb:'apps/special/images/diy-nostyle.png',
				text:'无样式'
			});
			ul.prepend(n);
			var v = ul.children('[name="'+t.value+'"]');
			(!set || v.length ? v : n).click();
			++oknum == total && ok && ok();
		}
		var cdata = _cached[data];
		if (cdata) {
			b(cdata);
		} else {
			$.ajax({
				type:'GET',
				dataType:'json',
				url:url,
				data:data,
				success:function(json){
					_cached[data] = json;
					b(json);
				}
			});
		}
	});
};

$.extend(DIY, {
	use:Load,
	setUI:function(){
		function term(cat, txt, val) {
			var li = $('<li><a><img src="apps/special/images/diy-nostyle.png" /></a><p class="val">'+(val||'(empty)')+'</p><p>['+txt+']</p><div class="set-ui-pop"><input cat="'+cat+'" type="hidden" class="theme-input" value="'+(val||'(empty)')+'" ></div></li>'),
				img = li.find('img'), span = li.find('p.val'),
				div = li.find('div'), input = div.find('input');
			
			input.bind('changed', function(e, val, thumb){
				img.attr('src', thumb);
				span.html(val);
				hide();
			});
			var hide = function(){
				div.appendTo(li);
				$doc.unbind('mousedown', ihide);
			};
			var ihide = function(e){
				var t = e.target, tag = t.nodeName || '*';
				div[0] == t || div.find(tag).index(t) != -1 ||
				li[0] == t || li.find(tag).index(t) != -1 || hide();
			};
			li.click(function(){
				div.appendTo(document.body);
				var pos = li.offset();
				div.css({
					top:pos.top,
					left:pos.left,
					zIndex:parseInt(li.closest('.ui-dialog').css('zIndex'))
				});
				$doc.mousedown(ihide);
			});
			return li;
		}
		return function(theme){
			var themeName = theme && ENV.themes[theme] && ENV.themes[theme].name || theme || '&nbsp;';
			var def = theme && ENV.themes[theme] && ENV.themes[theme]['define'] || {};
			var origTheme = Theme.link.attr('theme');
			var title = '设置风格';
			if (theme == 'custom') {
				LANG.BUTTON_OK = '保存';
				LANG.BUTTON_PREVIEW = '预览';
				LANG.BUTTON_SAVEAS = '另存为';
			} else if (theme) {
				LANG.BUTTON_OK = (ENV.themes[theme] && ENV.themes[theme].reserved) ? null : '保存';
				LANG.BUTTON_PREVIEW = '预览';
				LANG.BUTTON_SAVEAS = '另存为';
			} else {
				LANG.BUTTON_OK = '保存';
				LANG.BUTTON_PREVIEW = '预览';
				title = '添加风格';
			}
			
			
			var d = localForm({title:title, width:720}, 'SET_UI', function(form, dialog){
				dialog.find('.head>span').html(themeName);
				var ul = dialog.find('ul');
				var o = {page:'页面', title:'标题', frame:'布局外框', widget:'模块外框'};
				for (var k in o) {
					ul.append(term(k, o[k], def[k] || ''));
				}
				for (var i=0,l=ENV.engines.length;i<l;i++) {
					var e = ENV.engines[i];
					ul.append(term('content/'+e.engine, e.text, def.content && def.content[e.engine] || ''));
				}
				dialog.dialog('option', 'position', 'center');
				ul.find('input').themeInput(null, true);
			}, function(form, flag){
				var nr = {}, dr = [], nc = {}, dc = [];
				form.find('.theme-input').each(function(){
					var t = this, cat = t.getAttribute('cat'), s = cat.split('/'), v = t.value;
					if (s[0] == 'content') {
						if (v && v != '(empty)') {
							nc[s[1]] = v;
							dc.push('"'+s[1]+'":"'+v+'"');
						}
					} else {
						if (v && v != '(empty)') {
							nr[cat] = v;
							dr.push('"'+cat+'":"'+v+'"');
						}
					}
				});
				dr.push('"content":{'+dc.join(',')+'}');
				var data = 'data='+encodeURIComponent('{'+dr.join(',')+'}');
				nr.content = nc;
				flag || (flag = 'ok');
				var url = '?app=special&controller=online&action=setUI&theme='+(theme||'')+'&flag='+flag+'&pageid='+ENV.pageid;
				if (flag == 'preview') {
					$.post(url, data, function(json){
						if (json.state) {
							ENV.themes.preview = json.theme;
							Theme.name = null;
							Theme.pname = null;
							Theme.set('preview');
						} else {
							d.message('fail', json.error);
						}
					}, 'json');
				} else if (flag == 'saveas' || (!theme && flag == 'ok')) {
					var t = flag == 'saveas' ? '另存为' : '保存';
					var dialog = localForm({title:t, width:400}, 'SAVE_AS', function(form){
						$(form[0].thumb).imageInput();
					}, function(form){
						data += '&'+form.serialize();
						$.post(url, data, function(json){
							if (json.state) {
								ENV.themes[json.name] = json.theme;
								DIY.addTheme(json.name, json.theme);
                                body.message('success', t+'完毕');
								d.dialog('close');
							} else {
                                d.message('fail', json.error);
							}
						}, 'json');
						dialog.dialog('close');
					});
				} else {
					$.post(url, data, function(json){
						if (json.state) {
							ENV.themes[theme]['define'] = nr;
							Theme.name = null;
							Theme.pname = null;
							Theme.set(theme);
                            body.message('success','保存完毕');
							d.dialog('close');
						} else {
                            d.message('fail',json.error);
						}
					}, 'json');
				}
			}, function(){
				Theme.name == 'preview' && Theme.set(origTheme);
			});
		};
	}()
});

DIY.registerInit(function(){
	Theme.init(ENV.theme, ENV.usedDir);
});

return Theme;

})();



