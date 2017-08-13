var Popup = (function(){

var docElem = document.documentElement, $doc = $(document),

	contains = docElem.compareDocumentPosition ? function(a, b) {
		return a === b || !!(a.compareDocumentPosition(b) & 16);
	} : function(a, b) {
		return (a.nodeType === 9 ? a.documentElement : a).contains(b.nodeType === 9 ? b.documentElement : b);
	},

	X_MARGIN = 3, Y_MARGIN = 12,
	CSS_BORDER_WIDTH = 3, CSS_POINT_WIDTH = 12, CSS_POINT_HEIGHT = 24;

return function(where, width, height) {
	var t = this;
	var _popup = $('<div class="diy-popup"></div>'),
	_border = $('<div class="diy-popup-border"></div>').appendTo(_popup),
	_pointer = $('<div class="diy-popup-point diy-popup-point-west"></div>').appendTo(_border),
	_box = $('<div class="diy-popup-box"></div>').appendTo(_popup),
	_visible = 0, _blurlock = 0, _width, _height;

	setSize(width, height);
	_popup.css({visibility:'hidden'}).appendTo(document.body);
	function checkblur(e){
		var t = e.target || this;
		_blurlock || contains(_popup[0], t) || contains(where[0], t) || hide();
	}
	function hide(){
		$doc.unbind('mousedown', checkblur);
		_visible = 0;
		where.triggerHandler('popuphide');
		_popup.css('display', 'none');
	}
	function setSize(w,h){
		w && (_width = w);
		h && (_height = h);
		_popup.css({
			width:_width,
			height:_height
		});
		_border.css({
			width:_width - CSS_BORDER_WIDTH*2,
			height:_height - CSS_BORDER_WIDTH*2
		});
	}
	function setpos(){
		var offset = where.offset(),
			offsetWidth = where[0].offsetWidth,
			offsetHeight = where[0].offsetHeight,
			offsetLeft = offset.left - ('pageXOffset' in window ? window.pageXOffset : docElem.scrollLeft),
			offsetTop  = offset.top - ('pageYOffset' in window ? window.pageYOffset : docElem.scrollTop) + offsetHeight/2,
			clientWidth = window.innerWidth || docElem.clientWidth,
			clientHeight = window.innerHeight || docElem.clientHeight,
			left, top = (clientHeight - _height) * .382,
			halfPointHeight = CSS_POINT_HEIGHT / 2,
			virtualMargin = halfPointHeight + Y_MARGIN,
			centerTop = offsetTop - top,
			pointTop = centerTop - halfPointHeight - CSS_BORDER_WIDTH;


		if (offsetLeft > (clientWidth - offsetWidth) / 2) {
			// east point
			_pointer.removeClass('diy-popup-point-west').addClass('diy-popup-point-east');
			left = offsetLeft - X_MARGIN - _width - CSS_POINT_WIDTH;
		} else {
			// west point
			_pointer.removeClass('diy-popup-point-east').addClass('diy-popup-point-west');
			left = offsetLeft + offsetWidth + X_MARGIN + CSS_POINT_WIDTH;
		}

		if (centerTop > _height - virtualMargin) {
			top = offsetTop - _height + virtualMargin;
			pointTop =  _height - CSS_POINT_HEIGHT - Y_MARGIN - CSS_BORDER_WIDTH;
		} else if (centerTop < virtualMargin) {
			top = offsetTop - virtualMargin;
			pointTop = Y_MARGIN - CSS_BORDER_WIDTH;
		}

		_pointer.css('top', pointTop);
		_popup.css({left:left,top:top,visibility:''});
	}
	function show(){
		if (_visible) return;
		_popup.css({visibility:'hidden', display:''});
		setpos();
		_visible = 1;
		where.triggerHandler('popupshow');
		setTimeout(function(){
			$doc.bind('mousedown', checkblur);
		}, 0);
	}
	t.show = show;
	t.hide = hide;
	t.setSize = setSize;
	t.zoom = function(flag){
		if (_visible) return;
		if (flag) {
			_popup.css({visibility:'hidden',display:'block'});
		} else {
			_popup.css({visibility:'',display:'none'});
		}
	};
	t.lock = function(flag){_blurlock = flag};
	t.visible = function(){return _visible};
	t.getBox = function(){return _box};
	t.getPopup = function(){return _popup};
};

})();