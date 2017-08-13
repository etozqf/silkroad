$(function() {
	var siderElement = $('#sider'),
	siderHr = siderElement.find('hr'),
	styleForm = $('#style-form'),
	demo = $('#demo'),
	btnSaveAs = $('#saveas'),
	btnSave = $('#save'),
	btnUse = $('#use'),
	btnDelete = $('#delete'),
	newStyleDialog = $('#new-style-name'),
	baseUrl = '?app=mobile&controller=setting&action=style',
	absUploadurl = function (url) {
		if (url.indexOf('://') === -1) {
			url = UPLOAD_URL + url;
		}
		return url;
	};
	/* upload */
	function appendReuploadBtn(elem) {
		elem.find('.mobile-style-bg-upload').mobileUploader(function(json) {
			elem.find('input').val(json.file);
			elem.find('img').replaceWith('<img src="'+UPLOAD_URL+json.file+'" />');
		});
	}
	$('.mobile-style-bg-panel').each(function() {
		appendReuploadBtn($(this));
	});
	/* sider */
	var Sider = (function() {

		var buildItem = function(item) {
			var elm = $('<a href="javascript:;"></a>');
			elm.html(item.name);
			elm.data('data', item);

			if (item.styleid == using) {
				elm.addClass('use');
			}
			if (item.system == '1') {
				siderHr.before(elm);
			} else {
				siderElement.append(elm);
			}
			elm.bind('click', function() {
				var $this = $(this),
				data = $this.data('data');
				if ($this.hasClass('current')) {
					return;
				}
				siderElement.children('.current').removeClass('current');
				$this.addClass('current');
				styleForm.trigger('set', data);
			});
			elm.bind('remove', {
				id: item.styleid
			}, function(event) {
				var id = event.data.id;
				for (var i=0; i < styles.length; i++) {
					if (styles[i].styleid === id) {
						styles.splice(i, 1);
					}
				}
			});
		};

		for (var i = 0, l = styles.length; i < l; i++) {
			buildItem(styles[i]);
		}

		this.buildItem = buildItem;
		return this;
	})();

	/* demo */
	demo.bind('change', function(event, data) {
		if (data.nav) {
			$('#demo-nav').css('background-color', data.nav);
		}
		if (data.button0) {
			$('#demo-button0').css('background-color', data.button0);
		}
		if (data.button1) {
			$('#demo-button1').css('background-color', data.button1);
		}
		if (data.background) {
			$('#demo-background').attr('src', data.background);
		}
	});
	/* style form */
	(function() {
		styleForm.bind('set', function(event, data) {
			var style = JSON.parse(data.data), i;
			styleForm.find('[name="id"]').val(data.styleid);
			styleForm.find('[name="style[nav]"]').val(style.nav);
			styleForm.find('[name="style[button0]"]').val(style.button0);
			styleForm.find('[name="style[button1]"]').val(style.button1);
			for (i in style.background) {
				var e = $('#' + i.replace('*', ''));
				e.find('input').val(style.background[i]);
				e.find('img').attr('src', absUploadurl(style.background[i]));
			}
			demo.trigger('change', {
				nav : style.nav,
				button0 : style.button0,
				button1 : style.button1,
				background: absUploadurl(style.background[i])
			});
			if (data.system == '1') {
				btnSave.hide();
				btnDelete.hide();
			} else {
				btnSave.show();
				if (siderElement.find('.current').hasClass('use')) {
					btnDelete.hide();
				} else {
					btnDelete.show();
				}
			}
			if (data.styleid == using) {
				btnUse.hide();
			} else {
				btnUse.show();
			}
			$('.color').each(function() {
				var $this = $(this),
				color = $this.val();
				$this.next().css('background', color);
			});

			// for IE
			// $('.mobile-style-setting').css('margin-left', '-3px');
		});
		/* color picker */
		$('.color').colorInput().bind('change', function() {
			var $this = $(this), data = {};
			data[$this.attr('data-role')] = $this.val();
			demo.trigger('change', data);
		});
		$('.mobile-style-bg-panel > img').bind('click', function() {
			demo.trigger('change', {
				'background': this.src
			});
		});
		/* button */
		btnSaveAs.bind('click', function() {

			var saveAs = function(name) {
				var data = styleForm.serializeObject();
				data.action = 'saveas';
				data.name = name;
				$.post(baseUrl, data, function(req) {
					if (!req.state) {
						return ct.error(req.error || '另存为失败');
					}
					styles.push(req.data);
					Sider.buildItem(req.data);
				}, 'json');
			};

			$('#newstylename').val('');
			if (newStyleDialog.parent().hasClass('ui-dialog')) {
				newStyleDialog.dialog('open');
			} else {
				newStyleDialog.dialog({
					title: '新增风格',
					width: 360,
					height: 100,
					resizable: false,
					buttons: {
						"确定": function() {
							saveAs($('#newstylename').val());
							newStyleDialog.dialog('close');
						},
						"取消": function() {
							newStyleDialog.dialog('close');
						}
					}
				});
			}
		});
		btnSave.bind('click', function() {
			var data = styleForm.serializeObject();
			data.action = 'save';
			$.post(baseUrl, data, function(req) {
				if (!req.state) {
						return ct.error(req.error || '保存失败');
					}
					ct.ok('保存成功');
			}, 'json');
		});
		btnUse.bind('click', function() {
			var current = siderElement.find('.current'),
			id = current.data('data').styleid;
			$.post(baseUrl, {
				'id' : id,
				'action' : 'use'
			}, function(req) {
				if (!req.state) {
					return ct.error(req.error || '使用失败');
				}
				siderElement.children('.use').removeClass('use');
				current.addClass('use');
				using = id;
				ct.ok('保存成功');
				btnUse.hide();
			}, 'json');
		});
		btnDelete.bind('click', function() {
			ct.confirm('确定要删除当前风格吗?', function() {
				var current = siderElement.find('.current');
				if (current.hasClass('use')) {
					return ct.error('不能删除当前使用风格');
				}
				$.post(baseUrl, {
					'id' : current.data('data').styleid,
					'action' : 'delete'
				}, function(req) {
					if (!req.state) {
						return ct.error(req.error || '删除失败');
					}
					var next = current.next();
					if (next[0] && (next[0].nodeName === 'HR')) {
						next = next.next();
					}
					if (!next[0]) {
						next = current.prev();
					}
					if (next[0] && (next[0].nodeName === 'HR')) {
						next = next.prev();
					}
					current.trigger('remove');
					next.trigger('click');
				}, 'json');
			});
		});
	})();
	siderElement.children('a.use').trigger('click');
});