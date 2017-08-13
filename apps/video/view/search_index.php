<?php $this->display('header');?>
<link rel="stylesheet" type="text/css" href="apps/video/css/search.css" />
<div class="bk_8"></div>
<div class="video-search-index">
	<div class="video-search-index-title"><img src="apps/video/images/title.png" /></div>
	<form method="get" action="." onsubmit="return formSubmit(this);">
		<input type="hidden" name="app" value="video" />
		<input type="hidden" name="controller" value="search" />
		<input type="hidden" name="action" value="search" />
		<div class="video-search-index-form">
			<input type="text" class="video-search-index-input" name="search" value="" />
			<input type="submit" class="video-search-index-submit" value="" />
		</div>
	</form>
	<div class="video-search-description">一键转载视频功能由<span>CmsTop云平台</span>强力驱动。通过该功能搜索到需要的视频后，只需要一键就可以快速转载到本站。</div>
	<div class="video-search-foot">搜索结果来自<img src="apps/video/images/soku.png" alt="搜酷" /></div>
</div>
<script type="text/javascript">
var formSubmit = function(form) {
	if (form.search.value === '') {
		ct.error('关键词不能为空');
		return false;
	}
	return true;
};
</script>