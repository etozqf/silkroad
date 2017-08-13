/**
 * CmsTop DIY Engine v1.1
 *
 * @author		kakalong
 * @copyright	2010 (c) cmstop.com
 * @version		$Id: diy.js 7319 2012-09-28 06:57:35Z kakalong $
 */

include("message.js");

include("finder.js");

(function($, window){
include("part/support.js");
include("part/template.js");
include("part/form.js");
include("part/dragment.js");
include("part/resizex.js");
include("part/popup.js");
include("part/popmenu.js");
include("part/dock.js");
include("part/pagination.js");
include("part/reel.js");
include("part/scrollpaper.js");
include("part/tabigation.js");
include("part/diy.js");
include("part/tip.js");
include("part/history.js");
include("part/hotkey.js");
include("part/port.js");
include("part/theme.js");
include("part/gen.js");
include("part/panel.js");
include("part/addpage.js");
include("part/placement.js");
include("part/frame.js");
include("part/widget.js");
// init
$(function(){
	body = $(document.body);
	DIY.trigger('init');
});
})(jQuery, window);