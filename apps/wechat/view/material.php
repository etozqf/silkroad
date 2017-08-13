<?php $this->display('head');?>
<form id="search" class="wechat-search-material wechat-search" onsubmit="return false;">
	<table>
		<tr>
			<td>
				<input type="text" name="starttime" class="input_calendar" style="width:120px;" />
			</td>
			<td> ~ </td>
			<td>
				<input type="text" name="endtime" class="input_calendar" style="width:120px;" />
			</td>
			<td>
				<div class="search">
					<input type="text" name="keyword" value=""  placeholder="标题" />
					<a href="javascript:;">搜索</a>
				</div>
			</td>
		</tr>
	</table>
	<button></button>
</form>
<div id="material" class="wechat-material wechat-w-1150 f_l">
	
	<!-- 图文 -->
	<div id="List-panel" class="wechat-material-panel" style="display:none;">
		<div class="bk_10"></div>
		<div class="wechat-add-buttons">
			<span class="wechat-list-multiple button_style_2">添加</span>
		</div>
		<div id="list-inner" class="wechat-material-panel-list"></div>
		<script id="listTemplate" type="text/template">
			<div class="wechat-list-box">
				<div class="wechat-list-content"></div>
				<div class="wechat-list-voice-footer">
					<a class="f_l edit" href="javascript:;">编辑</a>
					<a class="f_r delete" href="javascript:;">删除</a>
				</div>
			</div>
		</script>
	</div>
	<!-- 图片 -->
	<div id="Picture-panel" class="wechat-material-panel" style="display:none;">
		<div class="bk_10"></div>
		<div class="wechat-add-buttons">
			<span id="picture-upload" class="button_style_2">上传</span>
			<span id="picture-select" class="button_style_2">选择</span>
		</div>
		<div class="bk_10"></div>
		<script id="pictureTemplate" type="text/template">
			<div class="wechat-list wechat-img-unload">
				<div class="wechat-list-img">
					<img src="" alt="" />
				</div>
				<div class="wechat-list-header">
					<img class="hand delete" width="16" height="16" title="删除" alt="删除" src="images/delete.gif">
					<img class="hand edit" width="16" height="16" title="编辑" alt="编辑" src="images/edit.gif">
				</div>
				<div class="wechat-list-footer-picture">
					<div class="wechat-list-overlay"></div>
					<textarea class="wechat-list-title-picture"></textarea>
				</div>
			</div>
		</script>
	</div>
	<!-- 语音 -->
	<div id="Voice-panel" class="wechat-material-panel" style="display:none;">
		<div class="bk_10"></div>
		<div class="wechat-add-buttons">
			<span id="voice-upload" class="button_style_2">上传</span>
			<span id="voice-select" class="button_style_2">选择</span>
		</div>
		<div class="bk_10"></div>
		<script id="voiceTemplate" type="text/template">
			<div class="wechat-list">
				<div class="wechat-list-voice-title"></div>
				<div class="wechat-list-voice-play wechat-play"></div>
				<div class="wechat-list-voice-footer">
					<a class="f_l edit" href="javascript:;">编辑</a>
					<a class="f_r delete" href="javascript:;">删除</a>
				</div>
			</div>
		</script>
	</div>
	<!-- 视频 -->
	<div id="Video-panel" class="wechat-material-panel" style="display:none;">
		<div class="bk_10"></div>
		<div class="wechat-add-buttons">
			<span id="video-upload" class="button_style_2">上传</span>
			<span id="video-select" class="button_style_2">选择</span>
		</div>
		<div class="bk_10"></div>
		<script id="videoTemplate" type="text/template">
			<div class="wechat-list wechat-list-video">
				<div class="wechat-list-img">
					<img src="images/icons/mp4.png" alt="" />
				</div>
				<div class="wechat-list-header">
					<img class="hand delete" width="16" height="16" title="删除" alt="删除" src="images/delete.gif">
					<img class="hand edit" width="16" height="16" title="编辑" alt="编辑" src="images/edit.gif">
				</div>
				<textarea class="wechat-list-video-title" disabled="disabled"></textarea>
			</div>
		</script>
	</div>
</div>
<div id="jplayer"></div>