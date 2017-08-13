(function(){
PICKER.register('cmstop', function(form){
	function query(e){
		PICKER.query(form);
		return false;
	}
	$('.modelset').modelset().bind('changed',query);
	$('.selectree').selectree().bind('changed', function(e, t) {
        form[0].catid.value = t.join(',');
        query(e);
    });
	$(form[0].keywords).keyup(query);
});
})();