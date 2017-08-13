var activityField = (function() {
    var fields = $('#activity-fields'),
        inputs = $('#inputs');
    return {
        init:function() {
            fields.click(function() {
                if (fields.children('span').hasClass("span_open")) {
                    fields.attr('title', '点击展开');
                    fields.children('span').removeClass("span_open");
                    fields.children('span').addClass("span_close");
                    inputs.slideUp();
                }
                else {
                    fields.attr('title', '点击收起');
                    fields.children('span').removeClass("span_close");
                    fields.children('span').addClass("span_open");
                    inputs.slideDown();
                }
                return false;
            });
            $('#manage_field').click(function() {
                ct.assoc && ct.assoc.open('?app=activity&controller=field', 'newtab');
                return false;
            });
            this.bindEvents();
        },
        render:function(contentid) {
            inputs.empty();
            inputs.load('?app=activity&controller=field&action=render'+(contentid ? '&contentid='+contentid : ''), function() {
                activityField.bindEvents();
            });
        },
        bindEvents:function() {
            $('#inputs input[name*="need"]').click(function(){
                var input = $(this);
                var have = input.parent().prev().find('input');
                this.checked && have.attr('checked',true);
            });
            $('#inputs input[name*="display"]').click(function(){
                var input = $(this);
                var have = input.parent().prev().prev().find('input');
                return !have.is(":checked") && ct.warn('请先启用此项！')?false:true;
            });
            $('#inputs input[name*="have"]').click(function(){
                var input = $(this);
                var need = input.parent().next().find('input');
                var display = input.parent().next().next().find('input');
                need.is(':checked') && need.attr('checked',false);
                display.is(':checked') && display.attr('checked',false);
            });
            $('#inputss tr:gt(0)').hover(function(){$(this).addClass('over')},function(){$(this).removeClass('over')});
        }
    };
})();
activityField.init();