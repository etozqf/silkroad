<div class="operation_area">
	<input id="search-material-input" type="text" name="search" placeholder="搜索标题" />
	<input id="search-material-button" type="button" class="button_style_1" value="搜索" />
</div>
<div class="picker-panel-left">
	<form>
		<input type="hidden" name="type" value="voice" />
		<ul id="voiceList" style="height:360px; overflow-y: auto;"></ul>
	</form>
</div>
<div id="voicePlayer"></div>
<script id="voiceListTemplate" type="text/template">
	<li>
		<div class="wechat-selector-picture-item" style="width:60px;">
			<input type="radio" name="content" value="" />
		</div>
		<div class="wechat-selector-picture-item" style="width:120px;"></div>
		<div class="wechat-selector-picture-item" style="width:160px;">
			<div class="wechat-list-voice-play"></div>
		</div>
	</li>
</script>
<script type="text/javascript">
(function() {
	var template = $('#voiceListTemplate').html(),
	voiceList = $('#voiceList'),
	jplayer = $('#voicePlayer'),
	offset = 0,
	size = 10,
	noMore = false,
	keyword = '';

	var renderList = function(json) {
		var elm = $(template),
		data = JSON.parse(json.content);
		elm.find('[name="content"]').val(JSON.stringify({
			id: json.id,
			src: data.src
		}));
		if (data.src.indexOf('://') === -1) {
			data.src = UPLOAD_URL + data.src;
		}
		elm.find('.wechat-list-voice-play').bind('click', {
			src: data.src
		}, function(event) {
			event.stopPropagation();
			jplayer.unbind('play').one('play', {
				src: event.data.src
			},function(event) {
				jplayer.jPlayer('stop');
				jplayer.jPlayer('clearMedia');
				var ext = event.data.src.split('.').pop();
				var mediaObject = {};
				mediaObject[ext] = event.data.src;
				setTimeout(function() {
					jplayer.jPlayer('setMedia', mediaObject);
					jplayer.jPlayer('play');
				}, 500);
			});
			if ($.jPlayer) {
				jplayer.trigger('play');
			}
		});
		elm.find('.wechat-selector-picture-item').eq(1).html(json.title)
		elm.bind('click', function() {
			$(this).find('[type="radio"]')[0].checked = true;
		});
		voiceList.append(elm);
	}

	var load = function() {
		if (noMore) return;
		$.get('?app=wechat&controller=material&action=ls', {
			type: 'voice',
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
		voiceList.empty();
		load();
	}
	voiceList.bind('scroll', function() {
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

	ct.fet(IMG_URL + 'js/lib/jquery.jplayer.js', function() {
		jplayer.jPlayer({
			swfPath: IMG_URL + 'js/lib/jplayer',
			solution: 'html, flash',
			supplied: 'mp3, wav',
			ready: function() {
				jplayer.trigger('play');
			}
		});
	});
})();
</script>