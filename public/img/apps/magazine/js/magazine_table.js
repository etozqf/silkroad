(function($){
// 使用的数据模板
var tpl = $.trim('<li>\
						<h3><a href="http://app.db.silkroad.news.cn/?app=magazine&controller=download&id={%eid%}" target="_blank">{%title%}</a></h3>\
						<p>{%description%}<!--[<a href="http://db.silkroad.news.cn/magazine/{%alias%}/{%eid%}/" target="_blank">详细</a>]--></p>\
					</li>');
$.fn.magazine_table = function(url,data){
	if(!data){data=""};
	var url = url+data+"&pagesize=10&page=%p&jsoncallback=?";
    // // 分页查询数据
    $("#tablelist").tablelist({
      baseurl:url,
      pager:".page",
      template:tpl,
      pagesize:10,
    });
};

$.fn.magazine_table(url);
})(jQuery)
