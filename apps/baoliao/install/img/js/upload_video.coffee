template = [
	'<div id="videoDialogHead" class="video-dialog-head">',
		'<div class="item first" url="?app=baoliao&controller=index&action=video">插入视频链接</div>',
		'<div class="item" url="?app=cloud&controller=video56&sid=baoliao">上传本地视频</div>',
	'</div>',
	'<div class="clear"></div>',
	'<div id="videoDialogBody" class="video-dialog-body">',
	'</div>',
	'<div id="dialog_close" class="dialog-close ie6Opacity video-dialog-close" classname="dialog-close ie6Opacity"></div>'
].join('\r\n')

# require cmstop.js
videoUploadDialog = confirmDialog = null
 
cmstop.fet "#{IMG_URL}apps/baoliao/css/video.css"
cmstop.fet "#{IMG_URL}js/lib/dialog/style.css"
cmstop.fet "#{IMG_URL}js/lib/cmstop.dialog.js", ()->
	videoUploadDialog = new window.dialog
		width: 400
		height: 392
		hasOverlay: 1
		html: template
	confirmDialog = new window.dialog
		width: 345
		height: 170

init = (videoDialogBody, callback)->
	videoDialogHead = $ '#videoDialogHead'

	videoDialogHead.children('.item').eq(1).remove() unless allowed56Video

	videoDialogHead.children('.item').bind 'click', (event)->
		item = $ event.currentTarget
		return if item.hasClass 'cur'
		url = item.attr 'url'
		ifm = $ '<iframe src="'+url+'" border="0" frameborder="0"></iframe>'
		videoDialogBody.empty().append(ifm)
		item.parent().children('.cur').removeClass 'cur'
		item.addClass 'cur'
		bindClose ifm, callback
	.eq(0).trigger 'click'
	$('#dialog_close').bind 'click', ()->
		_close = ()->
			callback $.cookie COOKIE_PRE+'video'
			videoUploadDialog.close()

		ifm = $('#videoDialogBody').children('iframe')
		if ifm.attr('src') is '?app=baoliao&controller=index&action=video'
			input = ifm[0].contentDocument.getElementById('video')
			if input and input.value isnt ''
				return if confirmDialog.isOpen
					hasOverlay: 1
				confirmDialog.open 
					confirm:()->
						_close()
					text: '你确定要关闭吗？若点击确定，则视频会添加失败。'
				return
		_close()


bindClose = (iframe, callback)->
	refreshCount = 0
	iframe.bind 'load', ()->
		if refreshCount++ == 7
			videoUploadDialog.close()
			callback $.cookie COOKIE_PRE+'video'

videoUploader = (callback)->
	return if videoUploadDialog.isOpen or videoUploadDialog is null
	return if $('#attachment_video').length > 0
	videoUploadDialog.open()
	setTimeout ()->
		videoDialogBody = $ '#videoDialogBody'
		init(videoDialogBody, callback)
	, 10
	refreshCount = 0

$.fn.extend videoUploader: (callback)->
	$.each this, (i, k)->
		$(k).bind 'click', (event)->
			event.preventDefault()
			$.cookie "#{COOKIE_PRE}video", null,
				'expires'	: -1
				'domain'	: COOKIE_DOMAIN
			videoUploader callback
		true