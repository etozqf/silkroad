/**
 * Table List
 *
 * Base on:
 *  jquery.js & jquery.pagination.js & cmstop.template.js
 *
 */
(function($){
$.fn.tablelist = function(options){
    return this.each(function(){
        var body = $(this), c = $.extend({}, {
            idfield:'',
            pagesize:10,
            baseurl:'',
            pager:null,
            template:''
        }, options||{}),
        rowStack = {}, tpl = new Template(c.template),
        pageOption = {
            callback: loadPage,
            items_per_page: c.pagesize,
            num_display_entries: 10,
            num_edge_entries: 0,
            prev_text:'上一页',
            next_text:'下一页',
            prev_show_always:false,
            next_show_always:false
        }, paged = 0;

        function buildRow(json){
            var item = $(tpl.render(json));

            var id = json[c.idfield];
            rowStack[id] = item;
    	    return item;
    	}
    	function loadPage(index){
    	    $.getJSON(c.baseurl.replace('%p', index+1), complete);
    	}
        function complete(json){
            rowStack = {};
            body.empty();
            body.triggerHandler('JsonLoaded', [json]);
            $.each(json.data, function(){
                body.append(buildRow(this));
            });
            for (var id in rowStack) {
                body.triggerHandler('RowAdded', [id, rowStack[id]]);
            }
            // TODO:hide loading or show message
            if (c.pager && !paged) {
                paged = 1;
                $(c.pager).pagination(json.total, pageOption);
            }
        }
        body.bind('deleteRow', function(e, id){
            rowStack[id].remove();
            delete rowStack[id];
        });
        $.getJSON(c.baseurl.replace('%p', 1), complete);
    });
};
})(jQuery);
