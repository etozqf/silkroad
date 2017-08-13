var isRightClick = function(){
	var RIGHT_KEY_VALUE = /maxthon[\/: ]2/i.test(navigator.userAgent) ? 0 : 2;
	return function(e){
		return e.button == RIGHT_KEY_VALUE;
	};
}();

var dragMent = function(elem, options){
	var t = this;
	t.origLeft = null;
	t.origTop = null;
	t.curLeft = null;
	t.curTop = null;
	t.dX = null;
	t.dY = null;
	t.clickX = 0;
	t.clickY = 0;
	t.ival = null;
	t.placeHolder = null;
	t.placeArea = null;
	t.$elem = elem.jquery ? elem : $(elem);
	t.elem = t.$elem[0];
	t.selectArea = options.selectArea;
	t.selectPlace = options.selectPlace;
	t.getGhost = options.getGhost;
	t.getHolder = options.getHolder;
	t.fromGhost = options.fromGhost;
	t.moveStarted = false;
	t.$elem.mousedown(function(e){
		if (isRightClick(e)) return;
		e.stopPropagation();
		e.preventDefault();// disable select pic
		(options.propagation || $doc).mousedown();
		t.dragInit(e);
	});
};
dragMent.prototype = {
	dragInit:function(e){
		var t = this, $h = t.$elem;

		t.clickX = e.pageX;
		t.clickY = e.pageY;

		$doc.bind('mousemove.dragment', function(e){
			t.dragIng(e);
		});
		$doc.bind('mouseup.dragment', function(e){
			t.dragEnd(e);
		});
		$doc.bind('selectstart.dragment', function(){return false;});
		$.browser.mozilla && (document.body.style.MozUserSelect = 'none');
		$h.triggerHandler('dragInit', []);
	},
	dragIng:function(e){
		var t = this, $g, g, $h = t.$elem;
		if (! t.moveStarted) {
			t.moveStarted = true;
			var offset = $h.offset();

			t.placeHolder = t.getHolder($h);
			t.hasPlaced = t.placeHolder.is(':visible');
			t.ghost = t.getGhost($h, offset.top, offset.left, e);
			offset = t.ghost.offset();
			t.origLeft = offset.left;
			t.origTop = offset.top;
			$g = t.ghost;
			g = $g[0];
			
			if (g.setCapture) {
				$g.bind('losecapture.dragment', function(e){
					t.dragEnd(e);
				});
				g.setCapture();
			}
		} else {
			$g = t.ghost;
			g = $g[0];
		}

		var scrollTop = $doc.scrollTop(),
			clientHeight = window.innerHeight || document.documentElement.clientHeight;

		t.dX = e.pageX - t.clickX;
		t.dY = e.clientY + scrollTop - t.clickY;
		t.curLeft = t.origLeft + t.dX;
		t.curTop  = t.origTop + t.dY;
		
		if (t.curTop - scrollTop + g.offsetHeight + 10 >= clientHeight) {
			scrollTop = scrollTop + 20;
		} else if (t.curTop - scrollTop < 10) {
			scrollTop = scrollTop - 20;
		}
		
		$g.css({
			left:t.curLeft,
			top:t.curTop
		});
		window.scrollTo($doc.scrollLeft(), scrollTop);
		
		var el = t.selectArea(e, g);
		if (el == 'cancel') {
			t.hasPlaced = false;
			t.placeHolder.hide();
		} else if (el) {
			t.hasPlaced = true;
			var where = t.selectPlace(el, e.pageY);
			where
				? t.placeHolder.insertBefore(where)
				: t.placeHolder.appendTo(el);
			t.placeHolder.show();
		}
	},
	dragEnd:function(e){
		var t = this;
		$doc.unbind('.dragment');
		$.browser.mozilla && (document.body.style.MozUserSelect = '');
		if (t.moveStarted) {
			t.moveStarted = false;
			var $g = t.ghost, g = t.ghost[0], $h = t.$elem;
			$g.unbind('.dragment');
			g.releaseCapture && g.releaseCapture();
			var o = t.fromGhost($g, t.hasPlaced);
			if (t.hasPlaced && o) {
				t.placeHolder.replaceWith(o);
			} else {
				t.placeHolder.remove();
			}
			t.placeHolder = null;
			$h.triggerHandler('dragEnd', []);
		}
	}
};

