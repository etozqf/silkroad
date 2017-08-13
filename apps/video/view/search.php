<?php $this->display('header');?>
<link rel="stylesheet" type="text/css" href="apps/video/css/search.css" />
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL;?>js/lib/pagination/style.css" />
<script type="text/javascript">
	var api_url = '?app=video&controller=search&action=',
	sourceAllowed = ['腾讯', '优酷', '土豆', '乐视', '56', '激动', '新浪', '搜狐', '爱奇艺', 'CNTV', '酷6']; // TODO: 这里如果需要扩展应该做成从API中读取来源列表
</script>
<div class="video-search-condition mar_l_8 f_l">
	<div class="bk_8"></div>
	<form id="search_form" method="get" action="?">
		<input id="keyword" type="text" name="keyword" class="video-search-keyword" value="<?php echo $search;?>" size="7" />
		<input class="button_style_1 video-search-btn" type="submit" name="" value="搜索" />
	</form>
	<div class="video-search-cond-title">画质：</div>
	<div class="video-search-cond">
		<a href="javascript:;" data="hd|0">不限</a>
		<a href="javascript:;" data="hd|1">高清</a>
	</div>
	<div class="video-search-cond-title">时长：</div>
	<div class="video-search-cond">
		<a href="javascript:;" data="time_length|0">不限</a><br />
		<a href="javascript:;" data="time_length|1">0-10分钟</a><br />
		<a href="javascript:;" data="time_length|2">10-30分钟</a><br />
		<a href="javascript:;" data="time_length|3">30-60分钟</a><br />
		<a href="javascript:;" data="time_length|4">60分钟以上</a>
	</div>
	<div class="video-search-cond-title">发布时间：</div>
	<div class="video-search-cond">
		<a href="javascript:;" data="limit_date|0">不限</a>
		<a href="javascript:;" data="limit_date|1">一天</a>
		<a href="javascript:;" data="limit_date|7">一周</a>
		<a href="javascript:;" data="limit_date|30">一月</a>
	</div>
	<div class="video-search-cond-title">来源网站</div>
	<div class="video-search-cond">
		<a href="javascript:;" data="site|0">不限</a>
		<a href="javascript:;" data="site|27">腾讯</a>
		<a href="javascript:;" data="site|14">优酷</a>
		<a href="javascript:;" data="site|1">土豆</a>
		<a href="javascript:;" data="site|17">乐视</a>
		<a href="javascript:;" data="site|2">56　</a>
		<a href="javascript:;" data="site|9">激动</a>
		<a href="javascript:;" data="site|3">新浪</a>
		<a href="javascript:;" data="site|6">搜狐</a>
		<a href="javascript:;" data="site|19">爱奇艺</a>
		<a href="javascript:;" data="site|15">CNTV</a>
		<a href="javascript:;" data="site|10">酷6</a>
	</div>
</div>
<div class="video-search-result f_r">
	<div id="table_list" class="video-search-list"></div>
	<div class="clear"></div>
	<div class="video-search-blank"></div>
</div>
<script type="text/javascript" src="apps/video/js/search.js"></script>