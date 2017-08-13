var resizeX = function(handle, checkBound){
	var t = this;
	t.origX = null;
	t.curX = null;
	t.dX = null;
	t.$handle = handle.jquery ? handle : $(handle);
	t.handle = t.$handle[0];
	t.checkBound = checkBound;
	t.pX = 0;
	t._resizeStarted = false;
	t.$handle.mousedown(function(e){
		e.stopPropagation();
		e.preventDefault();
		$doc.mousedown();
		t.resizeInit(e);
	});
}
resizeX.prototype = {
	resizeInit:function(e){
		var t = this, h = t.handle, $h = t.$handle;
		t.origX = $h.position().left;
		t.curX = t.origX;
		t.pX = e.pageX;
		t.dX = 0;
		$doc.bind('mousemove.resizeX', function(e){
			t.resizeIng(e);
		});
		$doc.bind('mouseup.resizeX', function(e){
			t.resizeEnd(e);
		});
		$doc.bind('selectstart.resizeX', function(){return false;});
		$.browser.mozilla && (document.body.style.MozUserSelect = 'none');
		document.body.style.cursor = 'e-resize';
		
		$h.triggerHandler('resizeInit',[t, t.origX]);
	},
	resizeIng:function(e){
		var t = this, $h = t.$handle, h = t.handle;
		if (! t._resizeStarted) {
			t._resizeStarted = true;
			if (h.setCapture) {
				$h.bind('losecapture.resizeX', function(e){
					t.resizeEnd(e);
				});
				h.setCapture();
			}
			$h.triggerHandler('resizeStart', [t, t.origX]);
		}
		var dX = e.pageX - t.pX, curX = t.origX + dX;
		if (t.checkBound(curX, $h)) {
			$h.css('cursor', curX < t.curX ? 'w-resize' : 'e-resize');
			t.curX = curX;
			t.dX = dX;
			$h.css('left', curX);
			$h.triggerHandler('resizeIng', [t, dX, curX, t.origX]);
		}
	},
	resizeEnd:function(e){
		var t = this, $h = t.$handle, h = t.handle;
		$doc.unbind('.resizeX');
		$.browser.mozilla && (document.body.style.MozUserSelect = '');
		document.body.style.cursor = '';
		if (t._resizeStarted) {
			t._resizeStarted = false;
			$h.unbind('.resizeX');
			h.releaseCapture && h.releaseCapture();
		}
		$h.triggerHandler('resizeEnd', [t, t.dX, t.curX, t.origX]);
	}
};