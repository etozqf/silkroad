<div class="operation_area">
	<input id="search-material-input" type="text" name="search" placeholder="搜索标题" />
	<input id="search-material-button" type="button" class="button_style_1" value="搜索" />
</div>
<div class="picker-panel-left">
	<form>
		<input type="hidden" name="type" value="picture" />
		<ul id="picList" style="height:360px; overflow-y: auto;"></ul>
	</form>
</div>
<script id="piclistTemplate" type="text/template">
	<li>
		<div class="wechat-selector-picture-item" style="width:60px;">
			<input type="radio" name="content" value="" />
		</div>
		<div class="wechat-selector-picture-item" style="width:120px;"></div>
		<div class="wechat-selector-picture-item" style="width:140px;">
			<img src="" alt="" />
		</div>
	</li>
</script>
<script type="text/javascript">
(function() {
	var template = $('#piclistTemplate').html(),
	picList = $('#picList'),
	offset = 0,
	size = 10,
	noMore = false,
	keyword = '';

	var renderList = function(json) {
		var elm = $(template);
		elm.find('[name="content"]').val(JSON.stringify({
			id: json.id,
			src: json.content
		}));
		if (json.content.indexOf('://') === -1) {
			json.content = UPLOAD_URL + json.content;
		}
		elm.find('img').attr('src', json.content);
		elm.find('.wechat-selector-picture-item').eq(1).html(json.title)
		.attr('title', json.title);
		elm.bind('click', function() {
			$(this).find('[type="radio"]')[0].checked = true;
		});
		picList.append(elm);
	}

	var load = function() {
		if (noMore) return;
		$.get('?app=wechat&controller=material&action=ls', {
			type: 'picture',
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
		picList.empty();
		load();
	}

	picList.bind('scroll', function() {
		if (noMore) return;
		if ($(this).scrollTop() + $(this).height() > this.scrollHeight - 5) {
			$(this).scrollTop($(this).scrollTop() - 30);
			load()
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