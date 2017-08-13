var $panel = null, _POPUP_INITS = {
	COMMAND:function(dock){
		var popup = new Popup(dock.getElement(), 100, 130), box = popup.getBox(),
			content = $('<div class="diy-popup-content diy-popup-content-command"></div>').appendTo(box);
		$.each([{
			text:'保存',
			action:DIY.save
		},{
			text:'保存并发布',
			action:function(){DIY.publish(ENV.pageid)}
		},{
			text:'保存为方案',
			action:DIY.saveScheme
		},{
			text:'拷贝页面',
			action:function(){DIY.copyPage(ENV.pageid)}
		}], function(i, v){
			$('<div class="diy-popup-content-item">'+v.text+'</div>').appendTo(content).click(function(){
				popup.hide();
				v.action();
			});
		});
		return popup;
	},
	THEME:function(dock){
		var popup = new Popup(dock.getElement(), 570, 340),
		box = popup.getBox(), themeCache = {},
		pagi = new Pagination(box, {
			width:box.width(),
			height:300
		}, 10, function(page){
			page.addClass('diy-popup-content diy-popup-content-theme');
		}, function(item, data){
			var theme = data.theme, info = data.info, ival = null,
				name = info.name, subname = subtitle(name, 6);
			themeCache[theme] = item;
			item.addClass('diy-popup-content-item diy-theme').append(
				'<img src="'+info.thumb+'" />'+
				'<span>'+subname+'</span>'+
				'<div class="diy-ctrl">'+
					'<i class="diy-icon diy-icon-preview" title="预览"></i>'+
					'<i class="diy-icon diy-icon-setting" title="设置"></i>'+
					'<i class="diy-icon diy-icon-remove" title="删除"></i>'+
				'</div>').attr('name', theme);
			name !== subname && item.attr('tip', name+':center:bottom-27');
			function iuse(){
				ival = null;
				Theme.use(theme);
			}
			item.click(function(){
				ival && clearTimeout(ival);
				ival = null;
				Theme.set(theme);
				item.addClass('diy-active').siblings('.diy-active').removeClass('diy-active');
			});
			item.find('.diy-ctrl').click(function(){
				return false;
			});
			item.find('.diy-icon-preview').hover(function(){
				ival && clearTimeout(ival);
				ival = setTimeout(iuse, 500);
			},function(){
				ival && clearTimeout(ival);
				ival || Theme.reset();
				ival = null;
			});
			item.find('.diy-icon-setting').click(function(){
				popup.hide();
				DIY.setUI(theme);
			});
			var del = item.find('.diy-icon-remove');
			info.reserved ? del.remove() : del.click(function(e){
                box.message('confirm','删除后会影响使用此风格的页面，确定要删除风格"'+info.name+'"吗？', function(){
					json('?app=special&controller=online&action=delTheme&theme='+theme, function(json){
						if (json.state) {
							item.remove();
							delete ENV.themes[theme];
							delete themeCache[theme];
							Theme.name == theme && themeCache.custom.click();
						} else {
                            box.message('fail', json.error);
						}
					});
				});
			});
			theme == Theme.name && item.addClass('diy-active');
		});
		var themes = [];
		for (var k in ENV.themes) {
			themes[k == 'custom' ? 'unshift' : 'push']({
				theme:k,
				info:ENV.themes[k]
			});
		}
		pagi.setData(themes);
		DIY.addTheme = function(name, theme){
			popup.zoom(1);
			pagi.addItem({theme:name, info:theme});
			popup.zoom(0);
		};
		var ctrl = $('<div class="diy-popup-ctrl"></div>').prependTo(box);
		$('<span class="diy-button diy-button-setting"><i></i>设 置</span>').click(function(){
			popup.hide();
			Gen.setPage();
		}).prependTo(ctrl);
		$('<span class="diy-button diy-button-add"><i></i>添 加</span>').click(function(){
			popup.hide();
			DIY.setUI();
		}).appendTo(ctrl);
		return popup;
	},
	LAYOUT:function(dock){
		var popup = new Popup(dock.getElement(), 615, 83), box = popup.getBox(), whole = $panel.add(popup.getPopup()),
		reshow = function(){
			whole.fadeIn();
			popup.lock(0);
		}, options = {
			selectArea:selectAreaByEvent,
			selectPlace:selectPlace,
			propagation:box,
			getHolder:function($h) {
				return $('<div class="diy-placeholder"></div>');
			},
			getGhost:function($h, top, left) {
				popup.lock(1);
				whole.fadeOut('fast');
				return $h.clone().css({
					position:'absolute',
					top:top,
					left:left,
					zIndex:400,
					opacity:.8,
					cursor:'move'
				}).appendTo(document.body);
			},
			fromGhost:function($g, found) {
				var scale = $g.attr('scale');
				$g.remove();
				if (!found) {
					reshow();
					return null;
				}
				if (scale == 'define') {
					var placeHolder = $('<div class="diy-placeholder"></div>');
					localForm({title:'输入列数', width:200},'NEW_FRAME',null,function(form){
						var frame = Gen.renderFrame(form[0].column.value);
						placeHolder.replaceWith(frame);
						var wrapper = initFrame(frame);
						History.log('add', [wrapper, wrapper[0].parentNode, wrapper.next()[0]]);
						reshow();
					},function(){
						placeHolder.remove();
						reshow();
					});
					return placeHolder;
				} else {
					var frame = Gen.renderFrame(scale.split('-'));
					setTimeout(function(){
                        var wrapper = initFrame(frame);
						History.log('add', [wrapper, wrapper[0].parentNode, wrapper.next()[0]]);
					}, 0);
					reshow();
					return frame;
				}
			}
		}, scaledef = {
			'1':'100%',
			'1-1':'1:1',
			'1-1-1':'1:1:1',
			'1-1-1-1':'1:1:1:1',
			'1-2':'1:2',
			'2-1':'2:1',
			'1-3':'1:3',
			'3-1':'3:1',
			'define':'自定义'
		};
		function buildFrameViewItem(scale, name) {
			var item = $('<div class="diy-popup-content-item diy-layout diy-layout-'+scale+'" scale="'+scale+'">'+
					'<a></a>'+
					'<span>'+name+'</span>'+
				'</div>');
			new dragMent(item, options);
			return item;
		}
		var content = $('<div class="diy-popup-content diy-popup-content-layout"></div>').appendTo(box);
		for (var k in scaledef) {
			content.append(buildFrameViewItem(k, scaledef[k]));
		}
		return popup;
	},
	WIDGET:function(dock){
		var popup = new Popup(dock.getElement(), 605, 320), box = popup.getBox(),
		whole = $panel.add(popup.getPopup()), optionsEngine = {
			selectArea:selectAreaByEvent,
			selectPlace:selectPlace,
			propagation:box,
			getHolder:function($h) {
				return $('<div class="diy-placeholder"></div>');
			},
			getGhost:function($h, top, left) {
				whole.fadeOut('fast');
				popup.lock(1);
				return $h.clone().css({
					position:'absolute',
					top:top,
					left:left,
					zIndex:151,
					opacity:.8,
					cursor:'move'
				}).removeAttr('tip').appendTo(document.body);
			},
			fromGhost:function($g, found) {
				var engine = $g.attr('engine');
				$g.remove();
				popup.lock(0);
				if (!found) {
					whole.fadeIn();
					return null;
				} else {
					popup.hide();
					$panel.fadeIn();
				}
				return DIY.addWidget(engine);
			}
		}, optionsInstant = $.extend({}, optionsEngine, {
			fromGhost:function($g, found) {
				var widgetid = $g.attr('widgetid'),
					engine = $g.attr('engine');
				$g.remove();
				popup.lock(0);
				if (!found) {
					whole.fadeIn();
					return null;
				} else {
					popup.hide();
					$panel.fadeIn();
				}
				return DIY.useWidget(engine, widgetid);
			}
		}), 
		tabigation = new Tabigation(box, 'click');
		tabigation.add('功能模块', function(tab, nav){
			var pagination = new Pagination(tab, {width:box.width(), height:255}, 24, function(page){
				page.addClass('diy-popup-content diy-popup-content-module');
			}, function(item, data){
				item.addClass('diy-popup-content-item diy-module');
				item.attr('engine', data.engine);
				var t = data.text, subt = subtitle(t, 4);
				t !== subt && item.attr('tip', t+':center:bottom-25');
				item.append('<img src="'+data.icon+'" /><span>'+subt+'</span>');
				new dragMent(item, optionsEngine);
			});
			pagination.setData(ENV.engines);
		}).add('共享模块', function(tab, nav){
			var pagination = new Pagination(tab, {width:box.width(), height:255}, 24, function(page){
				page.addClass('diy-popup-content diy-popup-content-module');
			}, function(item, data){
				item.addClass('diy-popup-content-item diy-module');
				item.attr('engine', data.engine);
				item.attr('widgetid', data.widgetid);

				item.attr('tip', (data.description || data.text)+':center:bottom-25');
				item.append('<img src="'+data.thumb+'" /><span>'+subtitle(data.text, 4)+'</span><b class="diy-ctrl"><i class="diy-icon diy-icon-remove"></i></b>');
				new dragMent(item, optionsInstant);
				item.find('.diy-icon-remove').click(function(e){
                    box.message('confirm', '确定要取消共享模块"'+data.text+'"吗？',function(){
						var url = '?app=special&controller=online&action=unshareWidget&widgetid='+data.widgetid;
						json(url, function(json){
							if (json.state) {
								item.remove();
							} else {
                                box.message('fail', json.error);
							}
						});
					});
				}).mousedown(function(e){
					e.stopPropagation();
					box.mousedown();
				});
			});
			pagination.setUrl('?app=special&controller=online&action=getWidget&page=%p&pagesize=%s');
			var ctrl = $('<div class="diy-popup-ctrl"></div>').prependTo(tab), lastQueryString = '',
				search = $('<input type="text" class="diy-search" />').appendTo(ctrl);
			
			var engine = ['<select><option value="">所有类型</option>'], engineVal = '';
			$.each(ENV.engines, function(k,v){
				engine.push('<option value="'+v.engine+'">'+v.text+'</option>');
			});
			engine.push('</select>');
			engine = $(engine.join('')).appendTo(ctrl);

			var folder = $('<select>'+
				'<option value="" selected="selected">所有文件夹</option>'+
				'<option value="1">个人文件夹</option>'+
				'<option value="2">公有文件夹</option>'+
			'</select>').appendTo(ctrl), folderVal = '';

			function query(){
				var qs = 'keyword='+encodeURIComponent(search.val())+'&engine='+engineVal+'&folder='+folderVal;
				if (qs !== lastQueryString) {
					lastQueryString = qs;
					pagination.query(qs);
				}
			}

			search.input(function(){
				this.style.cssText = this.value === '' ? '' : 'background-image:none';
				query();
			});

			engine.selectlist().bind('changed', function(e, t) {
                engineVal = t.checked[0];
                query();
            });
            folder.selectlist().bind('changed', function(e, t) {
                folderVal = t.checked[0];
                query();
            });
		}).focus(0);
		return popup;
	},
	SETTING:function(dock){
		var popup = new Popup(dock.getElement(), 500, 300), box = popup.getBox();
		var tabigation = new Tabigation(box, 'click');
		tabigation.add('SEO相关', function(tab, nav){
			var content = $('<div class="diy-popup-content diy-popup-content-seo"></div>').appendTo(tab),
				seoItems = {
					'title':'标题',
					'Keywords':'关键字',
					'Description':'描述',
					'Copyright':'版权信息'
				};
			function buildSEOItem(key, text, value) {
				var item = $('<div class="diy-popup-content-item diy-seo">'+
					'<i class="diy-icon diy-icon-edit"></i><label>'+text+'&nbsp;&nbsp;：</label>'+
					'<a>'+value+'</a>'+
					'<textarea cols="40" rows="2" name="'+key+'">'+value+'</textarea>'+
				'</div>').appendTo(content),
				a = item.find('a'), textarea = item.find('textarea'), editmode = false,
				setEdit = function(){
					if (editmode) return;
					editmode = true;
					item.addClass('diy-actived');
					textarea.focus();
				},
				exitEdit = function(){
					if (!editmode) return;
					editmode = false;
					item.removeClass('diy-actived');
				};
				item.find('.diy-icon-edit').add(a).click(function(){
					setEdit();
				});
				textarea.blur(function(){
					if (this.value != this.defaultValue) {
						var t = this, v = this.value;
						a.text(v);
						textarea.val(v);
						t.defaultValue = v;
						if (t.name == 'title') {
							ENV.title = v;
							document.title = (DIY.isChanged() ? '*　' : '') + v;
						} else {
							ENV.metas[key] = v;
						}
					}
					exitEdit();
				});
				item.dblclick(setEdit);
			}
			for (var k in seoItems) {
				buildSEOItem(k, seoItems[k], (k == 'title' ? ENV.title : ENV.metas[k])||'');
			}
		}).add('资源文件', function(tab, nav){
			var R_EXT = /^(?:(css|js)|(png|gif|jpeg|jpg))$/,
			ctrl = $('<div class="diy-popup-ctrl"></div>').appendTo(tab),
            content = $('<div class="diy-popup-content diy-popup-content-res"></div>').appendTo(tab),
            inner = $('<div class="diy-popup-content-inner"></div>').appendTo(content), notInFinder = 0,
            fd = new Finder({
                baseUrl:envUrl('?app=special&controller=resource&action=%a&path=%p'),
                width:655,
                height:400,
                allowSelectExt:'css|js',
                allowViewExt:'css|js',
                multi:1,
                events:{
                    post:function(items){
                        $.each(items, function(i, d){
                            if (!(d.path in ENV.resources)) {
                                buildRESItem(d);
                                ENV.resources[d.path] = d;
                            }
                        });
                    },
                    hide:function(){
                        popup.lock(0);
                    }
                }
            });

			function codeEditor(data){
			    var url = '?app=special&controller=resource&action=editFile&path='+encodeURIComponent(data.path);
                LANG.BUTTON_OK = '保存';
                ajaxForm('编辑文件'+subtitle(data.path, 24), url, function(json){
                    body.message('success', '保存成功');
                }, function(form){
                    setTimeout(function(){
                        form.find('textarea').editplus({
                            buttons: 'save,fullscreen,wrap'
                        });
                    }, 0);
                }, null, null, function(){
                    notInFinder && popup.lock(0);
                    notInFinder = 0;
                });
			}

			fd.setEditor(codeEditor, 'js|css');
			fd.setEditor(function(data, item){
                var _this = this, icon = item.find('.ui-finder-icon'),
                editor = new ImageEditor(data.path, envUrl('?app=special&controller=resource&action=getConfig&path='+data.path));
                editor.bind('saved', function(json){
                    icon.empty().attr('delayimage', json.data.path);
                    _this._previewImage(icon);
                });
            }, 'jpg|jpeg|png|gif');

			function buildRESItem(data) {
				var ext = R_EXT.exec(data.ext);
				ext = ext ? (ext[1] ? ext[1].toLowerCase() : 'image') : 'unknow';
				var item = $(
				'<div class="diy-popup-content-item diy-res">'+
					'<span class="diy-ext diy-ext-'+ext+'"></span>'+
					'<span class="diy-item-detail">'+
						'<a href="'+data.url+'" target="_blank" title="'+data.path+'">'+subtitle(data.path, 24)+'</a>'+
						'<strong>大小：'+data.size+'</strong><strong>修改日期：'+data.mtime+'</strong>'+
					'</span>'+
				'</div>').appendTo(inner);
				if (ext == 'css' || ext == 'js') {
					$('<i title="编辑" class="diy-icon diy-icon-edit"></i>').click(function(){
						popup.lock(1);
						notInFinder = 1;
						codeEditor(data);
					}).prependTo(item);
				}
				$('<i title="删除" class="diy-icon diy-icon-remove"></i>').click(function(e){
					item.remove();
					delete ENV.resources[data.path];
				}).prependTo(item);
			}

			$('<span class="diy-button diy-button-add"><i></i>添加资源</span>').click(function(){
			    popup.lock(1);
			    fd.reset().open();
			}).appendTo(ctrl);
			$.each(ENV.resources, function(path, d){
				buildRESItem(d);
			});
		}).focus(0);
		return popup;
	},
	PAGE:function(dock){
		var popup = new Popup(dock.getElement(), 250, 80), box = popup.getBox();
		var ctrl = $('<div class="diy-popup-ctrl"></div>').appendTo(box);
		$('<span class="diy-button diy-button-add"><i></i>添加页面</span>').click(function(){
			popup.hide();
			DIY.addPage(function(json){
                DIY.trigger('addPage', [json.data]);
                body.message('confirm', '页面添加成功，开始设计此页？', function(){
                    DIY.designPage(json.data.pageid);
                });
                return true;
            });
		}).appendTo(ctrl);

		var content = $('<div class="diy-popup-content diy-popup-content-page"></div>').appendTo(box),
			inner = $('<div class="diy-popup-content-inner"></div>').appendTo(content);

		function addItem(v){
			var item = $(
			'<div class="diy-popup-content-item" pageid="'+v.pageid+'" name="'+v.name+'" url="'+v.url+'">'+
				'<i></i>'+
				'<span>'+subtitle(v.name, 10)+'</span>'+
			'</div>').appendTo(inner);
			if (v.locked) {
				item.addClass('diy-locked').attr('title', v.lockedby+'正在编辑');
			} else {
				var cmd = $('<div class="diy-popup-content-item-ctrl"></div>').prependTo(item),
				pop = new Popmenu(cmd, 90, 210, [{
					text:'查看',
					action:function(){window.open(item.attr('url'), '_blank');}
				},{
					text:'发布',
					action:function(){DIY.publish(v.pageid)}
				},{
					text:'拷贝页面',
					action:function(){DIY.copyPage(v.pageid)}
				},{
					text:'页面下线',
					action:function(){DIY.offline(v.pageid, item.attr('name'))}
				},{
					text:'页面设置',
					action:function(){DIY.setPage(v.pageid, item.attr('name'))}
				},{
					text:'编辑模版',
					action:function(){DIY.editTemplate(v.pageid)}
				},{
					text:'删除页面',
					action:function(){DIY.delPage(v.pageid, item.attr('name'))}
				}]);
				cmd.bind('popmenushow', function(){
					cmd.addClass('diy-actived');
				}).bind('popmenuhide', function(){
					cmd.removeClass('diy-actived');
				});
			}
			if (ENV.pageid == v.pageid) {
				v.locked || item.addClass('diy-actived');
			} else {
				item.click(function(){DIY.designPage(v.pageid)});
			}
		}
		function adaptHeight(){
			popup.zoom(1);
			popup.setSize(null, content.outerHeight() + 50);
			popup.zoom(0);
		}

		inner.html(spinning());
		adaptHeight();
		json('?app=special&controller=online&action=getPages', function(json){
			inner.empty();
			$.each(json, function(k, v){
				addItem(v);
			});
			adaptHeight();
		}, 'GET');



		DIY.bind('addPage', function(data){
            addItem(data);
            adaptHeight();
        }).bind('delPage', function(pageid){
			inner.find('[pageid="'+pageid+'"]').remove();
			adaptHeight();
		}).bind('setPage', function(pageid, json){
			var page = inner.find('[pageid="'+pageid+'"]');
			page.attr('name', json.name);
			page.find('span').text(json.name);
		});
		return popup;
	}
}, _DOCK_INITS = {
	BACK:function(dock){
		dock.bind('click', function(){
			dock.isEnable() && History.back();
		});
		DIY.bind('hasBack',function(){
			dock.enable();
		}).bind('noBack',function(){
			dock.disable();
		});
	},
	FORWARD:function(dock){
		dock.bind('click', function(){
			dock.isEnable() && History.forward();
		});
		DIY.bind('hasForward',function(){
			dock.enable();
		}).bind('noForward',function(){
			dock.disable();
		});
	},
	COMMAND:createPopup('COMMAND'),
	PREVIEW:function(dock){
		dock.bind('click', function(){
			DIY.preview();
			DIY.designMode ? dock.unactive() : dock.active();
		});
	},
	THEME:createPopup('THEME'),
	LAYOUT:function(dock){
		new dragMent(dock.getElement(), {
			selectArea:selectAreaByEvent,
			selectPlace:selectPlace,
			getHolder:function($h){
				return $('<div class="diy-placeholder"></div>');
			},
			getGhost:function($h, top, left){
				$panel.fadeOut('fast');
				return $('<div class="diy-layout diy-layout-1" scale="1"><a></a></div>').css({
					position:'absolute',
					top:top,
					left:left,
					zIndex:400,
					opacity:.8,
					cursor:'move'
				}).appendTo(document.body);
			},
			fromGhost:function($g, found) {
				$g.remove();
				$panel.fadeIn();
				if (!found) {
					return null;
				}
				var frame = Gen.renderFrame([1]);
				setTimeout(function(){
                    var wrapper = initFrame(frame);
					History.log('add', [wrapper, wrapper[0].parentNode, wrapper.next()[0]]);
				}, 0);
				return frame;
			}
		});
		createPopup('LAYOUT')(dock);
	},
	WIDGET:createPopup('WIDGET'),
	SETTING:createPopup('SETTING'),
	PAGE:createPopup('PAGE')
};
function createPopup(name){
	var init = _POPUP_INITS[name];
	return function(dock){
		var popup = null;
		dock.bind('click', function(){
			popup || (popup = init(dock));
			popup.visible() ? popup.hide() : popup.show();
		}).bind('popupshow', function(){
			dock.active();
		}).bind('popuphide', function(){
			dock.unactive();
		});
	};
}

$.extend(DIY, {
	save:function(){
		var url = '?app=special&controller=online&action=save',
		data = 'jsondata='+encodeURIComponent(Gen());
		json(url, data, function(json){
			if (json.state) {
                body.message('success', json.info);
				History.savePoint();
			} else {
                body.message('fail', json.error);
			}
		});
	},
	saveScheme:function(){
		var url = '?app=special&controller=online&action=saveScheme';
		ajaxForm({
			title:'保存方案',
			width:450
		}, url, function(json){
			if (json.state) {
                body.message('success', json.info);
			} else {
                body.message('fail', json.error);
			}
		}, function(form){
            form.find('[name="typeid"]').selectlist();
            form.find('[name="thumb"]').imageInput(1, 1);
        }, null, null, null, function(data){
			data.push({
				name:'jsondata',
				value:Gen()
			})
		});
	},
	preview:function(){
		var body = $(document.body);
		if (DIY.designMode) {
			body.removeClass('diy-design-mode');
			DIY.designMode = 0;
			$('.diy-wrapper').each(function(){
				this.style.margin = this.getAttribute('view-margin');
			});
		} else {
			DIY.designMode = 1;
			body.addClass('diy-design-mode');
			$('.diy-wrapper').each(function(){
				this.style.margin = this.getAttribute('design-margin');
			});
		}
	},
	publish:function(pageid){
		var data = pageid == ENV.pageid
			? 'jsondata='+encodeURIComponent(Gen())
			: null;
		var url = '?app=special&controller=online&action=publish&pageid='+pageid;
		json(url, data, function(ret){
			if (ret.state) {
				$('div.diy-widget').removeClass('diy-modified');
				History.savePoint();
                body.message('success', '发布成功&nbsp;<a href="'+ret.url+'" target="_blank" style="color:#fff;">点击查看</a>&nbsp;&nbsp;', 5);
			} else {
                body.message('fail', ret.error);
			}
		});
	},
	copyPage:function(pageid){
		var url = '?app=special&controller=online&action=copyPage&pageid='+pageid;
		ajaxForm({
			title:'拷贝页面',
			width:320
		}, url, function(json){
			DIY.trigger('addPage', [json.data]);
            body.message('confirm', '页面拷贝成功，开始设计此页？', function(){
				DIY.designPage(json.data.pageid);
			});
		});
	},
	designPage:function(){
		var rePage = /pageid=\d+/, bUrl, lHref = location.href;
		if (rePage.test(lHref)) {
			bUrl = lHref.replace(rePage, 'pageid=%p');
		} else {
			bUrl = lHref + (lHref.indexOf('?') > -1 ? '&' : '?') + 'pageid=%p';
		}
		return function(pageid){
			window.location = bUrl.replace('%p', pageid);
		};
	}(),
	editTemplate:function(pageid){
		var url = '?app=special&controller=online&action=editTemplate&pageid='+pageid;
        LANG.BUTTON_OK = '保存';
        ajaxForm('编辑页面模板', url, function(){
            body.message('success', '保存成功');
        }, function(form){
            setTimeout(function(){
                form.find('textarea').editplus({
                    buttons: 'save,fullscreen,wrap'
                });
            }, 0);
        });
	},
	offline:function(pageid, title){
		title = title ? ('页面《'+title+'》') : '当前页面';
        body.message('confirm', '确定要将'+title+'下线吗?',
		function(){
			var url = '?app=special&controller=online&action=offline&pageid='+pageid;
			json(url, function(json){
				if (json.state) {
                    body.message('success', title+'已下线');
				} else {
                    body.message('fail', json.error);
				}
			});
		});
	},
	delPage:function(pageid, title){
		title = title ? ('页面《'+title+'》') : '当前页面';
        body.message('confirm', '此操作不可恢复，确定要删除'+title+'吗？',function(){
			json('?app=special&controller=online&action=delPage&pageid='+pageid,
			function(json){
				if (json.state) {
                    body.message('info', '已删除');
					if (pageid == ENV.pageid) {
						setTimeout(function(){
							window.location = location.href.replace(/&?pageid=\d*/, '');
						}, 500);
					}
					DIY.trigger('delPage', [pageid]);
				} else {
                    body.message('fail', json.error);
				}
			});
		});
	},
	setPage:function(pageid, title){
		title = title ? ('页面《'+title+'》') : '当前页面';
		var url = '?app=special&controller=online&action=setPage&pageid='+pageid;
		ajaxForm({
			title:'设置'+title,
			width:320
		}, url, function(json){
			DIY.trigger('setPage', [pageid, json]);
		});
	}
});

function movement(elem, handle){
	var clickX = null, clickY = null, origLeft = null, origTop = null;
	function mousedown(e){
		var pos = elem.offset();
		origLeft = pos.left - ('pageXOffset' in window ? window.pageXOffset : docElem.scrollLeft);
		origTop  = pos.top - ('pageYOffset' in window ? window.pageYOffset : docElem.scrollTop);
		clickX = e.clientX;
		clickY = e.clientY;
		$doc.bind('mouseup.movement', mouseup);
		$doc.bind('mousemove.movement', mousemove);
		$doc.bind('selectstart.movement', function(){return false;});
		$.browser.mozilla && (document.body.style.MozUserSelect = 'none');
		if (handle[0].setCapture) {
			handle.bind('losecapture.movement', mouseup);
			handle[0].setCapture();
		}
	}
	function mousemove(e){
		var dX = e.clientX - clickX, dY = e.clientY - clickY,
			newTop = origTop + dY, newLeft = origLeft + dX,
			offsetHeight = handle[0].offsetHeight,
			offsetWidth = elem[0].offsetWidth,
			clientHeight = window.innerHeight || docElem.clientHeight,
			clientWidth = window.innerWidth || docElem.clientWidth;
		if (newTop < 0) {
			newTop = 0;
		} else if (newTop + offsetHeight > clientHeight) {
			newTop = clientHeight - offsetHeight;
		}

		if (newLeft < 0) {
			newLeft = 0;
		} else if (newLeft + offsetWidth > clientWidth) {
			newLeft = clientWidth - offsetWidth;
		}

		elem.css({left:newLeft,top:newTop});
	}
	function mouseup(e){
		$doc.unbind('.movement');
		handle.unbind('.movement');
		$.browser.mozilla && (document.body.style.MozUserSelect = '');
		handle[0].releaseCapture && handle[0].releaseCapture();
	}
	handle.bind('mousedown', mousedown);
}

DIY.registerInit(function(){
	// movement
	$panel = $(TEMPLATE.PANEL).appendTo(document.body);

	movement($panel, $panel.find('.diy-panel-header'));


	$panel.find('.diy-panel-dock').each(function(){
		var dock = new Dock(this);
		_DOCK_INITS[dock.getName().toUpperCase()](dock);
	});
});
