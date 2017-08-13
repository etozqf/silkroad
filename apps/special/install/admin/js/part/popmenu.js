var Popmenu = (function(){

var docElem = document.documentElement, $doc = $(document),

	contains = docElem.compareDocumentPosition ? function(a, b) {
		return a === b || !!(a.compareDocumentPosition(b) & 16);
	} : function(a, b) {
		return (a.nodeType === 9 ? a.documentElement : a).contains(b);
	},

	X_MARGIN = -1, Y_MARGIN = -8,
	CSS_BORDER_SIZE = 6;

return function(elem, width, height, data){
	var _popmenu = $('<div class="diy-popmenu"></div>'),
	_border = $('<div class="diy-popmenu-border"></div>').appendTo(_popmenu),
	_box = $('<div class="diy-popmenu-box"></div>').appendTo(_popmenu),
	_whole = _popmenu.add(elem),
	_visible = 0, _inited = 0, _ihide = null, _ishow = null;

	_popmenu.css({
		width:width,
		height:height
	});
	_border.css({
		width:width - CSS_BORDER_SIZE,
		height:height - CSS_BORDER_SIZE
	});

	function checkblur(e){
		contains(_popmenu[0], e.target) || contains(elem[0], e.target) || hide();
	}
	function clearTimeHide(){
		_ihide && clearTimeout(_ihide);
		_ihide = null;
		_whole.unbind('mouseenter', clearTimeHide);
	}
	function clearTimeShow(){
		_ishow && clearTimeout(_ishow);
		_ishow = null;
		elem.unbind('mouseleave', clearTimeShow);
	}
	function timeHide(){
		_ihide = setTimeout(hide, 300);
		_whole.bind('mouseenter', clearTimeHide);
	}
	function timeShow(){
		_ishow = setTimeout(show, 600);
		elem.bind('mouseleave', clearTimeShow);
	}
	function hide(){
		clearTimeHide();
		if (!_visible) return;
		_whole.unbind('mouseleave', timeHide);
		_visible = 0;
		// hide
		_popmenu.css('display', 'none');
		elem.triggerHandler('popmenuhide');
	}
	function init(){
		_inited = 1;
		$.each(data, function(k, v){
			var item = $('<div class="diy-popmenu-item"><span>'+v.text+'</span></div>').click(function(){
				v.action();
				hide();
			}).appendTo(_box);
		});
		_popmenu.appendTo(document.body);
	}
	function show(){
		clearTimeShow();
		if (_visible) return;
		_visible = 1;

		elem.mousedown();

		// init
		_inited || init();

		// position
		var offset = elem.offset(),
		offsetLeft = offset.left - ('pageXOffset' in window ? window.pageXOffset : docElem.scrollTop),
		offsetTop  = offset.top - ('pageYOffset' in window ? window.pageYOffset : docElem.scrollLeft),
		offsetWidth = elem[0].offsetWidth;

		// show
		_popmenu.css({
			top:offsetTop + Y_MARGIN,
			left:offsetLeft + offsetWidth + X_MARGIN,
			display:'block'
		});

		elem.triggerHandler('popmenushow');

		_whole.bind('mouseleave', timeHide);

		setTimeout(function(){
			$doc.bind('mousedown', checkblur);
		}, 0);
	}

	elem.bind('mouseenter', timeShow).bind('click', function(e){
		e.preventDefault();
		e.stopPropagation();
		show();
	});

	this.hide = hide;
	this.show = show;
};

})();