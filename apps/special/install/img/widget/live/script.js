(function(){
var replace = function() {
		this.find('object,embed').each(function(){
            var self = $(this),
	            width = parseInt(self.attr('width')) || self.width(),
	            height = parseInt(self.attr('height')) || self.height(),
                parent = self.parent();
            self.remove();
            parent.append('<img src="'+IMG_URL+'apps/special/widget/live/live.jpg" width="' + width + '" height="' + height + '" style="border:none;"/>');
        });
	};
function _init() {
    var container = $('#live-action-container');
    $.getJSON('?app=video&controller=thirdparty&action=getlist', function(json){
        if (!json || !json.data || !json.data.length) return;

        $.each(json.data, function(index, item) {
            container.append('<button type="button" class="button_style_1" style="margin-left:0;" value="'+item.id+'">'+item.title+'</button>');
        });

        container.find(':button').click(function() {
            var _dialog = ct.iframe({
                url:'?app=video&controller=thirdparty&action=selector&islive=1&id='+$(this).attr('value'),
                width:800,
                height:469
            }, {
                ok:function(r) {
                    $('#src').val(r.video);
                    _dialog.dialog('close');
                }
            });
            return false;
        });
    });
}
DIY.registerEngine('live', {
    dialogWidth:400,
    addFormReady:function(form, dialog) {
        _init();
    },
    editFormReady:function(form, dialog) {
        _init();
    },
    afterRender: function(widget) {
        replace.apply(this.content);
    },
    beforeSubmit:function(form, dialog) {},
    afterSubmit:function(form, dialog) {}
});
})();