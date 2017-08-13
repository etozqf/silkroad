(function(){
DIY.registerPort('phpwind', function(view, form){
	$('.tips').attrTips('tips', 'tips_green');
	$.each($('select'), function(i,k) {
		k = $(k);
		if (k.attr('multiple')) {
			return true;
		}
		if (k.children('option:selected').length === 0) {
			k.children('option').eq(0).attr('selected', 'selected');
		}
	});
	$('.discuz_selectlist').selectlist();
});
})();