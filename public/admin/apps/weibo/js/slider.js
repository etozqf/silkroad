var slider = function () {
	var self = this;
	var _panel = $('#weibo-pic-panel');
	var _postion = parseInt(_panel.css('left'));
	var _length = _panel.children().length;
	var _index = 0;
	var _choose = null;
	var _left = function() {
		if (_length + _index <= 6) return;
		_index--;
		_postion = _postion - 90;
		_panel.animate({left:(_postion+'px')}, 'fast');
	}
	var _right = function() {
		if (_index >= 0) return;
		_index++;
		_postion = _postion + 90;
		_panel.animate({left:(_postion+'px')}, 'fast');
	}
	var _bindClick = function(obj) {
		obj.bind('click', function(o) {
			o = $(o.currentTarget);
			if (o.hasClass('weibo-choose')) {
				self.setChoose(null);
			}
			else {
				self.setChoose(o);
			}
		});
	}
	var __init__ = function() {
		$('#weibo-pic-left').bind('click', function () {
			for (var i=0;i<6;i++) _left();
		});
		$('#weibo-pic-right').bind('click', function () {
			for (var i=0;i<6;i++) _right();
		});
		$.each(_panel.children(), function(i,k) {
			_bindClick($(k));
		});
	}
	self.addPic = function (url, choose) {
		var obj = _panel.prepend('<div class="weibo-pic-box weibo-pic"><img src="' + url + '" alt="" width="80" height="60" /></div>').children(':first');
		_bindClick(obj);
		_length++;
		if (choose) {
			while (_index < 0) {
				_right();
			};
			self.setChoose(obj);
		}
	}
	self.setChoose = function (obj) {
		_choose && _choose.removeClass('weibo-choose');
		_choose = obj;
		obj && _choose.addClass('weibo-choose');
	}
	self.getChoose = function () {
		return (_choose) ? _choose.find('img').attr('src') : false;
	}
	self.clear = function () {
		_panel.empty();
		_postion = _postion - (90 * _index);
		_panel.css('left', _postion+'px');
		_index = 0;
		_length = 0;
		_choose = null;		
	}
	self.test = function () {
		console.info(_length);
		console.info(_index);
		console.info(_choose);
	}
	__init__();
}