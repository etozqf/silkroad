/**
 * Flash Image Editor
 *
 * @copyright (c) CmsTop {@link http://www.cmstop.com}
 * @author    kakalong {@link http://yanbingbing.com}
 * @version   $Id: cmstop.imageEditor.js 7467 2012-10-09 08:00:41Z kakalong $
 */
(function(window, undefined){
var document = window.document,
R_SPLIT = /[\s|,]+/,
INSTS = {},
SWF_URL = 'imageEditor/ImageEditor.swf',
overlay = null,
flashVersion = (function(){
	try {
		return (window.ActiveXObject
			? (new window.ActiveXObject('ShockwaveFlash.ShockwaveFlash')).GetVariable('$version')
			: navigator.plugins['Shockwave Flash'].description).match(/\d+/g).join('.') || null;
	} catch (e) {
		return null;
	}
})(), toElement = function() {
	var div = document.createElement('div');
	return function(html) {
		div.innerHTML = html;
		html = div.firstChild;
		div.removeChild(html);
		return html;
	};
}();
function append(elem, value) {
	elem.appendChild(value);
	return elem;
}
function each(o, fn) {
	o = o.split(R_SPLIT);
	for (var i = 0, l = o.length,t = o[0];i < l && fn.call(t, t, i) !== false; t = o[++i]){}
}
function version_test(a, b) {
	if (!a) return false;
	a = a.split('.');
	b = b.split('.');
	a[0] = parseInt(a[0]);
	b[0] = parseInt(b[0]);
	for (var i=0, l=b.length; i < l; i++) {
		if (a[i]==undefined || a[i] < b[i]) {
			return false;
		} else if (a[i] > b[i]) {
			return true;
		}
	}
	return true;
}
function _genValue(obj, enc) {
	if (obj instanceof Object) {
		var arr = [];
		for (var k in obj) {
			obj[k] == null || arr.push(k+'='+ _genValue(obj[k], 1));
		}
		obj = arr.join('&');
	}
	return enc ? encodeURIComponent(obj) : obj;
}
function _genAttrs(obj) {
	var arr = [];
	for (var k in obj) {
		obj[k] == null || arr.push([k,'="',_genValue(obj[k]),'"'].join(''));
	}
	return arr.join(' ');
}
function _genParams(obj) {
	var arr = [];
	for (var k in obj) {
		obj[k] == null || arr.push(['<param name="', k, '" value="', _genValue(obj[k]), '" />'].join(''));
	}
	return arr.join('');
}
function createFlash(opts) {
	if (!opts.movie) {
		return false;
	}

	if (!version_test(flashVersion, '11.1')) {
		alert ('Upgrade your Flash Player...');
		return false;
	}

	var div = document.createElement('div'), attrs;
	opts.type = 'application/x-shockwave-flash';
	if (window.ActiveXObject) {
	    attrs = {
			classid:'clsid:D27CDB6E-AE6D-11cf-96B8-444553540000'
		};
		each('width height id', function(k) {
			opts[k] && (attrs[k] = opts[k]);
			delete opts[k];
		});
		div.innerHTML = [
			'<object ', _genAttrs(attrs), '>',
				_genParams(opts),
			'</object>'
		].join('');
	} else {
		opts.src = opts.movie;
		delete opts.movie;
		div.innerHTML = '<embed ' + _genAttrs(opts) + ' />';
	}
	return div.firstChild;
}
function callFlash(movie, functionName, argumentArray) {
	try {
		movie.CallFunction('<invoke name="' + functionName + '" returntype="javascript">' + __flash__argumentsToXML(argumentArray||[], 0) + '</invoke>');
	} catch (ex) {
		throw "Call to " + functionName + " failed";
	}
}
function showOverlay() {
	if (overlay) {
	    overlay.style.display = "block";
		return;
	}
	overlay = toElement('<div style="position:fixed;top:0;left:0;z-index:999998;height:100%;width:100%;background-color:black;opacity:0.5;filter:Alpha(Opacity=50);"></div>');
	append(document.body, overlay);
	var halt = function(event){
		if (!overlay.offsetWidth) {
			return;
		}
		event = event || window.event;
		event.preventDefault && event.preventDefault();
		event.returnValue = false;
		event.stopPropagation && event.stopPropagation();
		event.cancelBubble = true;
	}, esc = function(event){
	    if ((event || window.event).keyCode == 27) {
			for (var k in INSTS) {
				INSTS[k].close();
			}
		}
	};
	if (document.addEventListener) {
		document.addEventListener('onmousewheel' in document ? 'mousewheel' : 'DOMMouseScroll', halt, false);
		document.addEventListener('keypress', esc, false);
	} else {
		document.attachEvent('onmousewheel', halt);
		document.attachEvent('onkeypress', esc);
	}
}
function removeEditor(t) {
	var movie = t.movie, elem = movie.parentNode;
	if (window.ActiveXObject && movie.readyState == 4) {
		movie.style.display = "none";
		for (var k in movie) {
			if (typeof movie[k] == "function") {
				movie[k] = null;
			}
		}
	}
	elem.removeChild(movie);
	document.body.removeChild(elem);
	movie = null;
	elem = null;
	var events = t.events;
	for (var k in events) {
		for (var i=0,l=events[k].length; i<l; i++) {
			events[k][i] = null;
		}
	}
	t.events = null;
	t.movie = null;
	t = null;
}
window.ActiveXObject && window.attachEvent("onunload", function(){
	for (guid in INSTS) {
		removeEditor(INSTS[guid]);
		INSTS[guid] = null;
	}
	overlay = null;
});
function hideOverlay() {
	if (overlay) {
		overlay.style.display = "none";
	}
	// fix : return focus to html
	var input = toElement('<input style="width:1px;height:1px;position:fixed;left:-10px;top:10px;" />');
	append(document.body, input);
	input.focus();
	document.body.removeChild(input);
	// not use any more
	input = null;
}
var ImageEditor = function(file, config, width, height) {
	width || (width = 750);
	height || (height = 500);
	var t = this,
	guid = 'IMGEDITOR' + (new Date()).getTime().toString(16),
	flashvars = {
		guid:guid,
		file:file,
		config:config
	},
	elem = toElement('<div style="position:fixed;width:'+width+'px;height:'+height+'px;top:50%;left:50%;margin-left:-'+(width/2)+'px;margin-top:-'+(height/2)+'px;background-color:#000;z-index:999999;filter:progid:DXImageTransform.Microsoft.Shadow(color=#000, Direction=135, Strength=4);box-shadow:3px 3px 5px #000;"></div>'),
	movie = createFlash({
		movie:SWF_URL,
		id:guid,
		width:width,
		height:height,
		flashvars:flashvars,
		quality:'high',
		wmode:'window',
		devicefont:'true',
		allowFullScreen:'true',
		allowScriptAccess:'always'
	});
	if (!movie) {
	    return;
	}
    t.movie = movie;
	t.guid = guid;
	t.events = {};
	t.bind('close', t.close);

	showOverlay();
	append(document.body, elem);
	append(elem, movie);
	elem = null;

	INSTS[guid] = t;
};
ImageEditor.prototype = {
	bind:function(event, func) {
		var t = this;
		(event in t.events) || (t.events[event] = []);
		t.events[event].push(func);
	},
	close:function() {
		var t = this;
		hideOverlay();
		delete INSTS[t.guid];
		// use setTimeout fix #error:SetReturnValue 
		setTimeout(function(){
			removeEditor(t);
		}, 0);
	}
};
ImageEditor.open = function(fileName, config, width, height) {
	return new ImageEditor(fileName, config, width, height);
};
ImageEditor.trigger = function(guid, evt, args) {
	var t = INSTS[guid];
	if (t && (evt in t.events)) {
		for (var i=0, func; (func = t.events[evt][i++]) && func.apply(t, args||[]) !== false;) {
		}
	}
};
ImageEditor.testExternalInterface = function(guid) {
	try {
		(guid in INSTS) && callFlash(INSTS[guid].movie, 'testExternalInterface');
	} catch (e) {}
};

window.ImageEditor = ImageEditor;

})(window);