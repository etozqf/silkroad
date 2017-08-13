(function(){
PICKER.register('enorth', function(form){
	function query(e){
		PICKER.query(form);
		return false;
	}
	$('.selectree').selectree().bind('changed',query);
	$(form[0].keyword).keyup(query);
	$(form[0].orderby).change(query);
});
})();