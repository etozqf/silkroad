(function() {
	var ts = {},
	panel = $('#template_list>ul');

	/* sider */
	ts.Sider = {
		init: function() {
			ts.Sider.listLoader = new ScrollLoader({
				panel: panel,
				size: 20,
				url: '?app=editor&controller=template&action=ls',
				template: '<li title="[name]" data-id="[templateid]"><a href="javascript:;">[name]</a><img src="images/delete.gif" class="hand" alt="删除" title="删除" /></li>',
				rowCallback: function(row, json) {
					row.bind('click', {
						json: json
					}, function(event) {
						ts.Editor.editInit(event.data.json.templateid);
					});
					row.find('img').bind('click', {
						json: json,
						row: row
					},function(event) {
						event.stopPropagation();
						ct.confirm('确定删除?', function() {
							$.post('?app=editor&controller=template&action=rm', {
								id: event.data.json.templateid
							}, function(req) {
								if (req.state) {
									event.data.row.remove();
									ct.ok('删除成功');
								} else {
									ct.error(req.error || '删除失败');
								}
							}, 'json');
						});
					});
				},
				errorCallback: function(message) {
					ct.error(message || '');
				}
			});
			ts.Sider.listLoader.load(0);
			$('#add_template').bind('click', function() {
				ts.Editor.addInit();
			});
		}
	};

	/* editor */
	ts.Editor = {
		editorId: 0,
		init: function() {
			$('#content').editor();
			ts.Editor.editorTitle = $('#editor_title');
			$('#submit').bind('click', ts.Editor.submit);
		},
		addInit: function() {
			ts.Editor.editorTitle.html('新建: <input type="text" id="name" value="" placeholder="模板名称" />');
			ts.Editor.editorId = 0;
			ed.setContent('');
		},
		editInit: function(id) {
			panel.children('.cur').removeClass('cur');
			panel.children('[data-id="'+id+'"]').addClass('cur');
			$.get('?app=editor&controller=template&action=content', {
				id: id
			}, function(req){
				if (!req || !req.state) {
					return ct.error(req.error || '读取内容失败');
				}
				ts.Editor.editorTitle.html('编辑: <span class="mar_l_8">'+req.name+'</span><img class="hand mar_l_8" src="images/edit.gif" alt="编辑" />');
				ts.Editor.editorTitle.children('img').bind('click', ts.Editor.editTitle);
				ed.setContent(req.content, {formart:'raw'});
				ts.Editor.editorId = id;
			}, 'json');
		},
		editTitle: function() {
			var name = ts.Editor.editorTitle.children('span').html();
			ts.Editor.editorTitle.html('编辑: <input type="text" id="name" value="'+name+'" placeholder="模板名称" />');
		},
		submit: function() {
			var data = {
				name: ($('#name').length > 0) ? $('#name').val() : '',
				content: ed.getContent({format:'raw'})
			}, action;
			if (ts.Editor.editorId) {
				action = 'edit';
				data['id'] = ts.Editor.editorId;
			} else {
				action = 'add';
			}
			if (!ts.Editor.editorId && !data['name']) {
				return ct.error('模板名称不可为空');
			}
			if (!ed.getContent()) {
				return ct.error('内容不可为空');
			}
			$.post('?app=editor&controller=template&action='+action, data, function(req) {
				if (!req.state) {
					return ct.error(req.error || '保存失败');
				}
				ct.ok('保存成功');
				if (action == 'add' ) {
					ts.Editor.editInit(req['id']);
				}
				ts.Sider.offset = 0;
				ts.Sider.listLoader.load(0);
			}, 'json');
		}
	}

	$(function() {
		ct.fet(IMG_URL+'js/lib/cmstop.scrollloader.js', function() {
			ts.Sider.init();
			ts.Editor.init();
		});
	});
	window.templateSetting = ts;
})();