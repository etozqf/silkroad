/**
 * cmstop Context Menu Plugin base on jQuery 1.2+
 *
 * @author     kakalong
 * @copyright  2010 (c) cmstop.com
 * @version    $Id: cmstop.contextMenu.js 8095 2013-01-29 06:38:20Z liyawei $
 */
(function($, window){
var document = window.document, doc = $(document),
RIGHT_KEY_VALUE = /maxthon[\/: ]2/i.test(navigator.userAgent) ? 0 : 2,
CLASSES = {
	contextMenu:'contextMenu',
	contextMenuActive:'contextMenuActive'
};
function innerWidth(){
	return document.documentElement.clientWidth;
}
function innerHeight(){
	return document.documentElement.clientHeight;
}
$.fn.contextMenu = function(menu, callback, aftershow, afterblur, beforeshow)
{
	// Defaults
	menu.jquery || (menu = $(menu));
	if (! menu.length) return this;
	// Add contextMenu class
	menu.addClass(CLASSES.contextMenu);
	// Loop each context menu
	this.each( function() {
		var el = $(this), offset = el.offset();
		// Simulate a true right click
		el.bind('contextMenu',function(_e, e){
			// Hide context menus that may be showing
			$('ul.'+CLASSES.contextMenu).blur();

            // chance to chagne the menu
            typeof beforeshow == 'function' && beforeshow(el, menu);

			// Get this context menu
			
			// Detect mouse position
			var x = e.pageX || e.clientX, y = e.pageY || e.clientY;
			if (!menu.children().length) {
				return;
			}
			menu.css({
                left:'-9999em',
                top:'-9999em',
				display:'block'
			});
			var mH = menu[0].offsetHeight, mW = menu[0].offsetWidth,
	    		sL = doc.scrollLeft(), sT = doc.scrollTop(),
                iH = innerHeight(), iW = innerWidth(),
                dW = doc.width(), dH = doc.height();
	    	if ((iH / 2) < (y - sT)) {
	    		y = y - mH;
	    	}
			if ((iW / 2) < (x - sL)) {
				x = x - mW;
			}
            if ((y + mH) > dH) y = dH - mH;
            if ((x + mW) > dW) x = dW - mW;

			menu.css({
				top: y < 0 ? 0 : y,
				left: x < 0 ? 0 : x
			});
			el.addClass(CLASSES.contextMenuActive);
			typeof aftershow == 'function' && aftershow(el);
			
			doc.unbind('mousedown.contextMenu');
			var blurMenu = function(){
				doc.unbind('mousedown.contextMenu');
				menu.hide();
				el.removeClass(CLASSES.contextMenuActive);
				typeof afterblur == 'function' && afterblur(el);
				return false;
			};
			var iBlur = function(e){
				menu.find('*').index(e.target) != -1 || blurMenu();
			};
			menu.blur(blurMenu);
			setTimeout(function(){
			    doc.bind('mousedown.contextMenu', iBlur);
			}, 0);
			
			// When items are selected
			menu.find('a').unbind('click.contextMenu')
			.bind('click.contextMenu',function(e){
				e.stopPropagation();
				e.preventDefault();
				menu.blur();
				var action = this.getAttribute('href',2);
				action = action.substr(action.lastIndexOf('#')+1);
				// Callback
				callback && callback(action, el, {x: x - offset.left, y: y - offset.top, docX: x, docY: y});
			});
			
			return false;
		}).mousedown(function(evt){
			evt.button == RIGHT_KEY_VALUE && (
			    evt.stopPropagation(),
			    doc.mousedown(),
				el.bind('mouseup.contextMenu',function(e) {
					e.stopPropagation();
					el.unbind('mouseup.contextMenu');
					el.trigger('contextMenu',[e]);
				})
			);
		});
	})
	// Disable browser context menu
	.add(menu).bind('contextmenu', function(){return false;});
	
	// Disable text selection
	if ( $.browser.mozilla ) {
		menu.css('MozUserSelect','none');
	} else if( $.browser.msie ) {
		menu.bind('selectstart.disableTextSelect', function() { return false; });
	} else {
		menu.bind('mousedown.disableTextSelect', function() { return false; });
	}
	return this;
};


})(jQuery, window);