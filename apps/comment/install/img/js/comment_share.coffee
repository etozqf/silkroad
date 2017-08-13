class ShareWeibo
	self = alertDialog = authWindow = null
	constructor: ()->
		self = this
		true
	init: ()=>
		if document.readyState is 'loading'
			$ ()=>
				load()
		else
			load()
	build: (data, panel)=>
		ico = if data.is_authorized then data.icon else data.icon_gray
		html = $ new Array('<label>' +
				'<div class="repost-btn">' +
					'<img src="'+ico+'" alt="'+data.interface+'" title="转发到'+data.name+'" />' +
					'<div class="tick"></div>' +
				'</div>' +
				'<input type="checkbox" name="share_weibo[]" value="'+data.apiid+'" style="visibility:hidden;" />' +
			'</label>').join ''
		panel.append html
		html.find('.repost-btn').bind 'click',
			item: data
		, (event)->
			if event.data.item.is_authorized
				bind this, event
			else
				element = $ this
				element.bind 'changeAuthorized',
					ico: event.data.item.icon
				, changeAuthorized
				authorize event.data.item.interface, element
		if data.is_authorized
			setTimeout ()->
				html.find('.repost-btn').click()
			, 10
	load = ()=>
		url = "#{APP_URL}?app=comment&controller=review&action=ajax_allow_weibo&jsoncallback=?"
		$.getJSON url, (res)=>
			return unless res and res.length
			panel = $('.loginform-user-info').children('.seccode-area')
			$.each res, (i, k)=>
				self.build k, panel
			panel.css 'visibility', 'visible'
	authorize = (type, element)=>
		url = "#{APP_URL}?app=cloud&controller=thirdlogin&action=redirect_to_authorize&type=#{type}"

		params = 'width=640,height=480,location=no,menubar=no,scrollbars=yes'
		authWindow = window.open url, undefined, params
		p = setInterval ()=>
			return unless authWindow.closed
			authsuccess = $.cookie COOKIE_PRE+'authsuccess'
			if authsuccess is '0'
				$.cookie COOKIE_PRE+'authsuccess', null,
					domain : COOKIE_DOMAIN
				authorizeSuccess element
			else
				authorizeError authsuccess if typeof authsuccess is 'string'
			clearInterval p
		, 1000

	authorizeSuccess = (element)=>
		element.trigger 'changeAuthorized'
	authorizeError = (message)=>
		unless alertDialog
			alertDialog = new window.dialog
				hasOverlay:1
				width:180
				height:28
				hasCloseIco:0
				closeDelay:3000
		alertDialog.open
			text: message
	changeAuthorized = (event)->
		element = $ this
		element.find('img').attr 'src', event.data.ico
		element.unbind().bind 'click', (event)->
			bind this, event
		setTimeout ()->
			element.find('.repost-btn').click()
		, 10
	bind = (element, event)->
		element = $ element
		checkbox = element.next 'input[type="checkbox"]'
		if checkbox.is ':checked'
			unselectedShareIco element
		else
			selectedShareIco element
		checkbox[0].checked = !checkbox.is ':checked'
		event.preventDefault()
	selectedShareIco = (element)->
		element.addClass('repost-btn-checked').removeClass('repost-btn')
	unselectedShareIco = (element)->
		element.addClass('repost-btn').removeClass('repost-btn-checked')

window.shareWeibo = new ShareWeibo()