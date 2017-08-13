(function(){
var textarea, editor;
var htmlCode = function(form, dialog) {
	form.css('overflow', 'hidden');
	textarea = form.find('#html_code');
	setTimeout(function(){
		editor = new $.Rte(textarea, {
			disable: ['insertimage']
		});
		textarea.val($.trim(editor.getCode()));
	}, 1);
};

DIY.registerEngine('html', {
	dialogWidth : 460,
	addFormReady:htmlCode,
	editFormReady:htmlCode,
	afterRender: function(widget){},
	beforeSubmit:function(form, dialog){
		textarea.val($.trim(editor.getCode()));
	},
	afterSubmit:function(form, dialog){}
});

})();