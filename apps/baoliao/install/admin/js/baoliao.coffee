escapeHtml = (unsafe) ->
	unsafe
		.replace(/&(?!amp;)/g, "&amp;")
		.replace(/<(?!lt;)/g, "&lt;")
		.replace(/>(?!gt;)/g, "&gt;")
		.replace(/"(?!quot;)/g, "&quot;")
		.replace(/'(?!#039;)/g, "&#039;")

class List
	nav = listForm = listElm = null
	where = []
	self = this
	resizing = 0
	height: 0
	page: 1
	pagesize: 0
	status: 2
	tableApp: null
	init: ()=>
		listForm = $('#list_form')
		nav = $('#nav')
		delBtn = $('#delete')
		listElm = $('#baoliao_list')
		@height = document.documentElement.clientHeight - 76
		@pagesize = parseInt(@height / 50)
		@pagesize-- if cmstop.IE > 0
		listElm.css('height', @height + 'px')
		nav.children('li').bind('click', @bindStatus)
		delBtn.bind 'click', ()=>
			@delete()
		@tableApp = listElm.table()
		@tableApp.load
			page: 1
			pagesize: @pagesize
			url: '?app=baoliao&controller=baoliao&action=page'
			template: $('#baoliao_list_template').html()
			where:
				status: @status
			pagefield: $('#pagination')
			row_callback: (li, json)=>
				li.bind('click', (event)=>
					obj = $(event.currentTarget)
					return if obj.hasClass('.cur')
					obj.parent().children('.cur').removeClass('cur')
					obj.addClass('cur')
					content.get(json.baoliaoid)
				)
				li.find(':checkbox').bind 'click', (event)->
					event.stopPropagation()
				li.children('.baoliao-list-delete').bind 'click', (event)=>
					event.stopPropagation()
					obj = $ event.currentTarget
					@delete
						'del[]': parseInt obj.parent().attr('id').substr(4)
			parsed_callback: ()->
				first.click() if first = listElm.children().eq(0)

		$('#select_all').bind 'click', (event)->
			obj = $ event.currentTarget
			$.each listElm.find('input:checkbox'), (i, k)->
				k.checked = obj.is ':checked'
				true
		$(window).resize ()=>
			@resize()
		searchForm = $ '#search_form'
		searchForm.bind 'submit', ()=>
			where =
				start: $('#search_start').val()
				end: $('#search_end').val()
				keyword: $('#search_keyword').val()
			@tableApp.reload
				where: where
			false
		$('#search_submit').bind 'click', ()=>
			searchForm.submit()

	bindStatus: (event)=>
		obj = $(event.currentTarget)
		return if obj.hasClass('s_3')
		nav.children('.s_3').removeClass('s_3')
		obj.addClass('s_3')
		@status = obj.attr('status')
		where.status = @status
		@tableApp.reload
			page: 1
			where: where
			pagesize: @pagesize
	delete: (data)=>
		data ?= listForm.serialize()
		return unless data
		ct.confirm('确定要删除这些报料吗?', ()=>
			$.post('?app=baoliao&controller=baoliao&action=delete', data, ()=>
				@tableApp.reload
					pagesize: @pagesize
			, 'json')
			return
		)
	resize: ()=>
		clearTimeout(resizing) if resizing > 0
		resizing = setTimeout(()=>
			@height = document.documentElement.clientHeight - 76
			@pagesize = parseInt(@height / 50)
			@pagesize-- if cmstop.IE > 0
			listElm.css('height', @height + 'px')
			@tableApp.reload 
				page: 1
				pagesize: @pagesize
			resizing = false
		, 100)

class Content
	contentTitle = contentDate = contentComment = contentPV = contentIp = 
	contentText = mainPanel = repostContext = repostEditBtn = repostEdit = 
	repostDisplay = repostTextarea = contentImage = contentVideo = controlBar = 
	repostLastTime = null
	editing = false
	id: 0
	data: null
	related: null
	init: ()=>
		contentTitle = $('#content_title')
		contentDate = $('#content_date')
		contentIp = $('#content_ip')
		contentComment = $('#content_comment')
		contentPV = $('#content_pv')
		contentText = $('#content_text')
		mainPanel = $('#main_panel')
		repostEditBtn = $('#repost_edit_exec')
		repostEdit = $('#repost_edit')
		repostDisplay = $('#repost_display')
		repostTextarea = $('#repost_textarea')
		repostContext = $('#repost_context')
		contentImage = $('#content_image')
		contentVideo = $('#content_video')
		controlBar = $ '#control_bar'
		repostLastTime = $('#repost_last_time')
		mainPanel.css('height', document.documentElement.clientHeight - 38 + 'px')

		repostEditBtn.bind 'click', ()=>
			@doReply()

		$('#repost_submit').bind 'click', ()=>
			@editRepost repostTextarea.val()

		repostTextarea.bind '_zoom', (event, data)->
			zoomHandle = repostTextarea.data 'zoomHandle'
			if zoomHandle
				clearInterval zoomHandle
			height = parseFloat repostTextarea.css 'height'
			return if height == data
			repostTextarea.css 'min-height', 0
			direct = if data - height > 0 then 1 else -1
			zoomHandle = setInterval ()->
				h = parseFloat repostTextarea.css 'height'
				h += direct
				repostTextarea.css 'height', h + 'px'
				if Math.abs(h - data) < 1
					clearInterval zoomHandle
					repostTextarea.css
						'height': data + 'px'
						'min-height': data + 'px'
			, 5
			repostTextarea.data 'zoomHandle', zoomHandle

		repostTextarea.bind 'show', ()->
			if cmstop.IE
				repostTextarea.unbind().removeClass('ready').css('height', '120px')
				return
			html = repostTextarea.val()
			if html.length < 1
				repostTextarea.val '回复内容'
				repostTextarea.addClass 'ready'
				_focus = ()->
					repostTextarea.val ''
					repostTextarea.removeClass 'ready'
					repostTextarea.trigger 'autoSize'
				_blur = ()->
					return if repostTextarea.val().length > 0
					repostTextarea.val '回复内容'
					repostTextarea.addClass 'ready'
					repostTextarea.css
						'height': '30px'
						'min-height': '30px'
					repostTextarea.one 'focus', ()=>
						_focus()
				repostTextarea.one 'focus', ()=>
					_focus()
				repostTextarea.bind 'blur', ()=>
					_blur()
					
			repostTextarea.bind 'autoSize', (event)=>
				event.stopPropagation()
				html = repostTextarea.val() or ''
				_textarea = $('#autosize_text')
				if _textarea.length is 0
					_textarea = $('<textarea id="autosize_text"></textarea>')
					_textarea.css
						'width'			: repostTextarea.css 'width'
						'height'		: '72px!important'
						'min-height'	: '72px!important'
						'position'		: 'fixed'
						'top'			: '99999px'
						'left'			: '99999px'
						'overflow-y'	: 'auto'
						'word-wrap'		: 'break-word'
						'line-height'	: repostTextarea.css 'line-height'
					$('body').append _textarea
				_textarea.val repostTextarea.val()
				_height = _textarea[0].scrollHeight
				repostTextarea.trigger '_zoom', _height
			for event in ['click', 'changed', 'keydown', 'keyup']
				repostTextarea.bind event, ()=>
					repostTextarea.trigger 'autoSize'
			if repostTextarea.onpropertychange
				input.onpropertychange = ()=>
					repostTextarea.trigger 'autoSize'
			else
				repostTextarea.bind 'input', ()=>
					repostTextarea.trigger 'autoSize'

		# 按钮的事件绑定
		for i in ['Reply', 'Edit', 'Comment', 'Relate', 'Delete']
			func = (i)=>
				$('#baoliao_btn_' + i).bind('click', ()=>
					@['do' + i]()
				)
			func(i)
		$(window).resize ()=>
			@resize()
		# mainPanel.bind 'scroll', (event)=>
		# 	p = mainPanel.scrollTop()
		# 	if p > 50
		# 		unless controlBar.hasClass 'fixed'
		# 			controlBar.addClass 'fixed' 
		# 			mainPanel.css 'margin-top', '33px'
		# 	else
		# 		if controlBar.hasClass 'fixed'
		# 			controlBar.removeClass 'fixed'
		# 			mainPanel.css 'margin-top', '0'
	get: (id)=>
		return if id == @id
		@clean() if @id > 0
		$.get('?app=baoliao&controller=baoliao&action=get', {id:id}, (response)=>
			unless response.state
				ct.error('报料不存在')
				return
			@build(response.data)
			mainPanel.show()
			#controlBar.removeClass 'fixed' if controlBar.hasClass 'fixed'
			@id = id
		, 'json')
	build: (data)=>
		@data = data
		contentTitle.html(data.title)
		contentDate.html(data.date)
		contentIp.html(data.ipstring)
		contentComment.html('评论: '+data.comments)
		contentPV.html('点击: '+data.pv)
		contentText.html(data.content)
		# 构建来源
		source = $('#source')
		language =
			name: '姓名'
			email: '邮箱'
			phone: '手机'
			qq: 'QQ'
			address: '地址'
		for i in ['name', 'email', 'phone', 'qq', 'address']
			source = $("<div class='baoliao-source-item'>#{language[i]}：#{data[i]}</div>").insertAfter(source) if data[i]
		# 构建回复
		if data.reply == '1'
			repostEdit.hide()
			repostDisplay.show()
			repostEditBtn.show()
			repostContext.html data.replytext
			repostLastTime.html "#{data.replydate} 最新回复&nbsp;"
		else
			repostEdit.show()
			repostTextarea.trigger 'show'
			repostDisplay.hide()
			repostEditBtn.hide()
		# 构造关联文章
		related = eval("(#{data.related})")
		@related = related
		# 构建图片
		data.image = eval(data.image)
		if data.image and data.image.length > 0
			template = $('#baoliao_image_template').html()
			$.each data.image, (i, src)=>
				html = $ template.replace(/\[src\]/g, UPLOAD_URL + src)
				contentImage.append(html)
				html.find('.delete').bind('click', (event)=>
					obj = $(event.currentTarget).parents('.baoliao-image-item')
					index = obj.index(contentImage.children())
					ct.confirm '删除该图片?', ()=>
						$.post("?app=baoliao&controller=baoliao&action=image_delete&id=#{@id}", {pic:index}, (response)=>
							unless response.state
								ct.error(response.error or '删除失败')
								return false
							obj.remove()
						,'json')
				)
			contentImage.show()
		# 构建视频
		data.video = eval("(#{data.video})") if data.video
		if data.video and data.video.vid
			template = $('#baoliao_video_template').html()
			html = $ template.replace(/\[swf\]/g, data.video.swf)
			contentVideo.append(html)
			html.find('.delete').bind 'click', (event)=>
				obj = $(event.currentTarget).parent()
				ct.confirm '删除视频?', ()=>
					$.post "?app=baoliao&controller=baoliao&action=video_delete&id=#{@id}", null, (response)=>
						unless response.state
							ct.error(response.error or '删除失败')
							return false
						contentVideo.empty()
					, 'json'
			contentVideo.show()
		true
	clean: ()=>
		# 取消编辑状态
		$('.clear_edit').click()
		elm.empty() for elm in [$('.baoliao-source-item'), contentTitle, contentDate, contentComment, contentPV, contentIp, contentText, contentImage, contentVideo]
		elm.val('') for elm in [repostTextarea]
		elm.hide() for elm in [mainPanel]
		contentImage.children('.baoliao-image-item').remove()
		@id = 0
		@related = null
	doReply: ()=>
		location.hash = 'reply'
		return if !repostEditBtn.is(':visible') and !repostTextarea.hasClass('ready')
		repostTextarea.val repostContext.html()
		repostEdit.show()
		repostTextarea.focus().trigger 'show'
		repostDisplay.hide()
		repostEditBtn.hide()
	doEdit: ()=>
		editor = null
		return if editing
		editing = true
		titleInput = contextInput = null
		mainPanel.find('.delete').show()
		controlBar.children('input').hide()
		_clean = ()->
			titleInput.remove()
			contextInput.remove()
			btnDiv.remove()
			contentTitle.show()
			contentText.show()
			editing = false
			mainPanel.find('.delete').hide()
			$('.miniEditor').remove()
			controlBar.children('input').show()
		title = escapeHtml contentTitle.text()
		context = contentText.html()
		titleInput = $('<input type="text" id="content_title_input" value="'+title+'" class="baoliao-content-title" />').insertAfter(contentTitle.hide())
		contextInput = $('<textarea id="content" class="baoliao-content">'+context+'</textarea>').insertAfter(contentText.hide())
		btnDiv = $('<div class="baoliao-edit-btn"></div>').appendTo(controlBar)
		$('<button class="btn clear_edit">取消</button>').prependTo(btnDiv).one('click', ()=>
			_clean()
		)
		$('<button class="btn">保存</button>').prependTo(btnDiv).one('click', ()=>
			$.post("?app=baoliao&controller=baoliao&action=edit&id=#{@id}",
				title: titleInput.val()
				content: editor.getCode()
			, (response)->
				unless response.state
					ct.error(response.error or '保存失败')
					return
				contentTitle.text(titleInput.val())
				contentText.html(editor.getCode())
				_clean()
			, 'json')
		)
		editor = new $.Rte contextInput,
			disable: ['italic', 'fontsize', 'fontname', 'forecolor', 'insertunorderedlist', 'insertorderedlist', 'justify', 'createlink', 'insertimage', 'html']
		contextInput.val($.trim(editor.getCode()))
		true
	doComment: ()=>
		unless @data.topicid
			ct.error('此报料暂无任何评论')
			return
		ct.assoc.open('?app=comment&controller=comment&action=index&rwkeyword=' + @data.topicid, 'newtab')
	doRelate: ()=>
		_chose = (ok, close)=>
			iframe = ct.iframe
					title: '选择相关内容'
					maxHeight: document.documentElement.clientHeight
					url: '?app=system&controller=port&action=picker'
				, ok: (r)=>
					@related = 
						id: r[0].contentid
						title: r[0].title
						url: r[0].url
					$.post "?app=baoliao&controller=baoliao&action=post_related&id=#{@id}", @related, (response)->
						if response.state 
							iframe.dialog('close')
							ok.call(this) if typeof ok == 'function'
						else
							ct.error(response.error or '异常错误')
					, 'json'
				, null, ()=>
					close.call(this) if typeof close == 'function'
			true
		if @related isnt null
			clear = 0
			inEditing = false
			popup = $('#popup')
			offset = $('#baoliao_btn_Relate').position()
			popup.css
				left: offset.left + 'px'
				top: offset.top + 22 + 'px'
			popup.find('#popup_title').attr('title', @related.title).html(@related.title)
			popup.show()
			popup.children('.view').attr('href', @related.url)
			popup.children('.edit').unbind().bind 'click', ()=>
				inEditing = true
				_chose ()=>
					popup.find('#popup_title').attr('title', @related.title).html(@related.title)
					popup.children('.view').attr('href', @related.url)
					$('#inpop').focus()
					inEditing = false
				, ()=>
					$('#inpop').focus()
					inEditing = false
			popup.children('.delete').unbind().bind 'click', ()=>
				$.post "?app=baoliao&controller=baoliao&action=delete_related", {id: @id}, (response)=>
					if response.state
						@related = null
						popup.hide()
					else
						ct.error response.error or '删除失败'
				, 'json'
			popup.children('.popup-close').unbind().bind 'click', ()=>
				popup.hide()
			popup.unbind().bind 'click', (event)->
				$('#inpop').focus()
				$('#inpop').data 'isFocused', true
				return false
			$('#inpop').unbind().focus().bind 'blur',(event)->
				return if inEditing
				$('#inpop').data 'isFocused', false
				clear = setTimeout ()->
					popup.hide() unless $('#inpop').data 'isFocused'
				, 200
				true
		else
			_chose()
	doDelete: ()=>
		ct.confirm('确定要删除该报料吗?', ()=>
			$.post('?app=baoliao&controller=baoliao&action=delete', "del[]=#{@id}", ()=>
				list.tableApp.reload()
				@clean()
			, 'json')
		)
		true
	editRepost: (text)=>
		$.post('?app=baoliao&controller=baoliao&action=post_repost', {id: @id,text: text}, (response)->
			unless response.state
				ct.error(response.error or '提交失败')
				return
			repostContext.html(repostTextarea.val())
			repostEdit.hide()
			repostDisplay.show()
			repostEditBtn.show()
			date = new Date()
			repostLastTime.html "#{date.getFullYear()}-#{date.getMonth()+1}-#{date.getDate()} 最新回复&nbsp;"
		, 'json')
	resize: ()=>
		mainPanel.css('height', document.documentElement.clientHeight - 38 + 'px')

list = new List
content = new Content
$ ()->
	list.init()
	content.init()
	$('.datepicker').DatePicker
		format:'yyyy-MM-dd HH:mm'
