/**
 * $Id: editor_plugin_src.js 201 2007-02-12 15:56:56Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright � 2004-2008, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	tinymce.create('tinymce.plugins.ct_templatePlugin', {
		init : function(ed, url) {
			this.editor = ed;

			// 注册命令
			ed.addCommand('ct_template', function() {
				ed.windowManager.open({
					file : '?app=editor&controller=template&action=index',
					width : 480,
					height : 270,
					inline : 1
				});
			});

			//注册按钮
			ed.addButton('ct_template', {
				title : 'html模板',
				cmd : 'ct_template',
				image : url + '/img/ct_template.png'
			});
		}
	});

	tinymce.PluginManager.add('ct_template', tinymce.plugins.ct_templatePlugin);
})();