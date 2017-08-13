$(function() {
	var panel = $('#list'),
	content = $('#content'),
	getContent = function (id) {
		$.get('?app=editor&controller=template&action=content', {
			id: id
		}, function(req){
			content.children('legend').html(req['name']);
			content.children('.code').html(req['content']);
		}, 'json');
	};

	ct.fet(IMG_URL+'js/lib/cmstop.scrollloader.js', function() {
		var listLoader = new ScrollLoader({
			panel: panel,
			size: 20,
			url: '?app=editor&controller=template&action=ls',
			template: '<li class="hand" title="[name]"><a href="javascript:;">[name]</a></li>',
			rowCallback: function(row, json) {
				row.bind('click', {
					json: json
				}, function(event){
					panel.children('.cur').removeClass('cur');
					$(this).addClass('cur');
					getContent(event.data.json.templateid);
				});
			},
			errorCallback: function(message) {
				ct.error(message || '');
			}
		});
		listLoader.load(0);

		$('#clearBtn').bind('click', function() {
			tinyMCEPopup.close();
		});
		$('#okBtn').bind('click', function() {
			var html = content.children('.code').html();
			tinyMCEPopup.execCommand('mceInsertContent', false, html);
			tinyMCEPopup.close();
		});
	});
});