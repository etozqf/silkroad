(function($) {
	var Defaults = {
			cls: '',
			disable: [],
			uploadUrl: '',
			uploaderInput: 'uploadfile'
		},

		Classes = {
			root: 'miniEditor',
			iframe: 'iframe',
			toolbar: 'toolbar',
			iframeBody: 'miniEditor-body'
		},

		documentTemplate =  '\
			<!DOCTYPE HTML>\
			<html lang="zh_CN">\
			<head>\
				<meta charset="UTF-8">\
				<title>Blank Page</title>\
				<style type="text/css">\
					body {\
						font-size: 14px;\
						font-family: Arial, Helvetica, Verdana, Tahoma, sans-serif !important;\
						line-height: 1.6;\
						margin: 8px;\
					}\
				</style>\
			</head>\
			<body contenteditable="true" spellcheck="false" class="'+Classes['iframeBody']+'">{CONTENT}</body>\
			</html>\
		',

		Commands = {

			bold: '加粗',

			italic: '斜体',

			fontsize: {
				'7_': 'Header 1',
				'6_': 'Header 2',
				'5_': 'Header 3',
				'4_': 'Header 4',
				'3_': 'Header 5',
				'2_': 'Header 6',
				'1_': 'Header 7'
			},

			fontname: {
				"SimSun": '宋体',
				"SimHei": '黑体',
				'Arial': 'Arial',
				'arial black': 'Arial Black',
				"Courier" : 'Courier',
				'comic sans ms':'Comic sans ms'
			},

			forecolor: ["#ffffff", "#000000", "#eeece1", "#1f497d", "#4f81bd", "#c0504d", "#9bbb59", "#8064a2", "#4bacc6", "#f79646", "#ffff00", "#f2f2f2", "#7f7f7f", "#ddd9c3", "#c6d9f0", "#dbe5f1", "#f2dcdb", "#ebf1dd", "#e5e0ec", "#dbeef3", "#fdeada", "#fff2ca", "#d8d8d8", "#595959", "#c4bd97", "#8db3e2", "#b8cce4", "#e5b9b7", "#d7e3bc", "#ccc1d9", "#b7dde8", "#fbd5b5", "#ffe694", "#bfbfbf", "#3f3f3f", "#938953", "#548dd4", "#95b3d7", "#d99694", "#c3d69b", "#b2a2c7", "#b7dde8", "#fac08f", "#f2c314", "#a5a5a5", "#262626", "#494429", "#17365d", "#366092", "#953734", "#76923c", "#5f497a", "#92cddc", "#e36c09", "#c09100", "#7f7f7f", "#0c0c0c", "#1d1b10", "#0f243e", "#244061", "#632423", "#4f6128", "#3f3151", "#31859b", "#974806", "#7f6000"],

			insertunorderedlist: '无序列表',

			insertorderedlist: '有序列表',
			
			underline: '下划线',
			
			justify: {
				left: '左对齐',
				center :'居中对齐',
				right:'右对齐'
			},
			
			createlink: '超链接',
			
			insertimage: '插入图像',

			html: '源码编辑',

			formatcontext: '一键排版'
		};


	var doc = $(document),

		docClickHide = {
			bindEventClose :  function(e, menu, menuContainer, fn, eFn) 
			{
				var el = e.target, tag = el.tagName || '*';
				el == menu[0] || menu.find(tag).index(el) != -1 || el == menuContainer[0] || menuContainer.find(tag).index(el) != -1 || docClickHide.executeClose(menuContainer,fn,eFn);
			},
			executeClose: function(menu, fn, eFn) 
			{
				fn(menu);
				doc.unbind('mousedown', eFn);
			}
		};

	Object.create = function(object) {
		function F(){};
		F.prototype = object;
		var o = new F();
		o.constructor = object;
		return o;
	};


	var inArray = function(item, array) {
			for(var i = 0, len = array.length; i <len; i++) {
				if(item == array[i]) {
					return true;
				}
			}
			return false;
	};


	// TODO: 标准化选区
	var rangeParent = function(win, doc) 
	{
		var theSelection = null,
			theRange = null,
			theParentNode = null,
			tostring = '';

		// IE selections
		if(doc.selection) 
		{ 
			theSelection = doc.selection;
			theRange = theSelection.createRange();

			try {
				theParentNode = theRange.parentElement();
			} 
			catch(e) 
			{
				return false;
			}
		} 
		// Mozilla selections
		else { 
			try 
			{
				theSelection = win.getSelection();
			} 
			catch(e) 
			{
				return false;
			}
			theRange = theSelection.getRangeAt(0);
			theParentNode = theRange.commonAncestorContainer; //选区的开始点和结束点,即选中的区域范围
		}

		while(theParentNode.nodeType == 3) {
			theParentNode = theParentNode.parentNode
		};

		return theParentNode;
	};


	// TODO: 为选区中某个元素执行回调
	var filterTagOfRange = function(win, doc, tag, fn) 
	{
		var theParentNode = rangeParent(win, doc);
		while(theParentNode.nodeName.toLowerCase() != 'body') 
		{
			var TagName = theParentNode.nodeName.toLowerCase();
			
			if(TagName == tag) 
			{
				fn(theParentNode);
				return;
			}

			theParentNode = theParentNode.parentNode;
		}
	};

	// TODO: 解决IE下意外失去选区焦点
	var makeUnselectable = function(node) 
	{
	    if (node.nodeType == 1) {
	        node.setAttribute("unselectable", "on");
	    }
	    var child = node.firstChild;
	    while (child) {
	        makeUnselectable(child);
	        child = child.nextSibling;
	    }
	};


	var core = {
			
			cacheElement: function(name, selector) {
				this[name] = selector; 
			},

			getCacheElement: function(name) {
				if(this[name]) return this[name];
			},

			getWin: function() {
				return this.getCacheElement('iframe').get(0).contentWindow;
			},

			getDoc: function() {
				return this.getWin().document;
			},

			createContainer: function() {
				var textarea  = this.getCacheElement('textarea'),
					container = $('<div class="'+Classes['root']+'" style="width:'+textarea.width()+'px;height:'+textarea.height()+'px;">');

				if(this.settings['cls']) {
					container.addClass(this.settings.cls);
				}

				textarea
					.before(container)
					.hide();

				return container;
			},

			createIframe: function() 
			{
				var container  = this.getCacheElement('container'),
					oIframe   = $('<iframe class="'+Classes['iframe']+'" scroll="on" frameborder="0" width="100%" >');
					container.append(oIframe);

				return oIframe;
			},

			createToolbar: function() 
			{
				var container = this.getCacheElement('container'),
					toolbar = $('<div class="' + Classes['toolbar'] + '">'), 
					ul = $('<ul>');

				var Cmds = this.Cmds;

				for(var cmd in Cmds) 
				{
					var li = $('<li></li>'), 
						a = $('<a href="" data-cmd="'+cmd+'" class="'+cmd+'"></a>');
						li.append(a);

					if(typeof Cmds[cmd] == 'object') 
					{
						var childDiv = $('<div class="'+cmd+'-container hide">');

						if(!$.isArray(Cmds[cmd])) 
						{
							for(var k in Cmds[cmd]) 
							{
								if(cmd == 'fontsize') 
								{
									var key = parseInt(k,10);
									var childA = $('<div data-cmd="'+cmd+"-"+key+'" class="'+cmd+"-"+key+'">'+Cmds[cmd][k]+'</div>');
								} 
								else 
								{
									var childA = $('<div data-cmd="'+cmd+"-"+k+'" class="'+cmd+"-"+k+'">'+Cmds[cmd][k]+'</div>');
								}

								childDiv.append(childA);

								if($.browser.msie) {
									// this.unselectable(childA);
									makeUnselectable(childA[0]);
								}

								this.bind(childA);
							}
						} 
						else 
						{
							for(var i = 0, len = Cmds[cmd].length; i < len; i++) 
							{
								var childA = $('<div data-cmd="'+cmd+"-"+Cmds[cmd][i]+'" class="forecolor-block" style="background-color:'+Cmds[cmd][i]+'"></div>');
									childDiv.append(childA);

								if($.browser.msie) {
									// this.unselectable(childA);
									makeUnselectable(childA[0]);
								}

								this.bind(childA);
							}
						}
						li
							.append(childDiv)
							.css({'position':'relative'});

						drop.bind(a);
					} 
					else 
					{
						a.text(Cmds[cmd]);
					}

					ul.append(li);

					this.bind(a);

					if($.browser.msie) {
						// this.unselectable(a);
						makeUnselectable(a[0]);
					}
				}

				toolbar
					.append(ul)
					.appendTo(container);

				return toolbar;
			},

			bind: function(el) 
			{
				var $this = this;
				el.click(function() 
				{
					$this.action($(this));
					return false;
				});
			},

			// TODO: 开启iframe.document.designMode为on
			enableDesignMode: function() 
			{
				var iframeDoc = this.getDoc(),
					tmplate   = documentTemplate.replace(/{CONTENT}/, this.getCacheElement('textarea').val());

				try {
					iframeDoc.open();
					iframeDoc.write(tmplate);
					iframeDoc.close();
				} catch(error) {

				}

				if(iframeDoc.designMode) {
					iframeDoc.designMode = 'on';
				}
			}, 

			// TODO: 获取并执行命令
			action: function(el) 
			{
				var $this = this;

				var cmd = el.attr('data-cmd'),
					val = null;

				if (el.hasClass('gray')) return;

				if(/fontsize/.test(cmd)) {
					val = parseInt(cmd.split('-')[1],10);
					cmd = 'fontsize';
					if(isNaN(val)) return;
				}
				else if(/fontname/.test(cmd)) 
				{
					val = cmd.split('-')[1];
					cmd = 'fontname';
				} 
				else if(/justify/.test(cmd)) 
				{
					cmd = cmd.replace('-', '');
					if(cmd == 'justify') return;
				}
				else if(/forecolor/.test(cmd)) 
				{
					val = cmd.split('-')[1];
					cmd = 'forecolor'
				} 

				
				// TODO: 添加、编辑、删除链接
				else if(cmd == 'createlink') 
				{
					
					if($.browser.msie) 
					{
						if(el.hasClass('on')) 
						{
							$this.exec('unlink',null);
							el.removeClass('on');
						} else {
							val = prompt('输入网址：','http://');
							this.exec(cmd,val);
						}
						return;
					} 
					else 
					{
						if(!el.data('created')) 
						{
							
							var LINK = Object.create(linkObject);

							LINK.settings = {
								namescape: this.getCacheElement('textarea').attr('name'),
								// TODO: 如果是链接则修改或删除该链接，否则就添加新链接
								exec: function(url,action) 
								{	
									if(action == 'remove') 
									{
										filterTagOfRange($this.getWin(), $this.getDoc(), 'a', function(A) {
											A.outerHTML = A.innerHTML;
										});
										/*$this.exec('unlink', null);*/
										return;
									}
									
									// A标签
									if(LINK.action == true) 
									{
										var theParentNode = rangeParent($this.getWin(), $this.getDoc());
										filterTagOfRange($this.getWin(),$this.getDoc(),'a',function(A){
											A.setAttribute('href', url);
											A.setAttribute('target', LINK.dialog.panel.find('.blankswitchChk').get(0).checked == true ? '_blank' : '_self');
										});
									} 
									else 
									{
										$this.exec(cmd, url);
										filterTagOfRange($this.getWin(),$this.getDoc(),'a',function(A){
											A.setAttribute('target', LINK.dialog.panel.find('.blankswitchChk').get(0).checked == true ? '_blank' : '_self');
										});
										
									}

									LINK.dialog.close();
								},
								iframeWin: $this.getWin(),
								iframeDoc: $this.getDoc()
							}

							LINK.init(LINK.settings.namescape+'-'+el[0].className);

							el.data('LINK', LINK);
							el.data('created', true);

						} else {
							el.data('LINK').dialog.open();
						}
					}

					return;
				} 


				// TODO: 插入远程或者用户上传的图片
				else if(cmd == 'insertimage') 
				{
					

					if(!el.data('created')) 
					{
						
						var textareaName = this.getCacheElement('textarea').attr('name');
						var getUploadImageUrlCallbackName = 'uploadCallback'+(+new Date());
						
						// 获取上传图片地址的回调函数
						$[getUploadImageUrlCallbackName] = function(result) {
							if(result.state == true) {
								$this.exec(cmd, result.file);
								UPLOAD.dialog.close();
							} else {

							}
						}

						var UPLOAD = Object.create(uploader);
							UPLOAD.settings = {
								namescape: textareaName,
								callbackGetUrl: 'parent'+'.$.'+getUploadImageUrlCallbackName,
								uploadUrl: this.settings.uploadUrl || '',
								uploaderInput: this.settings.uploaderInput
							}
						UPLOAD.init(UPLOAD.settings.namescape+'-'+el[0].className);

						el.data('UPLOAD', UPLOAD);
						el.data('created', true);

					} 
					else 
					{
						el.data('UPLOAD').dialog.open();
					}
					
					return;
				}
				// 源码编辑
				else if(cmd == 'html')
				{
					var text = $.trim($this.getDoc().body.innerHTML),textarea,
					ifm = $this.container[0].getElementsByTagName('iframe')[0],
					toolbar = this.getCacheElement('toolbar')[0];
					if (ifm.style.display == 'none') {
						textarea = ifm.nextSibling;
						$this.setCode(textarea.value);
						textarea.parentNode.removeChild(textarea);
						ifm.style.display = 'block';
						var icons = toolbar.getElementsByTagName('a'), i;
						for (i in icons) {
							if (typeof(icons[i])!='object' || !icons[i].tagName || icons[i].tagName != 'A') {
								continue;
							}
							if (icons[i].getAttribute('data-cmd') == 'html') {
								$(icons[i]).removeClass('on');
							} else {
								$(icons[i]).removeClass('gray');
							}
						}
					} else {
						var width = ifm.offsetWidth,
						height = ifm.offsetHeight,
						textarea = document.createElement('textarea');
						textarea.value = text;
						$this.container[0].insertBefore(textarea,ifm.nextSibling);
						textarea.style.width = width - 6 + 'px';
						textarea.style.height = height - 6 + 'px';
						ifm.style.display = 'none';
						var icons = toolbar.getElementsByTagName('a'), i;
						for (i in icons) {
							if (typeof(icons[i])!='object' || !icons[i].tagName || icons[i].tagName != 'A') {
								continue;
							}
							if (icons[i].getAttribute('data-cmd') == 'html') {
								$(icons[i]).addClass('on');
							} else {
								$(icons[i]).addClass('gray');
							}
						}
					}
					return;
				}
				// 一键排版
				else if (cmd == 'formatcontext') {
					var htmlContent = $.trim($this.getDoc().body.innerHTML),
					allowTags = ['p', 'a', 'img', 'br', 'b', 'strong', 'cmstop'],
					tagPatrn = /<\s*([\/]?)\s*([\w]+)[^>]*>/ig;
					htmlContent = htmlContent.replace(/<(\/)?div(\s|>)/g, '<$1p$2');
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
					htmlContent = htmlContent.replace(/<a\s[^>]*>(.*?)<\/a>/img,'$1');// remove link
					htmlContent = htmlContent.replace(/<p>(<img[^>]+>)<\/p>/img,'<p style="text-align:center;text-indent:0">$1</p>');
					$this.getDoc().body.innerHTML = htmlContent;
					$.each($($this.getDoc().body).find('img'), function(i, img) {
						img = $(img);
						$('<img />').attr({
							src: img.attr('src')
						}).insertAfter(img);
						img.remove();
					});
					return;
				}
				this.exec(cmd, val);
			},

			// TODO: 为选区执行命令
			exec: function(cmd, val) 
			{
				this.getWin().focus();
				this.getDoc().execCommand(cmd, false, val);
			},

			// TODO: 检查选区对应的标签
			checkState: function() 
			{
				var $this = this;

				var toolbar = this.getCacheElement('toolbar');
					toolbar.find('li>a').each(function () {
						$(this).removeClass('on');
					});

				/* TODO: 筛选出所选节点 */
				var theParentNode = rangeParent(this.getWin(), this.getDoc());

				while(theParentNode.nodeName.toLowerCase() != 'body') 
				{
					var TagName = theParentNode.nodeName.toLowerCase(),
						currCmd = theParentNode.getAttribute('data-cmd');

					if(TagName == 'strong' || TagName == 'b' || currCmd == 'bold') {
						$this.setState('bold', 'on');
					}

					if(TagName == 'i' || TagName == 'em' || currCmd == 'bold') {
						$this.setState('italic', 'on');
					}

					if(TagName == 'u' || currCmd == 'underline') {
						$this.setState('underline', 'on');
					}

					if(TagName == 'a') {
						$this.setState('createlink', 'on');
					}

					if(TagName == 'ul') {
						$this.setState('insertunorderedlist', 'on');
					}

					if(TagName == 'ol') {
						$this.setState('insertorderedlist', 'on');
					}


					theParentNode = theParentNode.parentNode;
				}

			},

			// TODO: 标示选区标签对应的状态
			setState: function(state, status) {
				this.getCacheElement('toolbar').find("a[data-cmd="+state+"]").addClass('on');
			},

			// TODO: 格式化iframe的HTML代码
			convert: function(theBody) 
			{

				var content = this.convertFont(theBody);
					content = this.convertUnderline(content);
					content = this.converItalic(content);
					content = this.convertBold(content);

				return content;
			},

			convertFont : function(theBody) 
			{
				if (!theBody) return '';
				var Cmds = this.Cmds;
				
				var fontSizes = {
					'1': '0.63em',
					'2': '0.82em',
					'3': '1.0em',
					'4': '1.13em',
					'5': '1.5em',
					'6': '2em',
					'7': '3em'
				};

				$(theBody).find('font').each(function(i, font) 
				{
					var $font   = $(font),
						html     = $font.html(),
						newSpan = $('<span>');

					newSpan
						.css({
							fontSize: 	fontSizes[$font.attr('size')],
							fontFamily: Cmds['fontname'][$font.attr('face')],
							color: $font.attr('color')
						});

					$font.before(newSpan);
					newSpan.html(html);
					$font.remove();

				});

				return theBody.innerHTML;
			},

			convertUnderline: function(content) 
			{
				if (typeof content != 'string') content = '';
				content = content.replace(/<u(\s+|>)/g, "<span data-cmd='underline' style='text-decoration:underline;'$1");
				content = content.replace(/<\/u(\s+|>)/g, "</span$1");
				return content;
			},

			converItalic: function(content) 
			{
				if (typeof content != 'string') content = '';
				content = content.replace(/<i(\s+|>)/g, "<span data-cmd='italic' style='font-style:italic;'$1");
				content = content.replace(/<\/i(\s+|>)/g, "</span$1");
				return content;
			},

			convertBold: function(content) 
			{
				if (typeof content != 'string') content = '';
				content = content.replace(/<b(\s+|>)/g, "<span data-cmd='bold' style='font-weight:bold;'$1");
				content = content.replace(/<\/b(\s+|>)/g, "</span$1");
				return content;
			},


			// TODO: 提交编辑器代码时textarea保存iframe里的HTML
			formSubmit: function() 
			{
				var $this = this,
					form = this.textarea.parents('form');

				form.submit(function()
				{
					$this.textarea.val($this.getCode());
				});
			},

			// TODO: 获取iframe里的HTML
			getCode: function() {
				var $this = this;
				var ifm = $this.container[0].getElementsByTagName('iframe')[0];
				if (ifm.style.display == 'none') {
					var textarea = ifm.nextSibling;
					$this.setCode($.trim(textarea.value));
				}
				return this.convert(this.getDoc().body);
			},

			setCode: function(html) {
				this.getDoc().body.innerHTML = html;
			},

			// 粘贴并格式化内容
			paste: function() {
				var win = this.getWin(), 
					doc = this.getDoc();

				var n = doc.createElement('div');
				var nText = doc.createTextNode('_');
				n.appendChild(nText);

				var theSel = new $.selection({win: win});
				theSel.deleteContents().insertNode(n);
				theSel.select(nText);

				setTimeout(function() {
					// 只保留p,img标签和img的src，其余标签都干掉
					var filterContent = $.filteredPaste.filters._default($(n).html(), {});
					$(n).html(filterContent);
				}, 10);
			}
	}	


	// TODO: 子菜单
	var drop = Object.create({

			namescape: 'drop',

			bind: function(el) 
			{
				var $this = this,
					childContainer = el.next();

				childContainer.data('open', false);

				el.click(function() {
					var _childContainer = $(this).next();
					if(_childContainer.data('open') == true) {
						$this.close(_childContainer);
					} else {
						$this.open(_childContainer);
					}
					return false;
				});

				childContainer.find('>div').click(function() {
					$this.close($(this).parent());
				});
			},

			open: function(el) 
			{
				// 点击其他区域隐藏
				function fireHide( e ) {
					docClickHide.bindEventClose(e, el.prev(), el, function( elem ) {
						drop.close(elem);
					}, arguments.callee);
				}
				doc.bind('mousedown', fireHide);

				el.prev().addClass('hover');
				el.removeClass('hide');
				el.data('open', true);
			},

			close: function(el) 
			{
				el.prev().removeClass('hover');
				el.addClass('hide');
				el.data('open', false);
			}
	});

	// TODO: 弹出层
	var dialog = {

			namescape: 'dialog',

			init: function(attrs, elContent) {
				this.createShadow();
				this.createPanel(attrs||{}, elContent);
				this.open();
			},
			createShadow: function() {
				var shadow = this.shadow = $('<div>');
					shadow.css({
						width: '100%',
						backgroundColor: '#000',
						position: 'absolute',
                        zIndex: 999,
						left: 0,
						top: 0,
						opacity: 0
					});

					$(document.body).append(shadow);

			},
			createPanel: function(attrs, elContent) {
				var panel = this.panel = $('<div class="'+attrs.cls+'">');

				this.injectCont(elContent);


				panel
					.css({
						position: 'absolute',
						width: (attrs.width||300) + 'px'
					})
					.hide();

				$(document.body).append(panel);
			},
			open: function() 
			{
				var panel = this.panel, shadow = this.shadow;

				shadow
					.css('height',$(document).height())
					.animate({
						opacity: 0.7
					})
					.show();

				panel
					.css({
						left: ($(window).width() - panel.width())/2 + $(document).scrollLeft() + 'px',
						top: ($(window).height() - panel.height())/2 + $(document).scrollTop() + 'px'
					})
					.show();

				if(this.callbacks.openAfter) this.callbacks.openAfter(this);
			},
			close: function() {
				this.shadow
					.hide()
					.animate({
						opacity: 0
					});
				
				this.panel
					.hide();
			},
			injectCont: function(el) {
				this.panel.append(el);
			}
	};


	// TODO: 链接模块
	var linkObject = {

			namescape: 'link',

			init: function(cls) 
			{
				var $this = this;

				var iframeWin = this.settings.iframeWin, iframeDoc = this.settings.iframeDoc;


				var wrapper = this.html(),


					oDialog = this.dialog = Object.create(dialog);


					oDialog.callbacks = {
						openAfter: function (dialog) 
						{
							iframeWin.focus();

							var weburl = dialog.panel.find('.weburl-url');
								weburl.val('http://');
								

							dialog.panel.find('.blankswitchChk')[0].checked = false;

							$this.action = false;

							// TODO:检查是否是A标签
							var preParentNode = rangeParent(iframeWin, iframeDoc);
							while(preParentNode.nodeName.toLowerCase() != 'body') 
							{
								if(preParentNode.nodeName.toLowerCase() == 'a') 
								{
									$this.action = true;
									var weburlInput = dialog.panel.find('.weburl-url');
									weburlInput.val(preParentNode.getAttribute('href'));
									dialog.panel.find('.blankswitchChk')[0].checked = preParentNode.getAttribute('target') == '_blank' ? true : false;
									dialog.panel.find('.remove-weburl').removeClass('hide');
									weburlInput.next().val('修改');
									break;
								}
								preParentNode = preParentNode.parentNode;
							}

							if($this.action == false) {
								var removeInput = dialog.panel.find('.remove-weburl');
								removeInput.prev().val('增加');
								removeInput.addClass('hide');
							}

							weburl.focus().select();

						}
					};



					oDialog.init({
						cls: 'rte-'+this.namescape+'-panel '+cls + '-wrapper',
						width: 500
					}, wrapper);



			},

			html: function() 
			{
				var $this = this;


				var inner = $('<div class="inner">')

					title = $('<div class="rte_panel_link_title">插入链接</div>'),

					HTML = '\
							<div class="'+this.namescape+'-container">\
								<input type="text" class="weburl-url" value="http://">\
								<input type="button" class="weburl-button" value="添加">\
								<input type="button" class="weburl-button remove-weburl hide" value="删除">\
							</div>\
							<div class="blankswitch"><label><input class="blankswitchChk" type="checkbox" name="blankswitchChk" />新窗口打开</label></div>\
						'
					;

				var container = $(HTML),
					closeBtn = $('<div class="btn-close"><a href="">取消</a></div>');


				inner.append(title);
				inner.append(container);
				inner.append(closeBtn);


				closeBtn.find('>a').click(function() {
					$this.dialog.close();
					return false;
				});

				container.find('.weburl-button').click(function() {
					if($this.settings.exec) $this.settings.exec(container.find('.weburl-url').val());
				});

				container.find('.weburl-url').keyup(function(e) {
					if(e.keyCode == 13) {
						if($this.settings.exec) $this.settings.exec($(this).val());
					}
				});

				container.find('.remove-weburl').click(function() {
					if($this.settings.exec) $this.settings.exec(null,'remove');
				});


				return inner;
			}
	};


	// TODO: 上传模块
	var uploader = {

			// iframe内的回调函数名,用于输出上传图片地址
			callbackGetUrl: '',

			namescape: 'uploader',

			init: function(cls) {
				var oDialog = this.dialog = Object.create(dialog),

				wrapper = this.html();

				oDialog.callbacks = {
					openAfter: function (dialog) {
						var panel = dialog.panel;
						panel.find('.filePrew').val('');
						panel.find('.uploadimagetxt').val('');
					}
				};

				oDialog.init({
					cls: 'rte-'+this.namescape+'-panel '+cls + '-wrapper',
					width: 500
				}, wrapper);
				
			},

			html: function() {

				var $this = this,

				inner = $('<div class="inner">'),

				title = $('<div class="rte_panel_image_title">插入图像</div>');

				var HTML = '\
					<div class="upload-container">\
						<div class="upload-tab"><em class="uploadimage-em active">上传图片</em> 或者 <em class="webimage-em">引用外站图片</em></div>\
						<div class="uploadimage">\
							<form enctype="multipart/form-data" method="POST" target="'+this.settings.namescape+'_uploadimageContainer" action="'+this.settings.uploadUrl+'" name="'+this.settings.namescape+'_uploadImageForm" id=="'+this.settings.namescape+'_uploadImageForm">\
								<input type="text" class="uploadimagetxt" disabled />\
						        <a class="browser" href="javascript:void(0);">\
									<span>浏览图片</span>\
									<input type="file" name="'+this.settings.uploaderInput+'" class="filePrew" size="10" title="支持jpg、jpeg、gif、png格式" />\
								</a>\
								<input class="upload-submit" type="submit" value="开始上传" />\
								<input type="hidden" value="'+this.settings.callbackGetUrl+'" name="callback" />\
							</form>\
							<iframe id="'+this.settings.namescape+'_uploadimageContainer" name="'+this.settings.namescape+'_uploadimageContainer" width="100%" style="display:none;"></iframe>\
						</div>\
						<div class="webimage" style="display:none;">\
							<input type="text" class="webimage-url" value="http://" />\
							<input type="button" class="webimage-button" value="确定" />\
						</div>\
					</div>\
				'
				;

				var container = $(HTML);
				
				var closeBtn = $('<div class="btn-close"><a href="">取消</a></div>');

				inner.append(title);
				inner.append(container);
				inner.append(closeBtn);

				var oFile = container.find('form').eq(0).get(0).elements[this.settings.uploaderInput];
				
				// 选择的图片地址赋值到文本框
				$(oFile).change(function(){
					$(this).parent().prev().val($(this).val());
				});

				closeBtn.find('>a').click(function() {
					$this.dialog.close();
					return false;
				});

				// Tab
				container.find('.upload-tab>em').click(function(){
					var oUploadimage = $(this).parent().next();
					if($(this).hasClass('uploadimage-em')) {
						$(this).addClass('active').next().removeClass('active');
						oUploadimage.show();
						oUploadimage.next().hide();
					} else {
						$(this).addClass('active').prev().removeClass('active');
						oUploadimage.hide();
						oUploadimage.next().show().find('.webimage-url').select();
					}
				});

				container.find('.webimage-button').click(function() {
					$[$this.settings.callbackGetUrl.replace('parent.$.','')]({state:true,file:$(this).prev().val()});
				});

				container.find('.webimage-url').keyup(function(e) {
					if(e.keyCode == 13) {
						$[$this.settings.callbackGetUrl.replace('parent.$.','')]({state:true,file:$(this).val()});
					}
				});

				return inner;
			}	
	}


	// TODO: CLASS Rte
	function Rte(textarea, options) 
	{
		this.settings = $.extend({}, Defaults, options||{});

		this.Cmds = {};

		for( var r in Commands) {
			if(inArray(r, this.settings.disable)) continue;
			this.Cmds[r] = Commands[r];
		}

		this.cacheElement('textarea', textarea);

		var container = this.createContainer();
		this.cacheElement('container', container);


		var toolbar = this.createToolbar();
		this.cacheElement('toolbar', toolbar);
			
		var iframe = this.createIframe();
		this.cacheElement('iframe', iframe);

		var RTE_HEIGHT = container.innerHeight() - toolbar.outerHeight();

		iframe.css('height', RTE_HEIGHT);

		this.initialization();

		$(this.getDoc().body).css('height',RTE_HEIGHT - 16);

	}

	Rte.prototype = {

		constructor: Rte,

		initialization: function() {
			this.enableDesignMode();
			this.formSubmit();

			var $this = this,
				iframeDoc = this.getDoc();

			$(iframeDoc)
				.mouseup(function(){
					$this.getCacheElement('toolbar').find("[class*=container]").each(function(i, elem){
						drop.close($(elem));
					})
					$this.checkState();
				})
				.keyup(function(){
					$this.checkState();
				});

			$(iframeDoc).bind('paste', function() {
				$this.paste();
			});
		}
	}

	$.extend(Rte.prototype, core);

	// TODO: namescape
	$.Rte = Rte;

	// TODO: Register jQuery Plugin
	$.fn.rte = function(options) {
		return this.each(function() {
			var $this = $(this),
				_rte = new Rte($this, options||{});
			$this.data('rte', _rte);
		});
	}

	// 粘贴内容后的过滤处理
	$.filteredPaste = {
		filters : {
			"_default" : function(pastedContent, options) {
				var defaultOptions = {
					"tags" : {
						"*" : { "attributes" : ["alt"]},
						// "a" : { "attributes" : ["href"] },
						"img" : { "attributes" : ["src"] }
						// "link" : { "attributes" : ["href"] }
					}
				}

				options = $.extend(defaultOptions, options);

				// Create DOM node and insert pastedContent
				var domElement = $("<div>").html(pastedContent);

				// remove all attributes of the element, except the attributes that are on the whitelist
				domElement.find("*").each(function(elm,index) {
					var attributes = $.map(this.attributes, function(item) {
						return item.name;
					});
					var $elm = $(this);
					var attributesToKeep = [];
					$.each(options.tags, function(tagName, tag) {
						if (tagName === "*" || $elm[0].nodeName.toLowerCase() === tagName) {
							$.extend(attributesToKeep, tag.attributes);
						}
					});
					$.each(attributes, function(i, item) {
						if($.inArray(item,attributesToKeep) == -1 ) {
							$elm[0].removeAttribute(item);
						}
					});
				});
				return this.clearTag(domElement.html());
			},
			"clearTag" : function(_string) 
			{
				var tagReg = /<\s*([\/]?)\s*([\w]+)[^>]*>/ig;
				var filterStrings = _string.replace(tagReg, function(_match, pos, originText) {
						switch(originText) {
							case 'span':
							case 'p':
							case 'b':
							case 'i':
							case 'strong':
							case 'u':
								if (pos) return _match;
								return '<'+originText+'>';
							case 'img':
								var src = _match.match(/src=['"]?(.*?)['"]?(?:\s|\/?>)/)[1];
								return '</p><p><img src="'+src+'" alt="" /></p><p>';
							default:
								return '';
						}
				});
				return filterStrings;
			}
		}
	};

	// 一个重要的选区函数
	$.selection = function(options) 
	{
		var start, end, node, bm;

		var settings = $.extend({}, options||{});

		var win = settings.win || window, doc = win.document;


		function selection() {
			return win.getSelection ? win.getSelection() : doc.selection;
		}


		this.getRangeAt = function() 
		{
			if($.browser.msie) {

			}

			var s = selection();
			var r = s.rangeCount > 0 ? s.getRangeAt(0) : doc.createRange();
			
			r.getStart = function() {
				return this.startContainer.nodeType==1 
					? this.startContainer.childNodes[Math.min(this.startOffset, this.startContainer.childNodes.length-1)] 
					: this.startContainer;
			}
			
			r.getEnd = function() {
				return this.endContainer.nodeType==1 
					? this.endContainer.childNodes[ Math.min(this.startOffset == this.endOffset ? this.endOffset : this.endOffset-1, this.endContainer.childNodes.length-1)] 
					: this.endContainer;
			}
			r.isCollapsed = function() {
				return this.collapsed;
			}

			return r;
		}

		this.insertNode = function(n, collapse) {
		 	if (collapse && !this.collapsed()) {
				this.collapse();
			}

			if ($.browser.msie) {
				var html = n.nodeType == 3 ? n.nodeValue : $(this.rte.dom.create('span')).append($(n)).html();
				var r = this.getRangeAt();
				r.insertNode(html);
			} else {
				var r = this.getRangeAt();
				r.insertNode(n);
				r.setStartAfter(n);
				r.setEndAfter(n);
				var s = selection();
				s.removeAllRanges();
				s.addRange(r);
			}
			return this.cleanCache();
		}

		this.cleanCache = function() {
			start = end = node = null;
			return this;
		}

		this.deleteContents = function() {
			if (!$.browser.msie) {
				this.getRangeAt().deleteContents();
			}
			return this;
		}

		this.select = function(s, e) {
			e = e||s;
			if ($.browser.msie) 
			{
				var r  = this.rte.doc.body.createTextRange(),
					r1 = r.duplicate(),
					r2 = r.duplicate();
				
				r1.moveToElementText(s);
				r2.moveToElementText(e);
				r.setEndPoint('StartToStart', r1);
				r.setEndPoint('EndToEnd',     r2);
				r.select();
			} else {
				
				var sel = selection(),
					r = this.getRangeAt();
				r.setStartBefore(s);
				r.setEndAfter(e);
				sel.removeAllRanges();
				sel.addRange(r);
			}
			return this.cleanCache();
		}

	}
})(jQuery);




