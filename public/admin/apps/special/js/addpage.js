include("message.js");

(function($, window){
include("part/support.js");
include("part/pagination.js");
include("part/reel.js");
include("part/scrollpaper.js");
include("part/tip.js");
include("part/addpage.js");

$(function(){
    body = $(document.body);
    addPage(function(json){
        var url = envUrl('?app=special&controller=online&action=design&pageid='+json.data.pageid);
        body.message('timer', '页面已创建，%t秒后将进入设计此页面', function(){
            window.location = url;
        });
    });
});
})(jQuery, window);