
function setFrameWrapper(frame){
	var f = frame.css('margin', '')[0], w = f.parentNode,
		margins = {0:'5px', 1:'3px', 2:'5px', 3:'3px'}, designMargin = [],
		style = f.getAttribute('frame-style'),
		auto = RE.center.test(style) && 'auto',
		margin = [
			$.curCSS(f, 'margin-top'),
			auto || (RE['margin-right'].exec(style) || {1:$.curCSS(f, 'margin-right')})[1],
			$.curCSS(f, 'margin-bottom'),
			auto || (RE['margin-left'].exec(style) || {1:$.curCSS(f, 'margin-left')})[1]
		], m = RE.width.exec(style);
	for (var k in margins) {
		var x = margin[k];
		designMargin.push((x == 'auto' || toFloat(x)) ? x : margins[k]);
	}
	margin = margin.join(' ');
	designMargin = designMargin.join(' ');
	
	w.style.margin = DIY.designMode ? designMargin : margin;
	w.setAttribute('view-margin', margin);
	w.setAttribute('design-margin', designMargin);
	f.style.margin = '0';
	
	if (!m || m[1] == 'auto') {
		w.style.width = '';
	} else {
		if (RE.percent.test(m[1])) {
			w.style.width = m[1];
			f.style.width = 'auto';
		} else {
			w.style.width = f.offsetWidth + 'px';
		}
	}
}

function initFrame(frame){
	frame.jquery || (frame = $(frame));
	var f = frame[0], c = getColor(frame),
		areas = frame.children('.diy-area'), n = areas.length, l = 0;
	if (!f.offsetWidth) {
		throw "parse frame error";
	}
	
	var wrapper = $(
	'<div class="diy-wrapper diy-placement '+c+'">' +
		'<div class="diy-top diy-border"></div>' +
		'<div class="diy-right diy-border"></div>' +
		'<div class="diy-bottom diy-border"></div>' +
		'<div class="diy-left diy-border"></div>' +
		'<div class="diy-handle diy-border"></div>' +
	'</div>');

    if (frame.hasClass('diy-hidden')) {
        frame.removeClass('diy-hidden');
        wrapper.addClass('diy-hidden');
    }
	
	wrapper.find('.diy-handle').hover(function(){
		wrapper.addClass('diy-over');
	},function(){
		wrapper.removeClass('diy-over');
	});
		
	frame.after(wrapper).appendTo(wrapper);
	
	setFrameWrapper(frame);
	
	var w = frame.width();
	
	areas.each(function(i){
		var t = this;
		pushGuid(t.id);
		if (i+1==n) return;
		var p = RE.percent.exec($.curCSS(t, 'width'));
		if (p) {
			l += toFloat(p[1]);
		} else {
			l += t.offsetWidth * 100 / w;
		}
		var h = $('<div class="diy-x-resize" title="点住拖动改变宽度" style="left:'+toFixed(l)+'%"></div>')
			.insertAfter(t);
		bindResizeEvent(h, frame);
	});
	new dragMent(wrapper, placeMentDragOptions);
	bindOver(wrapper);
	bindMove(wrapper);
    return wrapper;
}
function bindResizeEvent(h, frame) {
	var p = h.prev('.diy-area'),
		n = h.next('.diy-area'),
		ph = p.prev('.diy-x-resize'),
		ppa = p.prevAll('.diy-area'),
		nna = n.nextAll('.diy-area');
	new resizeX(h, function(curX){
		return ((ph.length ? (curX < ph.position().left + ph[0].offsetWidth) : (curX < 0))
		|| (curX > n.position().left + n[0].offsetWidth - h[0].offsetWidth)) ? false : true;
	});
	var tl,tr,origScale;
	h.bind('resizeInit',function(e, o){
		h.addClass('diy-active');
		var tips = frame.children('.diy-resize-tips');
		if (tips.length) {
			tl = tips[0];
			tr = tips[1];
		} else {
			tl = $('<div class="diy-resize-tips"></div>').appendTo(frame)[0];
			tr = $('<div class="diy-resize-tips"></div>').appendTo(frame)[0];
		}
		var l = h.position().left;
		tl.style.visibility = 'hidden';
		tl.style.display = 'block';
		var pw = p.css('width'), nw = n.css('width'),
			opw = p[0].offsetWidth, onw = n[0].offsetWidth;
		origScale = {pw:pw, nw:nw, hl:h.css('left')};
		tl.innerHTML = pw+'（'+opw+'px）';
		tr.innerHTML = nw+'（'+onw+'px）';
		tl.style.left = (l - 8 - tl.offsetWidth)+'px';
		tl.style.visibility = 'visible';
		tr.style.left = (l + 8 + h[0].offsetWidth)+'px';
		tr.style.display = 'block';
	}).bind('resizeIng',function(e, o, dx, curX){
		var v = microAjust(dx, p, ppa, nna, frame);
		tl.innerHTML = v.pw+'%（'+v.opw+'px）';
		tr.innerHTML = v.nw+'%（'+v.onw+'px）';
		tl.style.left = (curX - 8 - tl.offsetWidth)+'px';
		tr.style.left = (curX + 8 + h[0].offsetWidth)+'px';
	}).bind('resizeEnd',function(e, o, dx){
		tl.style.display = 'none';
		tr.style.display = 'none';
		h.removeClass('diy-active');
		if (dx) {
			var v = microAjust(dx, p, ppa, nna, frame);
			var pw = toFixed(v.pw)+'%', nw = toFixed(v.nw)+'%', hl = toFixed(v.hl)+'%',
				newScale = {pw:pw,nw:nw,hl:hl};
			p.width(pw);
			n.width(nw);
			o.$handle.css('left', hl);
			History.log('resize', [h, p, n, origScale, newScale]);
			DIY.trigger('changed');
		}
	});
}


function microAjust(dx, p, ppa, nna, frame) {
	// Micro-adjustment change into percent format
	var fw = frame.width(), fpw = 99.9, l = 0;
	ppa.length && ppa.each(function(){
		var w = $.curCSS(this,'width'), v = RE.percent.exec(w);
		if (v) {
			l += toFloat(v[1]);
		} else {
			l += this.offsetWidth * 100 / fw;
		}
	});
	fpw -= l;
	nna.length && nna.each(function(){
		var w = $.curCSS(this,'width'), v = RE.percent.exec(w);
		if (v) {
			fpw -= toFloat(v[1]);
		} else {
			fpw -= this.offsetWidth * 100 / fw;
		}
	});
	var opw = Math.round(p.width() + dx),
	npw = toFloat(toFixed(opw * 100 / fw)),
	nw = toFloat(toFixed(fpw - npw)),
	onw = Math.round(nw * fw / 100);
	return {
		pw:npw,
		nw:nw,
		opw:opw,
		onw:onw,
		hl:l + npw
	}
}

DIY.registerInit(function(){
    $('.diy-frame').each(function(){
        initFrame(this);
    });
});
