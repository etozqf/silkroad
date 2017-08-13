formSubmit = (form)->
	for i in $('input.required')
		try
			if i.value.length < 2
				$(i).parent('.ov').children('.info').html('不能为空')
				return false
		catch exc
			return false
	if form.email && form.email.value.length > 0
		unless /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(form['email'].value)
			$(form['email']).parent('.ov').children('.info').html('email格式不正确')
			return false
	if form.phone && form.phone.value.length > 0
		unless /[\d\+\.\(\)]/.test(form['phone'].value)
			$(form['phone']).parent('.ov').children('.info').html('电话格式不正确')
			return false
	return true
$ ()->
	attachmentPicTemplate = $('#attachment_pic_template').html()
	attachmentVideoTemplate = $('#attachment_video_template').html()
	GoTop.init(
		dom: "<div class='mod-gotop'><a hre=''>返回顶部</a></div>"
		rel: $(".header-topbar")
		dir: 'right'
	)
	new Uploader(document.getElementById('upload_pic'),
		script: '?app=baoliao&controller=index&action=upload'
		multi: false
		fileExt: '*.png;*.jpg;*.jpeg;*.gif'
		fileDesc: 'png|jpg|gif'
		fieldName: 'Filedata'
		sizeLimit: 1048576
		jsonType: 1
		uploadComplete: (response)->
			if (response)
				$('#upload_info').empty()
				html = $(attachmentPicTemplate.replace('{src}', UPLOAD_URL + response.data).replace('{value}', response.data))
				$('#attachment_panel').append(html)
				html.find('.delete').bind('click', (event)->
					$(event.currentTarget).parent('.attachment-img').remove()
					imgEmbed.show()
				)
				imgEmbed.hide() if maxUpload > 0 and $('#attachment_panel').children('.attachment-img').length == maxUpload
			else
				$('#upload_info').html('图片上传失败');
	)
	$('#upload_video').videoUploader (response)->
		data = eval "(#{response})"
		return if !data or data.length is 0
		response = encodeURI(response)
		html = $(attachmentVideoTemplate.replace('{thumb}', data.thumb).replace('{value}', response))
		$('#attachment_video').remove()
		$('#attachment_panel').prepend(html)
		html.find('.delete').bind('click', (event)->
			$('#attachment_video').remove()
		)
	
	imgEmbed = $('#upload_pic').children('embed')
	$('#post_form').bind('submit', (form)->
		formSubmit(form.currentTarget)
	)

	for i in $('input.required')
		$(i).bind('blur', (event)->
			obj = $ event.currentTarget
			info = obj.parent().find('.info')
			if obj.val().length < 2 then info.html('不能为空') else info.html('')
		)
	editor = new $.Rte $('#content'),
		disable: ['italic', 'fontsize', 'fontname', 'forecolor', 'insertunorderedlist', 'insertorderedlist', 'justify', 'createlink', 'insertimage', 'html']
	$('#content').val($.trim(editor.getCode()))