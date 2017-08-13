var Gen = (function(){
function ival(v) {
	v = $.trim(v);
	if (!v) return null;
	var m = RE.ival.exec(v);
	if (m) {
		return m[0];
	}
	v = parseFloat(v);
	return isNaN(v) ? null : (v == 0 ? '0' : v+'px');
}
function bval(v) {
	v = $.trim(v);
	if (!v) return null;
	var m = RE.bval.exec(v);
	if (m) {
		return m[0];
	}
	v = parseFloat(v);
	return isNaN(v) ? null : (v == 0 ? '0' : v+'px');
}
function sval(v) {
	v = $.trim(v);
	return v && RE.sval.test(v) ? v : null;
}
function hasNext(elem, className){
	while (elem = elem.nextSibling) {
		if ( elem.nodeType == 1 && hasClass(elem, className)) {
			return true
		}
	}
	return false;
}
function isCenter(style) {
	var a = {1:null};
	return ( (RE['margin-left'].exec(style) || a)[1] == 'auto'
		&& (RE['margin-right'].exec(style) || a)[1] == 'auto' )
	|| RE.center.test(style);
}
function fold(name, frm){
	var checkbox = $(frm[name]),
	label = checkbox.closest('label').next(),
	labelo = label.nextAll('label'),
	topsign = label.children('span:first'),
	minput = topsign.next(),
	inputs = labelo.find('select,input'),
	toggle = function(checked) {
		if (checked) {
			inputs.attr('disabled','').removeAttr('disabled');
			labelo.show();
			topsign.show();
			minput.is(':text') && minput.removeClass('diy-size-15').addClass('diy-size-3');
		} else {
			inputs.attr('disabled','disabled');
			labelo.hide();
			topsign.hide();
			minput.is(':text') && minput.removeClass('diy-size-3').addClass('diy-size-15');
		}
	};
	toggle(checkbox[0].checked);
	checkbox.click(function(){
		toggle(this.checked);
	});
}

function Gen(){
	var data = [];
	data.push('"head":'+Gen.genHead());
	$('div.diy-root').each(function(){
		data.push('"'+this.id+'":'+Gen.genArea(this, true));
	});
	return '{'+data.join(',')+'}';
}
$.extend(Gen, {
	genHead:function(){
		return ['{',
			'"title":',Gen.q(ENV.title),
			',"theme":',Gen.q(Theme.name),
			',"meta":',Gen.genMeta(ENV.metas||{}),
			',"resource":',Gen.genRes(ENV.resources||{}),
			',"body-style":',Gen.q(body.attr('body-style')),
			',"a-style":',Gen.q(body.attr('a-style')),
		'}'].join('');
	},
	genMeta:function(o){
		var data = [];
		for (var k in o) {
			data.push(Gen.q(k)+':'+Gen.q(o[k]));
		}
		return '{'+data.join(',')+'}';
	},
	genRes:function(o){
		var data = [];
        $.each(o, function(path){
            data.push(Gen.q(path));
        });
		return '['+data.join(',')+']';
	},
	genArea:function(area, root) {
		var data = ['{',
			'"id":"', area.id, '",'
		];
		if (! root) {
			var style = ['width:'+$.curCSS(area, 'width')];
			if (children(area.parentNode, '.diy-area').length > 1 && !hasNext(area, 'diy-area'))
			{
				style.push('float:right');
			}
			data.push('"style":'+Gen.q(style.join(';'))+',');
		}
		data.push('"items":{');

		var items = [];
		each(children(area, 'div'), function(){
			hasClass(this, 'diy-wrapper')
			 ? items.push(Gen.genWrapper(this))
			 : hasClass(this, 'diy-widget')
				 && items.push('"'+this.id+'":'+Gen.genWidget(this));
		});
		return (data.join('') + items.join(',') + '}}');
	},
	genWrapper:function(wrapper) {
		var frame = children(wrapper, '.diy-frame')[0];
		var data = ['"',frame.id,'":{',
			'"id":"',frame.id,'"',
            ',"hidden":', (hasClass(wrapper, 'diy-hidden') ? '1' : '0'),
			',"theme":{',
				'"frame":',Gen.q(frame.getAttribute('frame-theme')),
				',"title":',Gen.q(frame.getAttribute('title-theme')),
			'}',
			',"style":{',
				'"frame":',Gen.q(frame.getAttribute('frame-style')),
				',"title":',Gen.q(frame.getAttribute('title-style')),
				',"title-w":',Gen.q(frame.getAttribute('title-w-style')),
				',"title-a":',Gen.q(frame.getAttribute('title-a-style')),
			'}',
			',"title":',Gen.genTitle(frame),
			',"items":{'
		].join('');
		var items = [];
		each(children(frame, '.diy-area'),function(){
			items.push('"'+this.id+'":'+Gen.genArea(this));
		});
		return (data + items.join(',') + '}}');
	},
	genWidget:function(widget) {
		return ['{',
			'"id":"',widget.id,'"',
			',"widgetid":"',widget.getAttribute('widgetid'),'"',
            ',"hidden":', (hasClass(widget, 'diy-hidden') ? '1' : '0'),
			',"theme":{',
				'"widget":',Gen.q(widget.getAttribute('widget-theme')),
				',"title":',Gen.q(widget.getAttribute('title-theme')),
				',"content":',Gen.q(widget.getAttribute('content-theme')),
			'}',
			',"style":{',
				'"widget":',Gen.q(widget.getAttribute('widget-style')),
				',"inner":',Gen.q(widget.getAttribute('inner-style')),
				',"title":',Gen.q(widget.getAttribute('title-style')),
				',"content":',Gen.q(widget.getAttribute('content-style')),
				',"inner-w":',Gen.q(widget.getAttribute('inner-w-style')),
				',"title-w":',Gen.q(widget.getAttribute('title-w-style')),
				',"content-w":',Gen.q(widget.getAttribute('content-w-style')),
				',"inner-a":',Gen.q(widget.getAttribute('inner-a-style')),
				',"title-a":',Gen.q(widget.getAttribute('title-a-style')),
				',"content-a":',Gen.q(widget.getAttribute('content-a-style')),
			'}',
			',"title":',Gen.genTitle(widget),
		'}'].join('');
	},
	genTitle:function(where) {
		var title = hasClass(where, 'diy-frame') ? children(where, '.diy-title') : $.find('.diy-title', where),
			item = [];
		title.length && $.each(children(title[0],'*'),function(){
			var a = $(this);
			item.push(['{',
				'"text":',Gen.q(a.text()),
				',"href":',Gen.q(a.attr('href')),
				',"img":',Gen.q(a.children('img').attr('data-src')),
				',"newtab":', a.attr('target') == '_blank',
				',"style":',Gen.q(a.attr('item-style')),
			'}'].join(''));
		});
        title = item.join(',');
        return title ? ('['+title+']') : 'null';
	},
	renderFrame:function(param){
		var frame = $('<div id="'+guid('f-')+'" class="diy-frame"></div>');
		if ($.isArray(param)) {
			var l = param.length;
			if (l < 2) {
				frame.append('<div id="'+guid('a-')+'" class="diy-area" style="width:100%"></div>');
			} else {
				var i=0, t = 0;
				for (i=0;i<l;i++) {
					t += (param[i] = toInt(param[i]));
				}
				var tp = 99.8;
				for (i=0;i<l;i++) {
					var p = tp;
					if (i < l-1) {
						p = (param[i]*100/t).toFixed(1);
						tp -= parseFloat(p);
					}
					frame.append('<div id="'+guid('a-')+'" class="diy-area" style="width:'+p+'%"></div>');
				}
			}
		} else {
			var per = toInt(param);
			if (per > 1) {
				var p = parseFloat((100/per).toFixed(1)), lp = (99.9 - (per-1)*p).toFixed(1);
				while (per--) {
					frame.append('<div id="'+guid('a-')+'" class="diy-area" style="width:'+(per ? p : lp)+'%"></div>');
				}
			} else {
				frame.append('<div id="'+guid('a-')+'" class="diy-area" style="width:100%"></div>');
			}
		}
		Theme.def.frame && frame.addClass('frame-'+Theme.def.frame);
		return frame;
	},
	renderWidget:function(engine){
		var id = guid('w-'),
		widget = $('<div id="'+id+'" class="diy-widget diy-modified">'+
			'<div id="'+id+'-i" class="diy-inner">'+
				'<div id="'+id+'-c" class="diy-content diy-content-'+engine+'"></div>'+
			'</div>'+
		'</div>');
		
		Theme.def.widget && widget.addClass('widget-'+Theme.def.widget);
		Theme.def.content && Theme.def.content[engine]
			&& widget.find('.diy-content').addClass('content-'+engine+'-'+Theme.def.content[engine]);
		return widget;
	},
	addTitle:function(where, t){
		t || (t = $('<div id="'+where[0].id+'-t" class="diy-title"></div>'));
		if (where.hasClass('diy-frame')) {
			where.children('.diy-area').eq(0).before(t);
		} else {
			where.find('.diy-inner').prepend(t);
		}
		var theme = where.attr('title-theme');
		theme = theme
			? (theme == '(empty)' ? '' : theme)
			: Theme.def.title;
		theme && t.addClass('title-'+theme);
		return t;
	},
	useSkin:function(widget, engine, skin) {
		if (!skin) return;
		try {
			skin = (new Function('return '+skin))();
		} catch (e) {
			return;	
		}
		var id = widget[0].id,
			theme = skin.theme, style = skin.style,
			inner = widget.find('.diy-inner'),
			content = widget.find('.diy-content'),
			wTheme = '', cTheme = '';
		// load css file
		if (theme.widget) {
			if (theme.widget != '(empty)') {
				Theme.addDir('widget/'+theme.widget);
				wTheme = theme.widget;
			}
			switchClass(widget, Theme.def.widget, wTheme, 'widget-');
		}
		
		if (theme.title && theme.title != '(empty)') {
			Theme.addDir('title/'+theme.title);
		}
		
		if (theme.content) {
			if (theme.content != '(empty)') {
				Theme.addDir('content/'+engine+'/'+theme.content);
				cTheme = theme.content;
			}
			switchClass(content, (Theme.def.content && Theme.def.content[engine]), cTheme, 'content-'+engine+'-');
		}
		
		// set cssText
		Theme.setRule('#'+id, style['widget']);
		Theme.setRule('#'+id+'-i', style['inner']);
		Theme.setRule('#'+id+'-t', style['title']);
		Theme.setRule('#'+id+'-c', style['content']);
		Theme.setRule('#'+id+'-i *', style['inner-w']);
		Theme.setRule('#'+id+'-t *', style['title-w']);
		Theme.setRule('#'+id+'-c *', style['content-w']);
		Theme.setRule('#'+id+'-i a', style['inner-a']);
		Theme.setRule('#'+id+'-t a', style['title-a']);
		Theme.setRule('#'+id+'-c a', style['content-a']);
		
		// change attr
		widget.attr({
			'widget-theme':theme.widget,
			'title-theme':theme.title,
			'content-theme':theme.content,
			'widget-style':style.widget,
			'inner-style':style.inner,
			'title-style':style.title,
			'content-style':style.content,
			'inner-w-style':style['inner-w'],
			'title-w-style':style['title-w'],
			'content-w-style':style['content-w'],
			'inner-a-style':style['inner-a'],
			'title-a-style':style['title-a'],
			'content-a-style':style['content-a']
		});
		
		// render title
		if (skin.title && skin.title.length) {
			var title = Gen.addTitle(widget);
			for (var i=0,t;t=skin.title[i++];) {
				title.append(Gen.renderSkinTitle(t));
			}
		}
	},
	renderSkinTitle:function(item){
		var text = item.img ? ('<img src="'+item.img+'"/>'+item.text) : item.text;
		if (!text) return '';
		return item.href
			? ('<a href="'+item.href+'" style="'+item.style+'" item-style="'+item.style+'">'+text+'</a>')
			: ('<span style="'+item.style+'" item-style="'+item.style+'">'+text+'</span>');
	},
	renderTitleItem:function(item, link){
		var text = item.img.value ? ('<img src="'+macroReplace(item.img.value)+'" data-src="'+item.img.value+'" border="0" />'+item.text.value) : item.text.value,
            morelist = '';
		if (!text) return '';
		var css = [], v = ival(item.offset.value), f = $(item['float']).filter(':checked').val(),
		target = item.newtab.checked ? '_blank' : '_self';
		if (f == 'right') {
			css.push('float:right');
			v && css.push('margin-right:'+v);
		} else {
            if (f == 'center') {
                css.push('display:block;text-align:center');
            } else {
                v && css.push('margin-left:'+v);
            }
            if (item['morelist.add'].checked) {
                morelist = '<a target="'+target+'" href="' + link + '" item-style="float:right;margin-right:5px;" style="float:right;margin-right:5px;" target="_blank">更多</a>';
            }
        }
		v = ival(item['font-size'].value);
		v && css.push('font-size:'+v);
		v = sval(item.color.value);
		v && css.push('color:'+v);
		css = css.join(';');
		return morelist + (item.href.value
			? ('<a target="'+target+'" href="'+item.href.value+'" style="'+css+'" item-style="'+css+'">'+text+'</a>')
			: ('<span style="'+css+'" item-style="'+css+'">'+text+'</span>'));
	},
	setPage:function(){
		LANG.BUTTON_PREVIEW = '预览';
		var oStyle = {
				body:body.attr('body-style'),
				a:body.attr('a-style')
			}, recover = false;
		localForm({title:'设置页面样式', width:500, height:300}, 'SET_PAGE',
		function(form, dialog){
			var frm = form[0], css = body.attr('body-style'), acss = body.attr('a-style'), m;
            each('font-family font-size color letter-spacing', function(i, s){
                if (m = RE[s].exec(css)) {
                    frm[s].value = m[1];
                }
                if (m = RE[s].exec(acss)) {
                    frm['a-'+s].value = m[1];
                }
            });
            // checkbox
            each('font-bold font-italic font-underline', function(i, s){
                if (RE[s].test(css)) {
                    frm[s].checked = true;
                }
                if (RE[s].test(acss)) {
                    frm['a-'+s].checked = true;
                }
            });
            each('background-color background-image background-repeat', function(i, s){
                if (m = RE[s].exec(css)) {
                    frm[s].value = m[1];
                }
            });
			if (m = RE['background-position'].exec(css)) {
				m = m[1].split(' ');
				frm['background-position-x'].value = m[0];
				if (m[1]) {
					frm['background-position-y'].value = m[1];
				}
			}
			form.find('.color-input').colorInput();
			ImageInput.prepare(form.find('.image-input'));
			family_fontsize(form.find('input.wfamily'),form.find('input.wfontsize'),form.find('input.afamily'),form.find('input.afontsize'));
		},function(form, flag){
			var frm = form[0], style = {
				body:[],
				a:[]
			};
			each('letter-spacing font-size color font-family', function(i, s){
				each('body a', function(j, t){
					var v = frm[t == 'body' ? s : ('a-'+s)].value;
					v = i < 2 ? ival(v) : sval(v);
					if (v) {
						style[t].push(s+':'+v);
					}
				});
			});
            // checkbox
            each('font-bold font-italic font-underline', function(i, s){
                each('body a', function(j, t){
                    var p = frm[t == 'body' ? s : ('a-'+s)];
                    p.checked && style[t].push(p.value);
                });
            });
            each('background-color background-image background-repeat', function(i, s){
                var v = sval(frm[s].value);
                if (v) {
                    if (s == 'background-image' && v != 'none') {
                        v = 'url('+v+')';
                    }
                    style.body.push(s+':'+v);
                }
            });
			var pos = [],
				b = bval(frm['background-position-x'].value);
			b && pos.push(b);
			b = bval(frm['background-position-y'].value);
			b && pos.push(b);
			pos.length && style.body.push('background-position:'+pos.join(' '));
			
			style.body = style.body.join(';');
			style.a = style.a.join(';');
			Theme.setRule('body', style.body);
			Theme.setRule('a', style.a);
			
			recover = flag == 'preview';
			// change attr
			if (!recover) {
                body.attr({
                    'body-style':style.body,
                    'a-style':style.a
                });
                History.log('pageStyle', [body, style, oStyle]);
            }
		},function(){
			if (recover) {
				Theme.setRule('body', oStyle.body);
				Theme.setRule('a', oStyle.a);
			}
		});
	},
	setFrameDialog:function(frame){
		LANG.BUTTON_PREVIEW = '预览';
		var id = frame[0].id,
			title = frame.find('.diy-title'),
            ofTheme = Theme.getThemeName('frame', frame.attr('frame-theme')),
            otTheme = Theme.getThemeName('title', frame.attr('title-theme')),
            lfTheme = ofTheme, ltTheme = otTheme,
			oAttr = {
                'frame-theme':frame.attr('frame-theme') || '',
                'title-theme':frame.attr('title-theme') || '',
				'frame-style':frame.attr('frame-style') || '',
				'title-style':frame.attr('title-style') || '',
				'title-w':frame.attr('title-w-style') || '',
				'title-a':frame.attr('title-a-style') || ''
			}, recover = false;
		localForm({title:'设置框架样式', width:500, height:500}, 'SET_FRAME',
		function(form, dialog){
			DIY.tabs(dialog, null, null, 'mouseenter');
			var frm = form[0];
			frm['frame-theme'].value = frame.attr('frame-theme')||'';
			frm['title-theme'].value = frame.attr('title-theme')||'';
			var m, style = {
				frame:frame.attr('frame-style'),
				title:frame.attr('title-style'),
				'title-w':frame.attr('title-w-style'),
				'title-a':frame.attr('title-a-style')
			};
			each('title-w title-a', function(i, t){
				var css = style[t];
                each('font-family font-size color letter-spacing', function(i, s){
                    if (m = RE[s].exec(css)) {
                        frm[t+'-'+s].value = m[1];
                    }
                });
                // checkbox
                each('font-bold font-italic font-underline', function(i, s){
                    if (RE[s].test(css)) {
                        frm[t+'-'+s].checked = true;
                    }
                });
			});
			each('frame title',function(i, t){
				var css = style[t];
				each('background-color background-image background-repeat',
				function(i, s){
					if (m = RE[s].exec(css)) {
						frm[t+'-'+s].value = m[1];
					}
				});
				if (m = RE['background-position'].exec(css)) {
					m = m[1].split(' ');
					frm[t+'-background-position-x'].value = m[0];
					if (m[1]) {
						frm[t+'-background-position-y'].value = m[1];
					}
				}
			});
			each('height width', function(){
				if (m = RE[this].exec(style.frame)) {
					frm['frame-'+this].value = m[1];
				}
			});
			if (isCenter(style.frame)) {
				frm['frame-center'].checked = true;
			}
			// frame margin
			var fcss = style['frame'];
			if (m = RE['margin'].exec(fcss)) {
				frm['frame-margin-top'].value = m[1];
			} else if (RE['margin-all'].test(fcss)) {
				frm['frame-margin-all'].checked = true;
				each('top right bottom left',function(){
					var k = 'margin-'+this;
					if (m = RE[k].exec(fcss)) {
						frm['frame-'+k].value = m[1];
					}
				});
			}
			// frame border
            each('style width color', function(i,s){
				if (m = RE['border-'+s].exec(fcss)) {
					frm['frame-border-top-'+s].value = m[1];
				} else if (RE['border-all-'+s].test(fcss)) {
                    frm['frame-border-all-'+s].checked = true;
					each('top right bottom left',function(){
						var k = 'border-'+this+'-'+s;
						if (m = RE[k].exec(fcss)) {
							frm['frame-'+k].value = m[1];
						}
					});
				}
			});
			// title padding
			var tcss = style['title'];
			if (m = RE.padding.exec(tcss)) {
				frm['title-padding-top'].value = m[1];
			} else if (RE['padding-all'].test(tcss)) {
				frm['title-padding-all'].checked = true;
				each('top right bottom left',function(){
					var k = 'padding-'+this;
					if (m = RE[k].exec(tcss)) {
						frm['title-'+k].value = m[1];
					}
				});
			}
			form.find('.item-detail').click(function(){
				if (hasClass(this, 'expanded')) {
					removeClass(this, 'expanded');
					this.nextSibling.style.display = 'none';
				} else {
					addClass(this, 'expanded');
					this.nextSibling.style.display = 'block';
					dialog[0].scrollTop = getPosition(this).top;
					if (!$.data(this, 'inited')) {
						$.data(this, 'inited', true);
						ImageInput.prepare($('.image-input', this.nextSibling));
					}
				}
			});
			form.find('.theme-input').each(function(){
				var t = this, k = t.name.split('-')[0];
				t.setAttribute('cat', k);
			}).themeInput(function(){
				dialog.dialog('option', 'position', 'center');
			});
			form.find('.color-input').colorInput();
			fold('frame-margin-all', frm);
			fold('frame-border-all-width', frm);
			fold('frame-border-all-style', frm);
			fold('frame-border-all-color', frm);
			fold('title-padding-all', frm);
		}, function(form, flag){
			var frm = form[0],
				ftv = frm['frame-theme'].value,
				ttv = frm['title-theme'].value,
				style = {
					frame:[],
					title:[],
					'title-w':[],
					'title-a':[]
				};
			each('height width', function(){
				var s = this, v = ival(frm['frame-'+s].value);
				v && style.frame.push(s+':'+v);
			});
			// frame margin
			if (frm['frame-margin-all'].checked) {
				each('top right bottom left',function(){
					var k = 'margin-'+this, v = ival(frm['frame-'+k].value);
					v && style.frame.push(k+':'+v);
				});
			} else {
				var v = ival(frm['frame-margin-top'].value);
				v && style.frame.push('margin:'+v);
			}
			if (frm['frame-center'].checked) {
				style.frame.push('margin-left:auto');
				style.frame.push('margin-right:auto');
			}
			// frame border
			each('style width color', function(i, s){
				if (frm['frame-border-all-'+s].checked) {
					each('top right bottom left',function(){
						var k = 'border-'+this+'-'+s,
							v = s == 'width'
								? ival(frm['frame-'+k].value)
								: sval(frm['frame-'+k].value);
                        v && style.frame.push(k+':'+v);
					});
				} else {
					var k = 'border-top-'+s, v = s == 'width'
								? ival(frm['frame-'+k].value)
								: sval(frm['frame-'+k].value);
					v && style.frame.push('border-'+s+':'+v);
				}
			});
            each('letter-spacing font-size color font-family', function(j, s){
                var v = frm['title-w-'+s].value;
                v = j < 2 ? ival(v) : sval(v);
                if (v) {
                    style.title.push(s+':'+v);
                    style['title-w'].push(s+':'+v);
                }
                v = frm['title-a-'+s].value;
                v = j < 2 ? ival(v) : sval(v);
                if (v) {
                    style['title-a'].push(s+':'+v);
                }
            });
            // checkbox
            each('font-bold font-italic font-underline', function(i, s){
                var p = frm['title-w-'+s];
                if (p.checked) {
                    style.title.push(p.value);
                    style['title-w'].push(p.value);
                }
                p = frm['title-a-'+s];
                p.checked && style['title-a'].push(p.value);
            });

			// frame title
			each('frame title',function(i, t){
				each('background-color background-image background-repeat',
				function(i, s){
					var v = sval(frm[t+'-'+s].value);
					if (v) {
						if (s == 'background-image' && v != 'none') {
							v = 'url('+v+')';
						}
						style[t].push(s+':'+v);
					}
				});
				var pos = [],
					b = bval(frm[t+'-background-position-x'].value);
				b && pos.push(b);
				b = bval(frm[t+'-background-position-y'].value);
				b && pos.push(b);
				pos.length && style[t].push('background-position:'+pos.join(' '));
			});
			// title padding
			if (frm['title-padding-all'].checked) {
				each('top right bottom left',function(){
					var k = 'padding-'+this, v = ival(frm['title-'+k].value);
					v && style.title.push(k+':'+v);
				});
			} else {
				var v = ival(frm['title-padding-top'].value);
				v && style.title.push('padding:'+v);
			}
			
			each('frame title title-w title-a',function(){
				style[this] = style[this].join(';');
			});
			
			// load css file
			var fTheme = Theme.getThemeName('frame', ftv), tTheme = Theme.getThemeName('title', ttv);
			// switch class
			switchClass(title, ltTheme, tTheme, 'title-');
            switchClass(frame, lfTheme, fTheme, 'frame-');
            lfTheme = fTheme;
            ltTheme = tTheme;

			Theme.setRule('#'+id, style['frame']);
			Theme.setRule('#'+id+'-t', style['title']);
			Theme.setRule('#'+id+'-t *', style['title-w']);
			Theme.setRule('#'+id+'-t a', style['title-a']);
			
			recover = flag == 'preview';
            // change attr
            var attr = {
                'frame-theme':ftv,
                'title-theme':ttv,
                'frame-style':style['frame'],
                'title-style':style['title'],
                'title-w-style':style['title-w'],
                'title-a-style':style['title-a']
            };
            frame.attr(attr);

            setFrameWrapper(frame);

            if (!recover) {
                History.log('frameStyle', [frame, attr, oAttr]);
            }

		}, function(){
			if (recover) {
				switchClass(frame, lfTheme, ofTheme, 'frame-');
				switchClass(title, ltTheme, otTheme, 'title-');
				
				Theme.setRule('#'+id, oAttr['frame-style']);
				Theme.setRule('#'+id+'-t', oAttr['title-style']);
				Theme.setRule('#'+id+'-t *', oAttr['title-w-style']);
				Theme.setRule('#'+id+'-t a', oAttr['title-a-style']);

                frame.attr(oAttr);
				
				setFrameWrapper(frame);
			}
		});
	},
	setWidgetDialog:function(widget){
		LANG.BUTTON_PREVIEW = '预览';
		
		var id = widget[0].id,
			engine = widget.attr('engine'),
			inner = widget.find('.diy-inner'),
			title = widget.find('.diy-title'),
			content = widget.find('.diy-content'),
			owTheme = Theme.getThemeName('widget', widget.attr('widget-theme')),
			otTheme = Theme.getThemeName('title', widget.attr('title-theme')),
			ocTheme = Theme.getThemeName('content/'+engine, widget.attr('content-theme')),
            lwTheme = owTheme, ltTheme = otTheme, lcTheme = ocTheme,
            oAttr = {
                'widget-theme':widget.attr('widget-theme')||'',
                'title-theme':widget.attr('title-theme')||'',
                'content-theme':widget.attr('content-theme')||'',
                'widget-style':widget.attr('widget-style')||'',
                'inner-style':widget.attr('inner-style')||'',
                'title-style':widget.attr('title-style')||'',
                'content-style':widget.attr('content-style')||'',
                'inner-w-style':widget.attr('inner-w-style')||'',
                'title-w-style':widget.attr('title-w-style')||'',
                'content-w-style':widget.attr('content-w-style')||'',
                'inner-a-style':widget.attr('inner-a-style')||'',
                'title-a-style':widget.attr('title-a-style')||'',
                'content-a-style':widget.attr('content-a-style')||''
            }, recover = false;
		
		localForm({title:'设置模块样式', width:500, height:500}, 'SET_WIDGET',
		function(form, dialog){
			DIY.tabs(dialog, null, null, 'mouseenter');
			var frm = form[0];
			frm['widget-theme'].value = widget.attr('widget-theme')||'';
			frm['title-theme'].value = widget.attr('title-theme')||'';
			frm['content-theme'].value = widget.attr('content-theme')||'';
			var m,
				wStyle = widget.attr('widget-style'),
				style = {
					inner:widget.attr('inner-style'),
					title:widget.attr('title-style'),
					content:widget.attr('content-style'),
					'inner-w':widget.attr('inner-w-style'),
					'title-w':widget.attr('title-w-style'),
					'content-w':widget.attr('content-w-style'),
					'inner-a':widget.attr('inner-a-style'),
					'title-a':widget.attr('title-a-style'),
					'content-a':widget.attr('content-a-style')
				};
			if (isCenter(wStyle)) {
				frm['widget-center'].checked = true;
			}
			each('height width', function(i,s){
				if (m = RE[s].exec(wStyle)) {
					frm['widget-'+s].value = m[1];
				}
			});
			if (m = RE.height.exec(style.content)) {
				frm['content-height'].value = m[1];
			}
			if (RE['text-center'].test(style.content)) {
				frm['content-text-center'].checked = true;
			}
			if (m = RE['line-height'].exec(style.content)) {
				frm['content-line-height'].value = m[1];
			}
            each('inner-w title-w content-w inner-a title-a content-a', function(i, t){
                var css = style[t];
                each('letter-spacing font-size color font-family', function(j, s){
                    if (m = RE[s].exec(css)) {
                        frm[t+'-'+s].value = m[1];
                    }
                });
                // checkbox
                each('font-bold font-italic font-underline', function(j, s){
                    if (RE[s].test(css)) {
                        frm[t+'-'+s].checked = true;
                    }
                });
            });
			each('background-color background-image background-repeat', function(i, s){
				each('inner title content', function(i, t){
					if (m = RE[s].exec(style[t])) {
						frm[t+'-'+s].value = m[1];
					}
				});
			});
			each('margin padding',function(i, s){
				var css = style.inner;
				if (m = RE[s].exec(css)) {
					frm['inner-'+s+'-top'].value = m[1];
				} else if (RE[s+'-all'].test(css)) {
					frm['inner-'+s+'-all'].checked = true;
					each('top right bottom left',function(i, t){
						var k = s+'-'+t;
						if (m = RE[k].exec(css)) {
							frm['inner-'+k].value = m[1];
						}
					});
				}
			});
			each('style width color', function(i, s){
				var css = style.inner;
				if (m = RE['border-'+s].exec(css)) {
					frm['inner-border-top-'+s].value = m[1];
				} else if (RE['border-all-'+s].test(css)) {
					frm['inner-border-all-'+s].checked = true;
					each('top right bottom left',function(){
						var k = 'border-'+this+'-'+s;
						if (m = RE[k].exec(css)) {
							frm['inner-'+k].value = m[1];
						}
					});
				}
			});
			if (m = RE['background-position'].exec(style.inner)) {
				m = m[1].split(' ');
				frm['inner-background-position-x'].value = m[0];
				if (m[1]) {
					frm['inner-background-position-y'].value = m[1];
				}
			}
			
			each('title content', function(i, s){
				var css = style[s];
				if (m = RE.padding.exec(css)) {
					frm[s+'-padding-top'].value = m[1];
				} else if (RE['padding-all'].test(css)) {
					frm[s+'-padding-all'].checked = true;
					each('top right bottom left',function(){
						var k = 'padding-'+this;
						if (m = RE[k].exec(css)) {
							frm[s+'-'+k].value = m[1];
						}
					});
				}
				if (m = RE['background-position'].exec(css)) {
					m = m[1].split(' ');
					frm[s+'-background-position-x'].value = m[0];
					if (m[1]) {
						frm[s+'-background-position-y'].value = m[1];
					}
				}
			});
			
			form.find('.item-detail').click(function(){
				if (hasClass(this, 'expanded')) {
					removeClass(this, 'expanded');
					this.nextSibling.style.display = 'none';
				} else {
					addClass(this, 'expanded');
					this.nextSibling.style.display = 'block';
					dialog[0].scrollTop = getPosition(this).top;
					if (!$.data(this, 'inited')) {
						$.data(this, 'inited', true);
						ImageInput.prepare($('.image-input', this.nextSibling));
					}
					family_fontsize($(this).next().find('input.wfamily'),$(this).next().find('input.wfontsize'),$(this).next().find('input.afamily'),$(this).next().find('input.afontsize'));
				}
			});
			form.find('.theme-input').each(function(i, t){
				var k = t.name.split('-')[0];
				if (k=='content') {
					k += '/'+engine;
				}
				t.setAttribute('cat', k);
			}).themeInput(function(){
				dialog.dialog('option', 'position', 'center');
			});
			form.find('.color-input').colorInput();
			
			fold('inner-margin-all', frm);
			fold('inner-padding-all', frm);
			fold('inner-border-all-width', frm);
			fold('inner-border-all-style', frm);
			fold('inner-border-all-color', frm);
			fold('title-padding-all', frm);
			fold('content-padding-all', frm);
		}, function(form, flag){
			var frm = form[0],
				wtv = frm['widget-theme'].value,
				ttv = frm['title-theme'].value,
				ctv = frm['content-theme'].value,
				style = {
					widget:[],
					inner:[],
					title:[],
					content:[],
					'inner-w':[],
					'title-w':[],
					'content-w':[],
					'inner-a':[],
					'title-a':[],
					'content-a':[]
				};
			// widget
			if (frm['widget-center'].checked) {
				style['widget'].push('margin-left:auto');
				style['widget'].push('margin-right:auto');
			}
			if (frm['content-text-center'].checked) {
				style['content'].push('text-align:center');
			}
			each('height width', function(){
				var s = this, v = ival(frm['widget-'+s].value);
				v && style['widget'].push(s+':'+v);
			});
			var v = ival(frm['content-height'].value);
			v && style['content'].push('height:'+v);
			
			var lhv = ival(frm['content-line-height'].value);
			lhv && style.content.push('line-height:'+lhv);
			// inner
			each('margin padding',function(i, s){
				if (frm['inner-'+s+'-all'].checked) {
					each('top right bottom left',function(){
						var k = s+'-'+this, v = ival(frm['inner-'+k].value);
						v && style['inner'].push(k+':'+v);
					});
				} else {
					var v = ival(frm['inner-'+s+'-top'].value);
					v && style['inner'].push(s+':'+v);
				}
			});
			each('style width color', function(i, s){
				if (frm['inner-border-all-'+s].checked) {
					each('top right bottom left',function(){
						var k = 'border-'+this+'-'+s,
							v = s == 'width'
								? ival(frm['inner-'+k].value)
								: sval(frm['inner-'+k].value);
						v && style['inner'].push(k+':'+v);
					});
				} else {
					var k = 'border-top-'+s, v = s == 'width'
								? ival(frm['inner-'+k].value)
								: sval(frm['inner-'+k].value);
					v && style['inner'].push('border-'+s+':'+v);
				}
			});
            each('inner title content', function(i, t){
                each('letter-spacing font-size color font-family', function(j, s){
                    var v = frm[t+'-w-'+s].value;
                    v = j < 2 ? ival(v) : sval(v);
                    if (v) {
                        style[t].push(s+':'+v);
                        style[t+'-w'].push(s+':'+v);
                    }
                    v = frm[t+'-a-'+s].value;
                    v = j < 2 ? ival(v) : sval(v);
                    if (v) {
                        style[t+'-a'].push(s+':'+v);
                    }
                });
                // checkbox
                each('font-bold font-italic font-underline', function(i, s){
                    var p = frm[t+'-w-'+s];
                    if (p.checked) {
                        style[t].push(p.value);
                        style[t+'-w'].push(p.value);
                    }
                    p = frm[t+'-a-'+s];
                    p.checked && style[t+'-a'].push(p.value);
                });
            });
			// inner title content
			each('inner title content',function(i, t){
				each('background-color background-image background-repeat',
				function(i, s){
					var v = sval(frm[t+'-'+s].value);
					if (v) {
						if (s == 'background-image' && v != 'none') {
							v = 'url('+v+')';
						}
						style[t].push(s+':'+v);
					}
				});
				var pos = [],
					b = bval(frm[t+'-background-position-x'].value);
				b && pos.push(b);
				b = bval(frm[t+'-background-position-y'].value);
				b && pos.push(b);
				pos.length && style[t].push('background-position:'+pos.join(' '));
			});
			// title content
			each('title content', function(i, t){
				if (frm[t+'-padding-all'].checked) {
					each('top right bottom left',function(){
						var k = 'padding-'+this, v = ival(frm[t+'-'+k].value);
						v && style[t].push(k+':'+v);
					});
				} else {
					var v = ival(frm[t+'-padding-top'].value);
					v && style[t].push('padding:'+v);
				}
			});
			each('widget inner title content inner-w title-w content-w inner-a title-a content-a',function(){
				style[this] = style[this].join(';');
			});
			
			// load css file
			var wTheme = Theme.getThemeName('widget', wtv),
                tTheme = Theme.getThemeName('title', ttv),
                cTheme = Theme.getThemeName('content/'+engine, ctv);

			// switch class
			switchClass(widget, lwTheme, wTheme, 'widget-');
			switchClass(title, ltTheme, tTheme, 'title-');
			switchClass(content, lcTheme, cTheme, 'content-'+engine+'-');
			// record
			lwTheme = wTheme;
			ltTheme = tTheme;
			lcTheme = cTheme;
			
			// set cssText
			Theme.setRule('#'+id, style['widget']);
			Theme.setRule('#'+id+'-i', style['inner']);
			Theme.setRule('#'+id+'-t', style['title']);
			Theme.setRule('#'+id+'-c', style['content']);
			Theme.setRule('#'+id+'-i *', style['inner-w']);
			Theme.setRule('#'+id+'-t *', style['title-w']);
			Theme.setRule('#'+id+'-c *', style['content-w']);
			Theme.setRule('#'+id+'-i a', style['inner-a']);
			Theme.setRule('#'+id+'-t a', style['title-a']);
			Theme.setRule('#'+id+'-c a', style['content-a']);
			
			recover = flag == 'preview';
			// change attr
			if (!recover) {
                var attr = {
                    'widget-theme':wtv,
                    'title-theme':ttv,
                    'content-theme':ctv,
                    'widget-style':style['widget'],
                    'inner-style':style['inner'],
                    'title-style':style['title'],
                    'content-style':style['content'],
                    'inner-w-style':style['inner-w'],
                    'title-w-style':style['title-w'],
                    'content-w-style':style['content-w'],
                    'inner-a-style':style['inner-a'],
                    'title-a-style':style['title-a'],
                    'content-a-style':style['content-a']
                };
                widget.attr(attr);
                History.log('widgetStyle', [widget, attr, oAttr]);
            }
		}, function(){
			if (recover) {
				switchClass(widget, lwTheme, owTheme, 'widget-');
				switchClass(title, ltTheme, otTheme, 'title-');
				switchClass(content, lcTheme, ocTheme, 'content-'+engine+'-');
				
				Theme.setRule('#'+id, oAttr['widget-style']);
				Theme.setRule('#'+id+'-i', oAttr['inner-style']);
				Theme.setRule('#'+id+'-t', oAttr['title-style']);
				Theme.setRule('#'+id+'-c', oAttr['content-style']);
				Theme.setRule('#'+id+'-i *', oAttr['inner-w-style']);
				Theme.setRule('#'+id+'-t *', oAttr['title-w-style']);
				Theme.setRule('#'+id+'-c *', oAttr['content-w-style']);
				Theme.setRule('#'+id+'-i a', oAttr['inner-a-style']);
				Theme.setRule('#'+id+'-t a', oAttr['title-a-style']);
				Theme.setRule('#'+id+'-c a', oAttr['content-a-style']);
			}
		});
	},
	setTitleDialog:function(where){
		var title = where.hasClass('diy-widget')
				? where.find('.diy-title') : where.children('.diy-title'),
			origTitle = null, hasTitle = title.length, recover = false,
            widgetid = where.attr('widgetid'),
            engine = DIY.getWidgetEngines()[where.attr('engine')],
            supportMorelist = false, morelistSetting = {},
            morelistLink = APP_URL + '?app=special&controller=morelist&action=widget&contentid='+ENV.contentid+'&widgetid='+widgetid,
            newtab = where.find('.diy-title > a').attr('target') == '_blank';
		LANG.BUTTON_PREVIEW = '预览';
        function openDialog() {
            localForm('设置标题', 'SET_TITLE', function(form){
                var frm = form[0], item = title.children(morelistSetting.add ? ':last' : null),
                    morelistItems = form.find('.fn-widget-morelist');
                if (item.length) {
                    var style = item.attr('item-style');
                    frm.text.value = item.text();
                    if (item[0] && item[0].nodeName == 'A') {
                        frm.href.value = item.attr('href');
                    }
                    frm.img.value = item.children('img').attr('data-src')||'';
                    var m = RE['float'].exec(style);
                    if (m) {
                        if (m[1] == 'right') {
                            frm['float'][2].checked = true;
                        } else {
                            frm['float'][0].checked = true;
                        }
                    } else if (RE['text-center'].exec(style) && RE['block'].exec(style)) {
                        frm['float'][1].checked = true;
                    }
                    each('offset font-size color',function(){
                        if (m = RE[this].exec(style)) {
                            frm[this].value = m[1];
                        }
                    });
                }
                // 对齐为居中时，禁用 offset 设置项
                $(frm['float']).change(function() {
                    var self = $(this), offset = $(frm['offset']), val = this.value;
                    if (! this.checked) return;
                    if (val == 'center') {
                        offset.attr('disabled', true);
                    } else {
                        offset.removeAttr('disabled');
                    }
                    if (val == 'right') {
                        morelistItems.hide();
                    } else {
                        morelistItems.show();
                    }
                }).trigger('change');
                form.find('.color-input').colorInput();
                ImageInput.prepare(form.find('.image-input'));
                if (supportMorelist) {
                    var chk = frm['morelist.add'];
                    parseInt(morelistSetting.add) && (chk.checked = true);
                    form.find('a.fn-widget-morelist').click(function() {
                        frm['href'].value = morelistLink;
                        ! chk.checked && (chk.checked = true);
                    });
                    if (morelistSetting.add) {
	                	var moreitem = title.children(morelistSetting.add ? ':first' : null);
	                	if (item.attr('href') == moreitem.attr('href')) {
	                		frm['morelist.titleurl'].checked = true;
	                	}
	                }
                } else {
                    morelistItems.hide();
                }
                form[0].newtab.checked = newtab;
                family_fontsize(form.find('input.wfamily'),form.find('input.wfontsize'),form.find('input.afamily'),form.find('input.afontsize'));
            }, function(form, flag){
            	// 提交表单
                if (!origTitle) {
                    origTitle = title.appendTo($fragments);
                } else {
                    title && title.remove();
                }
                if (form[0]['morelist.titleurl'].checked) {
                	morelistLink = form.find('a.fn-widget-morelist').prev().val();
                }
                var item = Gen.renderTitleItem(form[0], morelistLink);
                title = item ? Gen.addTitle(where).html(item) : null;
                var callback = function() {
                    recover = flag == 'preview';
                    if (flag == 'nextstep') {
                        LANG.BUTTON_OK = '完成';
                        LANG.BUTTON_CANCEL = null;
                        DIY.setStyle(where);
                    }
                };
                if (supportMorelist) {
                    // 保存更多链接设置
                    var data = 'setting[morelist][add]=' + (form.find('[name=morelist.add]').attr('checked') ? 1 : 0);
                    json('?app=special&controller=online&action=editWidgetSetting&widgetid='+widgetid, data, function(json) {
                        if (json && json.state) {
                            callback();
                        } else {
                            form.message('fail', json && json.error || '区块设置修改失败');
                        }
                    });
                } else {
                    callback();
                }
            }, function(){
                if (recover) {
                    title && title.remove();
                    Gen.addTitle(where, origTitle);
                }
            });
        }
        if (engine && engine.support && $.isArray(engine.support) && $.inArray('morelist', engine.support) !== false) {
            // 异步检查当前模块的内容设置，如果是手动，则不显示列表相关的设置
            json('?app=special&controller=online&action=getOneWidget', 'widgetid='+widgetid, function(json) {
                if (json && json.state && json.widget.data && json.widget.data.method != 1) {
                    supportMorelist = true;
                    morelistSetting = json.widget.setting && json.widget.setting.morelist || {};
                }
                openDialog();
            }, openDialog, 'GET');
        } else {
            openDialog();
        }
	},
	q:function(){
		var escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
			meta = {
				'\b': '\\b',
				'\t': '\\t',
				'\n': '\\n',
				'\f': '\\f',
				'\r': '\\r',
				'"' : '\\"',
				'\\': '\\\\'
			};
		return function(str) {
			str = str ? (str + '') : '';
			escapable.lastIndex = 0;
			return escapable.test(str) ?
				'"' + str.replace(escapable, function (a) {
					var c = meta[a];
					return typeof c === 'string' ? c :
					'\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
				}) + '"' :
				'"' + str + '"';
		}
	}()
});
return Gen;
})();
function family_fontsize(wfamily, wfontsize, afamily, afontsize)
{
	var family = new Array('微软雅黑','黑体','宋体', 'Arial', 'Courier', 'Georgia', 'Palatino', 'Verdana', 'Helvetica', 'Courier New', 'Times New Roman', 'NSimSun', 'FangSong', 'KaiTi', 'FangSong_GB2312', 'KaiTi_GB2312', 'Microsoft JhengHei');
	var fontsize = new Array('12px','14px','16px','18px','20px');
	var family_options = '';
	for (var f = 0; f < family.length; f++) {
		family_options += '<option value="'+family[f]+'">'+family[f]+'</option>';
	}
	var size_options = '';
	for (var s = 0; s < fontsize.length; s++) {
		size_options += '<option value="'+fontsize[s]+'">'+fontsize[s]+'</option>';
	}
	var wfamilytext = '请选择';
	var wfontsizetext = '请选择';
	if (wfamily.val() != '') {
		wfamilytext = wfamily.val();
	}
	if (wfontsize.val() != '') {
		wfontsizetext = wfontsize.val();
	}
	if (!wfamily.next('select').length) {
		wfamily.hide().after('<select class="title-w-s-font-family"><option value="'+wfamily.val()+'">'+wfamilytext+'</option>'+family_options+'</select>');
	}
	if (!wfontsize.next('select').length) {
		wfontsize.hide().after('<select class="title-w-s-font-size"><option value="'+wfontsize.val()+'">'+wfontsizetext+'</option>'+size_options+'</select>');
	}
	wfamily.next().change(function(){
		wfamily.val(wfamily.next().find("option:selected").val());
	});
	wfontsize.next().change(function(){
		wfontsize.val(wfontsize.next().find("option:selected").val());
	});
	var afamilytext = '请选择';
	var afontsizetext = '请选择';
	if (afamily.val() != '') {
		afamilytext = afamily.val();
	}
	if (afontsize.val() != '') {
		afontsizetext = afontsize.val();
	}
	if (!afamily.next('select').length) {
		afamily.hide().after('<select class="title-a-s-font-family"><option value="'+afamily.val()+'">'+afamilytext+'</option>'+family_options+'</select>');
	}
	if (!afontsize.next('select').length) {
		afontsize.hide().after('<select class="title-a-s-font-size"><option value="'+afontsize.val()+'">'+afontsizetext+'</option>'+size_options+'</select>');
	}
	afamily.next().change(function(){
		afamily.val(afamily.next().find("option:selected").val());
	});
	afontsize.next().change(function(){
		afontsize.val(afontsize.next().find("option:selected").val());
	});
}