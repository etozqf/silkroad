$(function(){
var RE_X = /(left|right|center)?([+-]?\d+)?/,
	RE_Y = /(top|bottom|middle)?([+-]?\d+)?/,
HALIGN = {
	left:function(offset, elem){return offset.left - tip.offsetWidth},
	right:function(offset, elem){return offset.left + elem.offsetWidth},
	center:function(offset, elem){return offset.left + (elem.offsetWidth - tip.offsetWidth) / 2}
}, VALIGN = {
	top:function(offset, elem){return offset.top - tip.offsetHeight},
	bottom:function(offset, elem){return offset.top + elem.offsetHeight},
	middle:function(offset, elem){return offset.top + (elem.offsetHeight - tip.offsetHeight) / 2}
}, $tip = $('<div class="diy-tip"></div>').bind('mousedown', function(){return false}).appendTo(document.body),
tip = $tip[0], _lastOverElem, _lastShowElem, _downhidelock = 0, _ishow = null;

function clearTimeShow(){
    _ishow && clearTimeout(_ishow);
    _ishow = null;
}

$doc.bind('mouseover', function(e){
	var elem = e.target;
    if (elem.nodeType === 3) {
        elem = elem.parentNode;
    }
	if (elem.nodeType !== 1) {
        return clearTimeShow();
	}
	var $elem = $(elem), $handle = $elem;
	if (_lastOverElem && tip === elem) {
		elem = _lastOverElem;
		$elem = $(elem);
	} else if (!$elem.attr('tip')) {
		$elem = $elem.parents('[tip]:first');
		elem = $elem[0];
		if (!elem) return clearTimeShow();
		$handle = $elem;
	}
    if (_lastOverElem == elem && _downhidelock) {
        return;
    }
    _lastOverElem = elem;
    clearTimeShow();
    _downhidelock = 0;

	e.stopPropagation();
	e.preventDefault();

    $tip.stop(true);
	
	var tipContent = $elem.attr('tip').split(':'),
		title = tipContent[0], x = tipContent[1] || 'center', y = tipContent[2] || 'top',
        delay = _lastShowElem === elem ? 0 : parseInt(tipContent[3] || 0), offset = $elem.offset();

	$tip.text(title);
	$tip.css({visibility:'hidden', display:'block'});

	var m, hAlign = 'left', dx = 0, vAlign = 'top', dy = 0;
	if (m = RE_X.exec(x)) {
		hAlign = m[1] && (m[1] in HALIGN) ? m[1] : 'left';
		dx = m[2] ? parseInt(m[2]) : 0;
	}
	if (m = RE_Y.exec(y)) {
		vAlign = m[1] && (m[1] in VALIGN) ? m[1] : 'top';
		dy = m[2] ? parseInt(m[2]) : 0;
	}

	$tip.css({
		left:HALIGN[hAlign](offset, elem) + dx,
		top:VALIGN[vAlign](offset, elem) + dy,
        visibility:'visible', display:'none', opacity:''
	});

    function show(){
        _lastShowElem = elem;
        $tip.show();
        function tmHide(){
            $handle.unbind('mouseleave', tmHide);
            $elem.unbind('mousedown', imHide);
            $tip.fadeOut(150, function(){
                _lastShowElem = null;
            });
        }
        function imHide(){
            $handle.unbind('mouseleave', tmHide).bind('mouseleave', function(){
                _downhidelock = 0;
                $handle.unbind('mouseleave', arguments.callee);
            });
            $elem.unbind('mousedown', imHide);
            _downhidelock = 1;
            $tip.hide();
        }

        $elem.bind('mousedown', imHide);
        $handle.bind('mouseleave', tmHide);
    }

    if (!delay) {
        show();
    } else {
        _ishow = setTimeout(show, delay);
    }
});

});