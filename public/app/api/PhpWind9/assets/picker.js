(function(){
PICKER.register('phpwind', function(form){
	function query(e){
		PICKER.query(form);
		return false;
	}
	$('.discuz_selectlist').selectlist().bind('changed',query);
	$(form[0].keyword).keyup(query);
	form.bind('submit', function(){
		return false;
	});
});
})();