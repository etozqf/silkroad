/**
 * $Id: editor_plugin_src.js 2009-05-05  $
 *
 * @author Ywindf
 * @modified by shanhuhai 2010.7.9
 */

(function() {

	tinymce.create('tinymce.plugins.OneKeyClearPlugin', {
		init : function(ed, url) {
			//允许的标签项
			allowTags = ['p', 'a', 'img', 'br', 'b', 'strong', 'cmstop', 'gffy'];
			//验证的正则
			tagPatrn = /<\s*([\/]?)\s*([\w]+)[^>]*>/ig;
			
			//注册清理的命令，可以在按钮被点击的时候触发
			ed.addCommand('mceOneKeyClear', function() {
				if (ed.selection.getContent()) {
					var htmlContentold = ed.selection.getContent();
					var htmlContent = htmlContentold;
					var htmlContentall = ed.getContent();
				} else {
					var htmlContent = ed.getContent();
					var htmlContentold = false;
					var htmlContentall = false;
				}
				htmlContent = htmlContent.replace(/[\n\r]*/g, '');
				var moreread = htmlContent.match(/<div id="moreread">.*<\/ul>/g);
				htmlContent = htmlContent.replace(/<div id="moreread">.*<\/ul>/g, '<gffy>');
				//console.info(htmlContent);/<div id="moreread">.*</ul></div>/
				//删除允许范围之外的标签
				htmlContent = htmlContent.replace(/<p class="mcePageBreak">/ig, '<cmstop>');
				htmlContent = htmlContent.replace(tagPatrn, function(withTag, isClose, htmlTag){
					var htmlReturn = '';
					htmlTag = htmlTag.toLowerCase();
					for (i = 0; i < allowTags.length; i++){
						if(allowTags[i] != htmlTag){
							continue;
						}
						if(isClose == ''){
							switch(htmlTag){
								case 'p':
									htmlReturn = '<p>';
									break;
								case 'a':
									htmlReturn = withTag;
									break;
								case 'br':
									htmlReturn = '</p><p>';
									break;
								default:
									htmlReturn = withTag;
									break;
							}
						}else
							htmlReturn = withTag;
						break;
					}
					return htmlReturn;
				});
				htmlContent = htmlContent.replace(/<cmstop>/ig, '<p class="mcePageBreak">');
				htmlContent = htmlContent.replace(/<a\s[^>]*>(.*?)<\/a>/img,'$1');// remove link
				htmlContent = htmlContent.replace(/<p>(<img[^>]+\/>)<\/p>/img,'<p style="text-align:center;text-indent:0">$1</p>');
				htmlContent = htmlContent.replace('<gffy>', moreread+'</div>');
				ed.setContent(htmlContent);
				htmlContent = ed.getContent();
				htmlContent = htmlContent.replace(/<p>(\s|&nbsp;|　)*(.*)<\/p>/img,function(a, b, c){
					if(c =='') return  '';
					else return '<p>'+c+'</p>';
				});
				if (htmlContentall && htmlContentold) {
					htmlContent = htmlContentall.replace(htmlContentold, htmlContent);
				}
				ed.setContent(htmlContent);
			});

			//注册按钮
			ed.addButton('onekeyclear', {
				title : '\u4e00\u952e\u6392\u7248',
				cmd : 'mceOneKeyClear'
			});

			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('onekeyclear', n.nodeName == 'IMG');
			});
		}
	});

	// Register plugin
	tinymce.PluginManager.add('onekeyclear', tinymce.plugins.OneKeyClearPlugin);
})();