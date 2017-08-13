(function(){
function init(form, dialog){
	fet('lib.tree lib.list lib.suggest', function(){
		setTimeout(function(){
				form.find('textarea').editplus({
				buttons: 'fullscreen,wrap,|,db,content,discuz,phpwind,shopex,/,loop,ifelse,elseif,|,preview'
			});
		}, 0);
	});
}
DIY.registerEngine('code', {
	addFormReady:init,
	editFormReady:init,
	beforeSubmit:function(form, dialog){},
	afterSubmit:function(form, dialog){}
});
})();