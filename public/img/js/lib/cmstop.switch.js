window.ctswitch = function(elm, opt) {
	var elmArray = new Array();
	opt = opt || {};
	opt.background = opt.background || IMG_URL + 'js/lib/switch/background.png';
	opt.backgroundg = opt.backgroundg || IMG_URL + 'js/lib/switch/background-g.png';
	opt.button = opt.button || IMG_URL + 'js/lib/switch/button.png';
	opt.text = opt.text || IMG_URL + 'js/lib/switch/text.png';
	var isCheckbox = function(elm) {
		try {
			if (elm.nodeName.toLowerCase() != 'input') return false;
			if (elm.getAttribute('type').toLowerCase() != 'checkbox') return false;
		} catch (exc) {
			return false;
		}
		return true;
	}
	var selector = function(string) {
		var elmArray = new Array(), match = /((?:[a-zA-Z][a-zA-Z0-9]?)*)?(?:#([a-zA-Z0-9_-]*))?(?:\.([a-zA-Z0-9_-]*))?/.exec(string);
		if (match[2]) {
			return [document.getElementById(match[2])];
		}
		if (match[3]) {
			if (typeof(document.getElementsByClassName) == 'undefined') {	// IE
				document.getElementsByClassName = function(searchClass) {
					var classElements = new Array();
					node = document;
					tag = '*';
					var els = node.getElementsByTagName(tag);
					var elsLen = els.length;
					var pattern = new RegExp("(^|\\s)"+searchClass+"(\\s|$)");
					for (i = 0, j = 0; i < elsLen; i++) {
						if ( pattern.test(els[i].className) ) {
							classElements[j] = els[i];
							j++;
						}
					}
					return classElements;
				}
			}
			elmArray = document.getElementsByClassName(match[3]);
			if (elmArray.length == 0) return [];
			if (match[1]) {
				for (var i=0,l=elmArray.length; i<l; i++) {
					if (elmArray[0].nodeName.toLowerCase() != match[1].toLowerCase()) elmArray.splice(i, 1);
				}
			}
			return elmArray;
		}
		if (match[1]) {
			return document.getElementsByTagName(match[1]);
		}
		return [];
	}

	var divMaker = function(attribute, style, parentObj) {
		attribute = attribute || {};
		style = style || {};
		var obj = document.createElement('div');
		for (var key in attribute) {
			if (key == 'class') {
				obj.setAttribute('class', attribute[key]);
				obj.setAttribute('className', attribute[key]);	// IE
			} else {
				obj.setAttribute(key, attribute[key]);
			}
		}
		for (var key in style) {
			obj.style[key] = style[key];
		}
		if (!parentObj) {
			parentObj = document.body;
		}
		parentObj.appendChild(obj);
		return obj;
	}

	var bind = function bind(obj, action, func) {
		if (window.addEventListener) {
			obj.addEventListener( action, func, false);
		} else if (window.attachEvent) { //IE
			obj.attachEvent('on' +action,func);
		}
	}
	var unbind = function(obj, action, func) {
		if (window.removeEventListener) {
			obj.removeEventListener(action, func , false);
		} else if (window.detachEvent) { //IE
			obj.detachEvent('on' +action, func);
		}
	}

	var checkbox2Switch = function(elm) {
		var container,background,button,text,value = elm.checked, isDrag = false;
		var switchChange = function() {
			button.style.left = value ? '38px':'1px';
			text.style.left = value ? '10px' : '40px';
			text.style.backgroundPosition = value ? '28px 0' : '0 0';
			background.style.backgroundImage = 'url("' + (value ? opt.background : opt.backgroundg) + '")';
		}
		var clickSwitch = function() {
			value = !value;
			if (elm.checked != value) {
				elm.click();
			}
			switchChange();
			isDrag = false;
		}
		var drag = function(obj) {
			var orginX,orginLeft,left;
			var dragMousedown = function(event) {
				if (isDrag) {
					return;
				}
				orginLeft = parseInt(obj.style.left);
				orginX = event.clientX;		
				bind(document.body, 'mousemove', dragMousemove);
				bind(document.body, 'mouseup', dragMouseup);
			}
			var dragMousemove = function(event) {
				isDrag = true;
				text.style.display = 'none';
				left = event.clientX - orginX + parseInt(obj.style.left);
				(left < 1) && (left = 1);
				(left > 38) && (left = 38);
				obj.style.left = left + 'px';
			}
			var dragMouseup = function(event) {
				unbind(document.body, 'mousemove', dragMousemove);
				unbind(document.body, 'mouseup', dragMousedown);
				unbind(document.body, 'mouseup', dragMouseup);
				if (!isDrag) {
					clickSwitch();
					return
				}
				isDrag = false;
				value = (left > 18);
				if (elm.checked != value) {
					elm.click();
				}
				switchChange();
				text.style.display = 'block';
				return false;
			}
			bind(obj, 'mousedown', dragMousedown);
		}
		container = document.createElement('div');
		container.setAttribute('class', 'ct-switch');
		container.setAttribute('className', 'ct-switch');
		container.style.position = 'relative';
		container.style.cursor = 'pointer';
		background = divMaker({}, {background:'url("'+(value?opt.background:opt.backgroundg)+'")', width:'80px', height:'26px',left:0,top:0,position:'absolute'}, container);
		button = divMaker({}, {background:'url("'+opt.button+'")', width:'40px', height:'26px', left:value?'38px':'1px', top:'1px', position:'absolute'}, container);
		text = divMaker({}, {backgroundImage:'url("'+opt.text+'")',backgroundPosition:value?'28px 0':'0 0', width:'28px', height:'17px',left:value?'10px':'40px', top:'4px', position:'absolute'}, container);
		background.ondragstart = button.ondragstart = text.ondragstart = function() {return false;}
		elm.style.display = "none";
		elm.parentNode.insertBefore(container, elm);
		bind(text, 'click', function(event) {
			clickSwitch();
		});
		drag(button);
	}
	if (typeof (elm) == 'object') {
		if (!isCheckbox(elm)) return false;
		elmArray = [elm];
	} else if (typeof (elm) == 'string') {
		elmArray = selector(elm);
	}
	for (var i=0,l=elmArray.length; i<l; i++) {
		checkbox2Switch(elmArray[i]);
	}
}