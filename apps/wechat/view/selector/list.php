<div class="operation_area">
	<input id="search-material-input" type="text" name="search" placeholder="搜索标题" />
	<input id="search-material-button" type="button" class="button_style_1" value="搜索" />
</div>
<div class="picker-panel-left">
	<form>
		<input type="hidden" name="type" value="list" />
		<ul id="ListList" style="height:360px; overflow-y: auto; padding-top: 30px;"></ul>
		<input type="hidden" name="template" value="" />
	</form>
</div>
<script type="text/javascript">
(function() {
	var list = $('#ListList'),
	offset = 0,
	size = 5,
	noMore = false,
	keyword = '';
	var renderList = function(json) {
		var data = JSON.parse(json.content), count = 0, i,
		first = null, img, div, 
		box = $('<div class="wechat-list-box"></div>'),
		elm = $('<div class="wechat-list-content"></div>'),
		footer = $('<div class="wechat-list-voice-footer t_c"></div>');
		box.css({
			margin :'0 auto 34px',
			position : 'relative'
		});
		box.append(elm);
		box.append(footer);

		var formartDate = function(time) {
			time = (Number(time)) * 1000;
			var date = new Date(time);
			return date.getFullYear() + '-' + (date.getMonth()+1) + '-' + date.getDate();
		}
		var autoFillPath = function(url) {
			if (url.indexOf('://') === -1) {
				url = UPLOAD_URL + url;
			}
			return url;
		}
		var buildImg = function(src) {
			var img = new Image;
			img.onerror = function() {
				img.src = IMG_URL + 'images/space.gif';
			}
			img.src = autoFillPath(src);
			img.removeAttribute('width');
			img.removeAttribute('height');
			return img;
		}

		$('<input type="radio" name="content" />').appendTo(footer).val(JSON.stringify({
			id: json.id,
			data: data
		}));
		for (i in data) {
			if (typeof data[i].title !== 'string') {
				continue;
			}
			if (first === null) {
				first = data[i];
				delete data[i];
			}
			count++;
		}
		if (count === 1) {
			elm.append('<div class="wechat-list-content-title">'+json.title+'</div>');
			elm.append('<div class="wechat-list-content-date">'+formartDate(json.created)+'</div>');
			elm.append('<div class="wechat-list-content-cover"></div>');
			elm.append('<div class="wechat-list-content-description">'+first.description+'</div>');
			elm.find('.wechat-list-content-cover').append($(buildImg(first.thumb)));
		} else {
			elm.append('<div class="wechat-list-content-date">' + formartDate(json.created) + '</div>');
			elm.append('<div class="wechat-list-content-cover">' +
				'<div class="wechat-list-content-cover-title">' + first.title + '</div>' +
			'</div>');
			elm.find('.wechat-list-content-cover').append($(buildImg(first.thumb)));
			for (i in data) {
				if ((typeof data[i].title !== 'string') || !data[i].title) {
					continue;
				}
				div = $('<div class="wechat-list-content-item"></div>');
				div.append('<div class="wechat-list-content-subtitle">' + data[i].title + '</div>');
				if (data[i].thumb) {
					$('<div class="wechat-list-content-subthumb"></div>').appendTo(div).append($(buildImg(data[i].thumb)));
				}
				elm.append(div);
			}
		}
		box.bind('click', function() {
			$(this).find('[type="radio"]')[0].checked = true;
			$('[name="template"]').val('<div class="wechat-list-content">' +
				$(this).children('.wechat-list-content').html() +
			'</div>');
		});
		list.append(box);
	}
	var load = function() {
		if (noMore) return;
		$.get('?app=wechat&controller=material&action=ls', {
			type: 'list',
			offset: offset,
			size: size,
			keyword: keyword
		}, function(req) {
			if (!req.state) {
				return ct.error(req.error || '异常错误');
			}
			if (req.data.length === 0) {
				noMore = true;
				return;
			}
			$.each(req.data, function(i, k){
				if (!offset || offset > k.id) {
					offset = k.id;
				}
				renderList(k);
			});
		});
	}
	var search = function() {
		noMore = false;
		keyword = $('#search-material-input').val();
		offset = 0;
		list.empty();
		load();
	}
	list.bind('scroll', function() {
		if (noMore) return;
		if ($(this).scrollTop() + $(this).height() > this.scrollHeight - 35) {
			$(this).scrollTop($(this).scrollTop() - 30);
			load();
		}
	});
	$('#search-material-button').bind('click', search);
	$('#search-material-input').bind('keydown', function(event) {
		if (event.keyCode === 13) {
			search();
		}
	});
	load();
})();
</script>